<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use  think\Route;
Route::get([
    'docs/edit/:id'  => ['admin/Document/index',[],['id'=>'\d+']],//编辑文档首页
    'docs/content/:id'=>['admin/Document/getContent',[],['id'=>'\d+']],//编辑文档
    'docs/show/:id'  => ['index/Document/show',[],['id'=>'\d+']],//文档的显示
    //index模块中的index控制器
    'docs/index'  => "index/index/index",//文档的首页
    'docs/list/:id'  => ['index/index/doclist',[],['id'=>'\d+']],//文档的列表页面
    'docs/detail/:id'  => ['index/index/docdetail',[],['id'=>'\d+']],//文档的详细信息
    'docs/pay/:id'  => ['index/index/alipay',[],['id'=>'\d+']],//文档的支付
    'search'  => 'index/index/search',
    //登录相关的路由
    'auth/login'  => "index/login/index",//登录
    'auth/register'  => "index/login/reg",//注册
    'auth/forgot' =>"index/login/reset",//找回密码
    'auth/logigout'=>"index/login/loginOut",//退出登录
    //index模块的member控制器
    'member/index'=>"index/member/index",//我的收藏
    'member/decollection/:id'=>['index/member/decollection',[],['id'=>'\d+']],//删除收藏
    'member/buy'=>"index/member/buy",//我的购买
    'member/reset'=>"index/member/reset",//修改密码
    'member/message'=>"index/member/message",//我的消息
    'member/member'=>"index/member/member",//个人中心
]);
Route::post([
    'docs/save'=> "admin/Document/save",//保存文档
    'docs/delete/:id'=> ['admin/Document/delete',[],['id'=>'\d+']],//删除文档
    'docs/sort/:id'=> ['admin/Document/sort',[],['id'=>'\d+']],//文档的排序
    'docs/history/delete'=>'admin/Document/deleteHistory',//删除文档记录
    'docs/history/restore'=>'admin/Document/restoreHistory',//回复文档记录
    'docs/upload'=>'admin/Document/upload'
]);
Route::rule('docs/history/:id','admin/Document/history','GET|POST');
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
