<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use League\CommonMark\CommonMarkConverter;
// 应用公共文件

if(!function_exists('markdown_converter')) {
    /**
     * 解析 markdown 字符串
     * @param $text
     * @return string
     */
    function markdown_converter($text){

//        $environment = League\CommonMark\Environment::createCommonMarkEnvironment();
//        $environment->addExtension(new Webuni\CommonMark\TableExtension\TableExtension());
//        $environment->addExtension(new Webuni\CommonMark\AttributesExtension\AttributesExtension());
//
//        $environment->addBlockParser(new HttpMethodParser());
//        $environment->addInlineParser(new AutoLinkParser());
//
//        $environment->addBlockRenderer('League\CommonMark\Block\Element\Heading',new SmartWiki\Extentions\Markdown\Renderer\HeadingRenderer());
//        $environment->addBlockRenderer('League\CommonMark\Block\Element\Document',new SmartWiki\Extentions\Markdown\Renderer\TocRenderer());
//        $environment->addBlockRenderer('SmartWiki\Extentions\Markdown\Element\HttpMethodBlock', new HttpMethodRenderer());
//
//        $converter = new League\CommonMark\Converter(new League\CommonMark\DocParser($environment), new League\CommonMark\HtmlRenderer($environment));
//
//
//        $html = $converter->convertToHtml($text);
        $converter = new CommonMarkConverter();
        $html= $converter->convertToHtml($text);

        return $html;
    }
}

function jsonResult($errcode, $data = null,$message = null)
{

    $message = empty($message) ?  "错误码".$errcode: $message;
    $content = ['errcode' => $errcode, 'message' => $message];
    if(empty($data) === false){
        $content['data'] = $data;
    }

//        $this->response = $this->response->json($content)
//            ->header('Pragma','no-cache')
//            ->header('Cache-Control','no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    return $content;
}



/**
 * 文档无限级分类
 * @param $data  查询的数据
 * @param $project_id  文档的类别
 * @param int $parent_id
 * @param int $level
 * @return array
 */
function getDocumentTree($data,$project_id,$parent_id=0,$level=0){
    static $arr=array();
    foreach($data as $key=>$value){
        if($value['parent_id'] == $parent_id){
            $value['level']=$level;     //用来作为在模版进行层级的区分
            $arr[] = $value;            //把内容存进去
            getDocumentTree($data,$project_id,$value['doc_id'],$level+1);    //回调进行无线递归
        }
    }
    return $arr;
}

/**获取project的类别的数据
 * @param $data
 * @param $project_id
 * @param int $parent_id
 * @param int $level
 * @return array
 */
function getProjectTree($data,$parent_id=0,$level=0){
    static $arr=array();
    foreach($data as $key=>$value){
        if($value['parent_id'] == $parent_id){
            $value['level']=$level;     //用来作为在模版进行层级的区分
            $arr[] = $value;            //把内容存进去
            getProjectTree($data,$value['id'],$level+1);    //回调进行无线递归
        }
    }
    return $arr;
}