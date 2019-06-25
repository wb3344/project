<?php

namespace app\index\controller;

use Config;
use think\Controller;
use think\DB;
use think\Session;

class GoodsCart extends Controller{

    public function huoqu(){

        //获取颜色，传值session
        if(isset($_POST['color'])){
            session('color',$_POST['color']);
        }else{
            session('color',null);
        }
        
        return 1;
     }

     public function huoqu1(){

        //获取尺码，传值session
        if(isset($_POST['ma'])){
            session('chima',$_POST['ma']);
        }else{
            session('chima',null);
        }
        return 2;
     }

    public function cart1(){

        //判断颜色，尺码，数量是否为空
        if(session('color') == null){
            session('chima',null);

            return 1;
        }

        if(session('chima') == null){
            session('color',null);
 
            return 2;
        }


        $list=db('cart')->where('uid',session('username'))->where('gid',$_POST['gid'])->find();
        if(isset($list)){
            session('color',null);
            session('chima',null);
            return 3;
        }


        $user=session('username');
        $color=session('color');
        
        $chima=session('chima');

        $gid=$_POST['gid'];
        $name=$_POST['name'];
        $img=$_POST['img'];
        $xinghao=$color." ".$chima;
        $num=$_POST['num'];
        $price=$_POST['price'];

        $arr=array('name'=>$name,'uid'=>$user,'gid'=>$gid,'img'=>$img,'xinghao'=>$xinghao,'num'=>$num,'price'=>$price);

        db('cart')->insert($arr);

        session('color',null);
        session('chima',null);

        return 4;

    }
	
    public function index(){

        if(isset($_GET['id'])){

            $id=$_GET['id'];
            $del=db("cart")->where('id',$id)->delete();
            // if($del>0){
            //     echo "<script>alert('删除成功')</script>";
            // }
        }

        $user=session('username');

        if(empty($user)){

            echo "<script>alert('请先登录');</script>";
            return redirect('Login/index');

        }

        $this->assign('user',$user);

        $list=db("cart")->where('uid',$user)->select();
        $count=db("cart")->count();
        $zong=0;

        $this->assign("list",$list);
        $this->assign("count",$count);
        $this->assign("zong",$zong);

        return $this->fetch();
    }

    public function cart2(){

        $user=session('username');
        if(empty($user)){

            echo "<script>alert('请先登录');</script>";
            return redirect('Login/index');

        }
        $this->assign('user',$user);

        $list=db("cart")->where('uid',$user)->select();

        $zong=0;
        $this->assign('zong',$zong);
        $this->assign('list',$list);

        $addlist=db('address')->where('uid',$user)->select();

        $this->assign('addlist',$addlist);

        return view();
    }

    public function add(){

        $user=session('username');

        $name=$_POST['name'];
        $phone=$_POST['phone'];
        $add1=$_POST['add1'];
        $add2=$_POST['add2'];
        $add3=$_POST['add3'];
        $add4=$_POST['add4'];
        $youbian=$_POST['youbian'];

        $arr=array('name'=>$name,'phone'=>$phone,'add1'=>$add1,'add2'=>$add2,'add3'=>$add3,'add4'=>$add4,'youbian'=>$youbian,'uid'=>$user);

        db('address')->insert($arr);

        session('addid',null);
        // return $this->success('添加成功',"GoodsCart/cart2");
        echo "<script>location='cart2'</script>";
        
    }

    public function addedit(){

        $id=$_GET['id'];
        $name=$_POST['name'];
        $phone=$_POST['phone'];
        $add1=$_POST['add1'];
        $add2=$_POST['add2'];
        $add3=$_POST['add3'];
        $add4=$_POST['add4'];
        $youbian=$_POST['youbian'];

        db('address')->where('id',$id)->data(['name'=>$name,'phone'=>$phone,'add1'=>$add1,'add2'=>$add2,'add3'=>$add3,'add4'=>$add4,'youbian'=>$youbian])->update();

        session('addid',null);

        // return $this->success('修改成功',"GoodsCart/cart2");
        echo "<script>location='cart2'</script>";
    }

     public function adddel(){

        $id=$_GET['id'];

        db('address')->where('id',$id)->delete();

        session('addid',null);

        echo "<script>location='cart2'</script>";
    }

    // public function ses(){

    //     $id=$_GET['addid'];

    //     Session::set('addid',$id);
    // }
    public function cart2_1(){

        $id=$_POST['addid'];
        session('addid',$id);

    }

    public function cart2_2(){

        if(empty(session('addid'))){

            echo "<script>alert('请选择/填写地址')</script>";
            echo "<script>location='cart2'</script>";
        };

        $addid=session('addid');
        $user=session('username');

        $list=db('address')->where('id',$addid)->select();

        foreach ($list as $k => $v) {
            $users=$v['name'];
            $phone=$v['phone'];
            $address=$v['add1'].$v['add2'].$v['add3'].$v['add4'];
            $addtime=date('Y-m-d H:i:s',time());
        }

        $list1=db('cart')->where('uid',$user)->select();

        foreach ($list1 as $va) {
            $id=$va['id'];

            $name=$va['name'];
            $xinghao=$va['xinghao'];
            $num=$va['num'];
            $price=$va['price'];
            $img=$va['img'];
            $bianhao=time().rand(00000,99999);
            $gid=$va['gid'];

            db('cart')->where('id',$id)->data(['bianhao'=>$bianhao])->update();

            $arr=array('name'=>$name,'phone'=>$phone,'xinghao'=>$xinghao,'num'=>$num,'price'=>$price,'user'=>$users,'address'=>$address,'addtime'=>$addtime,'bianhao'=>$bianhao,'img'=>$img,'uid'=>$user,'gid'=>$gid);

            db('dingdan')->insert($arr);

        }
           
            session('addid',null);

            echo "<script>location='cart3'</script>";

        // return view();
    }

    public function cart3(){

        $user=session('username');
        if(empty($user)){

            echo "<script>alert('请先登录');</script>";
            return redirect('Login/index');

        }
        $this->assign('user',$user);

        $zong=0;
        $list=db('cart')->where('uid',$user)->select();
        $this->assign('list',$list);
        $this->assign('zong',$zong);

        return view();
    }

    public function cart4(){
    
        $user=session('username');
        if(empty($user)){

            echo "<script>alert('请先登录');</script>";
            return redirect('Login/index');

        }

        if(!isset($_POST['a'])){
            echo "<script>alert('请选择支付方式');</script>";
            echo "<script>location='cart3'</script>";
        }else{

            $this->assign('user',$user);

            db("cart")->delete(true);

            return view();
        }
        
    }

    public function tuichu(){
        session('username',null);
        echo "<script>alert('退出成功')</script>";
        return redirect('Index/index');
    }
}
