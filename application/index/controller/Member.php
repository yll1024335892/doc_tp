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


use app\model\CollectionModel;
use app\model\MessageUserModel;
use app\model\OrderModel;
use app\model\UserUserModel;
use think\Request;
use org\Verify;

class Member extends Base
{
    /**
     * 我的收藏
     * @return mixed
     */
    public function index()
    {
        $this->assign("active", "/member/index");
        $collectModel = new CollectionModel();
        $userId = session("id");
        $res = $collectModel->alias('c')
            ->field('c.id,c.project_id,c.is_price,p.project_name,p.description,p.thumbnail')
            ->join('project p', 'c.project_id = p.project_id', "RIGHT")
            ->where('c.user_id', $userId)
            ->paginate(18);
        $page = $res->render();
        $this->assign(["page" => $page]);
        return $this->fetch("", ['list' => count($res) > 0 ? $res : ""]);
    }

    /**
     * 个人中心
     */
    public function member()
    {
        $this->assign("active", "/member/member");
        return $this->fetch();
    }

    /**
     * 激活用户
     */
    public function isactive()
    {
        if (Request::instance()->isAjax()) {
            // sendEmail("邮箱激活",session('username'),)
            $isActive = UserUserModel::field('is_active')->where("id", "eq", input('param.id'))->find();
            echo $isActive['is_active'];
            if(!empty($isActive)){
               if($isActive['is_active']){
                   $token=md5(time());
                   session("is_active_token",$token);
                //   $url = Request::instance()->domain() . "/index/member/isactive?id=".input('param.id')."&token=".$token;
                   return json(msg(1, [], $isActive));
               }
            }
        }
     //   UserUserModel::save([],['id']);
        echo "单独请求！";
    }

    /**
     * 删除收藏
     * @param $id   收藏表的id
     */
    public function decollection($id)
    {
        $collectionModel = new CollectionModel();
        $res = $collectionModel->deleteCollection($id);
        $this->redirect("/member/index");
    }

    /**
     * 通过ajax添加收藏
     */
    public function addcollection()
    {
        if (Request()->isAjax()) {//是ajax过来的值
            //判断是否有收藏
            $userid = input("param.userid");
            $projecid = input("param.projecid");
            $rule = [
                ['userid', 'number', '用户id不为空'],
                ['projecid', 'number', '文档id不为空']
            ];
            $result = $this->validate(compact('userid', 'projecid'), $rule);
            if (true !== $result) {
                return json(msg(-1, '', $result));
            }
            $collectionModel = new CollectionModel();
            $addRes = $collectionModel->addCollection($userid, $projecid);
            if (1 == $addRes) {
                return json(msg(-1, [], "已经收藏！"));
            } else if (2 == $addRes) {
                return json(msg(1, [], "收藏成功！"));
            } else if (3 == $addRes) {
                return json(msg(-1, [], "收藏失败！"));
            }
        }
    }

    /**
     * 我的购买
     * @return mixed
     */
    public function buy()
    {
        $this->assign("active", "/member/buy");
        $orderModel = new OrderModel();
        $userId = session("id");
        $res = $orderModel->alias('u')
            ->field('u.id,u.project_id,p.project_name,p.description,p.thumbnail')
            ->join('project p', 'u.project_id = p.project_id')
            ->where('u.user_id', $userId)
            ->where("u.is_pay", "eq", "1")
            ->paginate(18);
        $page = $res->render();
        $this->assign("page", $page);
        return $this->fetch("", ['list' => $res]);
    }

    /**
     * 重置密码
     * @return mixed
     */
    public function reset()
    {
        $this->assign("active", "/member/reset");
        return $this->fetch();
    }

    /**
     * 我的消息
     * @return mixed
     */
    public function message()
    {
        $this->assign("active", "/member/message");
        $messageUserModel = new MessageUserModel();

        $res = $messageUserModel->alias("u")
            ->field("u.*,m.title,m.contain,m.is_cancel,m.is_delete,m.msg_type")
            ->join("message m", "u.msg_id = m.id")
            ->where("u.user_id", "eq", session("id"))
            ->paginate(18);
        $page = $res->render();
        $this->assign("page", $page);
        return $this->fetch("", ["list" => count($res) > 0 ? $res : ""]);
    }

}