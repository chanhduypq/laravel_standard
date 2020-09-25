<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use Illuminate\Support\Str;

class CronJobController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createClass()
    {
//        $model = new Classes();
//        $model->name = Str::random();
//        $model->save();
        return 'tạo lớp thành công';
    }

    public function createStudent()
    {
        ini_set('max_execution_time', 0);

        $classesIds = Classes::pluck('id')->toArray();
        $array = [
            'Nhập môn cơ sở dữ liệu',
            'Cơ sở dữ liệu nâng cao',
            'Nhập môn lập trình',
            'Kĩ thuật lập trình',
            'Thiết kế web 1',
            'Thiết kế web 2',
            'Nhập môn lập trình web',
            'Lập trình web nâng cao',
            'php',
            'C#',
            'Java',
            'Quản lý hệ thống thông tin',
            'Toán logic 1',
            'Toán logic 2',
            'Lập trình Windows',
            'Lập trình C++',
            'IOS',
            'Android',
        ];

        if (empty($classesIds)) {
            foreach ($array as $mon) {
                $model = new Classes();
                $model->name = $mon;
                $model->slug = Str::random(6)."-".Str::slug($mon);
                $model->save();
            }
            $classesIds = Classes::pluck('id')->toArray();
        }

        $hoArr = ['Lê', 'Nguyễn', 'Trần', 'Đinh', 'Hoàng', 'Huỳnh', "Vũ", "Võ", "Đặng", "Bùi", "Đỗ", "Hồ", "Ngô", "Dương", "Lý", "Phạm", "Phan", "Trương"];
        $demArr = ['Công', 'Văn', 'Thị', 'Gia', 'Minh', 'Anh', 'Đức', 'Duy', "Tấn", "Ngọc"];
        $tenArr = [
            'Tuệ','Phúc',
            'Luân', 'Lâm',
            'Quang','Lựu',
            'Lý','Châu',
            'Tâm', 'Tuấn',
            "Thành", "Uyên",
            "Trinh", "Tú",
            "Hùng", "Ngọc",
            "Hiếu", "Hoàng",
            "Thanh", "Thịnh",
            "Hoa", "Hồng",
            "Minh", "Đạt",
            "Tùng", "Nguyên",
            "Hưng", "Lan",
            "Giang", "Huyền",
            "Thủy", "Thúy",
            "Quảng", "Hải",
            "Phương", "Liễu",
            "Duy","Nguyên",
            "Trang","Cường",
            "Bảo", "Phong",
            "Nam","Trí",
            "Đăng", "Việt",
            "Vũ", "Lê",
            "Lệ","Tưởng",
            "Tuyên","Danh",
            "Linh","An",
            "Bình","Cẩm",
            "Chuyên","Huy",
            "Dũng","Đức",
            "Dung","Quỳnh",
            "Tuân", "Sơn",
            "Khoa","Phụng",
            "Nhân","Trung",
            "Tính","Định",
            "Thái","Bằng",
            "Đào","Vy",
            "Tín","Thiện",
            "Chương","Thu",
            "Hạnh","Thư",
            "Thắng","Thân",
            ];
        for($i=0;$i<100000;$i++){
            $model = new Student();
            $ho = $hoArr[rand(0,17)];
            if(rand(1,2)==1){
                $dem = $demArr[rand(0,9)];
            }
            else{
                $dem = null;
            }
            $ten = $tenArr[rand(0,80)];
            $name = $ho." ".($dem?$dem." ":"").$ten;
            $model->name = $name;
            $model->slug = Str::random(6) . '-' . Str::slug($name);
            $model->classes_id = $classesIds[array_rand($classesIds)];
            $model->created_id = rand(1, 2);
            $countInterest = rand(1, 3);
            $interests = [];
            $fullInterest = ['học tập','làm việc','tập thể dục'];
            for($i=0;$i<$countInterest;$i++){
                $interests[$fullInterest[rand(0,2)]] = 1;
            }
            $model->interest = array_keys($interests);
            if(rand(1,2)==1){
                $ho = $hoArr[rand(0,17)];
                if(rand(1,2)==1){
                    $dem = $demArr[rand(0,9)];
                }
                else{
                    $dem = null;
                }
                $ten = $tenArr[rand(0,80)];
                $name = $ho." ".($dem?$dem." ":"").$ten;
                $information['information']['vo_chong'] = $name;
            }
            else{
                $information['information']['vo_chong'] = null;
            }
            if(rand(1,2)==1){
                $information['information']['number_of_children'] = rand(1, 10);
            }
            else{
                $information['information']['number_of_children'] = null;
            }

            $model->information = $information;
            $model->save();
        }

        return 'tạo sinh viên thành công';
    }


}
