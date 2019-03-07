<?php
/**
 * Created by PhpStorm.
 * User: Shicw
 * Date: 2019/3/7
 * Time: 14:35
 */
namespace app\admin\model;
use think\Db;
use think\Exception;
use think\Model;

class StudentModel extends Model
{
    /**
     * 办理入住
     * @param $data
     * @return array
     */
    public function doCheckIn($data){
        //判断宿舍是否满员
        $found = $this->name('dormitory')->where(['id'=>$data['dormitory_id']])->find();
        if($found['real_student_count']===$found['max_student_count']){
            return ['code' => 0, 'msg' => '宿舍已满员'];
        }

        $this->startTrans();//开启事务处理
        try{
            //写入信息
            $result = $this->name('student')->insert([
                'student_id' => $data['student_id'],
                'name' => $data['name'],
                'mobile' => $data['mobile'],
                'class' => $data['class'],
                'birthday' => strtotime($data['birthday']),
                'grade' => $data['grade'],
                'building_id' => $data['building_id'],
                'dormitory_id' => $data['dormitory_id'],
                'bed_id' => $data['bed_id'],
                'create_time' => time(),
            ]);
            if(!$result){
                throw new Exception("登记失败");
            }
            //该床位status设为1
            $result = $this->name('dormitory_bed')->where(['id'=>$data['bed_id']])->update(['status'=>1]);
            if (!$result) {
                throw new Exception("办理入住失败");
            }
            //宿舍实际人数+1
            $result = $this->name('dormitory')->where(['id'=>$data['dormitory_id']])->setInc('real_student_count',1);
            if (!$result) {
                throw new Exception("办理入住失败");
            }
            $this->commit();// 提交事务
        }catch(Exception $e){
            $this->rollback();// 回滚事务
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
        return ['code' => 1, 'msg' => '办理入住成功'];
    }

    /**
     * 办理退宿
     * @param $studentId
     * @return array
     */
    public function doCheckOut($studentId){
        //判断该学生信息是否存在
        $found = $this->name('student')->where(['student_id'=>$studentId,'delete_time'=>0])->find();
        if(!$found){
            return ['code' => 0, 'msg' => '该学生住宿信息不存在'];
        }

        $this->startTrans();//开启事务处理
        try{
            //删除学生住宿信息
            $result = $this->name('student')->where('student_id',$studentId)->update(['delete_time'=>time()]);
            if(!$result){
                throw new Exception("办理退宿失败");
            }
            //该床位status设为0
            $result = $this->name('dormitory_bed')->where(['id'=>$found['bed_id']])->update(['status'=>0]);
            if (!$result) {
                throw new Exception("办理退宿失败");
            }
            //宿舍实际人数-1
            $result = $this->name('dormitory')->where(['id'=>$found['dormitory_id']])->setDec('real_student_count',1);
            if (!$result) {
                throw new Exception("办理退宿失败");
            }
            $this->commit();// 提交事务
        }catch(Exception $e){
            $this->rollback();// 回滚事务
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
        return ['code' => 1, 'msg' => '办理退宿成功'];
    }
}