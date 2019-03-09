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
     * 宿舍管理页面
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * 获取楼,宿舍
     */
    public function getDormitoryTree(){
        $buildingId = getAdmin()['building_id'];//获取管理员的所属楼号,若为0,则代表admin操作,可以查看全部楼
        if($buildingId>0){
            $buildingArr = Db::name('building')->where('building_id',$buildingId)->select();
        }else{
            $buildingArr = Db::name('building')->select();
        }
        $data = [];

        foreach ($buildingArr as $key1 => $a){
            $thisBuilding = ['name'=>$a['building_id'].'('.$a['name'].')','id'=>$a['building_id'],'type'=>'building','children'=>[]];
            //为楼创建楼层children子节点数组
            for($i=0;$i<$a['floor_count'];$i++){
                array_push($thisBuilding['children'],['name'=>($i+1).'层','type'=>'floor','building_id'=>$a['building_id'],'children'=>[]]);
            }

            //循环每个楼获取其宿舍
            $dormitory = Db::name('dormitory')
                ->field(['id','dormitory_number','floor'])
                ->where(['building_id'=>$a['building_id'],'delete_time'=>0])
                ->select();
            //循环每个宿舍,获取其床位数,然后按照楼层分组
            foreach ($dormitory as $key2 => $d){
                $thisDormitory = ['name'=>$d['dormitory_number'],'id'=>$d['id'],'type'=>'dormitory','children'=>[]];
                $bed = Db::name('dormitory_bed')
                    ->field(['id','bed_number','status'])
                    ->where(['dormitory_id'=>$d['id']])
                    ->select();
                foreach ($bed as $b){
                    $statusStr = $b['status'] ? '有人' : '无人';
                    $thisBed = ['name'=>$b['bed_number'].'号床'.'('.$statusStr.')','type'=>'bed','id'=>$b['id'],'status'=>$b['status']];
                    array_push($thisDormitory['children'],$thisBed);
                }
                array_push($thisBuilding['children'][$d['floor']-1]['children'],$thisDormitory);
            }
            array_push($data,$thisBuilding);
        }
        if(count($data)>0){
            $this->success('请求成功','',$data);
        }else{
            $this->error('无数据');
        }
    }
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

    /**
     * 根据床id获取学生住宿信息
     */
    public function getStudentDetail(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $bedId = $this->request->get('bedId');

        $found = Db::name('student s')
            ->field(['s.*','d.dormitory_number','db.bed_number'])
            ->join([
                ['dormitory d','d.id=s.dormitory_id'],
                ['dormitory_bed db','db.id=s.bed_id'],
            ])
            ->where(['bed_id'=>$bedId,'s.delete_time'=>0])
            ->find();
        if($found){
            $found['birthday'] = date("Y-m-d",$found['birthday']);
            $this->success('请求成功','',$found);
        }else{
            $this->error('无该学生住宿信息');
        }
    }
    /**
     * 添加宿舍页面
     */
    public function add(){
        $buildingId = $this->request->get('buildingId');
        $floor = $this->request->get('floor');
        $this->assign([
            'buildingId' => $buildingId,
            'floor' => $floor
        ]);
        return $this->fetch();
    }
    /**
     * 添加宿舍提交
     */
    public function addPost(){
        $data = $this->request->post()['data'];
        $model = new DormitoryModel();
        $result = $model->doAdd($data);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 添加宿舍提交
     */
    public function delete(){
        $dormitoryId = $this->request->post('id');
        $model = new DormitoryModel();
        $result = $model->doDelete($dormitoryId);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
}