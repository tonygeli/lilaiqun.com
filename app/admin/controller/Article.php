<?php
namespace app\admin\controller;
//use think\{Controller,Db,Input};//
use think\Controller;
use think\Db;
use think\Input;
use clt\Form;
use think\Debug;
use app\admin\model\Article as MArticle;

class Article extends Common
{
    public function _initialize(){
        parent::_initialize();
        $this->dao=db('article');
    }

    public function index() {
        return $this->fetch();
    }

    /**
     * 获取文章列表数据 GET page limit
     * @return array
     */
    public function list() {
        $page =input('page')?input('page'):1;
        $pageSize =input('limit')?input('limit'):config('pageSize');
        $list = $this->dao->order('listorder desc')
            ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
            ->toArray();
        return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
    }


    public function del() {

    }

    public function switchState() {
        $id=input('post.id');
        $arRet = array(
            'code' => 0
        );
        $status=$this->dao->where(array('id'=>$id))->value('status');//判断当前状态情况
        if($status==1){
            $data['status'] = 0;
            $this->dao->where(array('id'=>$id))->setField($data);
            $arRet['data'] = array(
                'state' => 0
            );
        }else{
            $data['status'] = 1;
            $this->dao->where(array('id'=>$id))->setField($data);
            $arRet['data'] = array(
                'state' => 1
            );
        }
        return $arRet;
    }

    public function edit(){
        if(request()->isPost()){
            $data = input('post.');
            if($this->dao->update($data)!==false){
                return array('code'=>1,'url'=>url('index'),'msg'=>'修改成功!');
            }else{
                return array('code'=>0,'url'=>url('index'),'msg'=>'修改失败!');
            }
        }else{
            $id = input('id');
            $info = $this->dao->where('id',$id)->find();
            $form=new Form($info);
            $returnData['vo'] = $info;
            $returnData['form'] = $form;

            $this->assign ('fields',MArticle::FIELDS);
            $this->assign ('info', $info );
            $this->assign ( 'form', $form );
            $this->assign ( 'title', lang('edit').lang('article'));

            return $this->fetch('edit');
        }

    }

    public function update() {
        if(request()->isPost()) {
            $data = input('post.');
            if ($this->dao->update($data) !== false) {
                return array('code' => 1, 'url' => url('index'), 'msg' => '修改成功!');
            } else {
                return array('code' => 0, 'url' => url('index'), 'msg' => '修改失败!');
            }
        }
    }

}