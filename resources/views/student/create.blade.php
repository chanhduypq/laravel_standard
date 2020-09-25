@extends('layouts.app')

@section('title', 'Student')

@section('script_page')
<script>
    $("select#interest").bsMultiSelect({cssPatch : {
       choices: {columnCount:'3' },
    }});
</script>
@endsection

@section('content')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css?v3" rel="stylesheet" type="text/css">
 <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div>
                <form method="POST" action="{{ route('student.store')}}" enctype="multipart/form-data">
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label" for="name">Họ tên:</label>
                        <input type="text" class="form-control col-sm-10{{ $errors->has('name')?' is-invalid':'' }}" name="name" id="name" value="{{ old('name') }}"/>
                    </div>
                    @error('name')
                        <div class="row">&nbsp;</div>
                        <div class="form-row">
                            <label class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="form-control col-sm-10 text-danger">{{ $errors->first('name') }}</div>
                        </div>
                    @enderror
                    <div class="row">&nbsp;</div>
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label" for="classes_id">Lớp:</label>
                        <select class="form-control col-sm-10" name="classes_id">
                            <option value="">Chọn lớp</option>
                            @foreach ($classes as $class)
                            <option value="{{ $class->id }}"<?php if(old('classes_id') == $class->id) echo ' selected'; ?>>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('classes_id')
                        <div class="row">&nbsp;</div>
                        <div class="form-row">
                            <label class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="form-control col-sm-10 text-danger">{{ $errors->first('classes_id') }}</div>
                        </div>
                    @enderror
                    <div class="row">&nbsp;</div>
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label" for="photo">Photo: </label>
                        <input type="file" class="form-control col-sm-10{{ $errors->has('photo')?' is-invalid':'' }}" name="photo" id="photo"/>
                    </div>
                    @error('photo')
                        <div class="row">&nbsp;</div>
                        <div class="form-row">
                            <label class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="form-control col-sm-10 text-danger">{{ $errors->first('photo') }}</div>
                        </div>
                    @enderror
                    <div class="row">&nbsp;</div>
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label" for="description">Mô tả: </label>
                        <input type="file" class="form-control col-sm-10{{ $errors->has('description')?' is-invalid':'' }}" name="description" id="description"/>
                    </div>
                    @error('description')
                        <div class="row">&nbsp;</div>
                        <div class="form-row">
                            <label class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="form-control col-sm-10 text-danger">{{ $errors->first('description') }}</div>
                        </div>
                    @enderror
                    <div class="row">&nbsp;</div>
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label" for="vo_chong">Họ tên vợ/chồng: </label>
                        <input type="text" class="form-control col-sm-10{{ $errors->has('information.vo_chong')?' is-invalid':'' }}" name="information[vo_chong]" id="vo_chong" value="{{ old('information.vo_chong') }}"/>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label" for="number_of_children">Số con(nếu có): </label>
                        <input type="text" class="form-control col-sm-10{{ $errors->has('information.number_of_children')?' is-invalid':'' }}" name="information[number_of_children]" id="number_of_children" value="{{ old('information.number_of_children') }}"/>
                    </div>
                    @error('information.number_of_children')
                        <div class="row">&nbsp;</div>
                        <div class="form-row">
                            <label class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="form-control col-sm-10 text-danger">{{ $errors->first('information.number_of_children') }}</div>
                        </div>
                    @enderror
                    <div class="form-row">
                        <label class="col-sm-2 col-form-label">Sở thích: </label>
                        <select name="interest[]" id="interest" class="form-control"  multiple="multiple" style="display: none;">
                            <option value="học tập">Học tập</option>
                            <option value="làm việc">Làm việc</option>
                            <option value="tập thể dục">Tập thể dục</option>
                        </select>

                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">
                        <div class="col text-center">
                            <input type="submit" value="Save"/>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
