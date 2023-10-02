<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
    <title>Sign up</title>
</head>
<body>
<form action="{{route('post_register')}}" method="POST" style="padding: 32px" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label ">Email address</label>
        <a style="color: tomato">*</a>
        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        @error('email')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <a style="color: tomato">*</a>
        <input type="password" name="password" class="form-control" id="password">
        @error('password')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
        <a style="color: tomato">*</a>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control">
        @error('confirm_password')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Username</label>
        <a style="color: tomato">*</a>
        <input type="text" name="username" class="form-control" id="exampleInputUsername" aria-describedby="emailHelp">
        @error('username')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Name</label>
        <a style="color: tomato">*</a>
        <input type="text" name="name" class="form-control" id="exampleInputName" aria-describedby="emailHelp">
        @error('name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="exampleInputGender" class="form-label">Gender</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" id="nam" value="nam" checked>
            <label class="form-check-label" for="boy">
                Nam
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" id="nu" value="nu">
            <label class="form-check-label" for="khac">
                Nữ
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" id="khac" value="khac">
            <label class="form-check-label" for="khac">
                Khác
            </label>
        </div>
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Birthday</label>
        <a style="color: tomato">*</a>
        <input type="date" name="birthday" class="form-control" id="exampleInputGender" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Image</label>
        <a style="color: tomato">*</a>
        <input type="file" name="image" class="form-control" id="exampleInputImage" aria-describedby="emailHelp" placeholder="link ảnh">
    </div>
    <div class="text-error">
        @if(session('status_error'))
            {{session('status_error')}}
        @endif
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<style>
    .text-error{
        color: tomato;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript">
    var password = document.getElementById("password")
        , confirm_password = document.getElementById("confirm_password");

    function validatePassword() {
        if (password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>
</body>

</html>
