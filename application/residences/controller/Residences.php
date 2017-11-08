<?php
namespace app\residences\controller;

use \app\common\Controller\Basic as BasicController;
use app\common\enums\ResponseCode;
use think\Request;

class Residences extends BasicController
{
    public function __construct(Request $request = null)
    {
        return parent::__construct($request);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
//
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
     * @return \think\Response
     */
    public function save()
    {
        $json = file_get_contents("php://input");
        $params = json_decode($json, true);
        $this->execSave($params);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $service = \think\Loader::model("\app\\residences\service\ResidencesService","service");
        $model = $service->get($id);
        if($model){
            $this->showResponse(ResponseCode::SUCCESS,'',$model);
        }else{
            $this->showResponse(ResponseCode::DATA_MISS,'查不到该数据');
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {

    }

    /**
     * 保存更新的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function update($id)
    {
        $json = file_get_contents("php://input");
        $params = json_decode($json, true);
        $params['id'] = $id;
        $this->execSave($params);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $service = \think\Loader::model("\app\\residences\service\ResidencesService", "service");
        $result = $service->deleteModel($id);
        if($result[0]){
            $this->showResponse(ResponseCode::SUCCESS,$result[1]);
        }else{
            $this->showResponse(ResponseCode::DATA_ERROR,$result[1]);
        }
    }

    /**
     * @param $params
     */
    protected function execSave($params)
    {
        $service = \think\Loader::model("\app\\residences\service\ResidencesService", "service");
        $result = $service->saveModel($params);
        if ($result) {
            $this->showResponse(ResponseCode::SUCCESS, '保存成功');
        } else {
            $this->showResponse(ResponseCode::DATA_ERROR, '保存失败');
        }
    }
}
