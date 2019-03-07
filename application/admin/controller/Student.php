<?php
/**
 * Created by PhpStorm.
 * User: Shicw
 * Date: 2019/3/7
 * Time: 11:29
 */
namespace app\admin\controller;
use app\admin\model\StudentModel;
use app\AdminBaseController;
use think\Controller;
use think\Db;

class Student extends AdminBaseController
{
    /**
     * 登记入住页面
     */
    public function checkIn(){
        $buildingId = getAdmin()['building_id'];
        $this->assign('buildingId',$buildingId);
        return $this->fetch();
    }
    /**
     * 登记入住提交
     */
    public function checkInPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $data = $this->request->post()['data'];
        $model = new StudentModel();
        $result = $model->doCheckIn($data);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 登记退宿页面
     */
    public function checkOut(){
        $buildingId = getAdmin()['building_id'];
        $this->assign('buildingId',$buildingId);
        return $this->fetch();
    }
    /**
     * 登记退宿提交
     */
    public function checkOutPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $studentId = $this->request->post('studentId');
        $model = new StudentModel();
        $result = $model->doCheckOut($studentId);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }

    /**
     * 根据学号获取学生住宿信息
     */
    public function getStudentDetail(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $studentId = $this->request->get('studentId');

        $found = Db::name('student s')
            ->field(['s.*','d.dormitory_number','db.bed_number'])
            ->join([
                ['dormitory d','d.id=s.dormitory_id'],
                ['dormitory_bed db','db.id=s.bed_id'],
            ])
            ->where(['student_id'=>$studentId,'s.delete_time'=>0])
            ->find();
        if($found){
            $found['birthday'] = date("Y-m-d",$found['birthday']);
            $this->success('请求成功','',$found);
        }else{
            $this->error('无该学生住宿信息');
        }
    }
}