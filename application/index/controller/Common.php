<?php
// +----------------------------------------------------------------------
// | 阿翼  Date: 2019/2/12  Time: 8:30
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------

namespace app\index\controller;


use think\Controller;

class Common extends Controller
{
    public function _initialize(){
        $this->assign([
            'username' => session("username"),
            'id'=>''
        ]);
    }
}