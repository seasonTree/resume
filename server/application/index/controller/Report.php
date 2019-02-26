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

    // public function getCandidate($parm = ''){
    //   //获取数据
    //   $resume = new Resume();
    //   $comm = new Communicate();
    //   $user = new User();

    //   if (isset($parm['ur'])) {
    //   $in_user = explode(',',$parm['ur']);
    //   $where_in = implode("','",$in_user);
    //   }

    //   $where = "communicate_time between '$parm[dtfm]' and '$parm[dtto]'";
    //   $communicate = $comm->getComm($where);//获取该时间段所有沟通信息
    //   $resume_ids = array_unique(array_column($communicate,'resume_id'));//需要获取简历的id集合
    //   $where_resume_in = implode("','",$resume_ids);
    //   $resumes = $resume->where("id in('$where_resume_in')")->getCandidate();
    //   foreach ($resumes as $k => $v) {
          
    //   }
      






    //   $candidate = $resume->getCandidate();
      
    //   foreach ($candidate as $k => $v) {

    //     $where = "communicate_time between '$parm[dtfm]' and '$parm[dtto]' and resume_id = $v[id]";
    //     if (isset($in_user)) {
    //       $where.= " and a.ct_user in('$where_in')";
    //     }

    //     $communicate = $comm->getComm($where);

    //     if (empty($communicate)) {
    //       if (isset($in_user)) {
    //         unset($candidate[$k]);
    //         continue;
    //       }
    //       if (strtotime($parm['dtfm']) < strtotime($v['ct_time']) && strtotime($parm['dtto']) > strtotime($v['ct_time'])) {
    //         $candidate[$k]['personal_name'] = $user->getUserName(['uname' => 'ct_user']);
    //         $candidate[$k]['communicate_count'] = 0;
    //       }
    //       else{
    //         unset($candidate[$k]);
    //         continue;
    //       }
    //     }
    //     else{

    //       $candidate[$k]['personal_name'] = $communicate[0]['personal_name'];
    //       $candidate[$k]['ct_time'] = $communicate[0]['communicate_time'];
    //       $candidate[$k]['communicate_count'] = count($communicate);

    //     }
        

    //   }
    //   return array_merge($candidate);
    // }

    public function getCandidate($parm = ''){
    	//获取数据
    	$resume = new Resume();
    	$comm = new Communicate();
    	$user = new User();

    	if (isset($parm['ur'])) {
			$in_user = explode(',',$parm['ur']);
			$where_in = implode("','",$in_user);
    	}

    	$candidate = $resume->getCandidate();
    	foreach ($candidate as $k => $v) {

    		$where = "communicate_time between '$parm[dtfm]' and '$parm[dtto]' and resume_id = $v[id]";
    		if (isset($in_user)) {
    			$where.= " and a.ct_user in('$where_in')";
    		}

    		$communicate = $comm->getComm($where);
    		// if ($v['graduation_time'] != '') {
    		// 	$graduation_time = explode('-',$v['graduation_time']);
	    	// 	if (count($graduation_time) > 1) {
	    	// 		$candidate[$k]['graduation_time'] = $graduation_time[1];
	    	// 	}
	    	// 	else{
	    	// 		$candidate[$k]['graduation_time'] = $graduation_time[1];
	    	// 	}
    		// }

    		if (empty($communicate)) {
    			if (isset($in_user)) {
    				unset($candidate[$k]);
    				continue;
    			}
    			if (strtotime($parm['dtfm']) < strtotime($v['ct_time']) && strtotime($parm['dtto']) > strtotime($v['ct_time'])) {
    				// $candidate[$k]['personal_name']	= $user->getUserName(['uname' => 'ct_user']);
    				$candidate[$k]['communicate_count'] = 0;
    			}
    			else{
    				unset($candidate[$k]);
    				continue;
    			}
    		}
    		else{

    			$candidate[$k]['personal_name'] = $communicate[0]['personal_name'];
    			$candidate[$k]['ct_time'] = $communicate[0]['communicate_time'];
    			$candidate[$k]['communicate_count'] = count($communicate);

    		}
    		

    	}
    	return array_merge($candidate);
    }

   //  public function getCandidate($parm = ''){
   //  	//获取数据
   //  	$resume = new Resume();
   //  	$candidate = $resume->getCandidate();
   //  	$comm = new Communicate();
   //  	$data = []; //最终数据
   //  	$user_arr = [];//临时数组,记录是否有重复数据用于叠加
   //  	$key = 1;//data数组的key
   //  	$user = new User();
   //  	if (isset($parm['ur'])) {
			// $in_user = explode(',',$parm['ur']);
			// $where_in = implode("','",$in_user);
			
   //  	}
   //  	foreach ($candidate as $k => $v) {
   //  		$where = "communicate_time between '$parm[dtfm]' and '$parm[dtto]' and resume_id = $v[id]";
   //  		// if ($parm['ur'] != '') {
   //  		// 	$in_user = implode("','",explode(',',$parm['ur']));
   //  		// 	$where.= " and a.ct_user in('$in_user')";
   //  		// }
   //  		if (isset($in_user)) {
   //  			$where.= " and a.ct_user in('$where_in')";
   //  		}
   //  		$communicate = $comm->getComm($where);
   //  		if (empty($communicate)) {
   //  			if (isset($in_user)) {
   //  				if (in_array($v['ct_user'],$in_user)) {
   //  					$candidate[$k]['communicate_count'] = 0;
		 //    			$personal_name = $user->getOne(['uname' => $v['ct_user']]);
		 //    			$candidate[$k]['personal_name'] = $personal_name['personal_name'];
		 //    			$candidate[$k]['id'] = $key;
		 //    			$data[$key] = $candidate[$k];
		 //    			$key++;
   //  				}
   //  			}
   //  			else{
   //  				//没有任何沟通情况，沟通为0，直接跳过
	  //   			// $candidate[$k]['communicate_count'] = 0;
	  //   			// $personal_name = $user->getOne(['uname' => $v['ct_user']]);
	  //   			// $candidate[$k]['personal_name'] = $personal_name['personal_name'];
	  //   			// $candidate[$k]['id'] = $key;
	  //   			// $data[$key] = $candidate[$k];
	  //   			// $key++;
   //  			}
    			
   //  			continue;
   //  		}
   //  		foreach ($communicate as $a => $b) {
   //  			//查询到沟通情况
   //  			$candidate[$k]['personal_name'] = '';
   //  			$candidate[$k]['ct_user'] = '';
   //  			if ($b['personal_name'] != '') {
   //  				$candidate[$k]['personal_name'] = $b['personal_name'];
   //  			}
   //  			$candidate[$k]['ct_user'] = $b['ct_user'];

   //  			if (array_key_exists($b['ct_user'], $user_arr)) {
   //  				//如果已经存在数据，叠加
   //  				$data[$user_arr[$b['ct_user']]]['communicate_count'] = $data[$user_arr[$b['ct_user']]]['communicate_count'] + 1;  
   //  			}
   //  			else{
   //  				//没有存在数据不叠加
   //  				$candidate[$k]['communicate_count'] = 1;
   //  				$candidate[$k]['id'] = $key;
   //  				$data[$key] = $candidate[$k];
   //  				$user_arr[$b['ct_user']] = $key;
   //  				$key++;

   //  			}
   //  		}
   //  		if (!array_key_exists($v['ct_user'], $user_arr)){
   //  			//没有任何沟通情况，沟通为0，直接跳过
   //  			if (isset($in_user)) {
   //  				if (in_array($v['ct_user'],$in_user)) {
   //  					$candidate[$k]['communicate_count'] = 0;
		 //    			$personal_name = $user->getOne(['uname' => $v['ct_user']]);
		 //    			$candidate[$k]['personal_name'] = $personal_name['personal_name'];
		 //    			$candidate[$k]['id'] = $key;
		 //    			$data[$key] = $candidate[$k];
		 //    			$key++;
   //  				}
   //  			}
   //  			else{
   //  				$candidate[$k]['communicate_count'] = 0;
	  //   			$personal_name = $user->getOne(['uname' => $v['ct_user']]);
	  //   			$candidate[$k]['personal_name'] = $personal_name['personal_name'];
	  //   			$candidate[$k]['id'] = $key;
	  //   			$data[$key] = $candidate[$k];
	  //   			$key++;
   //  			}
    			
   //  		}
   //  		$user_arr = [];//清空临时数组
   //  	}
   //  	return array_merge($data);
   //  }

    public function recruitmentTotal(){
    	//招聘负责人统计
    	$input = input('get.');
    	$data = $this->getRecruitmentTotal($input);
    	return json(['code' => 0,'msg' => '获取成功','data' => $data]);
    }

    public function getRecruitmentTotal($parm){
    	//获取招聘负责人统计数据
    	$user = new User();
    	$comm = new Communicate();
    	

    	if (isset($parm['ur'])) {
			$in_user = explode(',',$parm['ur']);
			$where_in = implode("','",$in_user);
			$user_info = $user->getUserInfo("uname in('$where_in')");
    	}
    	else{
    		$user_info = $user->getUserInfo();
    	}

    	
    	foreach ($user_info as $k => $v) {

    		$where = "communicate_time between '$parm[dtfm]' and '$parm[dtto]' and ct_user = '$v[uname]'";
    		if (isset($in_user)) {
    			$where.= " and ct_user in('$where_in')";
    		}

    		$user_info[$k]['screen'] = 0;
			$user_info[$k]['arrange_interview'] = 0;
			$user_info[$k]['arrive'] = 0;
			$user_info[$k]['approved_interview'] = 0;
			$user_info[$k]['entry'] = 0;
    		$communicate = $comm->getCommInfo($where);
    		foreach ($communicate as $a => $b) {
    			$user_info[$k]['screen'] = $user_info[$k]['screen']+$b['screen'];
    			$user_info[$k]['arrange_interview'] = $user_info[$k]['arrange_interview']+$b['arrange_interview'];
    			$user_info[$k]['arrive'] = $user_info[$k]['arrive']+$b['arrive'];
    			$user_info[$k]['approved_interview'] = $user_info[$k]['approved_interview']+$b['approved_interview'];
    			$user_info[$k]['entry'] = $user_info[$k]['entry']+$b['entry'];
    		}
    	}
    	return $user_info;
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
    	if (isset($parm['ur'])) {
			$in_user = explode(',',$parm['ur']);
			$where_in = implode("','",$in_user);
			$user_data = $user->getUserInfo("uname in('$where_in')");
    	}
    	else{
    		$user_data = $user->getUserInfo();
    	}
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
			$obj->getActiveSheet()->setCellValue("C1", '是否推荐');
			$obj->getActiveSheet()->setCellValue("D1", '是否安排');
			$obj->getActiveSheet()->setCellValue("E1", '是否到场');
			$obj->getActiveSheet()->setCellValue("F1", '是否通过');
			$obj->getActiveSheet()->setCellValue("G1", '是否入职');
			$data = $this->getRecruitment($input);
			//数据
    		foreach ($data as $k => $v) {
    			$k = $k+2;

    			$obj->getActiveSheet()->setCellValue("A".$k,$v['personal_name'] == ''?  $v['ct_user'] : $v['personal_name']);
				$obj->getActiveSheet()->setCellValue("B".$k, $v['name']);
				$obj->getActiveSheet()->setCellValue("C".$k, $v['screen'] == 1 ?'是':'否');
				$obj->getActiveSheet()->setCellValue("D".$k, $v['arrange_interview'] ==1 ?'是':'否');
				$obj->getActiveSheet()->setCellValue("E".$k, $v['arrive'] == 1 ?'是':'否');
				$obj->getActiveSheet()->setCellValue("F".$k, $v['approved_interview'] == 1 ?'是':'否');
				$obj->getActiveSheet()->setCellValue("G".$k, $v['entry'] == 1 ?'是':'否');
    		}
    		header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment;filename="招聘负责人明细.xlsx"');
	        ob_end_clean();//清除缓冲区,避免乱码
	        $objWriter = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
	        $objWriter->save('php://output');
	        $obj->disconnectWorksheets();
    		
    	}
    	if ($input['type'] == 1) {
    		//宽度设置
    		$obj->getActiveSheet()->getColumnDimension('A')->setWidth(12);
			$obj->getActiveSheet()->getColumnDimension('B')->setWidth(10);
			$obj->getActiveSheet()->getColumnDimension('C')->setWidth(15);
			$obj->getActiveSheet()->getColumnDimension('D')->setWidth(10);
			$obj->getActiveSheet()->getColumnDimension('E')->setWidth(15);
			$obj->getActiveSheet()->getColumnDimension('F')->setWidth(10);
			//表头
    		$obj->getActiveSheet()->setCellValue("A1", '招聘负责人');
			$obj->getActiveSheet()->setCellValue("B1", '推荐人数');
			$obj->getActiveSheet()->setCellValue("C1", '安排面试人数');
			$obj->getActiveSheet()->setCellValue("D1", '到场人数');
			$obj->getActiveSheet()->setCellValue("E1", '通过面试人数');
			$obj->getActiveSheet()->setCellValue("F1", '入职人数');
			$data = $this->getRecruitmentTotal($input);
			foreach ($data as $k => $v) {
				$k = $k+2;

    			$obj->getActiveSheet()->setCellValue("A".$k,$v['personal_name'] == ''?  $v['uname'] : $v['personal_name']);
				$obj->getActiveSheet()->setCellValue("B".$k, $v['screen']);
				$obj->getActiveSheet()->setCellValue("C".$k, $v['arrange_interview']);
				$obj->getActiveSheet()->setCellValue("D".$k, $v['arrive']);
				$obj->getActiveSheet()->setCellValue("E".$k, $v['approved_interview']);
				$obj->getActiveSheet()->setCellValue("F".$k, $v['entry']);
    		}
    		header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment;filename="招聘负责人统计.xlsx"');
	        ob_end_clean();//清除缓冲区,避免乱码
	        $objWriter = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
	        $objWriter->save('php://output');
	        $obj->disconnectWorksheets();

			
    	}
    	if ($input['type'] == 2) {
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
			$communicate_count = array_column($data,'communicate_count');
			$max_count = max($communicate_count);//最大沟通数量
			$cel = ['K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];//列集合
			for ($i = 0; $i < $max_count; $i++) { 
				$obj->getActiveSheet()->getColumnDimension($cel[$i])->setWidth(20);
				$obj->getActiveSheet()->setCellValue($cel[$i]."1", '沟通'.($i+1));
			}

			$comm = new Communicate();
			//数据
    		foreach ($data as $k => $v) {
    			$k = $k+2;
    			$obj->getActiveSheet()->setCellValue("A".$k, $v['personal_name'] == ''?  $v['ct_user'] : $v['personal_name']);
				$obj->getActiveSheet()->setCellValue("B".$k, $v['name']);
				$obj->getActiveSheet()->setCellValue("C".$k, $v['phone']);
				$obj->getActiveSheet()->setCellValue("D".$k, $v['email']);
				$obj->getActiveSheet()->setCellValue("E".$k, $v['school']);
				$obj->getActiveSheet()->setCellValue("F".$k, $v['educational']);
				$obj->getActiveSheet()->setCellValue("G".$k, $v['graduation_time']);
				$obj->getActiveSheet()->setCellValue("H".$k, $v['work_year']);
				$obj->getActiveSheet()->setCellValue("I".$k, $v['source']);
				$obj->getActiveSheet()->setCellValue("J".$k, $v['communicate_count']);
				$content = $comm->getContent("communicate_time between '$input[dtfm]' and '$input[dtto]' and resume_id = $v[id]");
				foreach ($content as $m => $n) {
					$obj->getActiveSheet()->setCellValue($cel[$m].$k,$n['content']);
				}
    		}

    		header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment;filename="候选人跟踪表.xlsx"');
	        ob_end_clean();//清除缓冲区,避免乱码
	        $objWriter = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
	        $objWriter->save('php://output');
	        $obj->disconnectWorksheets();
    		
    	}
    }
    
}
