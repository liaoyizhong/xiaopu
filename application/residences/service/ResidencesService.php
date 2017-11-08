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

    /**
     * @param $id
     * @return ResidencesModel|null
     */
    public function get($id)
    {
        return ResidencesModel::get($id,["is_delete"=>2]);
    }

    /**
     * //创建或者更新model
     * @param $params
     * @return false|int
     */
    public function saveModel($params)
    {
        if (isset($params['id']) && $params['id']) {
            $model = $this->get($params['id']);
            unset($params['id']);
            if (!$model) {
                return [FALSE, '没有该数据'];
            }
        } else {
            $model = new ResidencesModel();
        }
        return $model->save($params);
    }

    public function deleteModel($id)
    {
        $model = $this->get($id);
        if (!$model) {
            return [FALSE, '查找的数据不存'];
        }
        if ($model->is_delete == 1) {
            return [FALSE, '数据已经被删除过'];
        }
        if ($model->save(["is_delete" => 1])) {
            return[TRUE,'刪除成功'];
        }else{
            return[FALSE,'删除失败'];
        }
    }

}