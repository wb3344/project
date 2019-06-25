<?php
namespace app\index\controller;

use Config;
use think\Controller;
use think\facade\Session;

class Index extends Controller
{
    public function index(){
    	// 登录
    	$list=session("username");
    	$this->assign("list",$list);


// -----------------------------------------------------------------------
        $dat = db("type")->where('count',0)->select();
        $lis = db("type")->where('count',1)->select();
    	$kkk = db("type")->where('count',2)->select();
        $this->assign("dat",$dat);
        $this->assign("lis",$lis);
        $this->assign("kkk",$kkk);

        $banner = db("banner")->select();
        $this->assign("banner",$banner);

        $a1 = db("goods")->limit(6)->order("id desc")->where('state','0')->select();
        $this->assign("a1",$a1);
        return view();
    } 
    
    public function sou(){
        //分类
        $dat = db("type")->where('count',0)->select();
        $lis = db("type")->where('count',1)->select();
        $kkk = db("type")->where('count',2)->select();
        $this->assign("dat",$dat);
        $this->assign("lis",$lis);
        $this->assign("kkk",$kkk);

        $name = $_POST['shop'];

        $where1 = [
            ["gname","like","%".$name."%"],
            ['state','=',0],
        ];

        if($name){
            $list=db("goods")->where($where1)->order('id desc')->paginate(4,false,['query'=>request()->param()]);
            $count = db("goods")->where($where1)->count();
        }else{
            $list=db('goods')->order('id desc')->select();
            $count = db("goods")->count();
        }
        //店铺热销

        $data = db("goods")->where('state',0)->limit(5)->order("id desc")->select();
        $this->assign("data",$data);
        $this->assign("count",$count);
        $this->assign('list',$list);

        //-------------------------------------------------------------------------
        $user=session('username');
        $this->assign('user',$user);
        return $this->fetch("Search_goods/index");
    }

    public function tuichu(){
    	Session::set('username','');
    	return redirect('Index/index');
    }
}
