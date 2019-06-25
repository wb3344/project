<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Order extends Controller{
	
    public function index(){
        
        //搜索查询
        $hao=session('sou');

        if($hao!=null){

            $list=db('dingdan')->where('bianhao',$hao)->select();
            $this->assign("list",$list);
            $count=db('dingdan')->count();
            $this->assign("count",$count);

            $status=array('1'=>'新订单,请发货','2'=>'已发货,等待收货','3'=>'已收货,待评价','4'=>'评价完成,待回复','5'=>'订单完成');
            $this->assign('status',$status);

            session('sou',null);
            return view();

        }

        //全部查询
    	$list=db('dingdan')->order('id',"desc")->paginate(5);
    	$this->assign("list",$list);
    	$count=db('dingdan')->count();
    	$this->assign("count",$count);

        $status=array('1'=>'新订单,请发货','2'=>'已发货,等待收货','3'=>'已收货,待评价','4'=>'评价完成,待回复','5'=>'订单完成');
        $this->assign('status',$status);
        return view();
    }

    public function fahuo(){

        $id=$_POST['id'];
        $status=$_POST['status'];
        if($status=='1'){

            db('dingdan')->where('id',$id)->data(['status'=>'2'])->update();
        }elseif($status=='2'){
            db('dingdan')->where('id',$id)->data(['status'=>'3'])->update();
        }elseif($status=='3'){
            db('dingdan')->where('id',$id)->data(['status'=>'4'])->update();
        }
        
    }

    public function shanchu(){
        $id=$_GET['id'];
        $status=$_GET['status'];

        if($status=='4'){
            db('dingdan')->where('id',$id)->delete();
            echo "<script>alert('删除成功')</script>";
            echo "<script>location='index'</script>";
        }else{
            echo "<script>alert('订单尚未完成，不能删除')</script>";
            echo "<script>location='index'</script>";
        }
    }
    
    public function pass(){
        return view();
    }

    public function sou(){

        if(empty($_POST['hao'])){

            return redirect('Order/index');
        }

        $hao=$_POST['hao'];

        $arr=db('dingdan')->where('bianhao',$hao)->find();

        if(!empty($arr)){

            session('sou',$hao);
            return redirect('Order/index');
        }else{
            echo "<script>alert('无此订单号')</script>";

            echo "<script>location='index'</script>";
        }
    }

}
