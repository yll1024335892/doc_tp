<?php

namespace app\admin\validate;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        ['user_name', 'unique:user', '管理员已经存在']
    ];

}