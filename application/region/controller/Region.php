<?php

namespace app\region\controller;

use \app\common\controller\Basic as BasicController;
use app\common\enums\ResponseCode;
use think\Response;

/**
 * User: liaoyizhong
 * Date: 2017/11/9/009
 * Time: 10:21
 */
class Region extends BasicController
{
    public function listProvinces()
    {
        $service = \think\Loader::model('RegionService', 'service');
        $data = $service->listProvinces();
        if (!$data) {
            $this->showResponse(ResponseCode::DATA_MISS, '数据不存在');
        }
        $this->showResponse(ResponseCode::SUCCESS, '', $data);
    }

    public function listCitys()
    {
        $service = \think\Loader::model('RegionService', 'service');
        $data = $service->listCitys();
        if (!$data) {
            $this->showResponse(ResponseCode::DATA_MISS, '数据不存在');
        }
        $this->showResponse(ResponseCode::SUCCESS, '', $data);
    }

    public function listAreas()
    {
        $service = \think\Loader::model('RegionService', 'service');
        $data = $service->listAreas();
        if (!$data) {
            $this->showResponse(ResponseCode::DATA_MISS, '数据不存在');
        }
        $this->showResponse(ResponseCode::SUCCESS, '', $data);
    }

    public function findProvince($id)
    {
        $service = \think\Loader::model('RegionService', 'service');
        $data = $service->findProvince($id);
        if (!$data) {
            $this->showResponse(ResponseCode::DATA_MISS, '数据不存在');
        }
        $this->showResponse(ResponseCode::SUCCESS, '', $data);
    }

    public function findCity($id)
    {
        $service = \think\Loader::model('RegionService', 'service');
        $data = $service->findCity($id);
        if (!$data) {
            $this->showResponse(ResponseCode::DATA_MISS, '数据不存在');
        }
        $this->showResponse(ResponseCode::SUCCESS, '', $data);
    }

    public function findArea($id)
    {
        $service = \think\Loader::model('RegionService', 'service');
        $data = $service->findArea($id);
        if (!$data) {
            $this->showResponse(ResponseCode::DATA_MISS, '数据不存在');
        }
        $this->showResponse(ResponseCode::SUCCESS, '', $data);
    }

    public function zhongShan()
    {
        $service = \think\Loader::model('RegionService', 'service');
        $data = $service->zhongShan();
        if (!$data) {
            $this->showResponse(ResponseCode::DATA_MISS, '数据不存在');
        }
        $this->showResponse(ResponseCode::SUCCESS, '', $data);
    }
}