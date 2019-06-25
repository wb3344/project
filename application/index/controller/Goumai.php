<?php

namespace app\index\controller;

use Config;
use think\Controller;
use think\Db;


class Goumai extends Controller{

    public function gou1(){

        //判断颜色，尺码，数量是否为空
        if(session('color') == null){
            session('chima',null);

            return 1;
        }

        if(session('chima') == null){
            session('color',null);
            return 2;
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

        db('goumai')->where('id',1)->update($arr);

        session('color',null);
        session('chima',null);

        return 3;

    }

     public function gou2(){

        $user=session('username');
        if(empty($user)){

            echo "<script>alert('请先登录');</script>";
            return redirect('Login/index');

        }
        $this->assign('user',$user);

        $list=db("goumai")->where('uid',$user=session('username'))->select();
        
        $this->assign('list',$list);

        $addlist=db('address')->where('uid',$user=session('username'))->select();

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

        // return $this->success('添加成功',"Goodsgoumai/goumai2");
        session('addid',null);
        echo "<script>location='gou2'</script>";
        
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

        // return $this->success('修改成功',"Goodsgoumai/goumai2");
        session('addid',null);
        echo "<script>location='gou2'</script>";
    }

    public function adddel(){

        $id=$_GET['id'];

        db('address')->where('id',$id)->delete();

        session('addid',null);
        // return $this->success('删除成功',"Goodsgoumai/goumai2");
        echo "<script>location='gou2'</script>";
    }

    public function gou2_1(){

        $id=$_POST['addid'];
        session('addid',$id);

    }

    public function gou2_2(){

        if(empty(session('addid'))){

            echo "<script>alert('请选择/填写地址')</script>";
            echo "<script>location='gou2'</script>";
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

        $list1=db('goumai')->where('uid',$user)->select();

        foreach ($list1 as $va) {
            $name=$va['name'];
            $xinghao=$va['xinghao'];
            $num=$va['num'];
            $price=$va['price'];
            $img=$va['img'];
            $bianhao=time().rand(00000,99999);
            $gid=$va['gid'];

            db('goumai')->where('id',1)->data(['bianhao'=>$bianhao])->update();

            $arr=array('name'=>$name,'phone'=>$phone,'xinghao'=>$xinghao,'num'=>$num,'price'=>$price,'user'=>$users,'address'=>$address,'addtime'=>$addtime,'bianhao'=>$bianhao,'img'=>$img,'uid'=>$user,'gid'=>$gid);

            db('dingdan')->insert($arr);

        }
           
            session('addid',null);

            echo "<script>location='gou3'</script>";

        // return view();
    }

    public function gou3(){

        $user=session('username');
        if(empty($user)){

            echo "<script>alert('请先登录');</script>";
            return redirect('Login/index');

        }
        $this->assign('user',$user);

        $list=db('goumai')->select();
        $this->assign('list',$list);

        return view();
    }

    public function gou4(){

        $user=session('username');
        if(empty($user)){

            echo "<script>alert('请先登录');</script>";
            return redirect('Login/index');

        }

        if(!isset($_POST['a'])){
            echo "<script>alert('请选择支付方式');</script>";
            echo "<script>location='gou3';</script>";
        }else{
            
            $this->assign('user',$user);

            // db("goumai")->delete(true);
            db('goumai')->where('id',1)->data(['name'=>null,'uid'=>null,'gid'=>null,'img'=>null,'xinghao'=>null,'price'=>null,'num'=>null,'bianhao'=>null])->update();

            return view();
        }
        
    }

    public function tuichu(){
        session('username',null);
        echo "<script>alert('退出成功')</script>";
        return redirect('Index/index');
    }
}
