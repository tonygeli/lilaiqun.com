<?php
namespace app\home\controller;

use Think\Config;
class Index extends Common
{
    Const pageSize = 10;

    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        $page = input('page') ? input('page') : 1;

        $recommends = db('article')
            ->where('recommend',1)
            ->field('id,title,username,thumb,description')
            ->limit(4)
            ->select();
        $list = db('article')
            ->field('id,title,username,thumb,description,hits')
            ->order('listorder desc')
            ->paginate(array('list_rows'=>self::pageSize,'page'=>$page));
        $page = $list->render();
        $posts = $list->toArray();

        $this->assign('url_image', Config::get('url_image'));
        $this->assign('recommends', $recommends);
        $this->assign('posts', $posts['data']);
        $this->assign('page', $page);
        return $this->fetch();
    }
}