@extends('layouts.app')

@section('content')
<div style="max-width:1200px; margin:40px auto; padding:25px; background:#fff0f5; border-radius:15px; box-shadow:0 8px 20px rgba(0,0,0,0.08); font-family:sans-serif;">

    <h2 style="text-align:center; color:#ff3399; margin-bottom:25px; font-size:28px; font-weight:bold;">Manage Tasks</h2>

    {{-- Success popup --}}
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <div style="margin-bottom:20px; text-align:right;">
        <a href="{{ route('task.create') }}"
            style="padding:12px 20px; background:#ff3399; color:white; border-radius:10px; text-decoration:none; font-weight:bold; transition:0.3s;"
            onmouseover="this.style.background='#e60073';" onmouseout="this.style.background='#ff3399';">
            Create Task
        </a>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:16px;">
            <thead>
                <tr style="background:#ffb3d1; color:white;">
                    <th style="padding:12px; border:1px solid #ff99cc;">Name</th>
                    <th style="padding:12px; border:1px solid #ff99cc;">Description</th>
                    <th style="padding:12px; border:1px solid #ff99cc;">Start</th>
                    <th style="padding:12px; border:1px solid #ff99cc;">Due</th>
                    <th style="padding:12px; border:1px solid #ff99cc;">Status</th>
                    <th style="padding:12px; border:1px solid #ff99cc;">Users</th>
                    <th style="padding:12px; border:1px solid #ff99cc;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr style="text-align:center; background:white; transition:0.3s;" 
                    onmouseover="this.style.background='#ffe6f2';" 
                    onmouseout="this.style.background='white';">
                    <td style="padding:12px; border:1px solid #ff99cc;">{{ $task->name }}</td>
                    <td style="padding:12px; border:1px solid #ff99cc;">{{ $task->description }}</td>
                    <td style="padding:12px; border:1px solid #ff99cc;">{{ $task->start_date }}</td>
                    <td style="padding:12px; border:1px solid #ff99cc;">{{ $task->due_date }}</td>
                    <td style="padding:12px; border:1px solid #ff99cc;">{{ $task->status }}</td>
                    <td style="padding:12px; border:1px solid #ff99cc;">
                        @foreach($task->users as $user)
                            {{ $user->name }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td style="padding:12px; border:1px solid #ff99cc;">
                        <div style="display:flex; justify-content:center; gap:5px;">
                            <a href="{{ route('task.edit',$task->id) }}"
                                style="padding:8px 14px; background:#ff3399; color:white; border-radius:8px; font-weight:bold; text-decoration:none; transition:0.3s;"
                                onmouseover="this.style.background='#e60073';" onmouseout="this.style.background='#ff3399';">
                                Edit
                            </a>
                            <form action="{{ route('task.delete',$task->id) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="padding:8px 14px; background:#ff3385; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer; transition:0.3s;"
                                    onmouseover="this.style.background='#e60073';" onmouseout="this.style.background='#ff3385';">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection