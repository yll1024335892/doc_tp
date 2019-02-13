<?php
// +----------------------------------------------------------------------
// | 阿翼  Date: 2019/2/12  Time: 9:26
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------

namespace app\model;


use think\Model;

class UserUserModel extends Model
{
    protected $name = 'user_user';//表名
    protected $pk = "id";//自增的id
    protected $autoWriteTimestamp = true;//自动添加时间戳

    /**
     * 根据用户名检测用户数据
     * @param $userName
     */
    public function checkUser($email)
    {
        return  $this->where("email","eq",$email)->find();
    }
    

}