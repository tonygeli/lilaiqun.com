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

    public function info() {
        $this->dao->where('id',input('id'))->setInc('hits');
        $info = $this->dao->where('id',input('id'))->find();
        $info['pic'] = $info['pic']?__PUBLIC__.$info['pic']:__HOME__."/images/sample-images/blog-post".rand(1,3).".jpg";
        $title_style = explode(';',$info['title_style']);
        $info['title_color'] = $title_style[0];
        $info['title_weight'] = $title_style[1];
        $title_thumb = $info['thumb'];
        $info['title_thumb'] = $title_thumb?__PUBLIC__.$title_thumb:__HOME__.'/images/sample-images/blog-post'.rand(1,3).'.jpg';
        if(DBNAME=='picture'){
            $pics = explode(':::',$info['pics']);
            foreach ($pics as $k=>$v){
                $info['pics'][$k] = explode('|',$v);
            }
        }
        $this->assign('info',$info);
//        if($info['template']){
//            $template = $info['template'];
//        }else{
//            $cattemplate = db('category')->where('id',$info['catid'])->value('template_show');
//            if($cattemplate){
//                $template = $cattemplate;
//            }else{
//                $template = DBNAME.'_show';
//            }
//        }
        return $this->fetch();
    }
}