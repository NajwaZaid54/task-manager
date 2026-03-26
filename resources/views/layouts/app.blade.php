<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>@yield('title') | Task Manager</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
html, body{height:100%;}

body{
    display:flex;
    background:#fde2e4;
}

/* Sidebar */
.sidebar{
    width:250px;
    min-width:250px;
    background:#fff0f3;
    height:100vh;
    display:flex;
    flex-direction:column;
    justify-content:space-between; /* logout bottom */
    box-shadow:2px 0 10px rgba(0,0,0,0.05);
}

/* Top: logo + profile */
.sidebar-top{
    padding-top:40px;
    display:flex;
    flex-direction:column;
    align-items:center;
}

.logo img{
    width:120px;
    max-width:100%;
    height:auto;
    margin-bottom:20px;
}

.profile-pic img{
    width:80px;
    height:80px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:20px;
    border:2px solid #ff4d8d;
}

/* Navigation links */
.nav-links{
    display:flex;
    flex-direction:column;
    padding:0 20px;
    flex:1;
}

.nav-links a{
    padding:15px 20px;
    text-decoration:none;
    color:black;
    font-weight:500;
    margin-bottom:5px;
    border-radius:8px;
    transition:0.3s;
}

.nav-links a.active, .nav-links a:hover{
    background:#ff4d8d;
    color:white;
}

/* Logout */
.sidebar-bottom{
    padding:20px;
}

.sidebar-bottom form button{
    width:100%;
    padding:12px;
    background:#ff4d8d;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:500;
    transition:0.3s;
}

.sidebar-bottom form button:hover{
    background:#e63c79;
}

/* Content */
.content{
    flex:1;
    padding:40px;
    overflow-y:auto;
    height:100vh;
}

.header{
    font-size:28px;
    font-weight:600;
    margin-bottom:25px;
    color:black;
}
</style>
</head>
<body>

<div class="sidebar">

    <!-- Top: logo + profile -->
    <div class="sidebar-top">
        <div class="profile-pic">
            <img src="{{ auth()->user()->profile_photo_url ?? asset('image/default-profile.png') }}" alt="Profile Picture">
        </div>
    </div>

    <!-- Navigation links -->
    <div class="nav-links">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('manage.task') }}" class="{{ request()->routeIs('manage.task') ? 'active' : '' }}">Manage Task</a>
        <a href="{{ route('task.report') }}" class="{{ request()->routeIs('task.report') ? 'active' : '' }}">Task Report</a>
        <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">Profile</a>
    </div>

    <!-- Logout bottom -->
    <div class="sidebar-bottom">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

</div>

<!-- Main content -->
<div class="content">
    <div class="header">@yield('header')</div>
    @yield('content')
</div>

</body>
</html>