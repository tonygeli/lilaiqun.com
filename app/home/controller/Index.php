<?php
namespace app\home\controller;
class Index extends Common{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        $recommends = db('article')->where('recommend',1)->select();
        return $this->fetch();
    }
}