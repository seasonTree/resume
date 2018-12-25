<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Env;
use app\index\model\Resume;
use app\index\model\Communicate;
use app\index\model\User;

use PHPExcel_IOFactory;
use PHPExcel;

class Report extends Controller
{	

    public function candidateList(){
    	//候选人报表
    	$input = input('get.');
    	$data = $this->getCandidate($input);
    	return json([ 'msg' => '获取成功','code' => 0,'data' => $data ]);
    }

    public function getCandidate($parm = ''){
    	//获取数据
    	$resume = new Resume();
    	$candidate = $resume->getCandidate();
    	$comm = new Communicate();
    	$data = []; //最终数据
    	$user_arr = [];//临时数组,记录是否有重复数据用于叠加
    	$key = 1;//data数组的key
    	$user = new User();
    	foreach ($candidate as $k => $v) {
    		$where = "communicate_time between '$parm[dtfm]' and '$parm[dtto]' and resume_id = $v[id]";
    		if ($parm['ur'] != '') {
    			$in_user = implode("','",explode(',',$parm['ur']));
    			$where.= " and a.ct_user in('$in_user')";
    		}
    		// if (isset($parm['ur'])) {
    		// 	$in_user = implode("','",explode(',',$parm['ur']));
    		// 	$where.= " and a.ct_user in('$in_user')";
    		// }
    		$communicate = $comm->getComm($where)->toArray();
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
    		if (!array_key_exists($v['ct_user'], $user_arr)) {
    			//没有任何沟通情况，沟通为0，直接跳过
    			$candidate[$k]['communicate_count'] = 0;
    			$personal_name = $user->getOne(['uname' => $v['ct_user']]);
    			$candidate[$k]['personal_name'] = $personal_name['personal_name'];
    			$candidate[$k]['id'] = $key;
    			$data[$key] = $candidate[$k];
    			$key++;
    		}
    		$user_arr = [];//清空临时数组
    	}
    	return array_merge($data);
    }

    public function recruitmentList(){
    	//针对招聘负责人的列表
    	$input = input('get.');

    	$data = $this->getRecruitment($input);

    	return json([ 'msg' => '获取成功','code' => 0,'data' => $data ]);
    }

