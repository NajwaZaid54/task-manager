<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Welcome | Task Manager</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

html, body{
    height:100%;
}

body{
    display:flex;
    align-items:center;
    justify-content:center;
    background:#fde2e4;
}

.welcome-container{
    width:60%;
    max-width:700px;
    text-align:center;
    background:white;
    padding:60px;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,0.15);
}

.welcome-container h1{
    font-size:36px;
    font-weight:600;
    margin-bottom:30px;
    color:black;
}

.welcome-container p{
    font-size:18px;
    margin-bottom:40px;
    color:black;
}

.welcome-container a{
    text-decoration:none;
    background:#ff4d8d;
    color:white;
    padding:15px 30px;
    border-radius:10px;
    font-size:16px;
    font-weight:500;
    transition:0.3s;
}

.welcome-container a:hover{
    background:#e63c79;
}
</style>
</head>
<body>

<div class="welcome-container">
<h1>Welcome to Task Manager</h1>
<p>Manage your tasks easily with a simple and beautiful dashboard.</p>
<a href="{{ route('login') }}">Get Started</a>
</div>

</body>
</html>