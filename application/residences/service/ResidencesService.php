<?php

namespace app\residences\service;

use app\common\service\BasicService;
use app\residences\models\ResidencesModel;

/**
 * User: liaoyizhong
 * Date: 2017/11/8/008
 * Time: 14:42
 */
class ResidencesService extends BasicService
{
    private $residenceModel;

    public function getRow($model)
    {
        $return = $model->getData();
        $regionService = \think\Loader::model('\app\region\service\RegionService', 'service');
        $proId = $regionService->switchToProvince($model->region_id);
        $proMes = $regionService->findProvince($model->region_id);
        $cityId = $regionService->switchToCity($model->region_id);
        $cityMes = $regionService->findCity($model->region_id);
        $areaMes = $regionService->findArea($model->region_id);

        $regionMes['value'] = $proId;
        $regionMes['lable'] = $proMes;
        $subregionMes['value'] = $cityId;
        $subregionMes['lable'] = $cityMes;
        $minregionMes['value'] = $model->region_id;
        $minregionMes['lable'] = $areaMes;
        $subregionMes['children'] = $minregionMes;
        $regionMes['children'] = $subregionMes;
        $return['region'] = $regionMes;

        $zhongShan = $regionService->zhongShan();
        $return['region_all'] = $zhongShan;
        return $return;
    }

    /**
     * //创建或者更新model
     * @param $params
     * @return false|int
     */
    public function saveModel($params)
    {
        $redis = new \think\cache\driver\Redis();
        $userId = $redis->get($_SERVER['HTTP_TOKEN']);
        $time = date("Y-m-d H:i:s");
        if (isset($params['id']) && $params['id']) {
            if(!$this->residenceModel = $this->get($params['id'])){
                return FALSE;
            }
            $params['updator_id'] = $userId;
            $params['updatetime'] = $time;
            unset($params['id']);
            if (!$this->residenceModel) {
                return [FALSE, '没有该数据'];
            }
        } else {
            $this->residenceModel = new ResidencesModel();
            $params['creator_id'] = $userId;
            $params['updator_id'] = $userId;
            $params['createtime'] = $time;
            $params['updatetime'] = $time;
        }

        if (!$this->residenceModel->save($params)) {
            return FALSE;
        }
        if ($this->residenceModel->getLastInsID()) {
            return $this->residenceModel->getLastInsID();
        } else {
            return $this->residenceModel->id;
        }
    }

    public function listRow($model)
    {
        $regionService = \think\Loader::model('\app\region\service\RegionService', 'service');
        $return['id'] = $model->id;
        $return['region_id'] = $model->region_id;
        $return['city_text'] = $regionService->findCity($model->region_id);
        $return['area_text'] = $regionService->findArea($model->region_id);
        $return['name'] = $model->name;
        $return['updator'] = isset($model->updator->nick_name)?$model->updator->nick_name:"";
        $return['updatetime'] = $model->updatetime;
        $return['is_hidden'] = $model->is_hidden;
        $return['is_hidden_text'] = $model->is_hidden == 1 ? "已屏蔽" : "展示中";
        return $return;
    }

    /**
     * @param array $params 查询条件
     */
    public function listModels($params = [])
    {
        $model = new ResidencesModel();
        if (isset($params['page']) && isset($params['size'])) {
            if (isset($params['where']['is_hidden'])) {
                //这个model的分页有点奇怪它似乎独立于model这个对象
                $list = $model->page($params['page'], $params['size'])
                    ->where('is_hidden', $params['where']['is_hidden'])
                    ->where('is_delete', 2)
                    ->select();
            } else {
                //这个model的分页有点奇怪它似乎独立于model这个对象
                $list = $model->page($params['page'], $params['size'])
                    ->where('is_delete', 2)
                    ->select();
            }
        } else {
            if (isset($params['where']['is_hidden'])) {
                //这个model的分页有点奇怪它似乎独立于model这个对象
                $list = $model->where('is_hidden', $params['where']['is_hidden'])
                    ->where('is_delete', 2)
                    ->select();
            } else {
                //这个model的分页有点奇怪它似乎独立于model这个对象
                $list = $model->where('is_delete', 2)->select();
            }
        }

        if (isset($params['where'])) {
            foreach ($params['where'] as $key => $value) {
                $model->where($key, $value);
            }
        }
        $return['total'] = $model->count();
        $return['per_page'] = isset($params['size']) ? $params['size'] : 1;
        $return['current_page'] = isset($params['page']) ? $params['page'] : 1;
        $return['last_page'] = isset($params['size']) && isset($params['page']) ? ceil($return['total'] / $return['per_page']) : 1;
        $return['list'] = array_map(function ($item) {
            return $this->listRow($item);
        }, $list);
        return $return;
    }

    public function findModel($params = [])
    {
        $model = new ResidencesModel();
        if (isset($params['where'])) {
            $model->where($params['where'])->where('is_delete', 2);
        }
        if (isset($params['whereOr'])) {
            $model->whereOr($params['whereOr'])->where('is_delete', 2);
        }
        return $model->find();
    }

    public function deleteModel($id)
    {
        $this->residenceModel = $this->get($id);
        \think\Db::startTrans();
        if (!$this->residenceModel) {
            \think\Db::rollback();
            return [FALSE, '查找的数据不存'];
        }
        $this->deleteDesigns($this->residenceModel);

        if ($this->residenceModel->is_delete == 1) {
            \think\Db::rollback();
            return [FALSE, '数据已经被删除过'];
        }
        if ($this->residenceModel->save(["is_delete" => 1])) {
            \think\Db::commit();
            return [TRUE, '刪除成功'];
        } else {
            \think\Db::rollback();
            return [FALSE, '删除失败'];
        }
    }

    /**
     * @param  \app\residences\models\ResidencesModel
     */
    public function deleteDesigns($model = '')
    {
        if(!$model){
            $entity = $this->residenceModel;
        }else{
            $entity = $model;
        }
        if (count($entity->designs)) {
            /** @var \app\residences\models\ResidencesDesignModel $item */
            foreach ($entity->designs as $item) {
                if (count($item->images)) {
                    /** @var \app\residences\models\ResidencesDesignImagesModel $value */
                    foreach ($item->images as $value) {
                        $value->delete();
                    }
                }
                $item->delete();
            }
        }
    }
}