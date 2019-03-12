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


use app\model\UserUserModel;
use think\Controller;
use org\Verify;
use think\Request;

class Login extends Controller
{
    public function index()
    {
        if (session('id')) {
            $this->redirect(url('/index'));
        } else {
            return $this->fetch();
        }

    }

    // 登录操作
    public function doLogin()
    {
        $userName = input("param.user_name");
        $password = input("param.password");
        $code = input("param.code");
        $rule = [
            ['userName', 'require', '用户名不能为空'],
            ['password', 'require', '密码不能为空'],
            ['code', 'require', '验证码不能为空']
        ];
        $result = $this->validate(compact('userName', 'password', "code"), $rule);
        if (true !== $result) {
            return json(msg(-1, '', $result));
        }

        $verify = new Verify();
        if (!$verify->check($code)) {
            return json(msg(-2, '', '验证码错误'));
        }

        $userModel = new UserUserModel();
        $hasUser = $userModel->checkUser($userName);

        if (empty($hasUser)) {
            return json(msg(-3, '', '此用户不存在'));
        }

        if (md5($password . config('salt')) != $hasUser['password']) {
            return json(msg(-4, '', '密码错误'));
        }

        if (1 != $hasUser['status']) {
            return json(msg(-5, '', '该账号被禁用'));
        }
        session('username', $hasUser['email']);
        session('id', $hasUser['id']);
        session('status',$hasUser['status']);
        session('is_active',$hasUser['is_active']);
        return json(msg(1, url('/index'), '登录成功'));
    }

    // 退出操作
    public function loginOut()
    {
        session('username', null);
        session('id', null);
        session('status',null);
        session('is_active',null);
        $this->redirect(url('/index'));
    }

    public function reg()
    {
        if (session('id')) {
            $this->redirect(url('/index'));
        } else {
            return $this->fetch();
        }
    }

    public function doReg()
    {
        if (request()->isAjax()) {
            $userName = input("param.user_name");
            $password = input("param.password");
            $password2 = input("param.password2");
            $code = input("param.code");
            $rule = [
                ['userName', 'require', '用户名不能为空'],
                ['password', 'require|max:25', '密码不能为空'],
                ['password2', 'require|confirm:password', '两次输入的密码不一致'],
                ['code', 'require', '验证码不能为空']
            ];
            $result = $this->validate(compact('userName', 'password2', 'password', "code"), $rule);
            if (true !== $result) {
                return json(msg(-1, '', $result));
            }
            $verify = new Verify();
            if (!$verify->check($code)) {
                return json(msg(-2, '', '验证码错误'));
            }

            $userModel = new UserUserModel();
            $hasUser = $userModel->checkUser($userName); //未激活的用户处理  todo
            if (!empty($hasUser)) {
                return json(msg(-3, '', '此用户已经存在'));
            }
            $insertRes = $userModel->save(['email' => $userName, 'password' => md5($password . config('salt'))]);
            if ($insertRes) {
                //  return json(msg(1, '', '注册成功,到邮箱中激活'));
                session(null);
                session('username', $userName);
                session('id', $insertRes);
                return json(msg(1, url('/index'), '登录成功'));
            } else {
                return json(msg(-5, '', '注册失败'));
            }
        }
    }

    public function reset()
    {
        return $this->fetch();
    }

    // 验证码
    public function checkVerify()
    {
        $verify = new Verify();
        $verify->imageH = 32;
        $verify->imageW = 100;
        $verify->length = 4;
        $verify->useNoise = false;
        $verify->fontSize = 14;
        return $verify->entry();
    }

}