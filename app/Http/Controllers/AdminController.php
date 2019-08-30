<?php
namespace App\Http\Controllers;

class AdminController extends Controller
{
    protected $http_code = 200;

    public function index()
    {
        return view('master');
    }

    public function successResponse($message='', $data=[])
    {
        return $this->response(true, $message, $data);
    }

    public function errorResponse($message='', $data=[])
    {
        return $this->response(false, $message, $data);
    }

    public function response($success=true, $message='', $data=[])
    {
        $data_response = [
            'success' => $success,
            'msg'   => $message,
            'data'  => $data
        ];

        if ( empty($data) ) unset($data_response['data']);

        return response()->json($data_response);
    }

}