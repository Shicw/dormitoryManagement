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
        return $this->fetch();
    }
    /**
     * 登记退宿提交
     */
    public function checkOutPost(){}
}