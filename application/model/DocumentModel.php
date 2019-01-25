<?php
/**
 * Created by 深圳市阿翼互联网有限公司.
 * User: yinliangliang
 * Date: 2019/1/13
 * Time: 14:26
 * file: DocumentModel.php
 * email:yll1024335892@163.com
 */

namespace app\model;


use think\Cache;
use think\Model;

class DocumentModel extends Model
{
    protected $name = 'document';
    protected $pk="doc_id";
    protected $createTime = 'create_time';
    protected $updateTime = 'modify_time';

    public static function deleteDocument($doc_id)
    {
        $documents = [];
        $doc = DocumentModel::find($doc_id);
        if (empty($doc) === false) {
            $documents[] = $doc;
            $recursion = function ($id, $callback) use (&$documents) {
                $docs = DocumentModel::where('parent_id', '=', $id)->select();

                foreach ($docs as $doc) {
                    $documents[] = $doc;
                    $callback($doc->doc_id, $callback);
                }

            };
            $recursion($doc->doc_id, $recursion);
        }
        foreach ($documents as $document){
            $document->delete();
        }
        return true;
    }

    public static function getDocumentFromCache($doc_id,$update = false)
    {
        $key = 'document.doc_id.'.$doc_id;
        $document = $update or Cache::get($key);
        if(empty($document) or $update){
            $document = DocumentModel::find($doc_id);
            Cache::set($key,$document,3600);
        }
        return $document;
    }


    /**
     * 从换成中获取解析后的文档内容
     * @param int $doc_id
     * @param bool $update
     * @return bool|string
     */
    public static function getDocumnetHtmlFromCache($doc_id,$update = false)
    {
        $key = 'document.html.' . $doc_id;

        $html = null;//$update or Cache::get($key);

        if(empty($html)) {
            $document = self::getDocumentFromCache($doc_id, $update);

            if (empty($document)) {
                return false;
            }
            if(empty($document->doc_content)){
                return '';
            }
//            $parsedown = new \Parsedown();
//
//            $html  = $parsedown->text($document->doc_content);

            $html = markdown_converter($document->doc_content);

            $html = str_replace('class="language-','class="',$html);
            Cache::set($key,$html,3600);
        }
        return $html;
    }
}