<?php
namespace app\residences\models;
use app\common\models\BasicModel;

/**
 * User: liaoyizhong
 * Date: 2017/11/8/008
 * Time: 14:39
 */

class ResidencesModel extends BasicModel
{
    protected $table = 'Residences';

    public function users()
    {
        return $this->belongsTo('\app\users\models\UsersModel','creator_id','id');
    }
}