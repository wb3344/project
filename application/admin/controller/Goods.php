<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Goods as Shop;

class Goods extends Controller{
    public function index(){
    	$data=db('goods')->where('state',0)->order('id','desc')->paginate(5);
        // dump($data);
        $this->assign('data',$data);
		return $this->fetch();
    }
    public function add(){
        $data=db('type')->select();
        $this->assign('data',$data);
		return $this->fetch();
    }
    // 渲染修改页面
    public function edit(){
        $id=input('id');
        $data=db('type')->select();
        $this->assign('data',$data);
        $list=db('goods')->find($id);
        $this->assign('list',$list);
        // dump($data);
        // dump($list);
		return $this->fetch();
    }
    // 修改商品信息
    public function update(){
        //图片上传处理,判断图片是否上传
        $file = request()->file('img');
        // dump($file);
        if(!empty($file)){
            $data['gid'] = $_POST['type'];
            $data['gname'] = $_POST['name'];
            // 多选框处理
            $ma = $_POST['ma'];
            $data['ma'] =implode(",",$ma);
            $color = $_POST['color'];
            $data['color'] = implode(",",$color);
            $info = $file->rule("uniqid")->move("public/image");
            // if($info){
            //     // echo "文件名:".$info->getFilename()."<br/>";    
            //     $image=\think\Image::open("public/image/".$info->getFilename());
            //     $image->thumb(50,50)->save("public/image/s_".$info->getFilename());
            // }
            $data['img'] = $info->getFilename();
            //正常接收值
            $data['price'] = $_POST['price'];
            $data['xiang'] = $_POST['editorValue'];
            $data['num'] = $_POST['num'];
            $data['nums'] = $_POST['nums'];
            $data['clicknum'] = $_POST['clicknum'];
            $data['time'] = date("Y-m-d H:i:s",time());
            // dump($data);
            $img = db("goods")->find($_POST['id']);
            $img1 = $img['img'];
            $img = "public/image/".$img1;
            if(file_exists($img)){
                // dump($img);
                unlink($img);
            }
            // dump($data);
            $m = db('goods')->where("id",$_POST['id'])->update($data);
            if ($m > 0) {
                return $m;
            }else{
                $this->error("修改失败");
        // dump($_POST);
            }
        }else{
            return "请上传图片";
        }
    }
    // 添加商品
    public function insert(){
        // dump($_POST);
        // dump($_FILES);
        // die();
        $file = request()->file('img');
        
        $info = $file->rule("uniqid")->move("public/image");
        // dump($info);
        $data['gid'] = $_POST['type'];
        $ma = $_POST['ma'];
        $color = $_POST['color'];
        // dump($file);
        if (empty($_POST['type'])){
            return "添加失败,请选择分类";
        }else{
            if (empty($info)) {
                return "添加失败，请选择图片";
            }else{
                if (empty($ma)) {
                    return "添加失败，请选择尺寸";
                }else{
                    if (empty($color)) {
                        return "添加失败，请选择颜色";
                    }else{
                        if (!empty($_POST['editorValue'])) {
                            // 多选框处理
                            $data['ma'] =implode(",",$ma);
                            $data['color'] = implode(",",$color);
                            $data['gname'] = $_POST['name'];
                            $data['img'] = $info->getFilename();
                            $data['xiang'] = $_POST['editorValue'];
                            // if () {
                            //     # code...
                            // }
                            $data['price'] = $_POST['price'];
                            $data['num'] = $_POST['num'];
                            $data['nums'] = $_POST['nums'];
                            $data['clicknum'] = $_POST['clicknum'];
                            $data['time'] = date("Y-m-d H:i:s",time());
                            // dump($data);
                            $m = db('goods')->insert($data);
                            // dump($m);
                            if ($m > 0) {
                                return 1;
                            }else{
                                $this->error("添加失败");
                            }
                        }else{
                            return "添加失败,请添加详情";
                        }
                    }
                }
            }
        }
    }
    // 渲染添加页面
    public function into(){
        $data = db("goods")->find(input('id'));
        $this->assign('data',$data);
        return $this->fetch();
    }

    //软删除
    public function delete(){
        //删除主键:$_GET['id']的记录
        $id = input('id');
        $m = db('goods')->where("id",$id)->update(['state'=>'1']);
        // dump($m);
        //根据返回值判断是否删除成功
        if ($m == true) {
            return redirect("Goods/index");
        }else{
            $this->error("删除失败");
        }    
    }
    public function delshop(){
        $data=db('goods')->where('state',1)->order('id','desc')->paginate(5);
        // dump($data);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function huifu(){
        //删除主键:$_GET['id']的记录
        $id = input('id');
        $m = db('goods')->where("id",$id)->update(['state'=>'0']);
        // dump($m);
        //根据返回值判断是否删除成功
        if ($m == true) {
            return redirect("Goods/delshop");
        }else{
            $this->error("删除失败");
        }    
    }
    // 删除
    public function delete1(){
        // dump($_GET);
        $img = db("goods")->find(input('id'));
        $img1 = $img['img'];
        $img = "public/image/".$img1;
        if(file_exists($img)){
            // dump($img);
            // dump($simg);
            unlink($img);
        }else{
            echo "123";
        }
        
        $data = db("goods")->where("id",input('id'))->delete();
        if ($data > 0) {
            return redirect("Goods/delshop");
        }else{
            $this->error("删除失败");
        }
    }

    //添加商品图片
    public function inAdd(){
        $files = request()->file('file');
        $info="";
        foreach($files as $file){
            // 移动到框架应用根目录/public/img 目录下
            $info = $file->rule("uniqid")->move( 'public/img');
            if($info){
                $info1[] = $info->getSaveName();
            }else{
                // 上传失败获取错误信息
                $file->getError();
            }
        }
        foreach ($info1 as $k => $v) {
            $data['gid'] = input('id');
            $data['picname'] = $v;
            $data['time'] = date("Y-m-d H:i:s".time());
            for ($i=0; $i < count($k); $i++) { 
                $m = db("goods_img")->insert($data);
            }
        }
        if ($m > 0) {
            return  $m;
        }else{
            $this->error();
        }
    }
    public function img(){
        $data = db("goods")->find(input('id'));
        $list = db("goods_img")->where('gid',input('id'))->select();
        $this->assign("data",$data);
        $this->assign("list",$list);

        
        return $this->fetch();
    }

    public function fenye(){
        $name=$_GET['fenye'];
        if($name){
            $data=db("goods")->where("gname","like","%".$name."%")->order('id desc')->paginate(3,false,['query'=>request()->param()]);
            $count=db("goods")->where("gname","like","%".$name."%")->count();
        }else{
            $data=db('goods')->order('id desc')->paginate(3);
            $count=db('goods')->count();
        }
        $this->assign('count',$count);
        // foreach ($data as $value) {
        //     $data1 = $value;
        // }
        // dump($data1);
        $this->assign('data',$data);
        return $this->fetch("Goods/index");
    }
}