<?php
/**
 * 资源分享
 * User: lilaiqun
 * Date: 16/03/2019
 * Time: 8:52 PM
 */

namespace app\home\controller;

class Resource extends Common
{
    public function _initialize(){
        parent::_initialize();
    }

    public function index() {
        return $this->fetch();
    }
}