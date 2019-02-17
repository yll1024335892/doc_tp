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
use mailer\PHPMailer;
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
            $value['level']=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;",$level);     //用来作为在模版进行层级的区分
            $arr[] = $value;            //把内容存进去
            getProjectTree($data,$value['id'],$level+1);    //回调进行无线递归
        }
    }
    return $arr;
}

/**
 * function sendEmail
 * @param $desc_content 是发送的内容
 * @param $toemail 收件人的邮箱
 * @param $desc_url 内容后面的发送的链接信息
 * return 1|失败的信息
 */
if(!function_exists("sendEmail")){
    function sendEmail($desc_content, $toemail,  $desc_url){
        $mail = new PHPMailer();
        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = "smtp.163.com";// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = "yll1024335892@163.com";// 发送方的163邮箱用户名，就是你申请163的SMTP服务使用的163邮箱
        $mail->Password = "LIANGliang123456";// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！
        $mail->SMTPSecure = "ssl";// 使用ssl协议方式
        $mail->Port = 587;// 163邮箱的ssl协议方式端口号是465/994
        $mail->setFrom("yll1024335892@163.com","Mailer");// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
        $mail->addAddress($toemail,'小Q资源网博客回复消息');// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
        $mail->addReplyTo("","Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        //$mail->addCC("xxx@163.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
        //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)
        //$mail->addAttachment("bug0.jpg");// 添加附件
        $mail->Subject = "阿翼文档";// 邮件标题
        $mail->Body = "以下是阿翼文档回复你的内容:".$desc_content."点击可以查看文章地址:".$desc_url;// 邮件正文
        //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用
        if(!$mail->send()){// 发送邮件
            return $mail->ErrorInfo;
        }else{
            return 1;
        }
    }
}

