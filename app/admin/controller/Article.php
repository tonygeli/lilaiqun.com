<?php
namespace app\admin\controller;
//use think\{Controller,Db,Input};//
use think\Controller;
use think\Db;
use think\Input;
use clt\Form;
use clt\Qiniu;
use think\Request;
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

    /**
     * 修改文章
     * @return array|mixed
     */
    public function edit(){
        $id = input('id');
        $info = $this->dao->where('id',$id)->find();
        $info['inputthumb'] = $info['thumb'];
        $form=new Form($info);
        $returnData['vo'] = $info;
        $returnData['form'] = $form;

        $this->assign ('fields',MArticle::FIELDS);
        $this->assign ('info', $info );
        $this->assign ( 'form', $form );
        $this->assign ( 'title', lang('edit').lang('article'));

        return $this->fetch('edit');
    }

    /**
     * 添加页面
     * @return mixed
     */
    public function add() {
        $form=new Form();
        $this->assign ( 'form', $form);
        $this->assign ('fields',MArticle::FIELDS);
        return $this->fetch('edit');
    }

    public function insert() {
        $request = Request::instance();
        $controllerName = $request->controller();
        $model = $this->dao;

        $fields = MArticle::FIELDS;
        $data = $this->checkfield($fields, input('post.'));
        if(isset($data['code']) && $data['code']==0){
            return $data;
        }
        if(empty($data['createtime'])){
            $data['createtime'] = date('Y-m-d H:i:s', time());
        }

        $data['userid'] = session('aid');
        $data['username'] = session('username');

        $title_style ='';
        if (isset($data['style_color'])) {
            $title_style .= 'color:' . $data['style_color'].';';
            unset($data['style_color']);
        }else{
            $title_style .= 'color:#222;';
        }
        if (isset($data['style_bold'])) {
            $title_style .= 'font-weight:' . $data['style_bold'].';';
            unset($data['style_bold']);
        }else{
            $title_style .= 'font-weight:normal;';
        }
        if(!empty($fields['title']['setup']['style']) && $fields['title']['setup']['style']==1) {
            $data['title_style'] = $title_style;
        }

        $aid = $data['aid'];
        unset($data['style_color']);
        unset($data['style_bold']);
        unset($data['aid']);
        unset($data['pics_name']);
        $id= $this->dao->insertGetId($data);
        if ($id !==false) {
            $catid = $controllerName =='page' ? $id : $data['catid'];

            if($aid) {
                $Attachment =db('attachment');
                $aids =  implode(',',$aid);
                $data2['id']=$id;
                $data2['catid']= $catid;
                $data2['status']= '1';
                $Attachment->where("aid in (".$aids.")")->update($data2);
            }
            if($controllerName=='page'){
                $result['url'] = url("admin/category/index");
            }else{
                $result['url'] = url("admin/".$controllerName."/index",array('catid'=>$data['catid']));
            }
            $result['msg'] = '添加成功!';
            $result['code'] = 1;
            return json($result);
        } else {
            $result['msg'] = '添加失败!';
            $result['code'] = 0;
            return json($result);
        }
    }

    public function update() {
        if(request()->isPost()) {
            $data = input('post.');
            // 输入缩略图
            if ($data['inputthumb']) {
                $data['thumb'] = $data['inputthumb'];
            }
            if ($this->dao->update($data) !== false) {
                return array('code' => 1, 'url' => url('index'), 'msg' => '修改成功!');
            } else {
                return array('code' => 0, 'url' => url('index'), 'msg' => '修改失败!');
            }
        }
    }

    public function getInfo() {
        $id = input('id');
        $info = $this->dao->where('id',$id)->find();
        return json([
            'code' => 1,
            'msg' => '请求成功',
            'data' => ['info'=>$info]
        ]);
    }

    /**
     * 校验输入字段
     * @param $fields
     * @param $post
     * @return mixed
     */
    protected function checkfield($fields,$post){
        foreach ( $post as $key => $val ) {
            if(isset($fields[$key])){
                $setup=$fields[$key]['setup'];
                if(!empty($fields[$key]['required']) && empty($post[$key])){
                    $result['msg'] = $fields[$key]['errormsg']?$fields[$key]['errormsg']:'缺少必要参数！';
                    $result['code'] = 0;
                    return $result;
                }
                if(isset($setup['multiple'])){
                    if(is_array($post[$key])){
                        $post[$key] = implode(',',$post[$key]);
                    }
                }
                if(isset($setup['inputtype'])){
                    if($setup['inputtype']=='checkbox'){
                        $post[$key] = implode(',',$post[$key]);
                    }
                }
                if(isset($setup['type'])){
                    if($fields[$key]['type']=='checkbox'){
                        $post[$key] = implode(',',$post[$key]);
                    }
                }
                if($fields[$key]['type']=='textarea'){
                    $post[$key]=addslashes($post[$key]);
                }elseif($fields[$key]['type']=='editor'){
                    if(isset($post['add_description']) && $post['description'] == '' && isset($post['content'])) {
                        $content = stripslashes($post['content']);
                        $description_length = intval($post['description_length']);
                        $post['description'] = str_cut(str_replace(array("\r\n","\t",'[page]','[/page]','&ldquo;','&rdquo;'), '', strip_tags($content)),$description_length);
                        $post['description'] = addslashes($post['description']);
                    }
                    if(isset($post['auto_thumb']) && $post['thumb'] == '' && isset($post['content'])) {
                        $content = $content ? $content : stripslashes($post['content']);
                        $auto_thumb_no = intval($post['auto_thumb_no']) * 3;
                        if(preg_match_all("/(src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", $content, $matches)) {
                            $post['thumb'] = $matches[$auto_thumb_no][0];
                        }
                    }
                }
            }
        }
        return $post;
    }

    /*****************************************  文章图片管理  *************************************************/
    public function image()
    {
        return $this->fetch('imageList');
    }

    public function imageList()
    {
        $qiniuSer = new Qiniu();
        $arImg = $qiniuSer->getBucketFileList(input('prefix'), input('marker'), input('limit'));
        $count = input('limit');
        if (count($arImg['items']) == $count) {
            $count++;
        }
        return $result = [
            'code' => 0,
            'msg' => '获取成功!',
            'data' => $arImg['items'],
            'count' => $count,
            'marker' => $arImg['marker'],
            'commonPrefixes' => $arImg['commonPrefixes'],
            'rel'=>1
        ];
    }

    public function moveImage()
    {

    }
}