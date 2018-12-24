<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Env;
use app\index\model\Resume;
use app\index\model\Communicate;
use app\index\model\User;

class Report extends Controller
{	

    public function candidateList(){
    	//候选人报表
    	$input = input('get.');

    	$resume = new Resume();
    	$candidate = $resume->getCandidate();
    	$comm = new Communicate();
    	$data = []; //最终数据
    	$user_arr = [];//临时数组,记录是否有重复数据用于叠加
    	$key = 1;//data数组的key
    	$user = new User();
    	foreach ($candidate as $k => $v) {
    		$communicate = $comm->getComm(['resume_id' => $v['id']])->toArray();
    		if (empty($communicate)) {
    			//没有任何沟通情况，沟通为0，直接跳过
    			$candidate[$k]['communicate_count'] = 0;
    			$personal_name = $user->getOne(['uname' => $v['ct_user']]);
    			$candidate[$k]['personal_name'] = $personal_name['personal_name'];
    			$candidate[$k]['id'] = $key;
    			$data[$key] = $candidate[$k];
    			$key++;
    			continue;
    		}
    		foreach ($communicate as $a => $b) {
    			//查询到沟通情况
    			$candidate[$k]['personal_name'] = '';
    			$candidate[$k]['ct_user'] = '';
    			if ($b['personal_name'] != '') {
    				$candidate[$k]['personal_name'] = $b['personal_name'];
    			}
    			$candidate[$k]['ct_user'] = $b['ct_user'];

    			if (array_key_exists($b['ct_user'], $user_arr)) {
    				//如果已经存在数据，叠加
    				$data[$user_arr[$b['ct_user']]]['communicate_count'] = $data[$user_arr[$b['ct_user']]]['communicate_count'] + 1;  
    			}
    			else{
    				//没有存在数据不叠加
    				$candidate[$k]['communicate_count'] = 1;
    				$candidate[$k]['id'] = $key;
    				$data[$key] = $candidate[$k];
    				$user_arr[$b['ct_user']] = $key;
    				$key++;

    			}
    		}
    		$user_arr = [];//清空临时数组
    	}
    	return json([ 'msg' => '获取成功','code' => 0,'data' => array_merge($data) ]);
    }

    public function recruitmentList(){
    	//针对招聘负责人的列表
    	$resume = new Resume();
    	$candidate = $resume->getCandidate();
    	$comm = new Communicate();
    	$data = []; //最终数据
    	$user_arr = [];//临时数组,记录是否有重复数据用于叠加
    	$key = 1;//data数组的key
    	$user = new User();
    	$user_data = $user->getUserInfo();

    	foreach ($user_data as $k => $v) {
    		$temp_data = $comm->getCommInfo(['ct_user' => $v['uname']]);
    		if (empty($temp_data)) {
    			continue;
    		}
    		foreach ($temp_data as $a => $b) {
    			if (array_key_exists($b['resume_id'],$user_arr)) {
    				foreach ($b as $n => $m) {
    					if ($m == 0 || $n == 'resume_id') {
    						continue;
    					}
    					
    					$data[$user_arr[$b['resume_id']]][$n] = 1;  
    				}
    			}
    			else{
    				$b['id'] = $key;
    				$data[$key] = $b;
    				$data[$key]['personal_name'] = $v['personal_name'];
    				$data[$key]['name'] = $resume->getUname(['id' => $b['resume_id']]);
	    			$user_arr[$b['resume_id']] = $key;
	    			$key++;
    			}
    			
    		}
    		$user_arr = [];
    	}
    	return json([ 'msg' => '获取成功','code' => 0,'data' => array_merge($data) ]);
    }

    public function export(){
    	//数据导出

    	if ($type == 1) {
    		
    	}
    	if ($type == 2) {
    		
    	}
    }
    
}
