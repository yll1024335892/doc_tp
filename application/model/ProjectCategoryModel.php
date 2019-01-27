<?php
// +----------------------------------------------------------------------
// | 阿翼  Date: 2019/1/27  Time: 13:43
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------

namespace app\model;


use think\Model;

class ProjectCategoryModel extends Model
{
    protected $name = 'project_category';
    protected $pk="id";

    /**
     * 获取栏目数据
     * @return mixed
     */
    public function getCategoryList()
    {
        return $this->field('id,cate_name name,parent_id pid,is_show')->select();
    }
    /**
     * 插入栏目信息
     * @param $param
     */
    public function insertNode($param)
    {
        try{

            $this->save($param);
            return msg(1, '', '添加节点成功');
        }catch(PDOException $e){

            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑栏目
     * @param $param
     */
    public function editNode($param)
    {
        try{

            $this->save($param, ['id' => $param['id']]);
            return msg(1, '', '编辑节点成功');
        }catch(PDOException $e){

            return msg(-2, '', $e->getMessage());
        }
    }


    /**
     * 删除栏目
     * @param $id
     */
    public function delNode($id)
    {
        try{

            $this->where('id', $id)->delete();
            return msg(1, '', '删除节点成功');

        }catch(PDOException $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}