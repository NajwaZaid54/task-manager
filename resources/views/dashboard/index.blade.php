@extends('layouts.app')

@section('content')
<div style="max-width:1200px; margin:40px auto; padding:25px; background:#fff0f5; border-radius:15px; box-shadow:0 8px 20px rgba(0,0,0,0.08); font-family:sans-serif;">

    <h2 style="text-align:center; color:#ff3399; margin-bottom:25px; font-size:28px; font-weight:bold;">Dashboard</h2>

    {{-- Filter Buttons --}}
    <div style="text-align:center; margin-bottom:20px;">
        <a href="{{ route('dashboard', ['filter'=>'day']) }}" 
           style="padding:8px 16px; margin:0 5px; background:{{ $filter=='day'?'#ff3399':'#ffb3d1' }}; color:white; border-radius:6px; text-decoration:none;">Day</a>
        <a href="{{ route('dashboard', ['filter'=>'week']) }}" 
           style="padding:8px 16px; margin:0 5px; background:{{ $filter=='week'?'#ff3399':'#ffb3d1' }}; color:white; border-radius:6px; text-decoration:none;">Week</a>
        <a href="{{ route('dashboard', ['filter'=>'month']) }}" 
           style="padding:8px 16px; margin:0 5px; background:{{ $filter=='month'?'#ff3399':'#ffb3d1' }}; color:white; border-radius:6px; text-decoration:none;">Month</a>
        <a href="{{ route('dashboard') }}" 
           style="padding:8px 16px; margin:0 5px; background:{{ $filter=='all'?'#ff3399':'#ffb3d1' }}; color:white; border-radius:6px; text-decoration:none;">All</a>
    </div>

    {{-- Alerts --}}
    @if($overdueTasks->count() > 0)
        <div style="background:#ff6666;color:white;padding:12px;border-radius:8px;margin-bottom:15px;">
            ⚠️ <strong>{{ $overdueTasks->count() }} task(s) overdue!</strong>
            <ul>
                @foreach($overdueTasks as $task)
                    <li>{{ $task->name }} - Due: {{ $task->due_date }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if($almostDueTasks->count() > 0)
        <div style="background:#ffb3d1;color:#800040;padding:12px;border-radius:8px;margin-bottom:15px;">
            ⏰ <strong>{{ $almostDueTasks->count() }} task(s) almost due within 2 days!</strong>
            <ul>
                @foreach($almostDueTasks as $task)
                    <li>{{ $task->name }} - Due: {{ $task->due_date }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Summary Cards --}}
    <div style="display:flex; gap:20px; flex-wrap:wrap; justify-content:center; margin-bottom:30px;">
        <div style="flex:1 1 200px; background:#ffccdd; padding:20px; border-radius:12px; text-align:center;">
            <h3 style="color:#ff3399; margin-bottom:10px;">Total Tasks</h3>
            <p style="font-size:24px; font-weight:bold;">{{ $tasks->count() }}</p>
        </div>
        <div style="flex:1 1 200px; background:#ffd6e0; padding:20px; border-radius:12px; text-align:center;">
            <h3 style="color:#ff3399; margin-bottom:10px;">Pending</h3>
            <p style="font-size:24px; font-weight:bold;">{{ $pending }}</p>
        </div>
        <div style="flex:1 1 200px; background:#ffe6f2; padding:20px; border-radius:12px; text-align:center;">
            <h3 style="color:#ff3399; margin-bottom:10px;">In Progress</h3>
            <p style="font-size:24px; font-weight:bold;">{{ $progress }}</p>
        </div>
        <div style="flex:1 1 200px; background:#fff0f5; padding:20px; border-radius:12px; text-align:center;">
            <h3 style="color:#ff3399; margin-bottom:10px;">Completed</h3>
            <p style="font-size:24px; font-weight:bold;">{{ $completed }}</p>
        </div>
        <div style="flex:1 1 200px; background:#ff4d4d; padding:20px; border-radius:12px; text-align:center;">
            <h3 style="color:white; margin-bottom:10px;">Overdue</h3>
            <p style="font-size:24px; font-weight:bold;">{{ $overdueCount }}</p>
        </div>
    </div>

    {{-- Pie Chart --}}
    <div style="margin-bottom:30px; display:flex; justify-content:center;">
        <canvas id="tasksChart" style="max-width:700px; width:100%; height:400px;"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('tasksChart').getContext('2d');
        const taskData = {
            labels: ['Pending','In Progress','Completed','Overdue'],
            datasets: [{
                data: [{{ $pending }}, {{ $progress }}, {{ $completed }}, {{ $overdueCount }}],
                backgroundColor: ['#ff9999','#ffb3d1','#ffccdd','#ff4d4d'],
                borderColor: ['#ff6666','#ff66b2','#ff99cc','#ff1a1a'],
                borderWidth: 1
            }]
        };

        new Chart(ctx, {
            type: 'pie',
            data: taskData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position:'bottom', labels:{ font:{size:14}, padding:20 } },
                    title: { display:true, text:'Tasks Status Overview', font:{size:18} },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const status = context.label;
                                const taskNames = {
                                    'Pending': @json($taskNamesByStatus['Pending']),
                                    'In Progress': @json($taskNamesByStatus['In Progress']),
                                    'Completed': @json($taskNamesByStatus['Completed']),
                                    'Overdue': @json($taskNamesByStatus['Overdue']),
                                };
                                const names = taskNames[status].join(', ');
                                return status + ': ' + context.parsed + ' (' + names + ')';
                            }
                        }
                    }
                }
            }
        });
    </script>

    {{-- Create Task Button --}}
    <div style="margin-bottom:20px; text-align:right;">
        <a href="{{ route('task.create') }}"
           style="padding:12px 20px; background:#ff3399; color:white; border-radius:10px; text-decoration:none; font-weight:bold; transition:0.3s;"
           onmouseover="this.style.background='#e60073';" onmouseout="this.style.background='#ff3399';">
            Create Task
        </a>
    </div>

    {{-- Recent Tasks Table --}}
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:16px;">
            <thead>
                <tr style="background:#ffb3d1; color:white;">
                    <th style="padding:12px; border:1px solid #ff99cc;">Name</th>
                    <th style="padding:12px; border:1px solid #ff99cc;">Description</th>
                    <th style="padding:12px; border:1px solid #ff99cc;">Start</th>
                    <th style="padding:12px; border:1px solid #ff99cc;">Due</th>
                    <th style="padding:12px; border:1px solid #ff99cc;">Status</th>
                    <th style="padding:12px; border:1px solid #ff99cc;">Assigned Users</th>
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
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection