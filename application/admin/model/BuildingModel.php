<?php
/**
 * Created by PhpStorm.
 * User: Shicw
 * Date: 2019/3/8
 * Time: 9:46
 */
namespace app\admin\model;
use think\Db;
use think\Model;

class BuildingModel extends Model
{
    /**
     * 添加宿舍楼
     * @param $data
     * @return array
     */
    public function doAdd($data){
        $insert = $this->name('building')->insert([
            'name' => $data['name'],
            'building_id' => $data['building_id'],
            'floor_count' => $data['floor_count'],

        ]);
        if ($insert) {
            return ['code' => 1, 'msg' => '添加成功'];
        } else {
            return ['code' => 0, 'msg' => '添加失败'];
        }
    }
    /**
     * 删除
     * @param $id
     * @return array
     */
    public function doDelete($id){
        $update = $this->name('building')->where('building_id',$id)->delete();
        if ($update) {
            return ['code' => 1, 'msg' => '删除成功'];
        } else {
            return ['code' => 0, 'msg' => '删除失败'];
        }
    }
}