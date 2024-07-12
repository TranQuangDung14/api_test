<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    //
    public function index()
    {
        $product = Product::get();
        return response()->json([
            'product'=>$product
        ]);
    }

    public function store(Request $request)
    {
        try {
            $product = new Product();
            $product->category_id = $request->category_id;
            $product->name = $request->name??null;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->quantity = $request->quantity;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '-' . Str::slug($image->getClientOriginalName(), '-') . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/image', $filename);
                $product->image = $filename;
            }
            $product->save();

            return response()->json([
                'message' => "thêm mới thành công!",
            ]);
        } catch (\Exception $e) {

            dd($e);
        }
    }
    public function show(Request $request)
    {
        // dd($request->all());
        $product = Product::where('id',$request->id)->first();

        return response()->json([
            $product
        ]);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $product = Product::find($request->id);
            $product->category_id = $request->category_id;
            $product->name = $request->name??null;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->quantity = $request->quantity;
            if ($request->hasFile('image')) {
                $image_old = $product->image;
                // dd('public/image/' .$image_old);
                Storage::delete('public/image/' . $image_old);
                $image = $request->file('image');
                $filename = time() . '-' . Str::slug($image->getClientOriginalName(), '-') . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/image', $filename);
                $product->image = $filename;
            }
            $product->update();

            return response()->json([
                'message'=> 'cập nhật thành công',
            ]);

        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function delete(Request $request,$id)
    {
        try {
            $product = Product::find($id);
            if (isset($product->image)) {
                $image_old = $product->image;
                Storage::delete('public/image/' . $image_old);
            }
            // dd($product);
            if (isset($product)) {
                $product->delete();
                return response()->json([
                    'message'=> 'xóa thành công',
                ]);
            }
            return response()->json([
                'message'=> 'Bản ghi đó không còn tồn tại',
            ]);
        } catch (\Exception $e) {
            //throw $th;
            // return response()->json([
            //     'message'=> 'Bản ghi đó không còn tồn tại',
            // ]);
            dd($e);
        }
    }

    public function sendMail()
    {
        try {
            // dd('vào11');
            $user = [
                'title' => 'Mail from Laravel',
                'body' => 'This is a test mail.'
            ];
    
            Mail::to('tranquangdung14062001@gmail.com')->send(new SendMail($user));
            // dd('vào112');
            return "Email đã được gửi thành công.";
        } catch (\Exception $e) {
            //throw $th;
            dd($e);
        }
    }
}
