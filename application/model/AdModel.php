<?php
// +----------------------------------------------------------------------
// | 阿翼  Date: 2019/2/4  Time: 15:15
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------

namespace app\model;


use think\Model;

class AdModel extends Model
{
    protected $name = 'ad';//表名
    protected $pk = "id";//自增的id
    protected $autoWriteTimestamp = true;//自动添加时间撮


    public function getAdsByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    public function getAllAds($where)
    {
        return $this->where($where)->count();
    }
}