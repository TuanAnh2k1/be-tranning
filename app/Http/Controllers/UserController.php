<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestAddUser;
use App\Http\Requests\RequestEditUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
Use Alert;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function postLogin(Request $request)
    {
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $arr = [
            $fieldType => $request->email,
            'password' => $request->password,
        ];

        if (!$arr[$fieldType])
            return redirect()->route('auth.login')->with('status_error_email', 'Vui lòng nhập email');
        if (!$arr['password'])
            return redirect()->route('auth.login')->with('status_error_password', 'Vui lòng nhập password');
        $data = User::where($fieldType, $arr[$fieldType])->first();
        if ($data->status == User::StatusLock) {
            return redirect()->route('post_login')->with('status_error', 'Tài khoản đã bị khóa vui lòng liên hệ admin');
        }
        if (Auth::guard('loyal_customer')->attempt($arr)) {
            return redirect()->route('get_all_user')->with('status_succses', 'Đăng nhập thành công');
        }
        return redirect()->route('auth.login')->with('status_error', 'Email hoặc mật khẩu không chính xác');
    }

    // Note: Tại sao có 2 function giống nhau?
    public function postRegister(RequestAddUser $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        if($request->hasFile('image')){
            $folder = User::FolderAvatar;
            $image = Storage::put('/'.$folder, $data['image']);
            $data['image'] = $image;
        }
        unset($data['_token']);
        User::create($data);
        return redirect()->route('auth.login');
    }

    public function postAddUser(RequestAddUser $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        if($request->hasFile('image')){
            $folder = User::FolderAvatar;
            $image = Storage::put('/'.$folder, $data['image']);
            $data['image'] = $image;
        }
        unset($data['_token']);
        User::create($data);
        return redirect()->route('get_all_user')->with('status_succses', 'Thêm thành công user');
    }

    public function logout()
    {
        Auth::guard('loyal_customer')->logout();
        return redirect()->route('auth.login');
    }

    public function getAllUser(Request $request)
    {
        $sort_date = $request->input('sort_date', 'moi_nhat');
        $page_size = number_format($request->input('page_size', 5));
        $date_filter_in = $request->input('date_filter_in', '');
        $date_filter_to = $request->input('date_filter_to', '');
        $search = $request->search;
        $filter_select = $request->input('filter_select', 'all');

        $sql = DB::table('userdb');
        if (!empty($search)) {
            $sql->where('username', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('birthday', 'like', "%$search%")
                ->orWhere('gender', 'like', "%$search%")
                ->get();
        }
        switch ($filter_select) {
            case 'nam': {
                $sql->where('gender', '=', User::Male);
                break;
            }
            case 'nu': {
                $sql->where('gender', '=', User::Female);
                break;
            }
            case 'hoatdong': {
                $sql->where('status', '=', User::StatusActive);
                break;
            }
            case 'khoa': {
                $sql->where('status', '=', User::StatusLock);
                break;
            }
            default;
        }
        if (!empty($date_filter_in) && !empty($date_filter_to)) $sql->where('created_at', '>=', $date_filter_in)
            ->where('created_at', '<=', $date_filter_to);
        $sort = $sort_date === 'moi_nhat' ? 'desc' : 'asc';
        $data = $sql->orderBy('created_at', $sort)->paginate($page_size);
        $lastPage = $data->lastPage();
        $currentPage = $data->currentPage();

        return view('listUser', ['data' => $data, 'sort_date' => $sort_date,
            'page_size' => intval($page_size),
            'search' => $search, 'filter_select' => $filter_select,
            'date_filter_in' => $date_filter_in,
            'date_filter_to' => $date_filter_to,
            'lastPage' => $lastPage,
            'currentPage'=>$currentPage,
        ]);
    }

    public function deleteUser(Request $request)
    {
        $delete_checked = $request->input('id_user','');
        $arr_delete_checked = explode(',',$delete_checked);
        if(!empty($arr_delete_checked) && $arr_delete_checked[0]==-1){
            return redirect()->route('get_all_user')->with('status_errors', 'Không được phép xóa tất cả dữ liệu');
        }
        if(!empty($arr_delete_checked)){
            foreach ($arr_delete_checked as $value){
                $id = number_format($value);
                $data = User::find($id);
                if(empty($data)){
                    return redirect()->route('get_all_user')->with('status_errors', 'Tài khoản không tồn tại');
                }
                if(Auth::guard('loyal_customer')->id()==$id){
                    return redirect()->route('get_all_user')->with('status_errors', 'Không thể xóa tài khoản đã đăng nhập');
                }
            }
            foreach ($arr_delete_checked as $value){
                $id = number_format($value);
                $user = User::find($id);
                $user->delete();
            }
            return redirect()->route('get_all_user')->with('status_succses', 'Xóa thành công user');
        }
        return redirect()->route('get_all_user')->with('status_errors', 'Không có user nào được chọn');
    }

    public function editUser(Request $request)
    {
        $id = $request->id;
        $data = User::find($id);
        if(empty($data)){
            return redirect()->route('get_all_user')->with('status_errors', 'Tài khoản không tồn tại');
        }
        return view('edit-user', ['data' => $data]);
    }

    public function updateUser(RequestEditUser $request)
    {
        $data = $request->all();
        $id = $request->id;
        $data_old = User::find($id);
        if($data_old->username != $request->username){
            $isNotExistName = User::select("*")
                ->where("username", $request->username)
                ->doesntExist();
            if (!$isNotExistName) {
                return redirect()->route('edit_user')->with('status_error_username', 'Người dùng đã tồn tại');
            }
        }
        if($request->hasFile('image')){
            $folder = User::FolderAvatar;
            $image = Storage::put('/'.$folder, $data['image']);
            $data['image'] = $image;
        }
        $data['password'] = bcrypt($request->password);
        $user = User::find($id);
        $user->update($data);

        return redirect()->route('get_all_user')->with('status_succses', 'Cập nhật thành công user');
    }
}
