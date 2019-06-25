<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Dingdan extends Controller
{
    public function dingdan(){

        $user=session('username');

        if(empty($user)){

            echo "<script>alert('请先登录');</script>";
            return redirect('Login/index');

        }
        $this->assign('user',$user);

        $list=db('dingdan')->where('uid',$user)->order('id',"desc")->paginate(5);
        $mafan=db('dingdan')->where('uid',$user)->find();

        $this->assign("list",$list);
        $this->assign("mafan",$mafan);

        return view();
    } 

    public function xiangqing(){

        $user=session('username');
        $this->assign('user',$user);

        $id=$_GET['id'];
        $list=db('dingdan')->where('id',$id)->select();
        $this->assign('list',$list);
    	return $this->fetch();
    }

    public function quxiao(){

        $id=$_POST['id'];
        db('dingdan')->where('id',$id)->delete();

    }

    public function zhuang(){

        $id=$_GET['id'];
        $status=$_GET['status'];

        if($status=='2'){
            db('dingdan')->where('id',$id)->data(['status'=>'3'])->update();
            echo "<script>location='dingdan'</script>";
        }elseif($status=='3'){
            // db('dingdan')->where('id',$id)->data(['status'=>'4'])->update();
            echo "<script>location='pingjia'</script>";
        }
    }

    public function pingjia(){

        $user=session('username');
        $this->assign('user',$user);

        $list=db('dingdan')->where('status','>','2')->order('id',"desc")->paginate(5);

        $p=db('pingjia')->select();

        $this->assign('list',$list);
        $this->assign('p',$p);

    	return $this->fetch();
    }

    public function shan(){

        $id=$_GET['id'];
        $status=$_GET['status'];

        if($status < "4"){

            echo "<script>alert('该商品尚未评价');</script>";
            echo "<script>location='pingjia'</script>";

        }

        db('pingjia')->where('uid',$id)->update(['haoping'=>'','content'=>'']);
        db('dingdan')->where('id',$id)->update(['status'=>'3']);

        echo "<script>alert('删除成功');</script>";
        echo "<script>location='pingjia'</script>";

    }

    public function pingjia2(){

        $user=session('username');
        $this->assign('user',$user);

        $id=$_GET['id'];
        $list=db('dingdan')->where('id',$id)->select();
        $this->assign('list',$list);

        $u=db('pingjia')->where('uid',$id)->select();

        if(!empty($u)){

            return view();
        }

        $ggid=$_GET['ggid'];
        $xinghao=$_GET['xinghao'];

        $data=['uid'=>$id,'ggid'=>$ggid,'user'=>$user,'xinghao'=>$xinghao];
        $ping=db('pingjia')->insert($data);
        
    	return view();
    }

    public function content(){

        $uid=$_GET['uid'];
        $hao=$_POST['ping'];
        $content=$_POST['pingjia'];
        $addtime=date('Y-m-d H:i:s',time());

        db('pingjia')->where('uid',$uid)->data(['haoping'=>$hao,'content'=>$content,'addtime'=>$addtime])->update();

        db('dingdan')->where('id',$uid)->data(['status'=>'4'])->update();

        echo "<script>alert('评论成功')</script>";

        echo "<script>location='pingjia'</script>";
        // echo '<script>window.close();</script>';
    }

    public function tuichu(){
        
        session('username',null);
        echo "<script>alert('退出成功')</script>";
        return redirect('Dingdan/dingdan');
    }

}
