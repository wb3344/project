<?php
namespace app\index\controller;

use Config;
use think\Controller;
use think\Session;

class Details extends Controller{
    public function index(){

        $id = input('id');
    	$list = db("goods")->find($id);
    	$this->assign("list",$list);
        //遍历颜色
    	$color = explode(",",$list['color']);
    	$this->assign("color",$color);
        //遍历尺码
    	$ma = explode(",",$list['ma']);
    	$this->assign("ma",$ma);
        //遍历商品信息
        $data = db("goods")->where('state',0)->limit(6)->order("id desc")->select();
        $this->assign("data",$data);
        //遍历同商品图片
        $img = db("goods_img")->where('gid',$id)->select();
        $this->assign("img",$img);
        //遍历二级分类
        $one = db("type")->where('count',0)->limit(3)->select();
        $this->assign("one",$one);
        $two = db("type")->where('count',1)->select();
        $this->assign("two",$two);     
        
//-------------------------------------------------------------------------
        //登录
        $user=session('username');
        $this->assign('user',$user);

        //遍历评论
        $ping=db('pingjia')->select();
        $this->assign('ping',$ping);
        return view();
    }

    public function tuichu(){
        session('username',null);
        return redirect('Index/index');
    }
    public function shou(){

        if (empty(session("username"))) {
            echo "1";
        }else{
            $id = $_POST['gid'];
            // dump($id);
            $shou = db("goods")->find($id);
            // dump($shou);
            if (!empty($shou)) {
                if ($shou['shou'] == '0' && $shou['uid'] == session("username")) {
                    //添加收藏
                    $data['shou'] = "1";
                    if (empty($data['uid'])) {
                        $data['uid'] = session('username');
                    }else{
                        $data['uid'] = $data['uid'].",".session('username');
                    }
                    $m = db("goods")->where('id',$id)->update($data);
                    echo '2';
                }else{
                    //取消收藏
                    $data['shou'] = "0";
                    $data['uid'] = session('username');
                    $m = db("goods")->where('id',$id)->update($data);
                    echo '3';
                }
            }
        }
    }
    public function zan(){
        if (empty(session("username"))) {
            echo "1";
        }else{
            $id = $_POST['gid'];
            // dump($id);
            $zan = db("goods")->find($id);
            // dump($zan);
            if (!empty($zan)) {
                if ($zan['zan'] == '0') {
                    //添加收藏
                    $data['zan'] = "1";
                    $data['uid'] = session('username');
                    $m = db("goods")->where('id',$id)->update($data);
                    echo '2';
                }else{
                    //取消收藏
                    $data['shou'] = "0";
                    $data['uid'] = "";
                    $m = db("goods")->where('id',$id)->update($data);
                    echo '3';
                }
            }
        }
    }
}
