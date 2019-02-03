<?php
function alipay($post,$notify_url,$return_url){
    $config = Config('alipay');
    $arr=[
        'app_id'=>$config['app_id'],
        'merchant_private_key'=>$config['merchant_private_key'],
        'notify_url'=>$notify_url,
        'return_url'=>$return_url,
        'charset'=>$config['charset'],
        'sign_type'=>$config['sign_type'],
        'gatewayUrl'=>$config['gatewayUrl'],
        'alipay_public_key'=>$config['alipay_public_key']
    ];
    vendor('alipay.AlipayTradeService');
    vendor('alipay.AlipayTradePagePayContentBuilder');
    $out_trade_no = trim($post['WIDout_trade_no']);
    $subject = trim($post['WIDsubject']);
    $total_amount = trim($post['WIDtotal_amount']);
    $body = trim($post['WIDbody']);
    $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
    $payRequestBuilder->setBody($body);
    $payRequestBuilder->setSubject($subject);
    $payRequestBuilder->setTotalAmount($total_amount);
    $payRequestBuilder->setOutTradeNo($out_trade_no);
    $aop = new \AlipayTradeService($arr);
    $response = $aop->pagePay($payRequestBuilder, $arr['return_url'], $arr['notify_url']);
    var_dump($response);
}

?>