<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 常用设置
// +----------------------------------------------------------------------
$basePath=json_decode(file_get_contents(dirname(Env::get('ROOT_PATH')).'/config.json'),true);
return [
    'mail' => [
        'host' => 'md-hk-3.webhostbox.net',
        'smtpauth' => TRUE,
        'username' => 'smtp@kooa.ai',
        'from' => 'smtp@kooa.ai',
        'fromname' => 'sales.kooa.ai',
        'password' => 'i&mVKH6FT2pc',//邮箱授权码
        'charset' => 'utf-8',
        'ishtml' => TRUE,
        'port'=>465,
        'smtpsecure'=>'ssl',
        'smtpdebug'=>4,
    ],
    'sendMessage'=>[
        'accessKeyId'=>'LTAI3NfY6JVzdFI7',
        'accessKeySecret'=>'uyAiiBCMMBAyDmkjo59TZOXhrhHNHP',
        'signName'=>'魔方科技',
        'templateCode'=>'SMS_137865176',
    ],
    'level' => 1,
    //推广会员层级
    'api_token' => 'GbwS8JFxJfW3uj86S',

    'redis'=>[
        'host'=>'127.0.0.1',
        'port'=>'6379',
        'author'=>'',
        'db_index'=>0,
    ],
    'api_key' => $basePath['api_key']
    
    
];

