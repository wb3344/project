<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;

class Index extends Controller{
    public function index(){
    	if(empty(session("adminname"))){
		$this->success("请先登录", 'Login/index');
    		
    	}else{
    		
    		$list=session("adminname");
    		$this->assign("list",$list);
    	}
        return view();
    }
    public function content(){
        //会员数
        $user=db("user")->select();
        $user=count($user);
        $this->assign('user',$user);

        //管理员数
        $admin=db("admin")->select();
        $admin=count($admin);
        $this->assign('admin',$admin);

        //商品
        $goods=db("goods")->select();
        $goods=count($goods);
        $this->assign('goods',$goods);


        $adminname=session("adminname");
        $this->assign('adminname',$adminname);


        return $this->fetch();
    }
   	
}
