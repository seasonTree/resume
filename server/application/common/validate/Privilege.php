<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19
 * Time: 12:00
 */
namespace app\common\validate;
use think\Validate;

class Privilege extends Validate{
    protected $rule=[
        'parent_id'=>'require',
        'pri_name'=>'require',
    ];
    protected $message=[
        'parent_id'=>'上级权限不能为空',
        'pri_name'=>'权限名称不能为空',
    ];
}