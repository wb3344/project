<?php
namespace app\index\controller;

use Config;
use think\Controller;
use think\facade\Session;

class Shou extends Controller{
    public function index(){
    	// 登录
    	$list=session("username");
    	$this->assign("list",$list);


        $data = db("goods")->where("shou","=","1")->paginate(9,false,['query'=>request()->param()]);
        // dump($data);
        // die();
        $this->assign("data",$data);

//-------------------------------------------------------------------------
        $user=session('username');
        $this->assign('user',$user);
        return view();
    	}
    	
    	public function tuichu(){
        session('username',null);
        return redirect('Index/index');
    	}
}