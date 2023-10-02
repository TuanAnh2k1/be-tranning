@php use Illuminate\Support\Facades\Storage; @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <title>List User</title>
</head>

<body>

    <script>
        $(document).ready(function() {
            @if (session('status_succses'))
                {
                    toastr.options.timeOut = 5000;
                    toastr.success('{{ session('status_succses') }}');
                }
            @elseif (session('status_errors')) {
                    toastr.options.timeOut = 5000;
                    toastr.error('{{ session('status_errors') }}');
                }
            @endif
        });
    </script>
    <style>
        .opacity {
            opacity: 0.2;
        }

        body {
            background-color: #f8f9fa !important
        }

        .p-4 {
            padding: 1.5rem !important;
        }

        .mb-0,
        .my-0 {
            margin-bottom: 0 !important;
        }

        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
        }

        /* user-dashboard-info-box */
        .user-dashboard-info-box .candidates-list .thumb {
            margin-right: 20px;
        }

        .user-dashboard-info-box .candidates-list .thumb img {
            width: 80px;
            height: 80px;
            -o-object-fit: cover;
            object-fit: cover;
            overflow: hidden;
            border-radius: 50%;
        }

        .user-dashboard-info-box .title {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            padding: 30px 0;
        }

        .user-dashboard-info-box .candidates-list td {
            vertical-align: middle;
        }

        .user-dashboard-info-box td li {
            margin: 0 4px;
        }

        .user-dashboard-info-box .table thead th {
            border-bottom: none;
        }

        .table.manage-candidates-top th {
            border: 0;
        }

        .user-dashboard-info-box .candidate-list-favourite-time .candidate-list-favourite {
            margin-bottom: 10px;
        }

        .table.manage-candidates-top {
            min-width: 650px;
        }

        .user-dashboard-info-box .candidate-list-details ul {
            color: #969696;
        }

        /* Candidate List */
        .candidate-list {
            background: #ffffff;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            border-bottom: 1px solid #eeeeee;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            padding: 20px;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        .candidate-list:hover {
            -webkit-box-shadow: 0px 0px 34px 4px rgba(33, 37, 41, 0.06);
            box-shadow: 0px 0px 34px 4px rgba(33, 37, 41, 0.06);
            position: relative;
            z-index: 99;
        }

        .candidate-list:hover a.candidate-list-favourite {
            color: #e74c3c;
            -webkit-box-shadow: -1px 4px 10px 1px rgba(24, 111, 201, 0.1);
            box-shadow: -1px 4px 10px 1px rgba(24, 111, 201, 0.1);
        }

        .candidate-list .candidate-list-image {
            margin-right: 25px;
            -webkit-box-flex: 0;
            -ms-flex: 0 0 80px;
            flex: 0 0 80px;
            border: none;
        }

        .candidate-list .candidate-list-image img {
            width: 80px;
            height: 80px;
            -o-object-fit: cover;
            object-fit: cover;
        }

        .candidate-list-title {
            margin-bottom: 5px;
        }

        .candidate-list-details ul {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-bottom: 0px;
        }

        .candidate-list-details ul li {
            margin: 5px 10px 5px 0px;
            font-size: 13px;
        }

        .candidate-list .candidate-list-favourite-time {
            margin-left: auto;
            text-align: center;
            font-size: 13px;
            -webkit-box-flex: 0;
            -ms-flex: 0 0 90px;
            flex: 0 0 90px;
        }

        .candidate-list .candidate-list-favourite-time span {
            display: block;
            margin: 0 auto;
        }

        .candidate-list .candidate-list-favourite-time .candidate-list-favourite {
            display: inline-block;
            position: relative;
            height: 40px;
            width: 40px;
            line-height: 40px;
            border: 1px solid #eeeeee;
            border-radius: 100%;
            text-align: center;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
            margin-bottom: 20px;
            font-size: 16px;
            color: #646f79;
        }

        .candidate-list .candidate-list-favourite-time .candidate-list-favourite:hover {
            background: #ffffff;
            color: #e74c3c;
        }

        .lock-color {
            color: #4a5568;
        }

        .candidate-banner .candidate-list:hover {
            position: inherit;
            -webkit-box-shadow: inherit;
            box-shadow: inherit;
            z-index: inherit;
        }

        .bg-white {
            background-color: #ffffff !important;
        }

        .p-4 {
            padding: 1.5rem !important;
        }

        .mb-0,
        .my-0 {
            margin-bottom: 0 !important;
        }

        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
        }

        .user-dashboard-info-box .candidates-list .thumb {
            margin-right: 20px;
        }

        .btn-add {
            background: #07a812;
        }

        .row-sort {
            display: flex;
            flex-direction: row;
        }

        tr:hover {
            background-color: aliceblue;
        }

        .btn-hover:hover {
            background-color: aliceblue;
        }

        .modal-delete {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, .4);
        }

        .modal-delete-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close-delete {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-delete:hover,
        .close-delete:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    <div class="content">
        <div class="container mt-3 mb-6" style="opacity: 1">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div>
                    <button type="button" class="btn btn-primary" id="logout">Logout</button>
                </div>
            </nav>
            <div class="container">
                <div class="row searchFilter">
                    <div class="col-sm-12">
                        <div class="input-group" style="justify-content: space-between">
                            <div style="flex-direction: row; display: flex">
                                <input type="text" name="search" id="search" value="{{ $search }}"
                                    required />
                                <div class="input-group-btn">
                                    <button type="submit" id="js__search-btn"
                                        class="btn btn-secondary btn-search">Search
                                    </button>
                                    <button id="addBtn" type="button" class="btn btn-secondary btn-add">
                                        <span class="glyphicon glyphicon-search"><i class="fas fa-plus"></i></span>
                                        <a href="{{ route('post_add_user') }}"
                                            style="text-decoration: none; color: white" class="label-icon">Add User</a>
                                    </button>
                                </div>
                            </div>
                            <div class="row searchFilter" style="justify-content: flex-end; display: flex">
                                <div class="col-12">
                                    <div class="input-group">
                                        <input type="date" name="date_filter_in" class="form-control btn-hover"
                                            id="date_filter_in" style="cursor: pointer" value="{{ $date_filter_in }}">
                                        <input type="date" name="date_filter_to" class="form-control btn-hover"
                                            id="date_filter_to" style="cursor: pointer" value="{{ $date_filter_to }}">
                                        <button type="submit" class="btn btn-primary" id="js__filter-date-btn">Fillter
                                            Date
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-4 mt-lg-0">
                <div class="row">
                    <div class="col-md-12">
                        <div class="user-dashboard-info-box table-responsive mb-0 bg-white p-4 shadow-sm">
                            <div style="float: right" class="row-sort col-12">
                                <div class="col-3"></div>
                                <button type="button" class="form-control btn-hover col-3" data-toggle="modal"
                                    data-target="#myModal" id="myModal">Delete checked
                                </button>
                                <!-- The Modal -->
                                <select class="form-control btn-hover col-3" name="sort_date" id="sort_date"
                                    style="cursor: pointer">
                                    <option value="moi_nhat" @if ($sort_date == 'moi_nhat') selected @endif>Ngày tạo
                                        mới nhất
                                    </option>
                                    <option value="cu_nhat" @if ($sort_date == 'cu_nhat') selected @endif>Ngày tạo
                                        cũ nhất
                                    </option>
                                </select>
                                <select class="form-control btn-hover col-3" name="filter_select" id="filter_select"
                                    style="cursor: pointer">
                                    <option value="all" @if ($filter_select == 'all') selected @endif>All
                                    </option>
                                    <option value="nam" @if ($filter_select == 'nam') selected @endif>Nam
                                    </option>
                                    <option value="nu" @if ($filter_select == 'nu') selected @endif>Nữ
                                    </option>
                                    <option value="hoatdong" @if ($filter_select == 'hoatdong') selected @endif>Hoạt động
                                    </option>
                                    <option value="khoa" @if ($filter_select == 'khoa') selected @endif>Bị Khóa
                                    </option>
                                </select>
                            </div>
                            <div style="float: right" class="row-sort col-12">
                                <a href="{{ '/listUser' }}">Bỏ chọn tất cả</a>
                            </div>
                            <table class="table manage-candidates-top mb-0">
                                <thead>
                                    <tr>
                                        <th>Candidate Name</th>
                                        <th class="text-center">Status</th>
                                        <th class="action text-right">Action</th>
                                        <th>Checked
                                            <input class="js-input-checkbox-all" type="checkbox" id="select_user_all"
                                                name="select_user_all" autocomplete="off" style="cursor: pointer">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr class="candidates-list">
                                            <td class="title">
{{--                                                <div class="thumb">--}}
{{--                                                    @if ($value->image !== null)--}}
{{--                                                    <img class="img-fluid" src="{{ Storage::url($value->image) }}"--}}
{{--                                                        alt="">--}}
{{--                                                    @endif--}}
{{--                                                </div>--}}
                                                <div class="candidate-list-details">
                                                    <div class="candidate-list-info">
                                                        <div class="candidate-list-title">
                                                            <h5 class="mb-0"><a
                                                                    href="">{{ $value->username }}</a></h5>
                                                        </div>
                                                        <div class="candidate-list-option">
                                                            <ul class="list-unstyled">
                                                                <li>Name:</li>
                                                                <li>{{ $value->name }}</li>
                                                            </ul>
                                                            <ul class="list-unstyled">
                                                                <li>Birthday:</li>
                                                                <li>{{ $value->birthday }}</li>
                                                            </ul>
                                                            <ul class="list-unstyled">
                                                                <li>Gender:</li>
                                                                <li>{{ $value->gender }}</li>
                                                            </ul>
                                                            <ul class="list-unstyled">
                                                                <li>Created At:</li>
                                                                <li>{{ $value->created_at }}</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            @if ($value->status === 1)
                                                <td class="candidate-list-favourite-time text-center">
                                                    <a class="candidate-list-favourite order-2 text-danger"
                                                        href="#"><i class="fas fa-check"
                                                            style="color: #07a812"></i></a>
                                                    <span class="candidate-list-time order-1">Active</span>
                                                </td>
                                            @else
                                                <td class="candidate-list-favourite-time text-center">
                                                    <a class="candidate-list-favourite order-2 text-danger"
                                                        href="#"><i class="fas fa-lock lock-color"></i></a>
                                                    <span class="candidate-list-time order-1">Lock</span>
                                                </td>
                                            @endif
                                            <td>
                                                <ul class="list-unstyled mb-0 d-flex justify-content-end">
                                                    <li><a href="{{ route('edit_user', $value->id) }}" method="get"
                                                            class="text-info" data-toggle="tooltip" title=""
                                                            data-original-title="Edit"><i
                                                                class="fas fa-pencil-alt"></i></a>
                                                    </li>
                                                    <li>
                                                        <div id={{ $value->id }} class="delete_user_id"
                                                            method="get" style="cursor: pointer">
                                                            <i class="far fa-trash-alt text-danger"></i>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul>
                                                    <input class="js-input-checkbox" type="checkbox" id="select_user"
                                                        name="select_user" value="{{ $value->id }}"
                                                        autocomplete="off" style="cursor: pointer">
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div style="float: left; flex-direction: row; display: flex">
                                <select class="form-control btn-hover" id="page_size" name="page_size"
                                    style="cursor: pointer">
                                    <option value=5 @if ($page_size == 5) selected @endif>5</option>
                                    <option value=10 @if ($page_size == 10) selected @endif>10</option>
                                    <option value=20 @if ($page_size == 20) selected @endif>20</option>
                                    <option value=50 @if ($page_size == 50) selected @endif>50</option>
                                    <option value=100 @if ($page_size == 100) selected @endif>100</option>
                                </select>
                                <div>
                                    @if ($data->hasPages())
                                        <!-- Pagination -->
                                        <div class="pull-right pagination">
                                            <ul class="pagination">
                                                {{-- Previous Page Link --}}
                                                @if ($data->onFirstPage())
                                                    <li class="disabled py-2 px-2">
                                                        <span><i class="fa fa-angle-left"></i></span>
                                                    </li>
                                                @else
                                                    <li class="py-2 px-2">
                                                        <a href="{{ $data->previousPageUrl() }}">
                                                            <span><i class="fa fa-angle-left"></i></span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @for ($i = 1; $i <= $lastPage; $i++)
                                                    <li class="page-item btn-hover  border  py-2 px-3 {{ $i }} {{ $currentPage == $i ? 'bg-primary text-white js-active-page' : '' }} js-paginate"
                                                        style="cursor: pointer">
                                                        {{ $i }}
                                                    </li>
                                                @endfor
                                                @if ($data->hasMorePages())
                                                    <li class="py-2 px-2">
                                                        <a href="{{ $data->nextPageUrl() }}">
                                                            <span><i class="fa fa-angle-right"></i></span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="disabled py-2 px-2">
                                                        <span><i class="fa fa-angle-right"></i></span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                        <!-- Pagination -->
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="myModalShow">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">DELETE USER</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    Xóa người dùng đã chọn
                </div>
                <!-- Modal footer -->
                <form action="{{ route('delete_user') }}" method="get">
                    <input type="text" id="user_id" name="id_user" hidden="hidden">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancel-delete"
                            data-dismiss="modal">Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" id="confirm-delete"
                            data-dismiss="modal">Delete
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $page_size = 5;
        $sort_date = 'moi_nhat';
        $search = '';
        $filter_select = '';
        $page = 1;
        $check_select_delete = 0;
        // const url = window.location.href;
        let selectUser = [];
        $(document).ready(function() {
            $('#sort_date').on('change', function() {
                setURL();
            })

            $('#filter_select').on('change', function() {
                setURL();
            })

            $('#page_size').on('change', function() {
                setURL();
            })

            $('#js__search-btn').on('click', function() {
                setURL();
            })
            $('#js__filter-date-btn').on('click', function() {
                setURL();
            })
            $('.js-input-checkbox').on('click', function() {
                if ($(this).is(":checked")) {
                    selectUser.push($(this).val());
                    console.log(selectUser);
                } else {
                    let arr = selectUser.filter(item => item !== $(this).val());
                    selectUser = [...arr];
                    console.log(selectUser);
                }
            })
            $('.js-input-checkbox-all').on('click', function() {
                if ($(this).is(":checked")) {
                    selectUser.push(-1);
                    $("input[class=js-input-checkbox]").prop("checked", true);
                } else {
                    selectUser = [];
                    $("input[class=js-input-checkbox]").prop("checked", false);
                }
            })
            $('.js-paginate').on('click', function() {
                $page = $(this).html().trim();
                setURL();
            })
            $('#logout').on('click', function() {
                window.location = "http://127.0.0.1:8000/logout";
            })
            $("#myModal").click(function() {
                $('.modal').addClass("d-block");
                $('.content').addClass("opacity");
                $('#user_id').val(selectUser)
            });
            $(".delete_user_id").click(function() {
                let user_id = $(this).attr('id')
                $('.modal').addClass("d-block");
                $('.content').addClass("opacity");
                $('#user_id').val(user_id)
            });
            $(".close").click(function() {
                $('.modal').removeClass("d-block");
                $('.content').removeClass("opacity");
            });
            $("#cancel-delete").click(function() {
                $('.modal').removeClass("d-block");
                $('.content').removeClass("opacity");
            });
            $("#confirm-delete").click(function() {
                $('.modal').removeClass("d-block");
                $('.content').removeClass("opacity");
            });
        })

        function setURL() {
            $search = $('#search').val().trim();
            $date_filter_in = $('#date_filter_in').val().trim();
            $date_filter_to = $('#date_filter_to').val().trim();
            $sort_date = $('#sort_date').val().trim();
            $filter_select = $('#filter_select').val().trim();
            $page_size = $('#page_size').val().trim();
            // TODO: get current URL
            $url_res =
                `http://127.0.0.1:8000/listUser?page_size=${$page_size}&page=${$page}&sort_date=${$sort_date}
        &search=${$search}&filter_select=${$filter_select}&date_filter_in=${$date_filter_in}&date_filter_to=${$date_filter_to}`;

            window.location = $url_res;
        }
    </script>
</body>

</html>
