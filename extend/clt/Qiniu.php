<?php

namespace clt;
use think\Config;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
class Qiniu
{
    private $auth;
    private $bucket;

    /**
     * Qiniu constructor.
     * @param bool $openBucket 是否为公开空间
     */
    function __construct($openBucket=true)
    {
        $this->auth = QiniuAuth::getInstance();
        if (!$openBucket) {
            $this->bucket = Config::get('QINIU_BUCKET_PRIVATE');
        } else {
            $this->bucket = Config::get('QINIU_BUCKET');
        }
    }

    public function getBucketManager()
    {
        $bucketManager = new BucketManager($this->auth);
    }


    /**
     * 获取空间中文件列表
     *
     * @param string $prefix    要列取文件的公共前缀
     * @param string $marker    上次列举返回的位置标记，作为本次列举的起点信息。
     * @param int $limit        本次列举的条目数
     */
    public function getBucketFileList($prefix='', $marker='', $limit = 20)
    {
        $bucketManager = new BucketManager($this->auth);
        $delimiter = '/';
        // 列举文件
        list($ret, $err) = $bucketManager->listFiles($this->bucket, $prefix, $marker, $limit, $delimiter);
        if ($err !== null) {
            return [];
        } else {
            return $ret;
        }
    }


    /**
     * 上传文件到七牛
     * @param $key          上传文件名
     * @param $filePath     上传文件的路径
     * @param null $params  自定义变量，规格参考 http://developer.qiniu.com/docs/v6/api/overview/up/response/vars.html#xvar
     * @param string $mime  上传数据的mimeType
     * @param bool $checkCrc    是否校验crc32
     * @return array        包含已上传文件的信息，类似：
     *  [
     *      "hash" => "<Hash string>",
     *      "key" => "<Key string>"
     *  ]
     * @throws \Exception
     */
    public function putFile(
        $key,
        $filePath,
        $params = null,
        $mime = 'application/octet-stream',
        $checkCrc = false)
    {
        // 初始化 UploadManager 对象
        $uploadMgr = new UploadManager();
        // 生成上传 Token
        $upToken = $this->auth->uploadToken($this->bucket);
        return $uploadMgr->putFile($upToken, $key, $filePath, $params, $mime, $checkCrc);
    }

}