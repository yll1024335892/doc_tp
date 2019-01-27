<?php
// +----------------------------------------------------------------------
// | 阿翼  Date: 2019/1/27  Time: 13:18
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\model\ProjectCategoryModel;


class Category extends Base
{
    // 栏目的管理列表
    public function index()
    {
        if(request()->isAjax()){
            $node=new ProjectCategoryModel();
            $nodes = $node->getCategoryList();
            $nodes = getTree(objToArray($nodes), false);
            return json(msg(1, $nodes, 'ok'));
        }
        return $this->fetch();
    }

    // 添加栏目
    public function categoryAdd()
    {
        $param = input('post.');
        $node = new ProjectCategoryModel();
        $flag = $node->insertNode($param);
        return json(msg($flag['code'], $flag['data'], $flag['msg']));
    }

    // 编辑栏目
    public function categoryEdit()
    {
        $param = input('post.');
        $node = new ProjectCategoryModel();
        $flag = $node->editNode($param);
        return json(msg($flag['code'], $flag['data'], $flag['msg']));
    }

    // 删除栏目
    public function categoryDel()
    {
        $id = input('param.id');
        $model=new ProjectCategoryModel();
        $flag = $model->delNode($id);
        return json(msg($flag['code'], $flag['data'], $flag['msg']));
    }
}