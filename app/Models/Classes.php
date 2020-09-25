<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model {
    protected $fillable = ['name', 'slug'];
    const RULES_FOR_ADD = [
        'name' => [
            'required',
            'max:30',
            'min:2',
            'unique:classes',
        ],
    ];
    const RULES_FOR_EDIT = [
        'name' => [
            'required',
            'max:30',
            'min:2',
        ],
    ];
    
    const VALIDATION_ERROR_MESSAGES = [
        'name.required' => 'Vui lòng nhập tên lớp.',
        'name.max' => 'Tên lớp không được dài quá 30 kí tự',
        'name.min' => 'Tên lớp không được ít hơn 2 kí tự',
        'name.unique' => 'Đã tồn tại lớp học mang tên này rồi',
    ];


    public function students() {
        return $this->hasMany('App\Models\Student');
    }

}
