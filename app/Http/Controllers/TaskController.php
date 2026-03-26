<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // List tasks: only for creator or assigned users
    public function index()
    {
        $userId = Auth::id();

        $tasks = Task::with('users')
            ->where(function($query) use ($userId) {
                $query->where('created_by', $userId)
                      ->orWhereHas('users', function($q) use ($userId) {
                          $q->where('user_id', $userId);
                      });
            })
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    // Show create form
    public function create()
    {
        $users = User::all();
        return view('tasks.create', compact('users'));
    }

    // Store new task
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'nullable|string',
            'start_date'=>'required|date',
            'due_date'=>'required|date|after_or_equal:start_date',
            'status'=>'required|in:Pending,In Progress,Completed',
            'assigned_to'=>'required|array'
        ]);

        $task = Task::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'start_date'=>$request->start_date,
            'due_date'=>$request->due_date,
            'status'=>$request->status,
            'created_by'=>Auth::id()
        ]);

        $task->users()->sync($request->assigned_to);

        return redirect()->route('manage.task')->with('success','Task added successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $task = Task::with('users')->findOrFail($id);
        $this->authorizeTask($task);

        $users = User::all();
        return view('tasks.edit', compact('task','users'));
    }

    // Update task
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $this->authorizeTask($task);

        $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'nullable|string',
            'start_date'=>'required|date',
            'due_date'=>'required|date|after_or_equal:start_date',
            'status'=>'required|in:Pending,In Progress,Completed',
            'assigned_to'=>'required|array'
        ]);

        $task->update($request->only('name','description','start_date','due_date','status'));
        $task->users()->sync($request->assigned_to);

        return redirect()->route('manage.task')->with('success','Task updated successfully!');
    }

    // Delete task
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $this->authorizeTask($task);

        $task->users()->detach();
        $task->delete();

        return redirect()->route('manage.task')->with('success','Task deleted successfully!');
    }

    // Check authorization: only creator or assigned users
    private function authorizeTask(Task $task)
    {
        $userId = Auth::id();
        if ($task->created_by != $userId && !$task->users->contains($userId)) {
            abort(403,'Unauthorized');
        }
    }
}