<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function index(Request $request)
    {
        try {
            $user = User::where('name','LIKE', '%' . $request->search . '%')->orderBy('id','desc')->get();

            return response()->json([
                'user'=>$user,
            ]);
            // return view('Admin.pages.auth.index',compact('user'));
        } catch (\Exception $e) {
            //throw $th;
            // dd($e);
            // return redirect()->back();
        }
    }
    //
    public function ShowUser(Request $request)
    {
        // $user = Auth::user();
        // // dd($user);
        // if($user){
            return response()->json([
                'user' => $request->user(),
            ]);
        // }
        // return response()->json([
        //     'message' => 'Không tìm thấy người dùng đã đăng nhập!',
        // ], 401);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string',
        ],
        [
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email phải là địa chỉ email hợp lệ',
            'password.required' => 'Mật khẩu không được bỏ trống',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray() // Lấy danh sách lỗi từ validate
            ], 422);
        }
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Thông tin tài khoản hoặc mật khẩu không chính xác!'
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken; // tạo mã token

        return response()->json([
            'message' => 'Đăng nhập thành công!',
            'user' => $user,
            'token' => $token
        ]);
    }
}
