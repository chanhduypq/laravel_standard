@extends('layouts.app')

@section('title', 'Student')

@section('script_page')
<script type="text/javascript">
    $('form').find('img, button, a, input[type="button"], input[type="submit"], svg').click(function (e) {
        $(this).parents('form').submit();
    });
    $("select#interest").bsMultiSelect({cssPatch : {
       choices: {columnCount:'3' },
    }});
</script>
@endsection

@section('content')

@if (Session::has('successful'))
    <div class="container alert-success rounded text-center mt-4 mb-4 col-sm-6">
        {{ Session::get('successful') }}
    </div>
@elseif (Session::has('error'))
    <div class="container alert-danger rounded text-center mt-4 mb-4 col-sm-6">
        {{ Session::get('error') }}
    </div>
@elseif (Session::has('permission'))
    <div class="container alert-danger rounded text-center mt-4 mb-4 col-sm-6">
        {{ Session::get('permission') }}
    </div>
@endif
<div class="container mt-2 bg-info">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('student') }}" method="POST">
                <div class="col-md-12">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                      <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                    </svg>
                </div>
                @csrf
                <div class="form-row">
                    <label class="col-sm-2 col-form-label">&nbsp;</label>
                    <label class="col-sm-3">
                        <input type="checkbox" name="has_vo_chong" @if($hasVoChong) checked @endif>
                        Đã lập gia đình
                    </label>
                </div>
                <div class="form-row">
                    <label class="col-sm-2 col-form-label">&nbsp;</label>
                    <label class="col-sm-3">
                        <input type="checkbox" name="has_no_vo_chong" @if($hasNoVoChong) checked @endif>
                        Chưa lập gia đình
                    </label>
                </div>
                <div class="form-row">
                    <label class="col-sm-2 col-form-label">Số người con: </label>
                    <input class="form-control col-sm-2" type="number" name="number_of_children" value="{{ $number_of_children }}">
                </div>

                <div class="form-row">
                    <label class="col-sm-2 col-form-label">Sở thích: </label>
                    <select name="interest[]" id="interest" class="form-control"  multiple="multiple" style="display: none;">
                        <option value="học tập" @if(in_array("học tập", $interest)) selected @endif>Học tập</option>
                        <option value="làm việc" @if(in_array("làm việc", $interest)) selected @endif>Làm việc</option>
                        <option value="tập thể dục" @if(in_array("tập thể dục", $interest)) selected @endif>Tập thể dục</option>
                    </select>
                </div>


            </form>
        </div>
    </div>
</div>
<div class="container mt-1">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="w-20">
                                Tên
                            </th>
                            <th class="w-10">
                                Lớp
                            </th>
                            <th class="w-10">
                                Ảnh đại diện
                            </th>
                            <th class="w-10">
                                Thông tin chi tiết của sinh viên
                            </th>
                            <th class="w-20">
                                Sở thích
                            </th>
                            <th class="w-30">
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $student)
                        <tr>
                            <td>
                                {{ $student->name }}
                                <br>
                                {{ !empty($student->information->vo_chong)?('Vợ chồng: '.$student->information->vo_chong.';'):'' }}
                                {{ !empty($student->information->number_of_children)?('Số người con: '.$student->information->number_of_children):'' }}
                            </td>
                            <td>
                                {{ $student->classes->name }}
                            </td>
                            <td>
                                @if (!empty($student->photo))
                                    <img style="width: 50px;height: 50px;" src="{{ url('storage/photos/'.$student->photo) }}" alt="" title="" />
                                @endif
                            </td>
                            <td>
                                @if ($student->description)
                                <a href="{{ route('student.download', [$student->slug]) }}">
                                    <svg width="3em" height="3em" viewBox="0 0 16 16" class="bi bi-file-arrow-down-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                      <path fill-rule="evenodd" d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM8 5a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 9.293V5.5A.5.5 0 0 1 8 5z"/>
                                    </svg>
                                </a>
                                @endif
                            </td>
                            <td>
                                @if (!empty($student->interest))
                                    @foreach ($student->interest as $value)
                                        <div>{{ $value }}</div>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                <div class="row text-center">
                                    <div class="col-sm-6">
                                        <a title="Edit" href="{{ route('student.edit', [$student->slug]) }}">
                                            <svg width="3em" height="3em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="col-sm-6">
                                        <form action="{{ route('student.destroy', [$student->slug])}}" method="post">
                                            <svg width="3em" height="3em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                              <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                                            </svg>
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            </form>

        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-8">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