    public function getRecruitment($parm = ''){
    	//获取数据主方法
    	$resume = new Resume();
    	$candidate = $resume->getCandidate();
    	$comm = new Communicate();
    	$data = []; //最终数据
    	$user_arr = [];//临时数组,记录是否有重复数据用于叠加
    	$key = 1;//data数组的key
    	$user = new User();
    	$user_data = $user->getUserInfo();
    	foreach ($user_data as $k => $v) {
    		if ($parm != '') {
    			$where = "communicate_time between '$parm[dtfm]' and '$parm[dtto]' and ct_user = '$v[uname]'";
    		}
    		else{
    			$where = "ct_user = '$v[uname]'";
    		}

    		$temp_data = $comm->getCommInfo($where);

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

    	return array_merge($data);
    }

    public function export(){
    	//数据导出
    	$input = input('get.');
    	
    	$obj = new \PHPExcel();
        $obj->setActiveSheetIndex(0);
        // dump($data);exit;
    	if ($input['type'] == 0) {
    		//宽度设置
    		$obj->getActiveSheet()->getColumnDimension('A')->setWidth(12);
			$obj->getActiveSheet()->getColumnDimension('B')->setWidth(12);
			$obj->getActiveSheet()->getColumnDimension('C')->setWidth(10);
			$obj->getActiveSheet()->getColumnDimension('D')->setWidth(10);
			$obj->getActiveSheet()->getColumnDimension('E')->setWidth(10);
			$obj->getActiveSheet()->getColumnDimension('F')->setWidth(10);
			$obj->getActiveSheet()->getColumnDimension('G')->setWidth(10);
			//表头
    		$obj->getActiveSheet()->setCellValue("A1", '招聘负责人');
			$obj->getActiveSheet()->setCellValue("B1", '候选人');
			$obj->getActiveSheet()->setCellValue("C1", '通过筛选');
			$obj->getActiveSheet()->setCellValue("D1", '安排面试');
			$obj->getActiveSheet()->setCellValue("E1", '到场');
			$obj->getActiveSheet()->setCellValue("F1", '通过面试');
			$obj->getActiveSheet()->setCellValue("G1", '入职');
			$data = $this->getRecruitment($input);
			//数据
    		foreach ($data as $k => $v) {
    			$k = $k+2;

    			$obj->getActiveSheet()->setCellValue("A".$k,$v['personal_name']);
				$obj->getActiveSheet()->setCellValue("B".$k, $v['name']);
				$obj->getActiveSheet()->setCellValue("C".$k, $v['screen'] == 1 ?'是':'否');
				$obj->getActiveSheet()->setCellValue("D".$k, $v['arrange_interview'] ==1 ?'是':'否');
				$obj->getActiveSheet()->setCellValue("E".$k, $v['arrive'] == 1 ?'是':'否');
				$obj->getActiveSheet()->setCellValue("F".$k, $v['approved_interview'] == 1 ?'是':'否');
				$obj->getActiveSheet()->setCellValue("G".$k, $v['entry'] == 1 ?'是':'否');
    		}
    		header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment;filename="招聘负责人统计.xls"');
	        ob_end_clean();//清除缓冲区,避免乱码
	        $objWriter = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
	        $objWriter->save('php://output');
	        $obj->disconnectWorksheets();
    		
    	}
    	if ($input['type'] == 1) {
    		//宽度设置
    		$obj->getActiveSheet()->getColumnDimension('A')->setWidth(12);
			$obj->getActiveSheet()->getColumnDimension('B')->setWidth(12);
			$obj->getActiveSheet()->getColumnDimension('C')->setWidth(15);
			$obj->getActiveSheet()->getColumnDimension('D')->setWidth(18);
			$obj->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$obj->getActiveSheet()->getColumnDimension('F')->setWidth(10);
			$obj->getActiveSheet()->getColumnDimension('G')->setWidth(15);
			$obj->getActiveSheet()->getColumnDimension('H')->setWidth(15);
			$obj->getActiveSheet()->getColumnDimension('I')->setWidth(12);
			$obj->getActiveSheet()->getColumnDimension('J')->setWidth(10);
			//表头
    		$obj->getActiveSheet()->setCellValue("A1", '招聘负责人');
			$obj->getActiveSheet()->setCellValue("B1", '候选人');
			$obj->getActiveSheet()->setCellValue("C1", '联系电话');
			$obj->getActiveSheet()->setCellValue("D1", '邮件');
			$obj->getActiveSheet()->setCellValue("E1", '毕业院校');
			$obj->getActiveSheet()->setCellValue("F1", '学历');
			$obj->getActiveSheet()->setCellValue("G1", '毕业年份');
			$obj->getActiveSheet()->setCellValue("H1", '工作年限');
			$obj->getActiveSheet()->setCellValue("I1", '简历来源');
			$obj->getActiveSheet()->setCellValue("J1", '沟通次数');
			$data = $this->getCandidate($input);
			//数据
			dump($data);
    		foreach ($data as $k => $v) {
    			$k = $k+2;
    			$obj->getActiveSheet()->setCellValue("A".$k, $v['personal_name']);
				$obj->getActiveSheet()->setCellValue("B".$k, $v['name']);
				$obj->getActiveSheet()->setCellValue("C".$k, $v['phone']);
				$obj->getActiveSheet()->setCellValue("D".$k, $v['email']);
				$obj->getActiveSheet()->setCellValue("E".$k, $v['school']);
				$obj->getActiveSheet()->setCellValue("F".$k, $v['educational']);
				$obj->getActiveSheet()->setCellValue("G".$k, $v['graduation_time']);
				$obj->getActiveSheet()->setCellValue("H".$k, $v['work_year']);
				$obj->getActiveSheet()->setCellValue("I".$k, $v['source']);
				$obj->getActiveSheet()->setCellValue("J".$k, $v['communicate_count']);
    		}

    		header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment;filename="候选人跟踪表.xls"');
	        ob_end_clean();//清除缓冲区,避免乱码
	        $objWriter = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
	        $objWriter->save('php://output');
	        $obj->disconnectWorksheets();
    		
    	}
    }
    
}
