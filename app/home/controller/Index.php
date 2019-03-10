<?php
namespace app\home\controller;

use Think\Config;
class Index extends Common{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        $recommends = db('article')
            ->where('recommend',1)
            ->field('id,title,username,thumb,description')
            ->limit(4)
            ->select();
        $posts = db('article')
            ->order('id desc')
            ->field('id,title,username,thumb,description')
            ->limit(10)
            ->select();


        $this->assign('url_image', Config::get('url_image'));
        $this->assign('recommends', $recommends);
        $this->assign('posts', $posts);
        return $this->fetch();
    }
}