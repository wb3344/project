<?php
namespace app\index\controller;

use Config;
use think\Controller;
use think\Session;


class Login extends Controller{
    public function index(){
        return view();
    }

    public function register(){
        return view();

    }
    //显示注册页面和用户名是否重复
    public function forget(){
        return view();


    }

    public function zhuce(){
      	$name=input('name');
      	// echo $name;
        $list=db('user')->where("username",$name)->find();
        if($list==null){
        	return 1;
        }else{
        	return 0;
        }
    }
    //储存验证码并且发送成功
    public function fasong(){

    	$phone=$_POST['phone'];
    	$code=rand(0000,9999);
    	//调用手机发送验证码方法
    	$result=$this->phones($code,$phone);
    	session("phone",$phone);
    	session("code",$code);
    	
    	if($result == 1){
    		
    		return 1;
    	}else{
    		return 0;
    	}

    }
    //校验码及数据库添加
    public function inst(){

		$username=input('username');
		$phone=input('phone');
		$pass=input('pass');
		$code=input('code');
		session("username",$username);
		// echo session('phone');
    	// echo $username.$phone.$pass.$code;
    	//判断是否为同一个手机号，密码是否相等
    	if(session('phone') == $phone  && session('code') == $code && $username!="" && $phone!="" && $pass!=""){
			$data['username']=$username;
			$data['phone']=$phone;
			$data['pass']=md5($pass); 
			$data['time']=date('Y-m-d H:i:s',time());
			$data['state']=1;
			//加入数据库
			$m=db("user")->insert($data);
			// echo $m;
			if($m > 0){
				//重定向
				return redirect('Index/index');
			}else{
				return '注册失败!';;
			}
    	}else{
    		//跳转注册
    		return $this->success('注册信息不完整','Login/register');
    	}
	}
    //验证码接口
    public function phones($code,$phone){
		// $code=rand(0000,9999);
	 //    $phone = 17769141997;
		$host = "https://fesms.market.alicloudapi.com";//api访问链接
	    $path = "/sms/";//API访问后缀
	    $method = "GET";
	    $appcode = "400d63a102264ea49a2efa09e64955f8";//替换成自己的阿里云appcod
	    $headers = array();
	    array_push($headers, "Authorization:APPCODE " . $appcode);
	    $querys = "code={$code}&phone={$phone}&skin=1&sign=1";  //参数写在这里, 自定义skin编号请找客服申请
	    $bodys = "";
	    $url = $host . $path . "?" . $querys;//url拼接

	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_FAILONERROR, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HEADER, false);
	    //curl_setopt($curl, CURLOPT_HEADER, true); 如不输出json, 请打开这行代码，打印调试头部状态码。
	    //状态码: 200 正常；400 URL无效；401 appCode错误； 403 次数用完； 500 API网管错误
	    if (1 == strpos("$".$host, "https://"))
	    {
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    }
	    $query = curl_exec($curl);
	    $query=json_decode($query,true);
	    // return ($query['Message']);
	    if($query['Message'] == "OK"){
	    	return 1;
	    }else{
	    	return 0;
	    }
	}

	public function denglu(){
		//登陆验证，	
		if($_POST['username'] == "" || $_POST['pass'] == ""){
			return $this->success('密码或账号不能为空','Login/index');
		}else{
			$name=$_POST['username'];
			$pass=md5($_POST['pass']);

			$list=db('user')->where("username",$name)->find();
			//状态判断
			if($list['state'] == 1){
				if($list['pass'] == $pass){
					session("username",$name);
					return redirect('Index/index');
				}else{
					return redirect('Login/index');
				}
			}else{
				$this->error('您的账户已冻结');
			}
			
		}
		

	}
	//忘记密码
	public function wangji(){
		$username=$_POST['getname'];
		$password=md5($_POST['password']);
		$data['pass']=$password;
		$phone=$_POST['phone'];
		$code=$_POST['code'];
		if(session('phone') == $phone  && session('code') == $code){

			$m=db("user")->where('username',$username)->update($data);
			echo $m;
			if($m > 0){
				return redirect('Login/register');
			}else{
				return $this->success('修改失败','Login/forget');
			}
		}else{
			return $this->success('验证码不正确','Login/register');
		}
		
	}
 
	public function ce(){
		$user=$_POST['user'];
		$userlist=db("user")->where("username",$user)->find();
		if($userlist == ''){
			echo 1;
		}else{
			echo 0;
		}

	}
    
}