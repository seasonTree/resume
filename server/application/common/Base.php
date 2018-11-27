<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27
 * Time: 19:14
 */
namespace app\common;
use think\Controller;
use app\index\model\ShortUrl as ShortUrlModel;

class Base extends Controller {
    public function lang() {
        switch ($_GET['lang']) {
            case 'cn':
                cookie('think_var', 'zh-cn',365*24*60*60);
                break;
            case 'en':
                cookie('think_var', 'en-us',365*24*60*60);
                break;
            case 'hk':
                cookie('think_var', 'zh-hk',365*24*60*60);
                break;
            //其它语言
        }
    }
}