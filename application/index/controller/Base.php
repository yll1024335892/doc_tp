<?php
// +----------------------------------------------------------------------
// | 阿翼  Date: 2019/2/11  Time: 19:02
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------

namespace app\index\controller;


use think\Controller;
use think\Request;
class Base  extends Controller
{
    public function _initialize()
    {
        if(empty(session('username')) || empty(session('id'))){
            $loginUrl = "/auth/login";
            if(request()->isAjax()){
                return msg(111, $loginUrl, '登录超时');
            }
            $this->redirect($loginUrl);
        }
        $this->assign([
            'id'     => session('id'),
            'username' => session('username')
        ]);
    }
}