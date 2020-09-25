<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        if (!Cache::has('classes')) {
//            Cache::put('classes', Classes::all(), 60);
//        }
//        $items = Cache::get('classes');
        $items = Classes::orderBy('id', 'desc')->paginate(5);

        return view('classes.index', array('items' => $items));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('classes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $model = new Classes();
        $rules = Classes::RULES_FOR_ADD;
        $validattionErrorMessages = Classes::VALIDATION_ERROR_MESSAGES;
        $validator = Validator::make($request->all(), $rules, $validattionErrorMessages);

        if ($validator->fails()) {
            return redirect()->route('class.create')
                            ->withErrors($validator->errors())
                            ->withInput();
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
            $model->slug = Str::random(6) . '-' . Str::slug($model->name);

            if ($model->save()) {
                session()->flash('successful', 'Thêm mới thành công');
            } else {
                session()->flash('error', 'Thất bại, vui lòng thử lại');
            }
            //
            return redirect()->route('class');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function show(Classes $classes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function edit(Classes  $classes, $slug)
    {
        $model = Classes::where('slug', $slug)->first();
        /**
         * if the item has been removed
         */
        if ($model == null) {
            return redirect()->route('class');
        }

        return view('classes.edit', ['item' => $model]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $model = Classes::where('slug', $slug)->first();
        /**
         * if the item has been removed
         */
        if ($model == null) {
            return redirect()->route('class');
        }
        /**
         *
         */
        $rules = Classes::RULES_FOR_EDIT;
        $rules['name'][] = Rule::unique('classes')->ignore($slug, 'slug');
        $validattionErrorMessages = Classes::VALIDATION_ERROR_MESSAGES;

        $validator = Validator::make($request->all(), $rules, $validattionErrorMessages);

        if ($validator->fails()) {
            return redirect()->route('class.edit', ['slug' => $slug])
                            ->withErrors($validator->errors())
                            ->withInput();
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
            $model->slug = Str::random(6) . '-' . Str::slug($model->name);
            if ($model->save()) {
                session()->flash('successful', 'Sửa thành công');
            } else {
                session()->flash('error', 'Thất bại, vui lòng thử lại');
            }
            //
            return redirect()->route('class');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classes $classes, $slug)
    {
        $model = Classes::where('slug', $slug)->first();
        /**
         * if the item has been removed
         */
        if ($model == null) {
            return redirect()->route('class');
        }
        /**
         * delete record on database
         */
        try {
            $model->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            session()->flash('error', 'Lớp này còn sinh viên nên không được xóa');
            return redirect()->route('class');
        }

        session()->flash('successful', 'Xóa thành công');
        return redirect()->route('class');
    }
}
