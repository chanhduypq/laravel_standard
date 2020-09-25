<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->isMethod("POST") === false){
            $hasVoChong = $hasNoVoChong = true;
        }
        else{
            $hasVoChong = $request->has('has_vo_chong');
            $hasNoVoChong = $request->has('has_no_vo_chong');
        }
        //
        $numberOfChildren = $request->get('number_of_children') ?? '';

        $query = Student::query();
        if ((!$hasVoChong && $hasNoVoChong) || ($hasVoChong && !$hasNoVoChong) ){
            if(!$hasVoChong && $hasNoVoChong){
                $query->whereNull('information->vo_chong');
            }
            else{
                $query->whereNotNull('information->vo_chong');
            }

        }
        if (ctype_digit($numberOfChildren)) {
            $query->where('information->number_of_children', '=', $numberOfChildren);
        }
        if ($request->has('interest')) {
            $interests = $request->get('interest');
            $query->where(function ($query) use ($interests) {
                foreach($interests as $interest) {
                    $query->orWhereJsonContains('interest', $interest);
                }
            });
        }
        $items = $query->orderBy('id', 'desc')->paginate(5);

        return view('student.index', [
                                        'items' => $items,
                                        'hasVoChong' => $hasVoChong,
                                        'hasNoVoChong' => $hasNoVoChong,
                                        'number_of_children' => $request->get('number_of_children'),
                                        'interest' => $request->get('interest')??[],
                                    ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = Gate::inspect('create', new Student());
        if (!$response->allowed()) {
            session()->flash('permission', $response->message());
            return redirect()->route('student');
        }

//        if(!auth()->user()->can('create', new Student())){
//            session()->flash('permission', 'Chỉ có user mang id 1,2 mới có quyền này thôi');
//            return redirect()->route('student');
//        }
        //
        return view('student.create', ['classes' => Classes::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = Gate::inspect('create', new Student());
        if (!$response->allowed()) {
            session()->flash('permission', $response->message());
            return redirect()->route('student');
        }
        //
        $model = new Student();
        $rules = Student::RULES_FOR_ADD;

        $validattionErrorMessages = Student::VALIDATION_ERROR_MESSAGES;
        $validator = Validator::make($request->all(), $rules, $validattionErrorMessages);

        if ($validator->fails()) {

            return redirect()->route('student.create')
                            ->withErrors($validator->errors())
                            ->withInput();
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
            $model->slug = Str::random(6) . '-' . Str::slug($model->name);
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
            try {
                $model->created_id = 1;//auth()->user()->id??1;
                $model->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return redirect()->route('student.create')
                            ->withErrors($validator->errors())
                            ->withInput();
            }
            if ($model->save()) {
                session()->flash('successful', 'Thêm mới thành công');
            } else {
                session()->flash('error', 'Thất bại, vui lòng thử lại');
            }
            //
            return redirect()->route('student');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student  $student, $slug)
    {
        $model = Student::where('slug', $slug)->first();
        /**
         * if the item has been removed
         */
        if ($model == null) {
            return redirect()->route('student');
        }

        $response = Gate::inspect('update', $model);
        if (!$response->allowed()) {
            session()->flash('permission', $response->message());
            return redirect()->route('student');
        }


        return view('student.edit', ['item' => $model, 'classes' => Classes::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $model = Student::where('slug', $slug)->first();
        /**
         * if the item has been removed
         */
        if ($model == null) {
            return redirect()->route('student');
        }
        $response = Gate::inspect('update', $model);
        if (!$response->allowed()) {
            session()->flash('permission', $response->message());
            return redirect()->route('student');
        }
        /**
         *
         */
        $rules = Student::RULES_FOR_EDIT;
        $validattionErrorMessages = Student::VALIDATION_ERROR_MESSAGES;

        $validator = Validator::make($request->all(), $rules, $validattionErrorMessages);
        if ($validator->fails()) {
            return redirect()->route('student.edit', ['slug' => $slug])
                            ->withErrors($validator->errors())
                            ->withInput();
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
            $model->slug = Str::random(6) . '-' . Str::slug($model->name);
            if(!$request->has('interest')){
                $model->interest = null;
            }
            if($request->file('photo')){
                Storage::delete('public/photos/'.$model->photo);
                Storage::delete('public/photos/thumbnail/'.$model->photo);
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
                Storage::delete('public/descriptions/'.$model->description);
                $path = Storage::putFile('public/descriptions', $request->file('description'));
                $model->description = str_replace('public/descriptions/', '', $path);
            }
            if ($model->save()) {
                session()->flash('successful', 'Sửa thành công');
            } else {
                session()->flash('error', 'Thất bại, vui lòng thử lại');
            }
            //
            return redirect()->route('student');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student, $slug)
    {
        $model = Student::where('slug', $slug)->first();
        /**
         * if the item has been removed
         */
        if ($model == null) {
            return redirect()->route('student');
        }
        $response = Gate::inspect('delete', $model);
        if (!$response->allowed()) {
            session()->flash('permission', $response->message());
            return redirect()->route('student');
        }
        /**
         * delete record on database
         */
        session()->flash('successful', 'Xóa thành công');
        $model->delete();

        return redirect()->route('student');
    }

    public function download($slug) {
        $model = Student::where('slug', $slug)->first();
        /**
         * if the item has been removed
         */
        if ($model == null || !$model->description || !file_exists(storage_path('app/public/descriptions/' . $model->description))) {
            return redirect()->route('student');
        }
        //download
        return response()->download(storage_path('app/public/descriptions/' . $model->description));
    }
}
