<?php 
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\facade\Session;

class Vip extends Controller{
    public function index(){
        //分页和会员个数
        $strr=db("user")->select();
        $chang=count($strr);
        $this->assign("chang",$chang);
        // echo session('search');
        
        // session("search",$content);
        $list=db("user")->order('id','desc')->paginate(2);
        $this->assign("list",$list);
        if(!empty($_GET['content'])){
            $content=$_GET['content'];
            // echo $content;
            $list=Db::name('user')->where('username','like',"%{$content}%")->order('id','desc')->paginate(2,false,['query' => request()->param()]);
            // $lsit=json_encode($list);
            // echo "<pre>";
            // var_dump($list);
            if(!empty($list)){
                $this->assign("list",$list);
            }else{
                return "没有数据或账户不存在";
            }
            
        }else{
        
        }

        return $this->fetch();
            
       
    }
    public function delete(){
        //vip删除操作
        $id=$_GET['id'];
        // return $id;
        $m=db("user")->where('id',$id)->delete();
    }
    public function state(){
        $id=$_POST['id'];
        $data['state']=2;
        $m=db("user")->where("id",$id)->update($data);
        return $m;
    }
    public function stat(){
        $id=$_POST['id'];
        $data['state']=1;
        $m=db("user")->where("id",$id)->update($data);
        return $m;
    }

    // public function search(){
    //     $content=$_POST['content'];
    //     session("search",$content);
    //     // return session("search");
    //     // $list=Db::name('user')->where('username','like','%$content%')->order('id','desc')->paginate(2);
    //     // $this->assign("lsit",$lsit);
    //     // return $this->fetch();
    // }



}
