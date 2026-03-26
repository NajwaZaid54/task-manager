<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | Task Manager</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
html, body{height:100%;}
body{display:flex;align-items:center;justify-content:center;background:#fde2e4;}
.login-container{
    width:50%;
    min-width:400px;
    max-width:600px;
    height:70%;
    background:white;
    padding:50px;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,0.15);
    display:flex;
    flex-direction:column;
    justify-content:center;
}
.title{text-align:center;font-size:32px;font-weight:600;margin-bottom:30px;color:black;}
input{
    width:100%;
    padding:15px;
    margin-bottom:10px;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:16px;
}
input:focus{outline:none;border-color:#ff4d8d;}
button{
    width:100%;
    padding:15px;
    background:#ff4d8d;
    border:none;
    color:white;
    font-size:16px;
    font-weight:500;
    border-radius:10px;
    cursor:pointer;
    transition:0.3s;
    margin-top:10px;
}
button:hover{background:#e63c79;}
.register-link{margin-top:20px;text-align:center;font-size:15px;}
.register-link a{color:#ff4d8d;text-decoration:none;font-weight:500;}
.error-msg{color:red;font-size:13px;margin-bottom:10px;}
</style>
</head>
<body>

<div class="login-container">

<div class="title">Task Manager Login</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
    @error('email') <p class="error-msg">{{ $message }}</p> @enderror

    <input type="password" name="password" placeholder="Password" required>
    @error('password') <p class="error-msg">{{ $message }}</p> @enderror

    <button type="submit">Login</button>
</form>

<div class="register-link">
Don't have an account? <a href="{{ route('register') }}">Register</a>
</div>

</div>

</body>
</html>