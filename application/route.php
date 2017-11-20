<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    'login/check' => ['users/login/check',['method'=>'post']],
    '__rest__'=>[
        'login'=>'users/login',
        'residences'=>'residences/residences'
    ],
    '[region]' => [
        'provinces/:id' => ['region/region/findProvince',['method' => 'get'], ['id' => '\d+']],
        'citys/:id' => ['region/region/findCity',['method' => 'get'], ['id' => '\d+']],
        'areas/:id' => ['region/region/findArea',['method' => 'get'], ['id' => '\d+']],
        'provinces' => 'region/region/listProvinces',
        'citys' => 'region/region/listCitys',
        'areas' => 'region/region/listAreas',
        'all' => 'region/region/zhongshan',
    ],
    'test' => 'test/test/index',
    'test/show' => 'test/test/show',
    'test/lists' => 'test/test/lists'
];
