<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Login</title>
</head>
<style>
    .form-container {
        /*padding: 20%;*/
    }
</style>

<body>
<form method="post" action="{{route('post_login')}}">
    @csrf
    <h1>Login</h1>
    <input name="email" placeholder="Email" type="text" required="">
    <div class="text-error">
        @if(session('status_error_email'))
            {{session('status_error_email')}}
        @endif
    </div>
    <input name="password" placeholder="Password" type="password" required="" style="bottom: 0">
    <div class="text-error">
        @if(session('status_error_password'))
            {{session('status_error_password')}}
        @endif
    </div>
    <div class="text-error">
        @if(session('status_error'))
            {{session('status_error')}}
        @endif
    </div>
    <button>Submit</button>
    {{ csrf_field() }}

</form>
<style type="text/css">
    form {
        box-sizing: border-box;
        width: 300px;
        margin: 100px auto 0;
        box-shadow: 2px 2px 5px 1px rgba(0, 0, 0, 0.2);
        padding-bottom: 40px;
        border-radius: 3px;
    }

    form h1 {
        box-sizing: border-box;
        padding: 20px;
    }

    input {
        margin: 40px 25px 0;
        width: 200px;
        display: block;
        border: none;
        padding: 10px 0;
        border-bottom: solid 1px #1abc9c;
        transition: all 0.3s cubic-bezier(0.64, 0.09, 0.08, 1);
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 96%, #1abc9c 4%);
        background-position: -200px 0;
        background-size: 200px 100%;
        background-repeat: no-repeat;
        color: #0e6252;
    }

    input:focus,
    input:valid {
        box-shadow: none;
        outline: none;
        background-position: 0 0;
    }

    input:focus::-webkit-input-placeholder,
    input:valid::-webkit-input-placeholder {
        color: #1abc9c;
        font-size: 11px;
        transform: translateY(-20px);
        visibility: visible !important;
    }

    button {
        margin: 35px 0 0 0;
        border: none;
        background: #1abc9c;
        cursor: pointer;
        border-radius: 3px;
        padding: 6px;
        width: 200px;
        color: white;
        margin-left: 25px;
        box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.2);
    }

    button:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 6px 0 rgba(0, 0, 0, 0.2);
    }

    .text-error {
        color: tomato;
        padding: 0 24px;
    }
</style>
</body>

</html>
