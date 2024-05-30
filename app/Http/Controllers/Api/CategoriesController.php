<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $category = Category::get();
        return response()->json([
            'category'=>$category
        ]);
    }

    public function store(Request $request)
    {
        try {
            $category = new category();
            $category->name = $request->name??null;
            $category->save();

            return response()->json([
                'message' => "thêm mới thành công!",
            ]);
        } catch (\Exception $e) {

            dd($e);
        }
    }
    public function show(Request $request)
    {

        $category = category::where('id',$request->id)->first();
        return response()->json([
            $category
        ]);
    }

    public function update(Request $request)
    {
        try {
            $category = category::find($request->id);
            $category->name = $request->name??null;
            $category->update();

            return response()->json([
                'message'=> 'cập nhật thành công',
            ]);

        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function delete(Request $request)
    {
        try {
            $category = category::find($request->id);
            $category->delete();
            return response()->json([
                'message'=> 'xóa thành công',
            ]);
        } catch (\Exception $e) {
            //throw $th;
            dd($e);
        }
    }
}
