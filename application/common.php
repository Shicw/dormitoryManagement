<?php
// 应用公共文件
//获取登录管理员的id
function getAdminId(){
    $sessionAdminId = session('admin.id');
    if (empty($sessionAdminId)) {
        return 0;
    }
    return $sessionAdminId;
}
//获取管理员信息
function getAdmin(){
    $sessionAdmin = session('admin');
    if (!empty($sessionAdmin)) {
        return $sessionAdmin;
    } else {
        return false;
    }
}