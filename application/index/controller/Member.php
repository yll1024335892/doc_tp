<?php
// +----------------------------------------------------------------------
// | 阿翼  Date: 2019/2/12  Time: 18:02
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 深圳市阿翼互联网有限公司 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 深圳市阿翼互联网有限公司
// +----------------------------------------------------------------------

namespace app\index\controller;


class Member extends Common
{
    /**
     * 我的收藏
     * @return mixed
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * 我的购买
     * @return mixed
     */
    public function buy(){
        return $this->fetch();
    }

    /**
     * 重置密码
     * @return mixed
     */
    public function reset(){
        return $this->fetch();
    }

    /**
     * 我的消息
     * @return mixed
     */
    public function message(){
        return $this->fetch();
    }

}