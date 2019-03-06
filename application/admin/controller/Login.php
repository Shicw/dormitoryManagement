<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2019/3/6
 * Time: 16:43
 */
namespace app\admin\controller;
use app\admin\model\UserModel;
use think\Controller;

class Login extends Controller
{
    /**
     * 登录页
     * @return mixed
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * 登录提交
     */
    public function doLogin(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $post = $this->request->post()['data'];
        $username = $post['username'];
        $password = $post['password'];
        if($username=='' || $password==''){
            $this->error('用户名密码不能为空');
        }
        $model = new UserModel();
        $result = $model->doLogin($username,$password,'admin');//登录验证
        if($result['code']==1){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 注销
     */
    public function loginOut(){
        session('admin',null);
        $this->success('注销成功');
    }
}