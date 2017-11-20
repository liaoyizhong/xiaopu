<?php
namespace app\test\controller;
use app\common\controller\Basic as BasicController;
use app\common\enums\ResponseCode;
use OSS\OssClient;

/**
 * User: liaoyizhong
 * Date: 2017/11/10/010
 * Time: 16:40
 */

class Test extends BasicController
{
    public function index()
    {
        $accessKeyId = "LTAI6JVNEbAQ2CCS";
        $accessKeySecret = "rDDwPK4mejJQJSbEObD8ueBIrNefO9";
        $endpoint = "http://oss-cn-hangzhou.aliyuncs.com";
        $bucket = "mondaytest01";
        try {
            $isImage = preg_match('/^image\//',current($_FILES)['type']);
            if(!$isImage){
                $this->showResponse(ResponseCode::UNKNOW_ERROR, '参数类型不正确');
            }
            preg_match('/.*\/(.*)/',current($_FILES)['type'],$matches);
            $name = 'pho/asdfafs.'.$matches[1];
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $resturn = $ossClient->uploadFile($bucket,$name,current($_FILES)['tmp_name']);
            echo '<pre>';var_dump($resturn);echo '</pre>';exit();
        } catch (OssException $e) {
            $this->showResponse(ResponseCode::UNKNOW_ERROR, $e->getMessage());
        }
    }

    public function show()
    {
        $view = new \think\View();
        return $view->fetch();
    }

    public function lists()
    {
        $accessKeyId = "LTAI6JVNEbAQ2CCS";
        $accessKeySecret = "rDDwPK4mejJQJSbEObD8ueBIrNefO9";
        $endpoint = "http://oss-cn-hangzhou.aliyuncs.com";
        $bucket = "mondaytest01";
        $prefix = 'pho/';
        $delimiter = '/';
        $nextMarker = '';
        $maxkeys = 30;
        $options = array(
            'delimiter' => $delimiter,
            'prefix' => $prefix,
            'max-keys' => $maxkeys,
            'marker' => $nextMarker,
        );
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $signedUrl = $ossClient->signUrl($bucket, 'pho/asdfafs.jpeg', 360);
            echo '<pre>';var_dump($signedUrl);echo '</pre>';exit();
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
    }
}