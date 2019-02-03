<?php
namespace app\index\controller;

use app\model\ProjectCategoryModel;
use app\model\ProjectModel;
use think\Controller;
use think\Db;

class Index extends Controller
{
    /**
     * 文档的首页显示
     * @return mixed
     */
    public function index()
    {
      //  $list=  Db::table('ayi_project_category')->alias('a')->join('__PROJECT__ b','a.id = b.type_id')->limit(0,8)->select();
        $cateModel=new ProjectCategoryModel();
        $cateList=$cateModel->select();
        $projectModel=new ProjectModel();
        foreach ($cateList as $key=>$val){
            $cateList[$key]['list']=$projectModel->where("type_id","eq",$val['id'])->select();
        }
        return $this->fetch("",['cateList'=>$cateList]);
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
