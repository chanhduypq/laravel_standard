<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesCollection;
use App\Http\Resources\Classes as ClassesResource;
use App\Models\Classes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ClassApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ClassesCollection(Classes::paginate(2));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $model = Classes::find($id);
        if($model){
            return new ClassesResource(Classes::find($id));
        }
        
        return response()->json([
            'message' => 'Không tồn tại lớp này'
        ], 201);
    }
    
    public function delete($id)
    {
        $model = Classes::find($id);
        if($model){
            $message = 'Xóa thành công!';
            try {
                $model->delete();
            } catch (\Illuminate\Database\QueryException $e) {
                $message = 'Lớp này đang có sinh viên nên không được xóa.';
            }
        }
        else{
            $message = 'Không tồn tại lớp này!';
        }
        
        return response()->json([
            'message' => $message
        ], 201);
    }

    public function add(Request $request)
    {
        $message = 'Thêm mới thành công!';
        //
        $model = new Classes();
        $rules = [
            'name' => [
                'required',
                'max:30',
                'min:2',
                'unique:classes',
            ],
        ];
        $validattionErrorMessages = [
            'name.required' => 'Vui lòng nhập tên lớp.',
            'name.max' => 'Tên lớp không được dài quá 30 kí tự',
            'name.min' => 'Tên lớp không được ít hơn 2 kí tự',
            'name.unique' => 'Đã tồn tại lớp học mang tên này rồi',
        ];
        $validator = Validator::make($request->all(), $rules, $validattionErrorMessages);
        
        if ($validator->fails()) {
            $message = $validator->errors()->toArray()['name'][0];
        } else {
            /**
             * save on database
             */
            $columnsInTable = \Illuminate\Support\Facades\Schema::getColumnListing('classes');
            foreach ($request->all() as $key => $value) {
                if(in_array($key, $columnsInTable)){
                    $model->$key = $value;
                }
            }
            $model->slug = Str::slug($model->name);
            $model->save();
        }
        
        return response()->json([
            'message' => $message
        ], 201);
    }
    
    public function edit(Request $request, $id)
    {
        
        $message = 'sửa thành công!';
        //
        $model = Classes::find($id);
        $rules = [
            'name' => [
                'required',
                'max:30',
                'min:2',
            ],
        ];
        $rules['name'][] = Rule::unique('classes')->ignore($id, 'id');
        $validattionErrorMessages = [
            'name.required' => 'Vui lòng nhập tên lớp.',
            'name.max' => 'Tên lớp không được dài quá 30 kí tự',
            'name.min' => 'Tên lớp không được ít hơn 2 kí tự',
            'name.unique' => 'Đã tồn tại lớp học mang tên này rồi',
        ];
        $validator = Validator::make($request->all(), $rules, $validattionErrorMessages);
        if ($validator->fails()) {
            $message = $validator->errors()->toArray()['name'][0];
        } else {
            /**
             * save on database
             */
            $columnsInTable = \Illuminate\Support\Facades\Schema::getColumnListing('classes');
            foreach ($request->all() as $key => $value) {
                if(in_array($key, $columnsInTable)){
                    $model->$key = $value;
                }
            }
            $model->slug = Str::slug($model->name);
            $model->save();
        }
        
        return response()->json([
            'message' => $message
        ], 201);
    }
    
}
