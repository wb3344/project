<?php
namespace app\admin\controller;
use think\Controller;

class Banner extends Controller{
    public function index(){
    	$data = db("banner")->order('id','desc')->select();
    	$this->assign("data",$data);
    	// dump($data);
    	
        return view();
    }
    public function add(){
        return $this->fetch();
    }
    public function img(){
    	$id = input("id");
    	$data = db("banner")->find($id);
    	// dump(explode(",",$data['image']));
    	$image = explode(",",$data['image']);
    	$this->assign("imag",$image);
        return view();
    }
    public function insert(){
		$files = request()->file('file');
        $info="";
        foreach($files as $file){
            // 移动到框架应用根目录/public/img 目录下
            $info = $file->rule("uniqid")->move( 'public/banner');
            if($info){
                $info1[] = $info->getFilename();
                // $info1[] = $info->getSaveName();
            }else{
                // 上传失败获取错误信息
                $file->getError();
            }
        }
        // dump($info1);
        foreach ($info1 as $k => $v) {
            $data['image'] = $v;
            $data['time'] = date("Y-m-d H:i:s".time());
            for ($i=0; $i < count($k); $i++) { 
                $m = db("banner")->insert($data);
            }
        }
        if ($m > 0) {
            return  $m;
        }else{
            $this->error();
        }
    }
   	public function edit(){
   		$id=input('id');
        $data=db('banner')->find($id);
        $this->assign('data',$data);
        return view();
    }
    public function update(){
        $id = input('id');
        $files = request()->file('file');
		foreach($files as $file){
			$info = $file->rule("uniqid")->move( 'public/banner');
			if($info){
				$info1[] = $info->getFilename();
			}else{
				$file->getError();
			}
		}
		$info2 = implode(",",$info1);
		// dump($info2);
		$data['image'] = $info2;
        // echo $id;

        //s删除之前的图片
        $img = db("banner")->find($id);
        $img1 = $img['image'];
        $img = "public/banner/".$img1;
        // dump($img);
        if(file_exists($img)){
            unlink($img);
        }
        
        $m = db('banner')->where('id',$id)->update($data);
        if ($m > 0) {
            return $m;
        }else{
            $this->error("修改失败");
        }
    }
    public function delete(){
    	$img = db("banner")->find($_GET['id']);
        $img1 = $img['image'];
        $img = "public/banner/".$img1;
        // dump($img);
        if(file_exists($img)){
            // dump($img);
            // dump($simg);
            unlink($img);
        }else{
            echo "123";
        }
        
        $data = db("banner")->where("id",$_GET['id'])->delete();
        if ($data > 0) {
            return redirect("index");
        }else{
            $this->error("删除失败");
        }
    }
}
