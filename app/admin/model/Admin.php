<?php
namespace app\admin\model;
use think\Model;
class Admin extends Model
{
	public function login($data){
		$user=db('admin')->where('username',$data['username'])->find();
		if($user){
			if($user['pwd'] == md5($data['password'])){
				session('username',$user['username']);
				session('aid',$user['admin_id']);
				return 1; //信息正确
			}else{
				return -1; //密码错误
			}
		}else{
			return -1; //用户不存在
		}
	}

	public function menu()
	{
		$authRule = db('auth_rule')->where('menustatus=1')->order('sort')->select();
        //声明数组
        $menus = array();
        foreach ($authRule as $key=>$val){
            $authRule[$key]['href'] = url($val['href']);
            if ($val['pid']==0) {
                if (session('aid')!=1) {
                    if (in_array($val['id'],$this->adminRules)) {
                        $menus[] = $val;
                    }
                } else {
                    $menus[] = $val;
                }
            }
        }
        return json([$menus]);
	}

}
