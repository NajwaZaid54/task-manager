@extends('layouts.app')

@section('content')
<div style="max-width:700px; margin:40px auto; padding:30px; background: #fff0f5; border-radius:15px; box-shadow:0 8px 20px rgba(0,0,0,0.08); font-family:sans-serif;">

    <h2 style="text-align:center; color:#ff3399; margin-bottom:25px; font-size:28px; font-weight:bold;">Create New Task</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div style="background:#ffe6e6; padding:15px; border-radius:10px; margin-bottom:20px; color:#b30000;">
            <ul style="margin:0; padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('task.store') }}">
        @csrf

        <div style="margin-bottom:20px;">
            <label style="display:block; font-weight:600; margin-bottom:6px;">Task Name</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter task name"
                style="width:100%; padding:12px 15px; border-radius:10px; border:1px solid #ff99cc; transition:0.3s; font-size:16px;"
                onfocus="this.style.borderColor='#ff3399';" onblur="this.style.borderColor='#ff99cc';">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; font-weight:600; margin-bottom:6px;">Description</label>
            <textarea name="description" placeholder="Enter task description"
                style="width:100%; padding:12px 15px; border-radius:10px; border:1px solid #ff99cc; transition:0.3s; font-size:16px;"
                rows="4" onfocus="this.style.borderColor='#ff3399';" onblur="this.style.borderColor='#ff99cc';">{{ old('description') }}</textarea>
        </div>

        <div style="display:flex; gap:20px; margin-bottom:20px;">
            <div style="flex:1;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}"
                    style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid #ff99cc; transition:0.3s;"
                    onfocus="this.style.borderColor='#ff3399';" onblur="this.style.borderColor='#ff99cc';">
            </div>
            <div style="flex:1;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}"
                    style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid #ff99cc; transition:0.3s;"
                    onfocus="this.style.borderColor='#ff3399';" onblur="this.style.borderColor='#ff99cc';">
            </div>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; font-weight:600; margin-bottom:6px;">Status</label>
            <select name="status"
                style="width:100%; padding:12px 15px; border-radius:10px; border:1px solid #ff99cc; transition:0.3s; font-size:16px;"
                onfocus="this.style.borderColor='#ff3399';" onblur="this.style.borderColor='#ff99cc';">
                <option value="Pending" {{ old('status')=='Pending'?'selected':'' }}>Pending</option>
                <option value="In Progress" {{ old('status')=='In Progress'?'selected':'' }}>In Progress</option>
                <option value="Completed" {{ old('status')=='Completed'?'selected':'' }}>Completed</option>
            </select>
        </div>

        <div style="margin-bottom:25px;">
            <label style="display:block; font-weight:600; margin-bottom:6px;">Assign To (Multiple)</label>
            <select name="assigned_to[]" multiple
                style="width:100%; padding:12px 15px; border-radius:10px; border:1px solid #ff99cc; transition:0.3s; font-size:16px;"
                onfocus="this.style.borderColor='#ff3399';" onblur="this.style.borderColor='#ff99cc';">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <small style="color:#ff3399;">Hold Ctrl/Cmd to select multiple users</small>
        </div>

        <button type="submit"
            style="width:100%; padding:14px; background:#ff3399; color:white; font-size:18px; font-weight:bold; border:none; border-radius:12px; cursor:pointer; transition:0.3s;"
            onmouseover="this.style.background='#e60073';" onmouseout="this.style.background='#ff3399';">
            Create Task
        </button>

    </form>
</div>
@endsection