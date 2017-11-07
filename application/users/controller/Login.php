<?php

namespace app\users\controller;

use \app\common\Controller\Basic as BasicController;
use think\Request;

class login extends BasicController
{
    public function check()
    {
        $json = file_get_contents("php://input");
        $params = json_decode($json, true);
        $service = \think\Loader::model('\app\users\service\UsersService','service');
        $model = $service->checkLogin($params);
        echo '<pre>';var_dump($model);echo '</pre>';exit();
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        
    }       

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
    
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
