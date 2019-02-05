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

use app\model\AdModel;
use think\Controller;

class Ad extends Controller
{
    /**
     * 广告的列表
     */
    public function index()
    {
        if (request()->isAjax()) {
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = [];
            if (!empty($param['searchText'])) {
                $where['title'] = ['like', '%' . $param['searchText'] . '%'];
            }
            $adModel = new AdModel();
            $selectResult = $adModel->getAdsByWhere($where, $offset, $limit);

            foreach ($selectResult as $key => $vo) {
                $imgSrc = $vo['thumbnail'] ? $vo['thumbnail'] : "/static/admin/images/blank_img.jpg";
                $selectResult[$key]['thumbnail'] = '<img src="' . $imgSrc . '" width="40px" height="40px">';
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }
            $return['total'] = $adModel->getAllAds($where);  // 总数据
            $return['rows'] = $selectResult;
            return json($return);
        }
        return $this->fetch();
    }

    public function typeList()
    {
        if(request()->isAjax()){
//            $node=new ProjectCategoryModel();
//            $nodes = $node->getCategoryList();
//            $nodes = getTree(objToArray($nodes), false);
//            return json(msg(1, $nodes, 'ok'));
        }
        return $this->fetch();
    }
    /**
     * 广告得位置
     */
    public function adPosition()
    {
        return $this->fetch();
    }

    /**
     * 广告得添加
     */
    public function addAd()
    {
        if(request()->isPost()){
            $param = input('post.');
            $param['expired']=strtotime($param['expired']);
            $adModel=new AdModel();
            $result = $adModel->save($param);
            if(false === $result){
                return json(msg(-1, '', $this->getError()));
            }else{
                return json(msg(1, url('Ad/index'), '添加文章成功'));
            }
        }
        return $this->fetch();
    }

    /**
     * 广告的编辑
     */
    public function editAd()
    {
        return $this->fetch();
    }

    /**
     * 可操作性的按钮显示
     * @param $id
     * @param $showid
     * @return array
     */
    private function makeButton($id)
    {
        return [
            '编辑' => [
                'auth' => 'document/editproject',
                'href' => url('document/editProject', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            'doc编辑' => [
                'auth' => 'document/index',
                'href' => "/docs/edit/" . $id,
                'btnStyle' => 'primary',
                'icon' => 'fa fa-edit'
            ],
            '删除' => [
                'auth' => 'document/deleteproject',
                'href' => "javascript:proDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]

        ];
    }
}