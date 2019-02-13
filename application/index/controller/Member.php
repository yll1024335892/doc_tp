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
        $this->assign(["page"=>$page]);
        return $this->fetch("", ['list' => count($res) >0 ? $res :""]);
    }

    public function decollection($id)
    {
        $collectionModel = new CollectionModel();
        $res = $collectionModel->deleteCollection($id);
        $this->redirect("/member/index");
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
        $messageUserModel=new MessageUserModel();

        $res= $messageUserModel->alias("u")
            ->field("u.*,m.title,m.contain,m.is_cancel,m.is_delete,m.msg_type")
            ->join("message m","u.msg_id = m.id")
            ->where("u.user_id","eq",session("id"))
            ->paginate(18);
        $page = $res->render();
        $this->assign("page", $page);
        return $this->fetch("",["list"=> count($res) >0 ? $res :""]);
    }

}