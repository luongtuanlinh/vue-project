<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\UserLoginRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherController extends AdminController
{
    public $userRepository;
    public $userLoginRepository;
    public $type;

    public function __construct(UserRepository $userRepository,
                                UserLoginRepository $userLoginRepository)
    {
        $this->userRepository = $userRepository;
        $this->userLoginRepository = $userLoginRepository;
        $this->type = User::ROLE_TEACHER;
    }

    public function all() {
        $all = $this->userRepository->allUser($this->type, ['name as text', 'id']);
        return $this->response(true, '', $all);
    }

    public function list(Request $request)
    {
        $list = $this->userRepository->userPaginate($this->type, $request->per_page);
        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        return $this->response(true, "", $data);
    }

    public function detail(Request $request) {
        $id = $request->id;
        $teacher = $this->userRepository->findById($id, ['name', 'phone', 'email']);
        return $this->response(true, '', $teacher);
    }

    public function store(UserRequest $request)
    {
        $data = $request->only('name', 'phone', 'email');
        $data['role'] = $this->type;
        DB::beginTransaction();
        try {
            $user_id = $this->userRepository->store($data);
            $this->userLoginRepository->storeUser(['user_id'=>$user_id, 'loginID'=>$data['email']], $this->type);
            DB::commit();
            return $this->response(true, 'thêm mới thành công');
        } catch(\Exception $ex) {
            DB::rollBack();
            Log::error('Create new teacher: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function update(UserRequest $request)
    {
        $data = $request->only('name', 'phone', 'email');
        DB::beginTransaction();
        try {
            $user_id = $this->userRepository->store($data, $request->id);
            $this->userLoginRepository->storeUser(['user_id'=>$user_id, 'loginID'=>$data['email']], $this->type);
            DB::commit();
            return $this->response(true, 'cập nhật dữ liệu thành công');
        } catch(\Exception $ex) {
            DB::rollBack();
            Log::error('update teacher: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'cập nhật không thành công');
        }
    }

    public function delete(Request $request)
    {
        $teacher = $this->userRepository->findById($request->id);

        if ( !$teacher ) {
            return $this->response(false, "dữ liệu không tồn tại hoặc đã bị xóa");
        }
        DB::beginTransaction();
        try{
            $user_login = $this->userLoginRepository->findUserLogin($teacher->id, $this->type);
            $user_login->delete();
            $teacher->delete();
            DB::commit();
            return $this->response(true, "xóa dữ liệu thành công");
        } catch(\Exception $ex) {
            Log::error('remove teacher: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, "xóa dữ liệu không thành công");
        }
    }
}
