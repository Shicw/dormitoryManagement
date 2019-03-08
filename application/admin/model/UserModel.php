<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2019/3/6
 * Time: 17:00
 */
namespace app\admin\model;
use think\Db;
use think\Model;

class UserModel extends Model
{
    protected $table = 'user';
    /**
     * 验证用户名密码
     * @param $username
     * @param $password
     * @param $type
     * @return array
     */
    public function doLogin($username, $password, $type){
        $find = $this->where(['username' => $username, 'password' => md5($password)])
            ->find();
        if ($find) {
            $find = $find->toArray();
            session($type, $find);//保存session
            //更新最后登录时间
            $this->where('id', $find['id'])->update(['last_login_time' => time()]);
            return ['code' => 1, 'msg' => '登录成功', 'data' => $find];
        } else {
            return ['code' => 0, 'msg' => '用户名或密码错误'];
        }
    }

    /**
     * 修改密码
     * @param $username
     * @param $oldPassword
     * @param $newPassword
     * @return array
     */
    public function changePassword($username, $oldPassword, $newPassword){
        //判断旧密码
        $find = $this->where(['username'=>$username,'password'=>md5($oldPassword)])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '旧密码错误'];
        }
        $update = $this->where('id',$find['id'])->update(['password'=>md5($newPassword)]);
        if ($update) {
            return ['code' => 1, 'msg' => '修改成功'];
        } else {
            return ['code' => 0, 'msg' => '修改失败'];
        }
    }

    /**
     * 禁用
     * @param $id
     * @return array
     */
    public function doDisable($id){
        $update = $this->where('id',$id)->update(['status'=>0]);
        if ($update) {
            return ['code' => 1, 'msg' => '禁用成功'];
        } else {
            return ['code' => 0, 'msg' => '禁用失败'];
        }
    }

    /**
     * 启用
     * @param $id
     * @return array
     */
    public function doEnable($id){
        $update = $this->where('id',$id)->update(['status'=>1]);
        if ($update) {
            return ['code' => 1, 'msg' => '禁用成功'];
        } else {
            return ['code' => 0, 'msg' => '禁用失败'];
        }
    }
    /**
     * 删除
     * @param $id
     * @return array
     */
    public function doDelete($id){
        $update = $this->where('id',$id)->update(['delete_time'=>time()]);
        if ($update) {
            return ['code' => 1, 'msg' => '删除成功'];
        } else {
            return ['code' => 0, 'msg' => '删除失败'];
        }
    }

    /**
     * 添加宿管用户
     * @param $data
     * @return array
     */
    public function doAdd($data){
        $insert = $this->insert([
            'username' => $data['username'],
            'password' => md5($data['password']),
            'name' => $data['name'],
            'building_id' => $data['building_id'],
            'status' => 1,
        ]);
        if ($insert) {
            return ['code' => 1, 'msg' => '添加成功'];
        } else {
            return ['code' => 0, 'msg' => '添加失败'];
        }
    }
}