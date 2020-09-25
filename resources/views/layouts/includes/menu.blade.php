<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
    <div class="container">

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Trang chủ
                        <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle<?php if (class_basename(Route::current()->controller) == 'ClassController') { echo ' active'; } ?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Lớp
                    </a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('class') }}">Danh sách</a>
                        <a class="dropdown-item" href="{{ route('class.create') }}">Tạo mới</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle<?php if (class_basename(Route::current()->controller) == 'StudentController') { echo ' active'; } ?>" href="#" id="navbarDropdown1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sinh viên
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('student') }}">Danh sách</a>
                        <a class="dropdown-item" href="{{ route('student.create') }}">Tạo mới</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link<?php if (class_basename(Route::current()->controller) == 'ReportController') { echo ' active'; } ?>" href="{{ route('file-import-export') }}">
                        Thống kê
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
