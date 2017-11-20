<?php

namespace app\users\service;

use app\common\service\BasicService;
use app\users\models\UsersModel;

/**
 * User: liaoyizhong
 * Date: 2017/11/7/007
 * Time: 14:48
 */
class UsersService extends BasicService
{
    const PASSWORDPRE = 'jiaju';

    const TOKENPRE = 'XiaoPu';

    /**
     * @param $id
     * @return null|static
     */
    public function get($id)
    {
        return UsersModel::get($id);
    }

    public function checkLogin($params)
    {
        if(!isset($params['name']) || !isset($params['password'])){
            return [FALSE,'缺必要参数'];
        }
        $model = new UsersModel();
        $list = $model->whereOr('nick_name',$params['name'])->whereOr('phone',$params['name'])->selectOrFail();
        if(!$list){
            [FALSE,'没有符合的数据'];
        }
        $passwordToken = self::PASSWORDPRE;
        $mdPassword = (string)md5($passwordToken.$params['password']);

        $checkState = FALSE;
        $userId = 0;
        foreach ($list as $item){
            $itemData = $item->getData();
            if($mdPassword === $itemData['password']){
                $userId = $itemData['id'];
                $checkState = TRUE;
                break;
            }
        }
        if($checkState){
            $token = $this->createToken($params['name']);
            $redis = new \think\cache\driver\Redis();
            $redis->set($token,$userId,7200);
            return [TRUE,"登录成功",['token'=>$token]];
        }else{
            return [FALSE,"登录失败，帐号密码不正确"];
        }
    }

   protected function createToken($str)
   {
       return md5(\app\users\service\UsersService::TOKENPRE.$str.time());
   }
}