<?php

namespace App\Http\Controllers\Api\auth;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
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
            if ($request->role) {
                $user->where('role', $request->role);
            };
            if ($request->user()->role == 1) {
                $user;
            } else {
                $user->where('role', 2);
                // $user = $user->where('role',2)->get();

            };
            // $user = $user->paginate(1);
            $user = $user->paginate(1);
            return response()->json([
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
        }
    }
    //
    public function ShowUser(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ], 200);
    }

    public function Notlogin(Request $request)
    {
        return response()->json([
            'massage' => 'Bạn chưa đăng nhập',
        ], 401);
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
            $user->fullname = $request->fullname ?? '';
            $user->address = $request->address;
            // $user->avatar = $request->avatar;
            $user->number_phone = $request->number_phone;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->save();
            // mail
            // $info['username'] = $user->username;
            // $info['fullname'] = $user->fullname;
            // if($user){
            //     Mail::to($user->email)->send(new SendMail($info));
            // }
            return response()->json([
                // 'user'=>$user,
                'message' => 'Thêm mới tài khoản thành công!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra!',
            ], 400);
        }
    }

    public function veryfy($email)
    {
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
    public function edit(Request $request)
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
            $user = $request->user();
            $user->username = $request->username;
            $user->fullname = $request->fullname ?? '';
            $user->address = $request->address ?? '';
            // $user->avatar = $request->avatar;
            $user->number_phone = $request->number_phone;
            // $user->email = $request->email;
            $user->role = $request->role;
            $user->update();

            return response()->json([
                'message' => 'cập nhật thành công!'
            ], 200);
        } catch (\Exception $e) {
            //throw $th;
            dd($e);
            return response()->json([
                'message' => 'cập nhật thất bại!'
            ], 400);
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

    public function editavatar(Request $request)
    {
        try {
            $avatar = $request->user();
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

    public function changePassword(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json(['error' => 'Không tìm thấy người dùng được xác thực nào'], 403);
            }
    
            $input = $request->all();
            $rules = [
                'old_password' => 'required',
                'new_password' => 'required|min:3',
                'new_confirm_password' => 'required|same:new_password',
            ];
            $messages = [
                'old_password.required' => '--Mật khẩu cũ không được để trống!--',
                'new_password.required' => '--Mật khẩu mới không được để trống!--',
                'new_confirm_password.required' => '--Xác nhận mật khẩu mới không được để trống!--',
                'new_confirm_password.same' => '--Xác nhận mật khẩu mới không khớp!--',
            ];
    
            $validator = Validator::make($input, $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()
                ], 422);
            }
    
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json(['message' => 'Mật khẩu hiện tại không khớp'], 403);
            }
    
            $user->password = Hash::make($request->new_password);
            $user->save();
    
            return response()->json(['message' => 'Mật khẩu đã được thay đổi thành công'], 200);
        } catch (\Throwable $e) {
            Log::error('lỗi: ' . $e->getMessage());
            return response()->json(['message' => 'Đã có lỗi xảy ra, vui lòng thử lại sau'], 500);
        }
    }
    

    public function sendResetEmail(Request $request)
    {
        try {
            $input = $request->all();
            $rules = [
                'email' => 'required'
            ];
            $messages = [
                'email.required' => '--email không được để trống!--',
            ];
    
            $validator = Validator::make($input, $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()
                ], 422);
            }
            $status = Password::sendResetLink(
                $request->only('email')
            );
    
            if ($status == Password::RESET_LINK_SENT) {
                return response()->json(['message' => 'Đã gửi email chứa liên kết đặt lại mật khẩu'], 200);
            } else {
                return response()->json(['error' => 'Không thể gửi email đặt lại mật khẩu'], 500);
            }
        } catch (\Exception $e) {
            dd($e);
            Log::error('lỗi: ' . $e->getMessage());
            return response()->json(['message' => 'Đã có lỗi xảy ra, vui lòng thử lại sau'], 500);
        }
    }

    public function reset(Request $request)
    {
        // dd('vào');
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Mật khẩu đã được đặt lại thành công'], 200);
        } else {
            return response()->json(['error' => 'Không thể đặt lại mật khẩu'], 500);
        }
    }

}

// sửa theo id
  // public function edit(Request $request, $id)
    // {
    //     try {
    //         $input = $request->all();
    //         $rules = array(
    //             'username' => 'required|string',
    //             'role' => 'required',
    //         );
    //         $messages = array(
    //             'username.required'     => '--Tên người dùng không được để trống!--',
    //             'role.required'         => '--Quyền không được để trống!--',
    //         );
    //         $validator = Validator::make($input, $rules, $messages);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'message' => $validator->errors()->toArray() // Lấy danh sách lỗi từ validate
    //             ], 422);
    //         }
    //         $user = User::findOrFail($id);
    //         $user->username = $request->username;
    //         $user->fullname = $request->fullname ?? '';
    //         $user->address = $request->address ?? '';
    //         // $user->avatar = $request->avatar;
    //         $user->number_phone = $request->number_phone;
    //         // $user->email = $request->email;
    //         $user->role = $request->role;
    //         $user->update();

    //         return response()->json([
    //             'message' => 'cập nhật thành công!'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         //throw $th;
    //         dd($e);
    //         return response()->json([
    //             'message' => 'cập nhật thất bại!'
    //         ], 400);
    //     }
    // }