<?php
namespace app\admin\model;
use think\Model;

class Cate extends Model {
        public function cateTree(){
            $date=$this->select();//查询cate表中的数据
            $res = $this->cateSort($date);
           return $res;
        }
        
        //定义一个数组，每循环一条记录就把它放入该数组并unset该记录
        public function cateSort($data,$pid=0,$count=0){
           static $arr=array();//静态初始化
            foreach ($data as $k => $v){
                if($v['pid']==$pid){
                    $v['count']=$count;
                    $arr[]=$v;
                    unset($data[$k]);//去掉不再使用的
                    $this->cateSort($data,$v['id'],$count+1);
                }
            }
            return $arr;
        }
}