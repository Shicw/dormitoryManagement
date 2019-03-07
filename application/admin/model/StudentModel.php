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
     * 等级入住提交
     * @param $data
     * @return array
     */
    public function doCheckIn($data){
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
                throw new Exception("登记失败");
            }
            $this->commit();// 提交事务
        }catch(Exception $e){
            $this->rollback();// 回滚事务
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
        return ['code' => 1, 'msg' => '登记成功'];
    }
}