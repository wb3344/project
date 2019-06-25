<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Comment extends Controller{
    public function index(){

    	$count=db('pingjia')->count();
    	$list=db('dingdan')->where('status','>','3')->order('id',"desc")->paginate(5);
    	$p=db('pingjia')->select();

    	$this->assign('p',$p);
    	$this->assign('list',$list);
    	$this->assign('count',$count);
        return view();
    }

   	public function edit(){

   		$uid=$_GET['uid'];
   		$list=db('pingjia')->where('uid',$uid)->select();

   		$this->assign('list',$list);
        return view();
    }

    public function huifu(){

    	$huifu=$_POST['huifu'];
    	$uid=$_POST['uid'];

    	db('dingdan')->where('id',$uid)->data(['status'=>'5'])->update();
    	db('pingjia')->where('uid',$uid)->data(['huifu'=>$huifu])->update();

        echo "<script>setTimeout(function(){parent.location.href='index'},500);</script>";
    }
}
