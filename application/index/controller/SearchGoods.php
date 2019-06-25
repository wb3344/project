<?php

namespace app\index\controller;

use Config;
use think\Controller;
use think\facade\Session;;

class SearchGoods extends Controller{
    public function index(){
        // 登录
        $list=session("username");
        $this->assign("list",$list);
        // dump($list);
        // die();
        //分类
    	$dat = db("type")->where('count',0)->select();
        $lis = db("type")->where('count',1)->select();
    	$kkk = db("type")->where('count',2)->select();
        $this->assign("dat",$dat);
        $this->assign("lis",$lis);
        $this->assign("kkk",$kkk);

        //店铺热销
    	$data = db("goods")->limit(5)->order("id desc")->where('state','=',0)->select();
        $this->assign("data",$data);

        $where = [
            ["gid","=",input('id')],
            ['state','=','0'],
        ];
        //便利商品信息
        $list = db("goods")->where($where)->paginate(9,false,['query'=>request()->param()]);
    	$count = db("goods")->where($where)->count();
        // dump($list);
        // die();
        $this->assign("list",$list);
    	$this->assign("count",$count);

        $user=session('username');
        $this->assign('user',$user);
        return view();
    }
    public function zhi(){
        $dat = db("type")->where('count',0)->select();
        $lis = db("type")->where('count',1)->select();
        $kkk = db("type")->where('count',2)->select();
        $this->assign("dat",$dat);
        $this->assign("lis",$lis);
        $this->assign("kkk",$kkk);


        $di = $_POST['di'];
        $gao = $_POST['gao'];

        $mmp = [
            ['price', '>', $di],
            ['price', '<', $gao],
        ];

        $data = db("goods")->where('state',0)->limit(5)->order("id desc")->select();
        $this->assign("data",$data);

        $list=db("goods")->where($mmp)->order('id desc')->paginate(4,false,['query'=>request()->param()]);
        $this->assign('list',$list);
        return $this->fetch("Search_goods/index");
    }
}
