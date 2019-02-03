<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$
return [
    // 模板参数替换
    'view_replace_str'       => array(
        '__CSS__'    => '/static/index/css',
        '__JS__'     => '/static/index/js',
        '__IMG__' => '/static/index/images',
        '__DOC__'=>'/static/document'
    ),
    //是否强制使用路由
    'url_route_must'=>false,
    'alipay' =>[
        'app_id' => "2018060360259556",
        //商户私钥
        'merchant_private_key' => "MIIEpAIBAAKCAQEAtvlB1jkzmMTjS748xbZ7ZHIcbbSoVbv9eZGra5Vy7q4XM+vJHUBDC0N6RW454mUBB/OrnkeJvB+rYNTpV69ZzNOX8P3ChrPaFuxkOgGBU8c4DVszctAuHafHkpo1jh+zBIteGfATAVMSOlzOcPZ8gUGnqPG9T9cBwud8PMbGkrNxLwpnFRi0rG8B6Sf80wbhxoajB5OYVN3MiRGOjgvdgsGTwWTKPuSg+rKy7ItQxS1oIQO9UEGFWzeMQUWun6sdKF0oucZ412LBYVmHVOYYc856u0vCXXJbnvPS0Up/U6rnRGxKqpjt8rwRF5AHrSviON3ZUkuwxEETB4m8G1PpCwIDAQABAoIBACG9gvNy5t3T6KoN8Xzv8n8KP83HE4eDu/EP+JFFJMV4mtS7NQHM2NAZ6FEnS0tBHtiNiWcNgAuNt3eKq2C8+A06M3mAAb//KEcz3iOIJYYCZYao+6q/UtGrH7Ub9KY5mDRNUtPrnkWPQPw8IuFXwou/RKB1u1geqLd9Ij6+1N02I80HPlHFBhW78dzKc5Jx5NFv4fTBMF/h2l22jmjoYjpGimqNvk0Py1MemxhZwWNQncV0hH6SaEBOaSctbxMFSKOcNH/E+dzntk9w92mZsbJLh8iUetz9XTSN1TAVqv83aP9yC66jnzjD+kKP8XQeSrRZlOSqTVnAl/G8DIETDukCgYEA42fj91HZXyaJI/PyE+Ed/PV47jajmhIdGm0LzmrOjmlrQfsE1YVM7RiVAl+BEAKJTgRiCAjt1R9ZPc48FJUO0ktPVDQnj79MJzeO5iwwyFfwPFAwd4V6AXxhgOEAcb13gYfjXNOqODYEl3W/2+xFr+Q7W0FrUV9G6Or/7/eqem0CgYEAzfscnxCPInSMVXVXdOJJrLddU3G8mY3M3vFGt5Hc7BMHi5pv89F20WoipFmseugtfrWyKyllomhBKLsMWafjif3vDGzY8HfoxzeDLnCEb9IKIkjebGNUhDTvyUQNEI4JXX/NLcxwtvUlDkOFpdTb+AVGrc7NOPztFGRD2FwExlcCgYEAy2wG6BlGqrAtI4U3+oJ5MexgNi+YX91uPVJoqkiu54Pz13z10Q9CRiGkQhAuwqSSMMEI9IkQP4KgcQJIilzgeku19ewFq7gDJl2zrcDADqdjloBhKrmy7xtVmVUs8ZH8Eln9j+8kKM5hgx9o9hFAMVynoDVAC/V/2CFHu2DAFm0CgYAsMxlGLLh6LUY7xE07AJ/MlyBImpxc2ue+Y0nIdF1SrxasZzxktmrnrv22BKQVT6MLkJOl3WE4w2RQCCszRep2nu3f3a+Dkkd6EDhvC6fvksOn7bkEyx/EqtNVfevLxJ7P2G+/xyHcsKf98pFIL2/wPbmX7EtvUh7RhF5mnABOSwKBgQCnuEvaeka27/rpcsI5RgMc+nz8T9h8zeh7rMEtE/xbFj5RVi6xSggcxL9FL/twcFA6AP0w2ZsnKygtemfMmeJ+qmB7wwCetKDo5IouWLmFfJgXOJGgqan8SA+R1uT34P2Hw8vF0Ttq1CPsIFJ0IVX9n5skJAXBSeF5ZFfUqzk2ng==",
        //编码格式
        'charset' => "UTF-8",
        //签名方式
        'sign_type'=>"RSA2",
        //支付宝网关
        'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
        //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
        'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAy8eFh0+KlDtfG7o70sydqITRHuSnlSJtw2/gdcN43ayA4BepJKBOsQYkOEfN18VlzRbwvywnRHRJ9CT0mlXr9oqxGk4r5LJpiZzFdnXZHPzR5/6mKW/8fXCGi84ebLReFxQDesZ5c+gRYuM/c3abX2HQowFMnktwfAKhoXhEN439H+XkLC4TIoqO4BOFAsAPfgKw50+6BVwtRLdYU430K+fWLVibCMA0Z+VUaOaVhwrX4XAYcjI3Gl6WPxVhKXJ72iQoOqAm7u9QAX1VAzPn5mcPyKWXs9k/YxOD8U0ABusiHgX/ioJnPRg5gd6PJKnGgodkwAX9N1stCc1oCo3HmQIDAQAB",
    ]
];
