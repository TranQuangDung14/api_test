<?php

namespace App\Http\Controllers\Api\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class AuthController extends Controller
{

    public function index(Request $request)
    {
        try {
            // dd('hêh');
            // $request->user();
            // dd($request->user()->role);
            $user = User::where('username', 'LIKE', '%' . $request->username . '%')->orderBy('id', 'desc');
            if($request->role){
                $user->where('role',$request->role);
            };
            if($request->user()->role == 1){
                $user;
            }
            else {
                $user->where('role',2);
                // $user = $user->where('role',2)->get();

            };
            $user =$user->paginate(1);
            return response()->json([
                'user' => $user,
            ],200);
        } catch (\Exception $e) {
        }
    }
    //
    public function ShowUser(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ],200);
    }

    public function Notlogin(Request $request)
    {
        return response()->json([
            'massage' => 'Bạn chưa đăng nhập',
        ],401);
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required|string',
                ],
                [
                    'email.required' => 'Email không được bỏ trống',
                    'email.email' => 'Email phải là địa chỉ email hợp lệ',
                    'password.required' => 'Mật khẩu không được bỏ trống',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->toArray() // Lấy danh sách lỗi từ validate
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
        } catch (\Exception $e) {
            //throw $th;
            return response()->json([
                'message' => $e
            ]);
        }

    }

    public function editfullname(Request $request)
    {
        // dd($request->all());
        // dd('aaa');
        // $user = User::find($request->user()->id);
        try {
            $user = $request->user();
            $user->fullname = $request->fullname;
            $user->update();
            return response()->json([
                'message' => 'cập nhật thành công!'
            ]);
        } catch (\Exception $e) {
            //throw $th;
            dd($e);
            return response()->json([
                'message' => 'cập nhật thất bại!'
            ]);
        }

        // dd($user);
    }

    public function create(Request $request)
    {
        try {
            $input = $request->all();
            $rules = array(
                // 'name' => 'required',
                'username' => 'required|string',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required',
                'role' => 'required',
            );
            $messages = array(
                'username.required'     => '--Tên người dùng không được để trống!--',
                'email.required'        => '--Email không được để trống!--',
                'email.string'          => '--Email phải là chuỗi!--',
                'email.email'           => '--Email không hợp lệ!--',
                'email.max'             => '--Email không được vượt quá 255 ký tự!--',
                'email.unique'          => '--Email đã tồn tại trong hệ thống!--',
                'password.required'     => '--Mật khẩu không được để trống!--',
                'role.required'         => '--Quyền không được để trống!--',
            );
            $validator = Validator::make($input, $rules, $messages);
            if ($validator->fails()) {
                    return response()->json([
                        'message' => $validator->errors()->toArray() // Lấy danh sách lỗi từ validate
                    ], 422);
            }
            $user = new User();
            $user->username = $request->username;
            $user->fullname = $request->fullname??'';
            $user->address = $request->address;
            // $user->avatar = $request->avatar;
            $user->number_phone = $request->number_phone;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->save();
            return response()->json([
                'message' =>'Thêm mới tài khoản thành công!',
            ],201);
        } catch (\Exception $e) {
            return response()->json([
                'message' =>'Có lỗi xảy ra!',
            ],400);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                dd($e),

            ], 200);
        }

    }
    public function edit(Request $request,$id)
    {
        try {
            $input = $request->all();
            $rules = array(
                'username' => 'required|string',
                'role' => 'required',
            );
            $messages = array(
                'username.required'     => '--Tên người dùng không được để trống!--',
                'role.required'         => '--Quyền không được để trống!--',
            );
            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                    return response()->json([
                        'message' => $validator->errors()->toArray() // Lấy danh sách lỗi từ validate
                    ], 422);
            }
            $user = User::findOrFail($id);
            $user->username = $request->username;
            $user->fullname = $request->fullname??'';
            $user->address = $request->address??'';
            // $user->avatar = $request->avatar;
            $user->number_phone = $request->number_phone;
            // $user->email = $request->email;
            $user->role = $request->role;
            $user->update();

            return response()->json([
                'message' => 'cập nhật thành công!'
            ],200);
        } catch (\Exception $e) {
            //throw $th;
            dd($e);
            return response()->json([
                'message' => 'cập nhật thất bại!'
            ],400);
        }
    }
    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'message' => 'Xóa thành công!'
            ]);
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    public function editavatar(Request $request,$id)
    {
        try {
            $avatar = User::findOrFail($id);
            // dd($avatar);
            if ($request->hasFile('avatar')) {
                $image_old = $avatar->avatar;
                Storage::delete('public/image/avatar/' . $image_old);
                $image = $request->file('avatar');
                $filename = time() . '-' . Str::slug($image->getClientOriginalName(), '-') . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/image/avatar', $filename);
                $avatar->avatar = $filename;
            }
            $avatar->save();
            // Toastr::success('cập nhật avatar thành công', 'success');
            return response()->json([
                'message' => 'cập nhật ảnh thành công!'
            ]);
            // return response()->json([
            //     'messege' => 'Cập nhật thành công!',
            // ], 200);
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Đăng xuất thành công!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Có lỗi xảy ra!'
            ]);
        }
    }

}
