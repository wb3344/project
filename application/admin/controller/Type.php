<?php
namespace app\admin\controller;
use think\Controller;

class Type extends Controller{
    public function index(){
        $data=db('type')->order('id','desc')->paginate(6);
        $this->assign('data',$data);
        $this->assign('list',$data);
        return $this->fetch();
    }
    
    public function add(){
        $type = input('type');
        if ($type == '' || $type == ' ') {
            $this->error("添加失败,顶级分类,不可为空");
        }
        $data['name'] = $type;
        $data['path'] = "0";
        if (!empty($data)) {
            $this->assign('type','顶级分类');
            $m = db('type')->insert($data);
            if ($m > 0) {
                $this->redirect("index");
            }else{
                $this->error("添加失败");
            }
        }
        return $this->fetch();
    }
    public function insert(){
        $this->assign("id",input('id'));
        $this->assign("type",input('name'));
        $this->assign("path",input('path'));
        return $this->fetch();
    }
    public function into(){
        $data['pid'] = input('id');
        $data['name'] = input('name');
        $data['path'] = input('path').",".input('id');
        $count = substr_count($data['path'] , ",");
        $data['count'] = $count;
        $this->assign('type','顶级分类');
        $m = db('type')->insert($data);
        if ($m > 0) {
            return $m;
        }else{
            $this->error("添加失败");
        }
        
    }
    public function edit(){
        $id=input('id');
        $data=db('type')->find($id);
        $this->assign('data',$data);
        // dump($data);
        return $this->fetch();
    }
    public function update(){
        $data['pid'] = input('name');
        $data['name'] = input('type');
        $m = db('type')->where("id",input('id'))->update($data);
        if ($m > 0) {
            return $m;
        }else{
            $this->error("修改失败");
        }
    }
    public function delete(){
        if (input('count') == "0") {
            $this->error("顶级分类,无法删除");
            // return redirect("Type/index");
        }
        // if (input('count')) {
            $data = db("type")->where("id",input('id'))->delete();
            if ($data > 0) {
                return redirect("Type/index");
            }else{
                $this->error("删除失败");
            }
        // }else{
            // $this->error("此类下还有分类,无法删除");
        // }
        
    }
}
