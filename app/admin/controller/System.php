<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use clt\Leftnav;
use app\admin\model\System as SysModel;
class System extends Common
{
    private $menus = []; 
    /********************************站点管理*******************************/
    //站点设置
    public function system($sys_id=1){
        $table = db('system');
        if(request()->isPost()) {
            $datas = input('post.');
            if($table->where('id',1)->update($datas)!==false) {
                savecache('System');
                return json(['code' => 1, 'msg' => '站点设置保存成功!', 'url' => url('system/system')]);
            } else {
                return json(array('code' => 0, 'msg' =>'站点设置保存失败！'));
            }
        }else{
            $system = $table->field('id,name,url,title,key,des,bah,copyright,ads,tel,email,logo')->find($sys_id);
            $this->assign('system', $system);
            return $this->fetch();
        }
    }
    public function email(){
        $table = db('config');
        if(request()->isPost()) {
            $datas = input('post.');
            foreach ($datas as $k=>$v){
                $table->where(['name'=>$k,'inc_type'=>'smtp'])->update(['value'=>$v]);
            }
            return json(['code' => 1, 'msg' => '邮箱设置成功!', 'url' => url('system/email')]);
        }else{
            $smtp = $table->where(['inc_type'=>'smtp'])->select();
            $info = convert_arr_kv($smtp,'name','value');
            $this->assign('info', $info);
            return $this->fetch();
        }
    }
    public function trySend(){
        $sender = input('email');
        //检查是否邮箱格式
        if (!is_email($sender)) {
            return json(['code' => -1, 'msg' => '测试邮箱码格式有误']);
        }
        $send = send_email($sender, '测试邮件', '您好！这是一封来自'.$this->system['name'].'的测试邮件！');
        if ($send) {
            return json(['code' => 1, 'msg' => '邮件发送成功！']);
        } else {
            return json(['code' => -1, 'msg' => '邮件发送失败！']);
        }
    }

    /**
     * 后台目录配置
     *
     * @author llq
     */
    public function menu()
    {
        $authRule = db('auth_rule')->order('sort')->select();
        //声明数组
        $menus = $this->getTree($authRule, 0, 0);
        $this->assign('menus', json_encode($menus));
        return $this->fetch();
    }


    /**
     * 递归获取树
     *
     * @author llq
     * @param array $nodes  所有节点
     * @param int   $pid    父id
     * @param int   $depth  深度
     * @return array
     */
    protected function getTree($nodes, $pid, $depth) 
    {
        $tree = [];
        if (2 == $depth) {
            return [];
        }
        foreach ($nodes as $node) {
            if ($pid == $node['pid']) {
                $node['child'] = $this->getTree($nodes, $node['id'], $depth+1);
                $tree[] = $node;
            }
        }
        return $tree;
    }

}
