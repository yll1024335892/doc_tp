<?php
/**
 * Created by 深圳市阿翼互联网有限公司.
 * User: yinliangliang
 * Date: 2019/1/14
 * Time: 10:18
 * file: Document.php
 * email:yll1024335892@163.com
 */

namespace app\index\controller;


use app\model\DocumentModel;
use app\model\ProjectModel;
use think\Controller;
use think\Request;

class Document  extends Controller
{
    public function show($id){
        $doc_id = intval($id);
        if($doc_id <= 0){
            $this->error("非法的数据格式！","/");
        }
        $doc = DocumentModel::getDocumentFromCache($doc_id);

        if(empty($doc) ){
            $this->error("文档不存在！","/");
        }
        $project = ProjectModel::getProjectFromCache($doc->project_id);
        if(empty($project)){
            $this->error("文档不存在！","/");
        }
    //权限处需要单独的处理 todo
//        $permissions = Project::hasProjectShow($project->project_id,$this->member_id);
//
//        //校验是否有权限访问文档
//        if($permissions === 0){
//            abort(404);
//        }elseif($permissions === 2){
//            $role = session_project_role($project->project_id);
//            if(empty($role)){
//                $this->data = $project;
//                return view('home.password',$this->data);
//            }
//        }else if($permissions === 3 && empty($member_id)){
//            return redirect(route("account.login"));
//        }elseif($permissions === 3) {
//            abort(403);
//        }

        $this->data['project'] = ProjectModel::getProjectFromCache($doc->project_id);

        $this->data['tree'] = ProjectModel::getProjectHtmlTree($doc->project_id,$doc->doc_id);
        $this->data['title'] = $doc->doc_name;

        if(empty($doc->doc_content) === false){
            $this->data['body'] = DocumentModel::getDocumnetHtmlFromCache($doc_id);
        }else{
            $this->data['body'] = '';
        }
        if(Request::instance()->isAjax()){
           // unset($this->data['member']);
            unset($this->data['project']);
            unset($this->data['tree']);
            $this->data['doc_title'] = $doc->doc_name;


            return jsonResult(0,$this->data);
        }

        return $this->fetch("",$this->data);
    }
}