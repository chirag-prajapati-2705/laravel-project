<?php

namespace App\Http\Controllers\Admin;

use App\Model\Category;
use App\Model\ProductImage;
use Illuminate\Http\Request;


use App\Http\Requests;
use Redirect;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Input;
use DB;
use Session;
use Intervention\Image\Facades\Image;
USE App\Service\UploadService;
USE Auth;

class CategoryController extends Controller
{

    public function __construct()
    {

    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $rules = array(
            'category_name' => 'required',
            'url' => 'required',
            'status' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('admin/category/create')->withInput()->withErrors($validator);
        } else {
            $category = New Category();
            $category->fill($request->all());
            $category->save();
            Session::flash('success', 'Category successfully created!');
            return Redirect('admin/category/show');
        }
    }

    public function edit($category_id, Request $request)
    {

        $category = Category::find($category_id);
        return view('admin.category.edit', compact('category'));
    }
    public function show()
    {
        $categories = Category::all();
        return view('admin.category.view')->with('categories', $categories);
    }

    public function update($category_id, Request $request)
    {
        $rules = array(
            'category_name' => 'required',
            'url' => 'required',
            'status' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('admin/category/edit/'.$category_id)->withInput()->withErrors($validator);
        } else {
            $category = Category::find($category_id);
            $category->fill($request->all());
            $category->save();
            Session::flash('success', 'Category successfully updated!');
            return Redirect::back();
        }
    }

    public function destroy($category_id)
    {
        $delete_category = Category::destroy($category_id);

        if ($delete_category) {
            Session::flash('success', 'Category successfully deleted!');
            return Redirect('admin/category/show');
        }
    }
}
