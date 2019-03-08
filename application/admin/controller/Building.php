<?php
/**
 * Created by PhpStorm.
 * User: Shicw
 * Date: 2019/3/7
 * Time: 14:04
 */
namespace app\admin\controller;
use app\admin\model\BuildingModel;
use app\AdminBaseController;
use think\Controller;
use think\Db;

class Building extends AdminBaseController
{
    /**
     * 查看宿舍楼楼页面
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * 加载列表
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
            $conditions['building_id'] = ['like', "%$keyword%"];
        }

        $rows = Db::name('building')
            ->where($conditions)
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
     * 添加楼
     */
    public function add(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $data = $this->request->post()['data'];
        $model = new BuildingModel();
        $result = $model->doAdd($data);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }

    /**
     * 删除楼
     */
    public function delete(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');
        $model = new BuildingModel();
        $result = $model->doDelete($id);
        if($result['code']===1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
}