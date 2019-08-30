<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

/**
 * Class SmsService
 * @package App\Services
 * send sms to phone number using esms (https://esms.vn)
 */
class SmsService
{
    protected $api_key;
    protected $secret_key;
    protected $url;

    CONST GET_BALANCE = 'http://rest.esms.vn/MainService.svc/json/GetBalance/';
    CONST SEND_SMS = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get';

    CONST MSG_RESPONSE = [
        100     => 'Thành công',
        99      => 'Lỗi không xác định, thử lại sau',
        101     => 'Đăng nhập thất bại (api key hoặc secret key không đúng)',
        102     => 'Tài khoản bị khóa',
        103     => 'Số dư tài khoản không đủ',
        104     => 'Mã Brandname không đúng',
        118     => 'Loại tin nhắn không hợp lệ',
        119     => 'Brandname quảng cáo phải gửi ít nhất 20 số điện thoại',
        131     => 'Nội dung tin nhắn brandname quảng cáo tối đa 442 kí tự'
    ];

    public function __construct()
    {
        $this->api_key = env('ESMS_API_KEY', '2E275634319E77929B203BCFE78358');
        $this->secret_key = env('ESMS_SECRET_KEY', '1E7ECBD09EC8308584CEED63419E08');
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function request($params=[])
    {
        $response = Curl::to($this->url)
            ->withData($params)
            ->asJson()
            ->get();

        return $response;
    }

    /**
     * @function get account balance from https://esms.vn
     * @return array
     */
    public function getBalance()
    {
        $this->url = self::GET_BALANCE . "{$this->api_key}/{$this->secret_key}";
        $res = $this->request();
        if ($res->CodeResponse==100) {
            return $this->response(false, $this->getMsgResponse($res->CodeResponse), ['balance'=>$res->Balance]);
        }

        return $this->response(true, $this->getMsgResponse($res->CodeResponse));
    }

    /**
     * @function send sms to phone number
     * @param string $number
     * @param string $message
     * @return array
     */
    public function sendSMS($number='', $message='message content')
    {
        if ( empty($number) ) {
            return $this->response(true, 'yêu cầu số điện thoại người nhận');
        }
        if ( empty($message) ) {
            return $this->response(true, 'bắt buộc nội dung tin nhắn');
        }

        $this->url = self::SEND_SMS;

        $params = [
            'Phone' => $number,
            'Content'   => $message,
            'ApiKey'    => $this->api_key,
            'SecretKey' => $this->secret_key,
            'IsUnicode' => 0,               //1: tin nhắn có dấu (not recommend)
            'SmsType'   => 4
        ];

        $response = $this->request($params);

        if ($response->CodeResult==100) {
            Log::info('Gửi sms thành công đến số: '. $number . '. nội dung: '.$message);
            return $this->response(false, $this->getMsgResponse($response->CodeResult));
        } else {
            Log::error('Gửi sms lỗi đến số: '. $number . '. nội dung: '.$message . '. response: '. json_encode($response));
            return $this->response(true, $this->getMsgResponse($response->CodeResult));
        }
    }

    /**
     * @param $error
     * @param $msg
     * @param array $data
     * @return array
     */
    public function response($error, $msg, $data=[])
    {
        return [
            'error' => $error,
            'msg'   => $msg,
            'data'  => $data
        ];
    }

    /**
     * @param $code
     * @return mixed
     */
    public function getMsgResponse($code)
    {
        return self::MSG_RESPONSE[$code];
    }

}
