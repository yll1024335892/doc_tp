<?php
// +----------------------------------------------------------------------
// | 阿翼  Date: 2019/2/12  Time: 19:10
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------

namespace app\model;


use think\Model;

class OrderModel extends Model
{
    protected $name = 'order';//表名
    protected $pk = "id";//自增的id
    protected $autoWriteTimestamp = true;//自动添加时间戳

    /**
     * 获取购买的数据
     * @param $id  用户的id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function buyList($id){
        return $this->alias('u')
            ->field('u.id,u.project_id,p.project_name,p.description,p.thumbnail')
            ->join('project p', 'u.project_id = p.project_id')
            ->where('u.user_id', $id)
            ->where("u.is_pay","eq","1")
            ->select();
    }

    public static function  getIspriceByUseridAndProjectid($userid,$projectid){
        $isPrice=OrderModel::field("")->where()->where()->find();
    }
    
}