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
    public function doclist($id)
    {
        //获取所有的类别
        $cateModel = new ProjectCategoryModel();
        $cateList = $cateModel->where("parent_id", "neq", "0")->where("is_show", "eq", "1")->select();
        $cateName=$cateModel->field("cate_name")->find($id);
        //获取指定类别的数据
        $projectModel=new ProjectModel();
        $proList=$projectModel->where("type_id","eq",(int)$id)->limit(0,14)->select();
        return $this->fetch("", ['cate' => $cateList,'project'=>$proList,"nowTypeId"=>(int)$id,"cateName"=>$cateName['cate_name']]);
    }

    /**
     * 文档的具体的详细
     */
    public function docdetail($id)
    {
        if ((int)$id > 0) {
            $model = new ProjectModel();
            //获取当前的文档
            $res = $model->where("project_id", "eq", $id)->find();
            $typeID = $res['type_id'];
            //相似性推荐
            $hotRes = $model->where("type_id", "eq", $typeID)->where("is_home", "eq", "1")->limit(0, 6)->select();
            if (count($hotRes) <= 0) {
                $hotRes = "";
            }
            if ($res['tag'] != null && isset($res['tag'])) {
                return $this->fetch("", ["data" => $res, "tag" => explode(";", $res['tag']), "hotRes" => $hotRes]);
            } else {
                return $this->fetch("", ["data" => $res, "tag" => "", "hotRes" => $hotRes]);
            }
        } else {
            $this->error("文档不存在");
        }

    }


}
