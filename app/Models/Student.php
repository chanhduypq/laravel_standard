<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    const RULES_FOR_ADD = [
        'name' => [
            'required',
            'max:30',
            'min:2',
        ],
        'classes_id' => 'required',
        'information.number_of_children' => 'nullable|numeric',
        'photo' => [
            'required',
            'mimes:jpeg,bmp,png',
            'max:512',//512KB
        ],
    ];
    const RULES_FOR_EDIT = [
        'name' => [
            'required',
            'max:30',
            'min:2',
        ],
        'classes_id' => 'required',
        'information.number_of_children' => 'nullable|numeric',
        'photo' => [
//            'required',
            'mimes:jpeg,bmp,png',
            'max:512',//512KB
        ],
    ];
    
    const VALIDATION_ERROR_MESSAGES = [
        'name.required' => 'Vui lòng nhập tên.',
        'name.max' => 'Tên không được dài quá 30 kí tự',
        'name.min' => 'Tên không được ít hơn 2 kí tự',
        'classes_id.required' => 'Vui lòng chọn lớp.',
    ];
    
    protected $fillable = ['name', 'classes_id', 'slug'];
    protected $casts = [
        'information' => 'object',
        'interest' => 'array'
    ];
    
    public function classes() {
        return $this->belongsTo('App\Models\Classes');
    }

    public static function boot() {
        parent::boot();

        self::creating(function($model) {
            
        });

        self::created(function($model) {
            $classModel = Classes::find($model->classes_id);
            $classModel->number_of_student++;
            $classModel->save();
        });

        self::updating(function($model) {
        });

        self::updated(function($model) {
        });

        self::deleting(function($model) {
        });

        self::deleted(function($model) {
            $classModel = Classes::find($model->classes_id);
            $classModel->number_of_student--;
            $classModel->save();
            
            Storage::delete('public/photos/'.$model->photo);
            Storage::delete('public/photos/thumbnail/'.$model->photo);
            Storage::delete('public/descriptions/'.$model->description);
        });
        
    }
}
