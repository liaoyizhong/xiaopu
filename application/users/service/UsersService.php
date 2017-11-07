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
    const PASSWORDTOKEN = 'jiaju';
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
        if(!isset($params['nick_name']) || !isset($params['password'])){
            return [FALSE,'缺必要参数'];
        }
        $model = new UsersModel();
        $list = $model->whereOr('nick_name',$params['nick_name'])->whereOr('phone',$params['nick_name'])->selectOrFail();
        if(!$list){
            [FALSE,'没有符合的数据'];
        }
        $passwordToken = self::PASSWORDTOKEN;
        $mdPassword = (string)md5($passwordToken.$params['password']);

        $checkState = FALSE;
        foreach ($list as $item){
            $itemData = $item->getData();
            if($mdPassword === $itemData['password']){
                $checkState = TRUE;
                break;
            }
        }
        if($checkState){
            return [TRUE,"登录成功"];
        }else{
            return [FALSE,"登录失败"];
        }
    }
}