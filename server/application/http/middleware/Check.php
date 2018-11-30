<?php

namespace app\http\middleware;


class Check
{
    public function handle($request, \Closure $next)
    {
        if (!$this->isLogin()) {

            return redirect('/');
        }
        if(!$this->isHasCheckPri()){
            return redirect('/user/userInfo');
        }
        return $next($request);

    }
    public function isLogin(){
        $user =session('user_info');
        if ($user && $user['id']){
            return true;
        }
        return false;
    }
    public function isHasCheckPri(){
        $user =session('user_info');

        if ($user && $user['id']){
            if($user['id'] === 1){
                return true;
            }
          $res = model('User')->chkPri( $user['id']);
            if($res['has']){
                return true;
            }else{
//                session('user',null);
                return false;
            }
        }
    }
}
