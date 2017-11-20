<?php

namespace app\residences\controller;

use app\common\controller\Basic as BasicController;
use app\common\enums\ResponseCode;
use think\Request;

class Residences extends BasicController
{
    protected $beforeActionList = [
        'checkLogin',
    ];

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $params = [];
        if(isset($_GET['page'])){
            $params['page'] = $_GET['page'];
        }
        if(isset($_GET['size'])){
            $params['size'] = $_GET['size'];
        }
        if(isset($_GET['is_hidden'])){
            $params['where']['is_hidden'] = $_GET['is_hidden'];
        }
        $service = \think\Loader::model("ResidencesService", "service");
        $model = $service->listModels($params);
        if (!$model) {
            $this->showResponse(ResponseCode::DATA_MISS, '查不到该数据');
        }

        $this->showResponse(ResponseCode::SUCCESS, '', $model);
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
        if(!isset($params['main']['name']) || !isset($params['main']['region_id']) || !isset($params['main']['address']))
        {
            $this->showResponse(ResponseCode::PARAMS_MISS, '缺必要参数');
        }
        $this->execSave($params);
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        $service = \think\Loader::model("\app\\residences\service\ResidencesService", "service");
        $model = $service->get($id);
        if (!$model) {
            $this->showResponse(ResponseCode::DATA_MISS, '查不到该数据');
        }
        $data = $service->getRow($model);
        $this->showResponse(ResponseCode::SUCCESS, '', $data);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {

    }

    /**
     * 保存更新的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function update($id)
    {
        $json = file_get_contents("php://input");
        $params = json_decode($json, true);
        $params['main']['id'] = $id;
        $this->execSave($params);
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $service = \think\Loader::model("\app\\residences\service\ResidencesService", "service");
        $result = $service->deleteModel($id);
        if ($result[0]) {
            $this->showResponse(ResponseCode::SUCCESS, $result[1]);
        } else {
            $this->showResponse(ResponseCode::DATA_ERROR, $result[1]);
        }
    }

    /**
     * @param $params
     */
    protected function execSave($params)
    {
        //开启事务
        \think\Db::startTrans();
        try {
            $service = \think\Loader::model("\app\\residences\service\ResidencesService", "service");
            $result = $service->saveModel($params['main']);
            //保存设计方案
            if (isset($params['design']) && count($params['design'])) {
                if(isset($params['main']['id'])){
                    //先清空原来的设计方案
                    $service->deleteDesigns();
                }
                foreach ($params['design'] as $key => $item) {
                    $designParams[$key] = $item;
                    $designParams[$key]['residences_id'] = $result;
                }
                $designService = \think\Loader::model("\app\\residences\service\ResidencesDesignService", "service");
                if ($designService->saveAll($designParams)) {
                    \think\Db::commit();
                    $this->showResponse(ResponseCode::SUCCESS, '保存成功');
                } else {
                    \think\Db::rollback();
                    $this->showResponse(ResponseCode::DATA_ERROR, '保存失败');
                }
            } else {
                \think\Db::commit();
                $this->showResponse(ResponseCode::SUCCESS, '保存成功');
            }
        }catch (\exception $e){
            \think\Db::rollback();
            $service = \think\Loader::model('\app\common\service\ErrorLogService','service');
            $service->save($e->getMessage());
            $this->showResponse(ResponseCode::DATA_ERROR, '保存失败');
        }
    }
}
