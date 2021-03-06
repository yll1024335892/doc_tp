<?php
// +----------------------------------------------------------------------
// | 阿翼
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------
namespace app\admin\validate;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        ['user_name', 'unique:user', '管理员已经存在']
    ];

}