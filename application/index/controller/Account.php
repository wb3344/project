<?php
namespace app\index\controller;

use Config;
use think\Controller;
use think\facade\Session;

class Account extends Controller{
    //用户名显示
    public function account(){
    	$list=session("username");
    	$this->assign("list",$list);
    	$arr=db("user")->where("username",$list)->find();
        $this->assign('arr',$arr);
        // var_dump($arr);
        return view();
    }
    //退出操作
    public function tuichu(){
    	Session::set('username','');
    	return redirect('Index/index');
    }
    //图片上传和个人信息修改
    public function people(){
    	$file=request()->file("pic");
        
		if($file != ''){
            $info=$file->rule("uniqid")->move("public/uploads");
            // echo "文件名:".$info->getFilename(); //图片的名字
            //原大小的图片
            $im=\think\Image::open("public/uploads/".$info->getFilename());
            
            //上传成功后对图片进行等比例缩放
            $im->thumb(120,120)->save("public/uploads/s_".$info->getFilename());
            $data['image']=$info->getFilename();
            $data['name']=$_POST['name'];
            $data['username']=$_POST['username'];
            $data['birthday']=$_POST['birthday'];
            $data['sex']=$_POST['sex'];
            $data['phone']=$_POST['phone'];
            $data['address']=$_POST['address'];
            // echo $data['name'];
            $m=db("user")->where("username",$data['username'])->update($data);
            if($m > 0){
                return redirect('Account/account');
            }else{
                return redirect('Account/account');
            }
        }else{
            $data['name']=$_POST['name'];
            $data['username']=$_POST['username'];
            $data['birthday']=$_POST['birthday'];
            $data['sex']=$_POST['sex'];
            $data['phone']=$_POST['phone'];
            $data['address']=$_POST['address'];
            // echo $data['name'];
            $m=db("user")->where("username",$data['username'])->update($data);
            if($m > 0){
                return redirect('Account/account');
            }else{
                return redirect('Account/account');
            }
        }
    	

    }

    public function security(){
        $list=session("username");
        $this->assign("list",$list);
        return view();
    }

    public function verify(){
        $role=session("username");
        $str=db("user")->where("username",$role)->find();
        $front=substr($str['phone'],0,2);
        $after=substr($str['phone'],9);
        $this->assign('str',$str);
        $this->assign('role',$role);
        $this->assign('front',$front);
        $this->assign('after',$after);
        return view();
    }

    public function verifytwo(){
        $list=session("username");
        $this->assign("list",$list);
        return view();
    }


    public function verifyeen(){
        $list=session("username");
        $this->assign("list",$list);
        return view();
    }



    public function jiaoyan(){
        $phone = input("rephone");
        $code = input("code");
        // dump(session('phone').session('code'));
        
        if(session('phone') == $phone  && session('code') == $code ){
            return  redirect('Account/verifytwo');
        }else{
            echo "哈哈哈";
        }
    }

    public function code(){
        if(input('pass') != "" && input('repass')){
            $code=input("code");
            // var_dump($code);
            if(!captcha_check($code)){
                $this->error('验证码错误');
            }else{
                $pass=md5(input('pass'));
                $data['pass']=$pass;
                $m=db("user")->where("username",session("username"))->update($data);
                return  redirect('Account/verifyeen');
            }
        }else{
            $this->error('密码不能为空');
        }
        
        
    }
}