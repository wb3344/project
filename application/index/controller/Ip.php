<?php
namespace app\index\controller;

use Config;
use think\Controller;
use think\Session;

class Ip extends Controller
{
	public function index(){
		// 登录
    	$list=session("username");
    	$this->assign("list",$list);
    	//-------------------
		$data = db("ip")->select();
		$this->assign("data",$data);

        return view();
	}
}