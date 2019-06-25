<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class User extends Controller{
    //管理员遍历
    public function index(){
        $strr=db("admin")->select();
        $list=db("admin")->order('id','desc')->paginate(5);
        $chang=count($strr);
        // echo "<pre>";

        // print_r($list)

        $keke=session("adminname");
        $fan=db("admin")->where('adminname',$keke)->find();
        $this->assign("fan",$fan);

        $this->assign("chang",$chang);

        $this->assign("list",$list);


        if(!empty($_GET['content'])){
            $content=$_GET['content'];
            // echo $content;
            $list=Db::name('admin')->where('adminname','like',"%{$content}%")->order('id','desc')->paginate(5,false,['query' => request()->param()]);
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
    public function cate(){
        return view();
    }
    public function edit(){
        $id=input('id');
        $list=db("admin")->find($id);
        $this->assign('list',$list);
        return $this->fetch();
    }
    //渲染添加页面
    public function add(){
        return view();
    }
    public function role(){
        return view();
    }
    public function rule(){
        return view();
    }

    //添加管理员
    public function inst(){
        $data['adminname']=$_POST['adminname'];
        $data['adminphone']=$_POST['phone'];
        $data['email']=$_POST['email'];
        $data['role']=$_POST['role'];
        $data['adminpass']=md5($_POST['pass']);
        $data['addtime']=date('Y-m-d H:i:s',time());
        $data['state']=1;
        // echo $data['role'];
        $m=db("admin")->insert($data);
        if($m > 0){
            $this->success('注册成功', 'User/index');
        }else{
            return '注册失败!';;
        }
    }
    //删除操作
    public function delete(){
        $id=$_GET['id'];
        $ad=db("admin")->where('id',$id)->find();
        if($ad['role'] == 1){
            return "无法删除";
        }else{
            $m=db("admin")->where('id',$id)->delete();
            return "删除成功";
        }
        
        // return $m;
    }

    //修改操作
    public function update(){
        $data['id']=input('id');
        $data['adminname']=input("adminname");
        $data['adminphone']=input("adminphone");
        $data['email']=input("email");
        $data['role']=input("role");
        $data['adminpass']=md5(input("adminpass"));

        $m=db("admin")->update($data);
        if($m>0){
            return redirect('User/index');
        }else{
            $this->error("修改失败");
        }
    }



    public function state(){
        $id=$_POST['id'];
        $data['state']=2;
        $m=db("admin")->where("id",$id)->update($data);
        return $m;
    }
    public function stat(){
        $id=$_POST['id'];
        $data['state']=1;
        $m=db("admin")->where("id",$id)->update($data);
        return $m;
    }
}
