<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19
 * Time: 12:00
 */
namespace app\common\validate;
use think\Validate;

class User extends Validate{
    protected $rule=[
        'username'=>'require',
        'password'=>'require|confirm',

    ];
    protected $message=[
        'username'=>'用户名不能为空',
        'password' =>'密码不一致'
    ];
}