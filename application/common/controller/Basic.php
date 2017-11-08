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
        $array = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        $json = json_encode($array);
        echo $json;
        exit;
    }

    public function checkLogin()
    {
        if(!$_SERVER['HTTP_XTOKEN']){
            $this->showResponse(ResponseCode::PARAMS_MISS,'缺token');
        }
        $redis = new \think\cache\driver\Redis();
        $userId = $redis->get($_SERVER['HTTP_XTOKEN']);
        if(!$userId){
            $this->showResponse(ResponseCode::DATA_MISS,'无效token');
        }
    }
}