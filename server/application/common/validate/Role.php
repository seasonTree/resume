<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19
 * Time: 12:00
 */
namespace app\common\validate;
use think\Validate;

class Role extends Validate{
    protected $rule=[
        'role_name'=>'require',
    ];
    protected $message=[
        'role_name'=>'角色名称不能为空',
    ];
}