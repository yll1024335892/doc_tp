<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    /**
     * 文档的首页显示
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 文档的列表页面显示
     */
    public function doclist(){
        return $this->fetch();
    }

    /**
     * 文档的具体的详细
     */
    public function  docdetail(){
        return $this->fetch();
    }


}
