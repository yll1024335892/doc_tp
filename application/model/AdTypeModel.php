<?php
// +----------------------------------------------------------------------
// | 阿翼  Date: 2019/2/5  Time: 14:13
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------

namespace app\model;


use think\Model;

class AdTypeModel extends Model
{
    protected $name = 'ad_type';//表名
    protected $pk = "id";//自增的id
    protected $autoWriteTimestamp = true;//自动添加时间撮

    /**
     * 获取节点的列表
     * @return false
     */
    public function getAdTypeList()
    {
        return $this->field('id,name,parent_id pid,is_show')->select();
    }

    /**
     * 插入节点
     * @param $param
     * @return mixed
     */
    public function insertNode($param)
    {
        try {
            $this->save($param);
            return msg(1, '', '添加节点成功');
        } catch (PDOException $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑节点
     * @param $param
     * @return mixed
     */
    public function editNode($param)
    {
        try {
            $this->save($param, ['id' => $param['id']]);
            return msg(1, '', '编辑节点成功');
        } catch (PDOException $e) {
            return msg(-2, '', $e->getMessage());
        }
    }
}