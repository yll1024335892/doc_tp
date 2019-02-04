<?php
// +----------------------------------------------------------------------
// | 阿翼  Date: 2019/2/4  Time: 14:12
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Controller;

class Ad extends Controller
{
    /**
     * 广告的列表
     */
    public function index(){
        $this->fetch();
    }

    /**
     * 广告得位置
     */
    public function adPosition(){
        $this->fetch();
    }

    /**
     * 广告得添加
     */
    public function addAd(){
        $this->fetch();
    }

    /**
     * 广告的编辑
     */
    public function editAd(){
        $this->fetch();
    }
}