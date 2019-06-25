<?php 
// 数据表中需添加一个 delete_time 字段保存删除时间
namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

class Goods extends Model{
	/**
     * 删除记录
     * @access public
     * @param mixed $data 主键列表 支持闭包查询条件
     * @return integer 成功删除的记录数
     */
    public static function destroy($data){
    	//很多静态方法中都能看到这个语句:实例化调用此方法的类 
        $model = new static();  
        $query = $model->db();  //获取数据库连接实例
        //如果参数为数组并且是关联数组(元素键名不为0)
        if (is_array($data) && key($data) !== 0) { 
        	//将数组做为条件表达式
            $query->where($data);
            $data = null;    //清空本次查询条件
        } elseif ($data instanceof \Closure) { // 如果不是数组，则视为闭包查询
        	//自动调用自定义的闭包函数
            call_user_func_array($data, [ & $query]);
            $data = null;   //执行完毕清空条件
        } elseif (is_null($data)) { //如以上都不满足，再判断参数是否为空
            return 0;   //参数为空，则返回0 ：假
        }
        
        //根据条件，获取结果集
        $resultSet = $query->select($data);
        $count     = 0;   //计数器清零，为统计返回值做准备
        if ($resultSet) {  //如果结果集存在
            foreach ($resultSet as $data) {  //遍历该结果集
                $result = $data->delete();   //逐条删除记录
                $count += $result;  //计数器自增
            }
        }
        return $count;   //返回删除的记录数量
    }
}
