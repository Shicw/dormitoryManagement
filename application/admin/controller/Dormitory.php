<?php
/**
 * Created by PhpStorm.
 * User: Shicw
 * Date: 2019/3/7
 * Time: 14:04
 */
namespace app\admin\controller;
use app\admin\model\DormitoryModel;
use app\AdminBaseController;
use think\Controller;
use think\Db;

class Dormitory extends AdminBaseController
{
    /**
     * 根据楼号楼层获取宿舍列表
     */
    public function getListByFloor(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $buildingId = $this->request->get('buildingId');
        $floor = $this->request->get('floor');


        $list = Db::name('dormitory')
            ->field(['id','dormitory_number'])
            ->where(['building_id'=>$buildingId,'floor'=>$floor,'delete_time'=>0])
            ->select();
        if(count($list)>0){
            $this->success('请求成功','',$list);
        }else{
            $this->error('无数据');
        }
    }

    /**
     * 根据宿舍id获取床位列表
     */
    public function getListByDormitory(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $dormitoryId = $this->request->get('dormitoryId');

        $list = Db::name('dormitory_bed')
            ->field(['id','bed_number'])
            ->where(['dormitory_id'=>$dormitoryId,'status'=>0])
            ->select();
        if(count($list)>0){
            $this->success('请求成功','',$list);
        }else{
            $this->error('无数据');
        }
    }

}