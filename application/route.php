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
    'docs/pay/:id'  => ['index/index/alipay',[],['id'=>'\d+']],
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
