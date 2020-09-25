<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentCollection;
use App\Http\Resources\Student as StudentResource;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class StudentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new StudentCollection(Student::paginate(2));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        if(!ctype_digit($id)){
            return response()->json([
                'message' => 'Id phải là số nguyên'
            ], 201);
        }
        $model = Student::find($id);
        if($model){
            return new StudentResource(Student::find($id));
        }
        
        return response()->json([
            'message' => 'Không tồn tại sinh viên này'
        ], 201);
    }
    
    public function delete($id)
    {
        if(!ctype_digit($id)){
            return response()->json([
                'message' => 'Id phải là số nguyên'
            ], 201);
        }
        $model = Student::find($id);
        if($model){
            $message = 'Xóa thành công!';
            $model->delete();
        }
        else{
            $message = 'Không tồn tại sinh viên này!';
        }
        
        return response()->json([
            'message' => $message
        ], 201);
    }

    public function add(Request $request)
    {
        $response = Gate::inspect('create', new Student());
        if (!$response->allowed()) {
            return response()->json([
                'message' => $response->message()
            ], 201);
        }
        $message = 'Thêm mới thành công!';
        //
        $model = new Student();
        $rules = Student::RULES_FOR_ADD;
        $validattionErrorMessages = Student::VALIDATION_ERROR_MESSAGES;
        $validator = Validator::make($request->all(), $rules, $validattionErrorMessages);
        
        if ($validator->fails()) {
            if(!empty($validator->errors()->toArray()['name'])){
                $message = $validator->errors()->toArray()['name'][0];
            }
            else{
                $message = $validator->errors()->toArray()['classes_id'][0];
            }
            
            return response()->json([
                'message' => $message
            ], 201);
            
        } else {
            /**
             * save on database
             */
            $columnsInTable = \Illuminate\Support\Facades\Schema::getColumnListing('students');
            foreach ($request->all() as $key => $value) {
                if(in_array($key, $columnsInTable)){
                    $model->$key = $value;
                }
            }
            $model->slug = Str::slug($model->name);
            if($request->file('photo')){
                $path = Storage::putFile('public/photos', $request->file('photo'));
                $model->photo = str_replace('public/photos/', '', $path);
                
                $image = $request->file('photo');

                $destinationPath = storage_path('app/public/photos/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$model->photo);
                
            }
            if($request->file('description')){
                $path = Storage::putFile('public/descriptions', $request->file('description'));
                $model->description = str_replace('public/descriptions/', '', $path);
            }    
            $model->created_id = auth()->user()->id;
            $model->save();
            
            return new StudentResource($model);
        }
        
        
    }
    
    public function edit(Request $request, $id)
    {
        if(!ctype_digit($id)){
            return response()->json([
                'message' => 'Id phải là số nguyên'
            ], 201);
        }
        
        $message = 'sửa thành công!';
        //
        $model = Student::find($id);
        $rules = Student::RULES_FOR_EDIT;
        
        $validattionErrorMessages = Student::VALIDATION_ERROR_MESSAGES;
        $validator = Validator::make($request->all(), $rules, $validattionErrorMessages);
        if ($validator->fails()) {
            if(!empty($validator->errors()->toArray()['name'])){
                $message = $validator->errors()->toArray()['name'][0];
            }
            else{
                $message = $validator->errors()->toArray()['classes_id'][0];
            }
            return response()->json([
                'message' => $message
            ], 201);
        } else {
            /**
             * save on database
             */
            $columnsInTable = \Illuminate\Support\Facades\Schema::getColumnListing('students');
            foreach ($request->all() as $key => $value) {
                if(in_array($key, $columnsInTable) && $key != 'photo' && $key != 'description'){
                    $model->$key = $value;
                }
            }
            try {
                $model->slug = Str::slug($model->name);
                $model->save();
                return new StudentResource($model);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => 'không tồn tại sinh viên có id '.$request->all()['classes_id']
                ], 201);
            }
        }
    }
    
    public function editone(Request $request, $id)
    {
        
        $message = 'sửa thành công!';
        //
        $model = Student::find($id);
        $rules = [
            'name' => [
                'max:30',
                'min:2',
            ],
        ];
        if($request->has('name')){
            array_unshift($rules['name'] , 'required');
        }
        
        $validattionErrorMessages = [
            'name.required' => 'Vui lòng nhập tên.',
            'name.max' => 'Tên không được dài quá 30 kí tự',
            'name.min' => 'Tên không được ít hơn 2 kí tự',
        ];
        $validator = Validator::make($request->all(), $rules, $validattionErrorMessages);
        if ($validator->fails()) {
            if(!empty($validator->errors()->toArray()['name'])){
                $message = $validator->errors()->toArray()['name'][0];
            }
            else{
                $message = $validator->errors()->toArray()['classes_id'][0];
            }
            
            return response()->json([
                'message' => $message
            ], 201);
        } else {
            /**
             * save on database
             */
            $columnsInTable = \Illuminate\Support\Facades\Schema::getColumnListing('students');
            foreach ($request->all() as $key => $value) {
                if(in_array($key, $columnsInTable)){
                    $model->$key = $value;
                }
            }
            $model->slug = Str::slug($model->name);
            try {
                $model->save();
                return new StudentResource($model);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => 'không tồn tại lớp có id '.$request->all()['classes_id']
                ], 201);
            }
        }
        
    }
    
}
