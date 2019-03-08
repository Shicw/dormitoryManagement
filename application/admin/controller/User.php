<?php
/**
 * Created by PhpStorm.
 * User: Shicw
 * Date: 2019/3/7
 * Time: 15:44
 */
namespace app\admin\controller;
use app\admin\model\UserModel;
use app\AdminBaseController;
use think\Controller;
use think\Db;

class User extends AdminBaseController
{
    /**
     * 查看宿管用户页
     */
    public function index(){
        $buildingList = Db::name('building')->select();
        $this->assign('buildingList',$buildingList);
        return $this->fetch();
    }

    /**
     * 加载宿管用户列表
     */
    public function getList(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $limit = $this->request->get('limit');
        $keyword = $this->request->param('keyword');//获取搜索关键词
        $conditions = [];//查询条件
        //关键字搜索
        if($keyword!=null){
            $conditions['username|name'] = ['like', "%$keyword%"];
        }

        $rows = Db::name('user')
            ->field(['id','username','name','status','building_id',
                'FROM_UNIXTIME(last_login_time,\'%Y-%m-%d %H:%i:%s\') last_login_time',
            ])
            ->where($conditions)
            ->where(['delete_time'=>0,'id'=>['>',1]])
            ->order('building_id asc')
            ->paginate($limit)
            ->toArray();
        if(count($rows['data'])>0){
            return [
                'code'=>0,
                'msg'=>'请求成功',
                'count'=>$rows['total'],
                'data'=>$rows['data']
            ];
        }else{
            return [
                'code' => 1,
                'msg' => '无数据',
                'count' => 0,
                'data' => ''
            ];
        }
    }
    /**
     * 禁用用户
     */
    public function disable(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');
        $model = new UserModel();
        $result = $model->doDisable($id);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 启用用户
     */
    public function enable(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');
        $model = new UserModel();
        $result = $model->doEnable($id);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 删除用户
     */
    public function delete(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');
        $model = new UserModel();
        $result = $model->doDelete($id);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 添加用户
     */
    public function addUser(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $data = $this->request->post()['data'];
        $model = new UserModel();
        $result = $model->doAdd($data);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
}