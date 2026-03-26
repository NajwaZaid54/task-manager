@extends('layouts.app')

@section('content')
<div style="max-width: 500px; margin: 50px auto; padding: 30px; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background: #fff;">
    <h2 style="text-align: center; margin-bottom: 25px; color: #333;">Update Profile</h2>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf

        <!-- Name -->
        <div style="margin-bottom: 20px;">
            <label for="name" style="display:block; margin-bottom:5px; font-weight: bold;">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            @error('name')
                <div style="color:red; margin-top:5px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div style="margin-bottom: 20px;">
            <label for="email" style="display:block; margin-bottom:5px; font-weight: bold;">Email</label>
            <input type="email" id="email" value="{{ $user->email }}" disabled
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px; background:#f1f1f1;">
        </div>

        <!-- Password -->
        <div style="margin-bottom: 20px;">
            <label for="password" style="display:block; margin-bottom:5px; font-weight: bold;">New Password (leave blank to keep current)</label>
            <input type="password" id="password" name="password" 
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            @error('password')
                <div style="color:red; margin-top:5px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password confirmation -->
        <div style="margin-bottom: 25px;">
            <label for="password_confirmation" style="display:block; margin-bottom:5px; font-weight: bold;">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <button type="submit" 
                style="width:100%; padding:12px; background:#4CAF50; color:white; font-weight:bold; border:none; border-radius:5px; cursor:pointer; transition: 0.3s;">
            Save Changes
        </button>
    </form>
</div>
@endsection