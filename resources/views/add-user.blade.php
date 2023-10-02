<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<title>Add User</title>
</head>
<body>
<form action="{{route('post_add_user')}}" method="POST" style="padding: 32px" enctype="multipart/form-data">
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
        <label for="exampleInputEmail1" class="form-label">Username</label>
        <a style="color: tomato">*</a>
        <input type="text" name="username" class="form-control" id="exampleInputUsername" aria-describedby="emailHelp">
        @error('username')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <a style="color: tomato">*</a>
        <input type="password" name="password" id="password" class="form-control">
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
        <input type="date" name="birthday" class="form-control" id="exampleInputGender" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Image</label>
        <input type="file" name="image" class="form-control" id="exampleInputImage" aria-describedby="emailHelp"
               placeholder="link ảnh">
    </div>
    <div>
        <button type="button" class="btn btn-secondary" id="cancel">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>

</form>
<style>
    .text-error {
        color: tomato;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript">

    $(document).ready(function () {
        $('#cancel').on('click', function () {
            window.location = "http://127.0.0.1:8000/listUser";
        })
    })
</script>
</body>

</html>
