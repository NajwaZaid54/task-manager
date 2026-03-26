@extends('layouts.app')

@section('content')
<head>
    <title>Task Report</title>
</head>
<body style="font-family:sans-serif; background:#ffe6f0;">

<div style="max-width:1200px; margin:40px auto; padding:25px; background:white; border-radius:15px;">

    <h2 style="text-align:center; color:#ff3399;">Task Report</h2>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('task.report') }}" 
        style="margin:20px 0; display:flex; flex-wrap:wrap; gap:10px; justify-content:center;">

        <select name="status" style="padding:8px; border-radius:8px;">
            <option value="">All Status</option>
            <option value="Pending" {{ request('status')=='Pending'?'selected':'' }}>Pending</option>
            <option value="In Progress" {{ request('status')=='In Progress'?'selected':'' }}>In Progress</option>
            <option value="Completed" {{ request('status')=='Completed'?'selected':'' }}>Completed</option>
        </select>

        <select name="period" style="padding:8px; border-radius:8px;">
            <option value="">All Time</option>
            <option value="day" {{ request('period')=='day'?'selected':'' }}>Today</option>
            <option value="week" {{ request('period')=='week'?'selected':'' }}>This Week</option>
            <option value="month" {{ request('period')=='month'?'selected':'' }}>This Month</option>
        </select>

        <input type="date" name="from_date" value="{{ request('from_date') }}" style="padding:8px;">
        <input type="date" name="to_date" value="{{ request('to_date') }}" style="padding:8px;">

        <button style="background:#ff3399; color:white; border:none; padding:8px 15px; border-radius:8px;">
            Filter
        </button>

    </form>

    {{-- SUMMARY --}}
    <div style="display:flex; gap:20px; justify-content:center; margin-bottom:20px;">
        <div style="background:#ffcccc; padding:15px; border-radius:10px;">
            Overdue: {{ $overdueTasks->count() }}
        </div>

        <div style="background:#fff3cd; padding:15px; border-radius:10px;">
            Almost Due: {{ $almostDueTasks->count() }}
        </div>

        <div style="background:#ccffcc; padding:15px; border-radius:10px;">
            Total: {{ $tasks->count() }}
        </div>
    </div>

    {{-- TABLE --}}
    <table border="1" width="100%" cellpadding="10" style="border-collapse:collapse; text-align:center;">
        <tr style="background:#ff99cc;">
            <th>Name</th>
            <th>Due Date</th>
            <th>Status</th>
        </tr>

        @foreach($tasks as $task)

            @php
                $isOverdue = \Carbon\Carbon::parse($task->due_date)->lt(\Carbon\Carbon::today()) && $task->status != 'Completed';
                $isAlmost = \Carbon\Carbon::parse($task->due_date)->between(\Carbon\Carbon::today(), \Carbon\Carbon::today()->copy()->addDays(2));
            @endphp

            <tr 
                @if($isOverdue) style="background:#ffcccc;"
                @elseif($isAlmost) style="background:#fff3cd;"
                @endif
            >
                <td>{{ $task->name }}</td>
                <td>{{ $task->due_date }}</td>
                <td>{{ $task->status }}</td>
            </tr>

        @endforeach

    </table>

</div>
<div style="text-align:right; margin-bottom:10px;">
    <a href="{{ route('task.report.pdf', request()->query()) }}"
       style="padding:8px 15px; background:#ff3399; color:white; border-radius:8px; text-decoration:none;">
       Export PDF
    </a>
</div>

</body>

@endsection