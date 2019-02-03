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
        $projectModel = new ProjectModel();
        $listData = $projectModel->where("is_home", "eq", "1")->limit(0, 30)->select();
        return $this->fetch("", ['list' => $listData]);
    }

    public function cateList()
    {
        $cateModel = new ProjectCategoryModel();
        $cateList = $cateModel->where("parent_id", "eq", "0")->select();
        $projectModel = new ProjectModel();
        foreach ($cateList as $key => $val) {
            $cateList[$key]['list'] = $projectModel->where("type_id", "eq", $val['id'])->limit(0, 8)->select();
        }
        return $this->fetch("", ['cateList' => $cateList]);
    }

    /**
     * 文档的列表页面显示
     */
    public function doclist()
    {
        $cateModel = new ProjectCategoryModel();
        $cateList = $cateModel->where("parent_id", "neq", "0")->where("is_show","eq","1")->select();
        return $this->fetch("",['cate'=>$cateList]);
    }

    /**
     * 文档的具体的详细
     */
    public function docdetail()
    {
        return $this->fetch();
    }


}
