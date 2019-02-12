<?php
// +----------------------------------------------------------------------
// | 阿翼  Date: 2019/2/12  Time: 21:10
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------

namespace app\model;


use think\Model;

class CollectionModel extends Model
{
    protected $name = 'collection';//表名
    protected $pk = "id";//自增的id
    protected $autoWriteTimestamp = true;//自动添加时间戳

    public function deleteCollection($id){
       return $this->where("id","eq",$id)->delete();
    }
}