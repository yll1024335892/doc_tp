<?php
/**
 * Created by 深圳市阿翼互联网有限公司.
 * User: yinliangliang
 * Date: 2019/1/13
 * Time: 13:58
 * file: Document.php
 * email:yll1024335892@163.com
 */

namespace app\admin\controller;


use app\model\DocumentModel;
use app\model\ProjectCategoryModel;
use app\model\ProjectModel;
use think\Controller;
use think\Request;

/**
 * Class Document文档相关的处理
 * @package app\admin\controller
 */
class Document extends Controller
{
    /**
     * 文档编辑首页
     */
    public function index($id)
    {
        if (empty($id) or $id <= 0) {
            //返回404页面
        }
        $project = ProjectModel::get($id);
        $project = $project->toArray();
        if (empty($project)) {
            //返回404页面
        }
        //判断是否有编辑权限  todo
//        if(Project::hasProjectEdit($id,$this->member->member_id) === false){
//            //返回404页面
//        }
        $docData = DocumentModel::where('project_id', '=', $id)
            ->order('doc_sort', 'ASC')
            ->field(['doc_id', 'doc_name', 'parent_id'])
            ->select();
        $this->detailProjectTree($docData, $id);
        $jsonArray = ProjectModel::getProjectArrayTree($id);
        if (empty($jsonArray) === false) {
            $jsonArray[0]['state']['selected'] = true;
            $jsonArray[0]['state']['opened'] = true;
        }
        $this->data['project_id'] = $id;
        $this->data['project'] = $project;
        $this->data['json'] = json_encode($jsonArray, JSON_UNESCAPED_UNICODE);
        return $this->fetch('', ['data' => $this->data]);
    }

