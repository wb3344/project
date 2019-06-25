<?php 
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;

class Login extends Controller{
    public function index(){
        return view();
    }

    public function places(){
    	$name=$_POST['name'];
    	$list=db('admin')->where("adminname",$name)->find();
    	if($list == null){
    		return 1;
    	}else{
    		return 0;
    	}
    }


    public function login(){
    	$name=$_POST['adminname'];
    	$pass=md5($_POST['password']);
    	$list=db('admin')->where("adminname",$name)->find();
    	if($list['adminname'] == $name  && $list['adminpass'] == $pass){
    		session("adminname",$name);
    		$this->success('欢迎回来，管理员'.$name, 'Index/index');
    	}else{
    		$this->error("密码错误");
    	}
    }

    public function out(){
    	Session::set('adminname','');
    	return redirect('Index/index');
    }

    public function replace(){
    	Session::set('adminname','');
    	return redirect('Login/index');
    }
}
