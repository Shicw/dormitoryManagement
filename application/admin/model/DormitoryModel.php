<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/7
 * Time: 14:07
 */
namespace app\admin\model;
use think\Db;
use think\Exception;
use think\Model;

class DormitoryModel extends Model
{
    /**
     * 添加宿舍
     * @param $data
     */
    public function doAdd($data){
        //判断宿舍是否存在
        $found = $this->name('dormitory')
            ->where(['building_id'=>$data['building_id'],'floor'=>$data['floor'],'dormitory_number'=>$data['dormitory_number']])
            ->find();
        if($found){
            return ['code' => 0, 'msg' => '该宿舍已存在'];
        }
        $this->startTrans();//开启事务处理
        try{
            //插入宿舍信息
            $dormitory = $this->name('dormitory')->insertGetId([
                'building_id'=>$data['building_id'],
                'floor'=>$data['floor'],
                'dormitory_number'=>$data['dormitory_number'],
                'max_student_count'=>$data['max_student_count']
            ]);
            if (!$dormitory) {
                throw new Exception("宿舍添加失败");
            }
            //插入床位信息
            for($i=0;$i<$data['max_student_count'];$i++){
                $result = $this->name('dormitory_bed')->insert([
                    'dormitory_id' => $dormitory,
                    'bed_number' =>  $i+1,
                    'status' => 0
                ]);
                if (!$result) {
                    throw new Exception("宿舍添加失败");
                }
            }
            $this->commit();// 提交事务
        }catch(Exception $e){
            $this->rollback();// 回滚事务
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
        return ['code' => 1, 'msg' => '宿舍添加成功'];
    }

    /**
     *删除宿舍
     * @param $dormitoryId
     * @return array
     */
    public function doDelete($dormitoryId){
        //判断宿舍的床位是否有人
        $found = $this->name('dormitory_bed')->where(['dormitory_id'=>$dormitoryId,'status'=>1])->find();
        if($found){
            return ['code' => 0, 'msg' => '该宿舍有人住宿不能删除'];
        }
        $result = $this->name('dormitory')->where('id',$dormitoryId)->update(['delete_time'=>time()]);
        if($result){
            return ['code' => 1, 'msg' => '宿舍删除成功'];
        }else{
            return ['code' => 0, 'msg' => '宿舍删除失败'];
        }
    }

}