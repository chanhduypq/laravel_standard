<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
//    public function before($user, $ability)
//    {
//        if ($user->isSuperAdmin()) {
//            return true;
//        }
//    }
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Auth\Access\Response
     */
    public function update(User $user, Student $student)
    {
        return $user->id === $student->created_id
                ? Response::allow()
                : Response::deny('Chỉ user thêm mới sinh viên này mới có quyền sửa');
    }

    /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        //
        return ($user->id === 1 || $user->id === 2)
                ? Response::allow()
                : Response::deny('Chỉ có user mang id 1 hoặc 2 mới có quyền này thôi');
    }
    public function delete(User $user, Student $student)
    {
        return $user->id === $student->created_id
                ? Response::allow()
                : Response::deny('Chỉ user thêm mới sinh viên này mới có quyền xóa');
    }
}

