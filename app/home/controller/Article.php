<?php
namespace app\home\controller;

class Article extends Common
{
    public function _initialize(){
        parent::_initialize();
        $this->dao=db('article');
    }

    public function index() {
        return $this->fetch();
    }
}