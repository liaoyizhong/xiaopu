<?php

namespace app\common\controller;

use app\common\enums\ResponseCode;
use \think\Controller as thinkController;

/**
 * User: liaoyizhong
 * Date: 2017/11/7/007
 * Time: 11:46
 */
abstract class Basic extends thinkController
{
    private $userId;

    public function showResponse($code = ResponseCode::SUCCESS, $message = '', $data = array())
    {
        if(isset($data['list'])){
            $array['code'] = $code;
            $array['message'] = $message;
            $array["total"] = isset($data['total'])?$data['total']:"";
            $array["per_page"] = isset($data['per_page'])?$data['per_page']:"";
            $array["current_page"] = isset($data['current_page'])?$data['current_page']:"";
            $array["last_page"] = isset($data['last_page'])?$data['last_page']:"";
            $array['data'] = $data['list'];
        }else{
            $array = [
                'code' => $code,
                'message' => $message,
                'data' => $data
            ];
        }

        $json = json_encode($array);
        echo $json;
        exit;
    }

    /**
     *  检查登录
     */
    public function checkLogin()
    {
        if (!isset($_SERVER['HTTP_TOKEN'])) {
            $this->showResponse(ResponseCode::PARAMS_MISS, '缺token');
        }
        $redis = new \think\cache\driver\Redis();
        $userId = $redis->get($_SERVER['HTTP_TOKEN']);
        if (!$userId) {
            $this->showResponse(ResponseCode::DATA_MISS, '无效token');
        }
    }
}