    /**
     * 文档的列表
     */
    public function doclist()
    {
        if (request()->isAjax()) {
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = [];
            if (!empty($param['searchText'])) {
                $where['project_name'] = ['like', '%' . $param['searchText'] . '%'];
            }
            $Model = new ProjectModel();
            $selectResult = $Model->getProjectsByWhere($where, $offset, $limit);
            foreach ($selectResult as $key => $vo) {
                $imgSrc = $vo['thumbnail'] ? $vo['thumbnail'] : "/static/admin/images/blank_img.jpg";
                $selectResult[$key]['thumbnail'] = '<img src="' . $imgSrc . '" width="40px" height="40px" onclick="javascript:scaleImg(this)">';
                $selectResult[$key]['cate']=$selectResult[$key]['type_id'];
                $selectResult[$key]['is_home']=$selectResult[$key]['is_home']?"是":"否";
                if (isset($vo['doc_tree']) && $vo['doc_tree'] != null) {
                    $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['project_id'], json_decode($vo['doc_tree'], true)[0]['doc_id']), true);
                } else {
                    $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['project_id'], ""), true);
                }
            }
            $return['total'] = $Model->getAllProjects($where);  // 总数据
            $return['rows'] = $selectResult;
            return json($return);
        }

        return $this->fetch();
    }

    /**
     * 添加一个文档
     */
    public function addProject()
    {
        if (request()->isPost()) {
            $param = input('post.');
            $param['price']=(float)$param['price'];
            unset($param['file']);
            //  $param['add_time'] = date('Y-m-d H:i:s');
            $model = new ProjectModel();
            $flag = $model->addProject($param);
            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }
        $projectCateModel=new ProjectCategoryModel();
        $treeData = getProjectTree($projectCateModel->select());
        return $this->fetch("",['treeData'=>$treeData]);
    }

    private function detailProjectTree($data, $project_id)
    {
        $treeData = getDocumentTree($data, $project_id);
        $model = new ProjectModel();
        $str = "[";
        foreach ($treeData as $val) {
            $str .= $val . ",";
        }
        $str = substr($str, 0, strlen($str) - 1);
        $str .= "]";
        $model->upDateTree($str, $project_id);
    }

    /**
     * 编辑文档
     */
    public function editProject()
    {
        $model = new ProjectModel();
        if (request()->isPost()) {
            $param = input('post.');
            $param['price']=(float)$param['price'];
            $flag = $model->addProject($param);
            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        } else {
            $res = $model->where("project_id", 'eq', (int)input("id"))->find();
            $projectCateModel=new ProjectCategoryModel();
            $treeData = getProjectTree($projectCateModel->select());
            return $this->fetch('', ['treeData'=>$treeData,"res"=>$res]);
        }

    }

    /**
     * 删除文档
     */
    public function deleteProject()
    {
        return json(msg(-1, null, "暂不支持删除！"));
    }

    /**
     * 编辑文档的内容
     */
    public function getContent($id)
    {
        if (empty($id) or $id <= 0) {
            // abort(404);
        }
        $doc = DocumentModel::find($id);
        if (empty($doc)) {
            return $this->jsonResult(40301);
        }
        //权限的判断处理  todo
//        $role = Project::hasProjectShow($doc->project_id, $this->member_id);
//        if ($role == false) {
//            return $this->jsonResult(40305);
//        }
        $this->data['doc']['doc_id'] = $doc->doc_id;
        $this->data['doc']['name'] = $doc->doc_name;
        $this->data['doc']['project_id'] = $doc->project_id;
        $this->data['doc']['parent_id'] = $doc->parent_id;
        $this->data['doc']['content'] = $doc->doc_content;

        unset($this->data['member']);
        return $this->jsonResult(0, $this->data);
    }

    /**
     * 保存文档
     */
    public function save()
    {
        $project_id = input('post.project_id');
        if (Request::instance()->isPost()) {
            $document = null;
            $content = input('editormd-markdown-doc', null);
            //如果是保存文档内容
            if (empty($content) === false) {
                $doc_id = intval(input('doc_id'));
                if ($doc_id <= 0) {
                    return $this->jsonResult(40301);
                }
                $document = DocumentModel::find($doc_id);
                if (empty($document)) {
                    return $this->jsonResult(40301);
                }
                //如果没有编辑权限  todo
//                if(Project::hasProjectEdit($document->project_id,$this->member_id) == false){
//                    return $this->jsonResult(40305);
//                }
                //如果文档内容没有变更
                if (strcasecmp(md5($content), md5($document->doc_content)) === 0) {
                    return $this->jsonResult(0, ['doc_id' => $doc_id, 'parent_id' => $document->parent_id, 'name' => $document->doc_name]);
                }
                $document->doc_content = $content;
                //      $document->modify_at = $this->member_id;   todo
            } else {
                //如果是新建文档
                //权限的操作  todo
//                if (Project::hasProjectEdit($project_id, $this->member_id) == false) {
//                    return $this->jsonResult(40305);
//                }
                $doc_id = intval(input('id', 0));
                $parentId = intval(input('parentId', 0));
                $name = trim(input('documentName', ''));
                $sort = intval(input('sort'));

                //文档名称不能为空
                if (empty($name)) {
                    return $this->jsonResult(40303);
                }
                $mapWhere['project_id'] = array('eq', $project_id);

                //查看是否存在指定的文档
                if ($doc_id > 0) {
                    $mapWhere['doc_id'] = array('eq', $doc_id);
                    $document = DocumentModel::where($mapWhere)->find();
                    if (empty($document)) {
                        return $this->jsonResult(40301);
                    }
                }
                //判断父文档是否存在
                if ($parentId > 0) {
                    $mapWhere['doc_id'] = array('eq', $parentId);
                    $parentDocument = DocumentModel::where($mapWhere)->find();
                    if (empty($parentDocument)) {
                        return $this->jsonResult(40301);
                    }
                }
                if ($doc_id > 0) {
                    //查看是否有重名文档
                    $mapWhere['doc_name'] = array('eq', $name);
                    $mapWhere['doc_id'] = array('NEQ', $doc_id);
                    $doc = DocumentModel::where($mapWhere)->field('doc_id')->find();
                    if (empty($doc) === false) {
                        return $this->jsonResult(40304);
                    }
                } else {
                    //查看是否有重名文档
                    $mapWhere['doc_name'] = array('eq', $name);
                    $doc = DocumentModel::where($mapWhere)->field('doc_id')->find();
                    if (empty($doc) === false) {
                        return $this->jsonResult(40304);
                    }
                }

                if (empty($document) === false and $document->parent_id == $parentId and strcasecmp($document->doc_name, $name) === 0 and $sort <= 0) {
                    return $this->jsonResult(0, ['doc_id' => $doc_id, 'parent_id' => $parentId, 'name' => $name]);
                }

                $document = $document ?: new DocumentModel();

                $document->project_id = $project_id;
                $document->doc_name = $name;
                $document->parent_id = $parentId;

                if ($doc_id <= 0) {
                    //创建者 todo
                    // $document->create_at = $this->member_id;
                    $sort = DocumentModel::where('parent_id', '=', $parentId)->order('doc_sort', 'DESC')->field("doc_sort")->find();

                    $sort = ($sort ? $sort['doc_sort'] : -1) + 1;

                } else {
                    //创建者 todo
                    // $document->modify_at = $this->member_id;
                }

                if ($sort > 0) {
                    $document->doc_sort = $sort;
                }
            }

            if ($document->save() === false) {
                return $this->jsonResult(500, null, '保存失败');
            }
            $data = ['doc_id' => $document->doc_id . '', 'parent_id' => ($document->parent_id == 0 ? '#' : $document->parent_id . ''), 'name' => $document->doc_name];

            return $this->jsonResult(0, $data);
        }

        return $this->jsonResult(405);
    }

    /**
     * 删除文档
     */
    public function delete($id)
    {
        $doc_id = intval($id);
        if ($doc_id <= 0) {
            return $this->jsonResult(40301);
        }

        $doc = DocumentModel::find($doc_id);
        //如果文档不存在
        if (empty($doc)) {
            return $this->jsonResult(40301);
        }
        //判断是否有编辑权限   todo
//        if(Project::hasProjectEdit($doc->project_id,$this->member_id) == false){
//            return $this->jsonResult(40305);
//        }
        $result = DocumentModel::deleteDocument($doc_id);

        if ($result) {
            return $this->jsonResult(0);
        } else {
            return $this->jsonResult(500);
        }
    }

    /**
     * 排序
     */
    public function sort($id)
    {
        //文档的权限  todo
//        if(Project::hasProjectEdit($id,$this->member_id) == false){
//            return $this->jsonResult(40305);
//        }

        $params = Request::instance()->getContent();
        if (empty($params) === false) {
            $params = json_decode($params, true);
            if (empty($params) === false) {
                foreach ($params as $item) {
                    //modify_at  需要去修改   todo
                    $data = ['parent_id' => $item['parent'], 'doc_sort' => $item['sort'], 'modify_at' => 1];
                    DocumentModel::where('project_id', '=', $id)->where('doc_id', '=', $item['id'])->update($data);
                }
            }
        }
        return $this->jsonResult(0);
    }

    /**
     * 查看历史记录
     */
    public function history()
    {

    }

    /**
     * 删除历史记录
     */
    public function deletehistory()
    {

    }


    /**
     * 文档的上传处理
     */
    public function upload()
    {
        $allowExt = ["jpg", "jpeg", "gif", "png"];

        //如果上传的是图片
        if (isset($_FILES['editormd-image-file'])) {
            $file = Request::instance()->file('editormd-image-file');
            $allowExt = explode('|', 'jpg|jpeg|gif|png');
        } elseif (isset($_FILES['editormd-file-file'])) {
            $file = Request::instance()->file('editormd-file-file');
            $allowExt = explode('|', 'txt|doc|docx|xls|xlsx|ppt|pptx|pdf|7z|rar');
        }

        $dirPath = ROOT_PATH . '/public/upload/' . date('Ymd');
        //如果目标目录不能创建
        if (!is_dir($dirPath) && !mkdir($dirPath)) {
            $data['success'] = 0;
            $data['message'] = '上传目录没有创建文件夹权限';
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        //如果目标目录没有写入权限
        if (is_dir($dirPath) && !is_writable($dirPath)) {
            $data['success'] = 0;
            $data['message'] = '上传目录没有写入权限';
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        //校验文件
        if (isset($file) && $file->isValid()) {
            //
            $ext = pathinfo($file->getInfo()['name'], PATHINFO_EXTENSION); //上传文件的后缀
            //判断是否是图片
            if (empty($ext) or in_array(strtolower($ext), $allowExt) === false) {
                $data['success'] = 0;
                $data['message'] = '不允许的文件类型';
                return json_encode($data, JSON_UNESCAPED_UNICODE);
            }
            //生成文件名
            $fileName = uniqid() . '_' . dechex(microtime(true)) . '.' . $ext;
            try {
                $path = $file->move(ROOT_PATH . '/public/upload/' . date('Ymd'), $fileName);
                $webPath = '/upload/' . date('Ymd') . '/' . $fileName;
                $data['success'] = 1;
                $data['message'] = 'ok';
                $data['alt'] = pathinfo($file->getInfo()['name'], PATHINFO_FILENAME);
                $data['url'] = url($webPath, "", "", true);
                if (isset($_FILES['editormd-file-file'])) {
                    //  $data['icon'] = resolve_attachicons($ext);
                }
                return json_encode($data, JSON_UNESCAPED_UNICODE);
            } catch (Exception $ex) {
                $data['success'] = 0;
                $data['message'] = $ex->getMessage();
                return json_encode($data, JSON_UNESCAPED_UNICODE);
            }
        }
        $data['success'] = 0;
        $data['message'] = '文件校验失败';
        return $this->response->json($data);
    }


    protected function jsonResult($errcode, $data = null, $message = null)
    {
        $message = empty($message) ? "错误码" . $errcode : $message;
        $content = ['errcode' => $errcode, 'message' => $message];
        if (empty($data) === false) {
            $content['data'] = $data;
        }

//        $this->response = $this->response->json($content)
//            ->header('Pragma','no-cache')
//            ->header('Cache-Control','no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        return $content;
    }


    // 上传文档缩略图
    public function uploadImg()
    {
        if (request()->isAjax()) {
            $base64_body = substr(strstr(input('imgData'), ','), 1);
            $data = base64_decode($base64_body);
            $path = ROOT_PATH . 'public' . DS . 'upload' . DS . 'docimg';
            if (!is_dir($path)) {
                $flag = mkdir($path, 0777, true);
            }
            $expath = date('Ymd') . time() . ".jpg";
            $path = $path . DS . $expath;
            file_put_contents($path, $data);
            return '/upload/docimg/' . $expath;
        }
    }

    public function docTree()
    {
        //结构树的处理逻辑
        if (!authCheck("document/docTree")) {
            return;
        }
        $param = input('param.');
        $model = new DocumentModel();
        // 获取现在的权限
        if ('get' == $param['type']) {
            $treeData = $model->getTree($param['id']);
            return json(msg(1, $treeData, 'success'));
        }
        if ('give' == $param['type']) {
            $checkID = $param['checkId'];
            $model->save(["is_price" => "0"], ["project_id" => $param['id']]);
            if ($checkID != "") {
                $checkIDArr = explode(",", $checkID);
                $list = array();
                foreach ($checkIDArr as $val) {
                    $list[] = ["project_id" => $param['id'], "doc_id" => $val, "is_price" => 1];
                }
                if (count($list) > 0) {
                    $model->isUpdate()->saveAll($list, true);
                }
            }
            return json(msg(1, "", '文档收费状态更新'));
        }
    }


    /**
     * 文档操作拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id, $showid)
    {
        return [
            '编辑' => [
                'auth' => 'document/editProject',
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
            '查看' => [
                'auth' => 'no',
                'href' => $showid ? "/docs/show/" . $showid : "javascript:",
                'btnStyle' => $showid ? "primary" : "primary disabled",
                'icon' => 'fa fa-eye'
            ],
            '删除' => [
                'auth' => 'document/deleteProject',
                'href' => "javascript:proDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]

        ];
    }
}