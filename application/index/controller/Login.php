<?php
// +----------------------------------------------------------------------
// | 阿翼  Date: 2019/2/11  Time: 20:34
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------

namespace app\index\controller;


use think\Controller;

class Login extends Controller
{
    public function index(){
        return $this->fetch();
    }

    public function reg(){
        return $this->fetch();
    }

    public function reset(){
        return $this->fetch();
    }
}