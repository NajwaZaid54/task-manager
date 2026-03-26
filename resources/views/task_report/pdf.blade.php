<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task Report PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ff99cc; padding: 8px; text-align: center; }
        th { background-color: #ffb3d1; color: white; }
        .overdue { background-color: #ffcccc; }
        .almost { background-color: #fff3cd; }
    </style>
</head>
<body>
    <h2 style="text-align:center; color:#ff3399;">Task Report</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                @php
                    $isOverdue = \Carbon\Carbon::parse($task->due_date)->lt(\Carbon\Carbon::today()) && $task->status != 'Completed';
                    $isAlmost = \Carbon\Carbon::parse($task->due_date)->between(\Carbon\Carbon::today(), \Carbon\Carbon::today()->copy()->addDays(2));
                @endphp
                <tr class="{{ $isOverdue ? 'overdue' : ($isAlmost ? 'almost' : '') }}">
                    <td>{{ $task->name }}</td>
                    <td>{{ $task->due_date }}</td>
                    <td>{{ $task->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>