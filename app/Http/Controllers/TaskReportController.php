<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // ✅ import Request yang betul
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // ✅ import Carbon
use Barryvdh\DomPDF\Facade\Pdf; // ✅ import PDF facade

class TaskReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $tasksQuery = Task::query()
            ->where(function($q) use ($userId){
                $q->where('created_by', $userId)
                  ->orWhereHas('users', fn($u) => $u->where('user_id', $userId));
            });

        // Filter
        if ($request->input('status')) {
            $tasksQuery->where('status', $request->input('status'));
        }

        $today = Carbon::today();

        if ($request->input('period') == 'day') {
            $tasksQuery->whereDate('due_date', $today);
        } elseif ($request->input('period') == 'week') {
            $tasksQuery->whereBetween('due_date', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()]);
        } elseif ($request->input('period') == 'month') {
            $tasksQuery->whereMonth('due_date', $today->month)
                       ->whereYear('due_date', $today->year);
        }

        if ($request->input('from_date') && $request->input('to_date')) {
            $tasksQuery->whereBetween('due_date', [$request->input('from_date'), $request->input('to_date')]);
        }

        $tasks = $tasksQuery->get();

        $overdueTasks = $tasks->filter(fn($t) => Carbon::parse($t->due_date)->lt($today) && $t->status != 'Completed');
        $almostDueTasks = $tasks->filter(fn($t) => Carbon::parse($t->due_date)->between($today, $today->copy()->addDays(2)));

        return view('task_report.index', compact('tasks', 'overdueTasks', 'almostDueTasks'));
    }

    public function exportPDF(Request $request)
{
    $userId = Auth::id();
    $today = Carbon::today();

    $tasksQuery = Task::query()
        ->where(function($q) use ($userId){
            $q->where('created_by', $userId)
              ->orWhereHas('users', fn($u) => $u->where('user_id', $userId));
        });

    if ($request->status) {
        $tasksQuery->where('status', $request->status);
    }

    if ($request->period == 'day') {
        $tasksQuery->whereDate('due_date', $today);
    } elseif ($request->period == 'week') {
        $tasksQuery->whereBetween('due_date', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()]);
    } elseif ($request->period == 'month') {
        $tasksQuery->whereMonth('due_date', $today->month)
                   ->whereYear('due_date', $today->year);
    }

    if ($request->from_date && $request->to_date) {
        $tasksQuery->whereBetween('due_date', [$request->from_date, $request->to_date]);
    }

    $tasks = $tasksQuery->get();
    $overdueTasks = $tasks->filter(fn($t)=> \Carbon\Carbon::parse($t->due_date)->lt($today) && $t->status != 'Completed');
    $almostDueTasks = $tasks->filter(fn($t)=> \Carbon\Carbon::parse($t->due_date)->between($today, $today->copy()->addDays(2)));

    $pdf = Pdf::loadView('task_report.pdf', compact('tasks','overdueTasks','almostDueTasks'));

    return $pdf->download('task_report_'.date('Y-m-d').'.pdf');
}
}