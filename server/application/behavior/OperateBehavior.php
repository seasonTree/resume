<?php

namespace app\behavior;

use \think\Controller;
use think\Exception;
use think\facade\Cookie;
use think\facade\Log;
use think\facade\Request;
use think\facade\Session;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/16
 * Time: 14:54
 */
class OperateBehavior extends Controller
{
    /**
     * 定义需要排除权限的路由
     */
    protected $exclude = [
        '/',
        '/api/user/logout',
        '/api/user/get_user_info',
        '/api/user/get_user_permission',
        '/api/user/change_password',
        '/api/user/update_avatar',
        '/api/dashboard/get',
        '/test'

    ];

    /**
     *定义未登录需要排除权限的路由
     */
    protected $login = [
        '/',
        '/index/index',
        '/api/user/login',

        
    ];


    /**
     * 权限验证
     */
    public function run()
    {
//        if(!in_array($_SERVER['SCRIPT_URL'],$this->exclude)){
//            halt($_SERVER);
//        }

        // //获取当前访问路由
        $url = getActionUrl();
        $userid = Session::get('user_info')['id'] ? Session::get('user_info')['id'] : 0;

        // if (in_array($url, $this->login)) {
        //     if (checkLogin() && $url != 'user/sendmessage') {
        //         $this->redirect('user/userInfo');
        //     }
        // }\
        if (!$userid && !in_array($url, $this->login)) {
            // if (checkLogin()) {
            //     $this->redirect($url);
            // }
            // $this->error('请先登陆', '/', '', '1');
            return $this->redirect('/');

        }

        
        if ($userid != 0) {
            //用户所拥有的权限路由(登录后)
            $auth = Session::get('auth') ? Session::get('auth') : [];
            $auth = array_merge($this->exclude,$auth);
            // dump($auth);exit;
        }
        else{
            $auth = $this->login;
            if (!in_array($url, $auth)) {
                // $this->error('请先登陆', 'index/index');
                echo json_encode(['code' => 403,'msg' => '请先登录']);exit;
            }
        }
        if ($userid != 1) {
            //超级管理员跳权限

            if (!in_array($url, $auth)) {
                // $this->error('无权限访问');
                echo '您没有权限访问';exit;
            }

        }
        // $page = Session::get('page') ? Session::get('page') : [];
        // $page = array_merge($this->untoken,$page);
        
        // if (!in_array($url, $page)) {
        //     if ($userid != 1) {
        //         //超级管理员略过
        //         $api_token = isset($_SERVER['HTTP_TOKEN']) ? $_SERVER['HTTP_TOKEN'] : '';
        //         if (!$api_token) {
        //             $this->error('token不存在');
        //         }
        //         date_default_timezone_set('UTC');
        //         $token = getSignature(date('YmdH',time()),base64_decode(config('config.api_key')));
        //         if ($api_token != $token) {
        //             $this->error('token无效');
        //         }
        //     }
            
        // }
        

    }

}