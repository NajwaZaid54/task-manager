<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $today = Carbon::today();

        // Filter by period
        $filter = $request->get('filter', 'all'); // default all
        $tasksQuery = Task::with('users')
            ->where(function($query) use ($userId) {
                $query->where('created_by', $userId)
                      ->orWhereHas('users', function($q) use ($userId) {
                          $q->where('user_id', $userId);
                      });
            });

        if ($filter == 'day') {
            $tasksQuery->whereDate('due_date', $today);
        } elseif ($filter == 'week') {
            $tasksQuery->whereBetween('due_date', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()]);
        } elseif ($filter == 'month') {
            $tasksQuery->whereMonth('due_date', $today->month)
                       ->whereYear('due_date', $today->year);
        }

        $tasks = $tasksQuery->get();

        // Counts by status
        $pending = $tasks->where('status','Pending')->count();
        $progress = $tasks->where('status','In Progress')->count();
        $completed = $tasks->where('status','Completed')->count();

        // Overdue & almost due tasks
        $overdueTasks = $tasks->filter(fn($t) => $t->due_date < $today && $t->status != 'Completed');
        $almostDueTasks = $tasks->filter(fn($t) => $t->due_date >= $today && $t->due_date <= $today->copy()->addDays(2) && $t->status != 'Completed');

        $overdueCount = $overdueTasks->count();

        // Task names by status (for pie chart tooltip)
        $taskNamesByStatus = [
            'Pending' => $tasks->where('status','Pending')->pluck('name')->toArray(),
            'In Progress' => $tasks->where('status','In Progress')->pluck('name')->toArray(),
            'Completed' => $tasks->where('status','Completed')->pluck('name')->toArray(),
            'Overdue' => $overdueTasks->pluck('name')->toArray(),
        ];

        return view('dashboard.index', compact(
            'tasks',
            'pending',
            'progress',
            'completed',
            'overdueTasks',
            'almostDueTasks',
            'overdueCount',
            'taskNamesByStatus',
            'filter'
        ));
    }
}