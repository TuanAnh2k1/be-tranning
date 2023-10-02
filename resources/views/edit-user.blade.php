<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<title>Edit user</title>
</head>
<body>
<form method="post" action="{{route('update_user', ['id'=>$data->id])}}" style="padding: 32px" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <a style="color: tomato">*</a>
        <input disabled type="email" name="email" class="form-control" id="exampleInputEmail1"
               aria-describedby="emailHelp"
               value="{{$data->email}}">
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Username</label>
        <a style="color: tomato">*</a>
        <input type="text" name="username" class="form-control" id="exampleInputUsername" aria-describedby="emailHelp" value="{{$data->username}}">
        @error('username')
        <span class="text-danger">{{ $message }}</span>
        @enderror
        <div class="text-error">
            @if(session('status_error_username'))
                {{session('status_error_username')}}
            @endif
        </div>
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
        <label for="exampleInputEmail1" class="form-label">Name</label>
        <a style="color: tomato">*</a>
        <input type="text" name="name" class="form-control" id="exampleInputName" aria-describedby="emailHelp" value="{{$data->name}}">
        @error('name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="exampleInputGender" class="form-label">Gender</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" id="nam" value="nam"
                   @if($data->gender=='nam') checked @endif>
            <label class="form-check-label" for="boy">
                Nam
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" id="nu" value="nu"
                   @if($data->gender=='nu') checked @endif>
            <label class="form-check-label" for="khac">
                Nữ
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" id="khac" value="khac"
                   @if($data->gender=='khac') checked @endif>
            <label class="form-check-label" for="khac">
                Khác
            </label>
        </div>
    </div>
    <div class="mb-3">
        <label for="exampleInputStaus" class="form-label">Status</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="status" value= 1
                   @if($data->status==1) checked @endif>
            <label class="form-check-label" for="active">
                Active
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="status" value= 0
                   @if($data->status==0) checked @endif>
            <label class="form-check-label" for="lock">
                Lock
            </label>
        </div>
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Birthday</label>
        <input type="date" name="birthday" class="form-control" id="exampleInputGender" aria-describedby="emailHelp"
               value="{{$data->birthday}}">
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Image </label>
        <img src="{{asset('storage/'.$data->image)}}" height="120px" width="120px"/>
        <input type="file" name="image" class="form-control" id="exampleInputImage" aria-describedby="emailHelp"
               placeholder="link ảnh" value="{{$data->image}}">
    </div>
    <div class="text-error">
        @if(session('status_error'))
            {{session('status_error')}}
        @endif
    </div>
    <div>
        <button type="button" class="btn btn-secondary" id="cancel">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

<style>
    .text-error {
        color: tomato;
        margin-bottom: 30px;
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
