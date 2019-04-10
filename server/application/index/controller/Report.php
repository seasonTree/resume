<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Env;
use app\index\model\Resume;
use app\index\model\Communicate;
use app\index\model\User;
use app\index\model\Client;
use app\index\model\ClientComm;
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
      $comm = new Communicate();
      $user = new User();

      if (isset($parm['ur'])) {
      $in_user = explode(',',$parm['ur']);
      $where_in = implode("','",$in_user);
      }

      $where_communicate = "date(communicate_time) between '$parm[dtfm]' and '$parm[dtto]'";
      $where_communicate = isset($where_in)?$where_communicate." and ct_user in('$where_in')":$where_communicate;
      $where_resume = "ct_time between '$parm[dtfm]' and '$parm[dtto]'";
      $where_resume = isset($where_in)?$where_resume." and ct_user in('$where_in')":$where_resume;
      $communicate = $comm->getComm($where_communicate);//获取该时间段所有沟通信息
      $resume_ids = array_unique(array_column($communicate,'resume_id'));//需要获取简历的id集合
      $resume_ids = implode("','",$resume_ids);
      $resumes = $resume->getCandidate($where_resume." or id in('$resume_ids')");
      $communicate_count = [];//沟通次数集合

      foreach ($communicate as $k => $v) {
        if (array_key_exists($v['resume_id'], $communicate_count)) {
          $communicate_count[$v['resume_id']]++;
        }
        else{
          $communicate_count[$v['resume_id']] = 1;  
        }
        
      }


      foreach ($resumes as $k => $v) {
          $resumes[$k]['communicate_count'] = isset($communicate_count[$v['id']])?$communicate_count[$v['id']]:0;
      }
      return $resumes;

      // $candidate = $resume->getCandidate();
      
      // foreach ($candidate as $k => $v) {

      //   $where = "communicate_time between '$parm[dtfm]' and '$parm[dtto]' and resume_id = $v[id]";
      //   if (isset($in_user)) {
      //     $where.= " and a.ct_user in('$where_in')";
      //   }

      //   $communicate = $comm->getComm($where);

      //   if (empty($communicate)) {
      //     if (isset($in_user)) {
      //       unset($candidate[$k]);
      //       continue;
      //     }
      //     if (strtotime($parm['dtfm']) < strtotime($v['ct_time']) && strtotime($parm['dtto']) > strtotime($v['ct_time'])) {
      //       $candidate[$k]['personal_name'] = $user->getUserName(['uname' => 'ct_user']);
      //       $candidate[$k]['communicate_count'] = 0;
      //     }
      //     else{
      //       unset($candidate[$k]);
      //       continue;
      //     }
      //   }
      //   else{

      //     $candidate[$k]['personal_name'] = $communicate[0]['personal_name'];
      //     $candidate[$k]['ct_time'] = $communicate[0]['communicate_time'];
      //     $candidate[$k]['communicate_count'] = count($communicate);

      //   }
        

      // }
      // return array_merge($candidate);
    }

/*******************************旧方法,获取候选人跟踪情况*****************************************/

   //  public function getCandidate($parm = ''){
   //  	//获取数据
   //  	$resume = new Resume();
   //  	$comm = new Communicate();
   //  	$user = new User();

   //  	if (isset($parm['ur'])) {
			// $in_user = explode(',',$parm['ur']);
			// $where_in = implode("','",$in_user);
   //  	}

   //  	$candidate = $resume->getCandidate();
   //  	foreach ($candidate as $k => $v) {

   //  		$where = "communicate_time between '$parm[dtfm]' and '$parm[dtto]' and resume_id = $v[id]";
   //  		if (isset($in_user)) {
   //  			$where.= " and a.ct_user in('$where_in')";
   //  		}

   //  		$communicate = $comm->getComm($where);
   //  		// if ($v['graduation_time'] != '') {
   //  		// 	$graduation_time = explode('-',$v['graduation_time']);
	  //   	// 	if (count($graduation_time) > 1) {
	  //   	// 		$candidate[$k]['graduation_time'] = $graduation_time[1];
	  //   	// 	}
	  //   	// 	else{
	  //   	// 		$candidate[$k]['graduation_time'] = $graduation_time[1];
	  //   	// 	}
   //  		// }

   //  		if (empty($communicate)) {
   //  			if (isset($in_user)) {
   //  				unset($candidate[$k]);
   //  				continue;
   //  			}
   //  			if (strtotime($parm['dtfm']) < strtotime($v['ct_time']) && strtotime($parm['dtto']) > strtotime($v['ct_time'])) {
   //  				// $candidate[$k]['personal_name']	= $user->getUserName(['uname' => 'ct_user']);
   //  				$candidate[$k]['communicate_count'] = 0;
   //  			}
   //  			else{
   //  				unset($candidate[$k]);
   //  				continue;
   //  			}
   //  		}
   //  		else{

   //  			// $candidate[$k]['personal_name'] = $communicate[0]['personal_name'];
   //  			$candidate[$k]['ct_time'] = $communicate[0]['communicate_time'];
   //  			$candidate[$k]['communicate_count'] = count($communicate);

   //  		}
    		

   //  	}
   //  	return array_merge($candidate);
   //  }

/******************************************************************************************************/


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

    		$where = "date(communicate_time) between '$parm[dtfm]' and '$parm[dtto]' and a.ct_user = '$v[uname]'";
    		// if (isset($in_user)) {
    		// 	$where.= " and a.ct_user in('$where_in')";
    		// }

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
    	//针对招聘负责人的列表(明细)
    	$input = input('get.');
      $check_date = isset($input['dtfm']) && isset($input['dtto'])?true:false;
      if (!$check_date) {
         return json(['msg' => '缺少日期','code' => 404]);
      }
    	$data = $this->getRecruitment($input);

    	return json([ 'msg' => '获取成功','code' => 0,'data' => $data ]);
    }

    public function getPersonRec(){
      //针对个人的获取招聘负责人明细
      $input = input('get.');
      $check_date = isset($input['dtfm']) && isset($input['dtto'])?true:false;
      if (!$check_date) {
         return json(['msg' => '缺少日期','code' => 404]);
      }
      $input['ur'] = Session::get('user_info')['uname'];
      $data = $this->getRecruitment($input);

      return json([ 'msg' => '获取成功','code' => 0,'data' => $data ]);
    }

    public function PersonRecExport(){
      //导出个人招聘负责人明细(针对个人)
      $input = input('get.');
      $check_date = isset($input['dtfm']) && isset($input['dtto'])?true:false;
      if (!$check_date) {
         return json(['msg' => '缺少日期','code' => 404]);
      }
      $input['ur'] = Session::get('user_info')['uname'];

      $obj = new \PHPExcel();//phpexcel
      $obj->setActiveSheetIndex(0);
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

        $obj->getActiveSheet()->setCellValue("A".$k,$v['ct_user']);
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

    public function getRecruitment($parm = ''){
    	//获取数据主方法
    	$resume = new Resume();
    	// $candidate = $resume->getCandidate();

    	$comm = new Communicate();
      /*******************************************旧方法，先存档***************************************************/
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
    			$where = "date(communicate_time) between '$parm[dtfm]' and '$parm[dtto]' and a.ct_user = '$v[uname]'";
    		}
    		else{
    			$where = "a.ct_user = '$v[uname]'";
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
    				// $data[$key]['personal_name'] = $v['personal_name'];
    				// $data[$key]['name'] = $resume->getUname(['id' => $b['resume_id']]);
	    			$user_arr[$b['resume_id']] = $key;
	    			$key++;


    			}
    			
    		}
    		$user_arr = [];
    	}

    	return array_merge($data);

      /******************************************************************************/
      // $where = "communicate_time between '$parm[dtfm]' and '$parm[dtto]'";

      // if (isset($parm['ur'])) {
      //     $in_user = explode(',',$parm['ur']);
      //     $where_in = implode("','",$in_user);
      //     $where.= " and a.ct_user in('$where_in')";
      // }
      // else{
      //     $user_model = new User();
      //     $in_user = array_column($user_model->field('uname')->select()->toArray(),'uname');
      // }

      // $data = $comm->alias('a')->join('rs_resume b','a.resume_id=b.id')->where($where)->field('a.*,b.name')->order('a.ct_user')->select()->toArray();
      // foreach ($data as $k => $v) {
      //     if () {
      //       # code...
      //     }
      // }

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

      			$obj->getActiveSheet()->setCellValue("A".$k,$v['ct_user']);
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

      			$obj->getActiveSheet()->setCellValue("A".$k,$v['uname']);
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
    			$obj->getActiveSheet()->setCellValue("A".$k,$v['ct_user']);
  				$obj->getActiveSheet()->setCellValue("B".$k, $v['name']);
  				$obj->getActiveSheet()->setCellValue("C".$k, $v['phone']);
  				$obj->getActiveSheet()->setCellValue("D".$k, $v['email']);
  				$obj->getActiveSheet()->setCellValue("E".$k, $v['school']);
  				$obj->getActiveSheet()->setCellValue("F".$k, $v['educational']);
  				$obj->getActiveSheet()->setCellValue("G".$k, $v['graduation_time']);
  				$obj->getActiveSheet()->setCellValue("H".$k, $v['work_year']);
  				$obj->getActiveSheet()->setCellValue("I".$k, $v['source']);
  				$obj->getActiveSheet()->setCellValue("J".$k, $v['communicate_count']);
  				$content = $comm->getContent("date(communicate_time) between '$input[dtfm]' and '$input[dtto]' and resume_id = $v[id]");
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

    public function clientRecData($input){
      //获取客户统计明细的数据
      $client_model = new Client();//取出客户信息
      $client_data = $client_model->field('id,client_name')->select()->toArray();
      $client_new = [];
      foreach ($client_data as $k => $v) {
         $client_new[$v['id']] = $v['client_name'];
      }
      // dump($data);
      $c_id = 0;//记录客户id
      $keys = 0;//新数据分组的key
      $new_data = [];//新数据

      $new_data[0]['screen'] = 0;
      $new_data[0]['arrange_interview'] = 0;
      $new_data[0]['arrive'] = 0;
      $new_data[0]['approved_interview'] = 0;
      $new_data[0]['entry'] = 0;
      $new_data[0]['client_name'] = '总共';
      $new_data[0]['client_id'] = 0;

      $count = function($option,$new_data,$keys){
        //处理统计内容
          switch ($option['type']) {
           case 1:
             $new_data[0]['screen']+=1;
             $new_data[$keys]['screen']+=1;
             break;
           
           case 2:
             $new_data[0]['arrange_interview']+=1;
             $new_data[$keys]['arrange_interview']+=1;
             break;

           case 3:
             $new_data[0]['arrive']+=1;
             $new_data[$keys]['arrive']+=1;
             break;

           case 4:
             $new_data[0]['approved_interview']+=1;
             $new_data[$keys]['approved_interview']+=1;
             break;

           case 5:
             $new_data[0]['entry']+=1;
             $new_data[$keys]['entry']+=1;
             break;
           default:
             // $new_data[$keys]['screen']+=0;
             // $new_data[$keys]['approved_interview']+=0;
             // $new_data[$keys]['arrange_interview']+=0;
             // $new_data[$keys]['arrive']+=0;
             // $new_data[$keys]['entry']+=0;
             break;
         }

         return $new_data;
      };

      $comm = new Communicate();
      // $data = $comm->alias('a')
      //             ->join('rs_client_communicate b','a.id=b.comm_id','left')
      //             ->where("date(communicate_time) between '$input[dtfm]' and '$input[dtto]'")
      //             ->field('a.id,a.screen,a.arrange_interview,a.arrive,a.approved_interview,a.entry,b.client_id,b.type')
      //             ->order('client_id asc')
      //             ->select()
      //             ->toArray();
      $comm_ids = implode(',',array_column($comm->where("date(communicate_time) between '$input[dtfm]' and '$input[dtto]'")
                                    ->field('id')
                                    ->select()
                                    ->toArray(),'id')); 
      $client_comm = new ClientComm();
      $data = [];
      if (!empty($comm_ids)) {
        $data = $client_comm->where("comm_id in($comm_ids)")->order('client_id asc')->select()->toArray();
      }

      if ($data) {
           foreach ($data as $k => $v) {
           // if ($v['client_id'] == '') {
           //    continue;
           // }

           if ($c_id != $v['client_id']) {
            //利用排序进行分组
              $keys++;
              $new_data[$keys]['screen'] = 0;
              $new_data[$keys]['arrange_interview'] = 0;
              $new_data[$keys]['arrive'] = 0;
              $new_data[$keys]['approved_interview'] = 0;
              $new_data[$keys]['entry'] = 0;
              $new_data[$keys]['client_name'] = $client_new[$v['client_id']];
              $new_data[$keys]['client_id'] = $v['client_id'];
              $c_id = $v['client_id'];
              unset($client_new[$v['client_id']]);

           }
           $new_data = $count($v,$new_data,$keys);
        }
      }


      if (!empty($client_new)) {
        //那些没有产生数据的客户全部赋予0值
         foreach ($client_new as $k => $v) {
            $keys++;
            $new_data[$keys]['screen'] = 0;
            $new_data[$keys]['arrange_interview'] = 0;
            $new_data[$keys]['arrive'] = 0;
            $new_data[$keys]['approved_interview'] = 0;
            $new_data[$keys]['entry'] = 0;
            $new_data[$keys]['client_name'] = $v;
         }
      }
      return $new_data;
    }

    public function clientRec(){
      //客户统计明细
      $input = input('get.');
      $check_date = isset($input['dtfm']) && isset($input['dtto'])?true:false;
      if (!$check_date) {
         return json(['msg' => '缺少日期','code' => 404]);
      }

      return json(['msg' => '获取成功','code' => 0,'data' => $this->clientRecData($input)]);

    }

    public function clientRecExport(){
      //导出客户统计数据
      $input = input('get.');
      $check_date = isset($input['dtfm']) && isset($input['dtto'])?true:false;

      if (!$check_date) {
        return json(['msg' => '缺少日期','code' => 404]);
      }

      $obj = new \PHPExcel();//phpexcel
      $obj->setActiveSheetIndex(0);
      //宽度设置
      $obj->getActiveSheet()->getColumnDimension('A')->setWidth(16);
      $obj->getActiveSheet()->getColumnDimension('B')->setWidth(16);
      $obj->getActiveSheet()->getColumnDimension('C')->setWidth(20);

      //设置居中
      $obj->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $obj->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
      //颜色
      $obj->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
      $obj->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('FFDDAA');

      //表头
      $obj->getActiveSheet()->setCellValue("A1", '客户');
      $obj->getActiveSheet()->setCellValue("B1", '统计');
      $obj->getActiveSheet()->setCellValue("C1", $input['dtfm'].'到'.$input['dtto']);

      $data = $this->clientRecData($input);
      //数据
      $n = 2;//第二行开始
      $start = 0;//合并用，开始
      $end = 0;//用于合并用，结束
      foreach ($data as $k => $v) {
        // $k = $k + 2;//第二行开始
        // $obj->getActiveSheet()->setCellValue("B".$k, $v['entry'] == 1 ?'是':'否');
        $start = $n;
        for ($i=0; $i < 5 ; $i++) { 
           switch ($i) {
             case 0:
               $obj->getActiveSheet()->setCellValue("B".$n,'推荐简历数');
               $obj->getActiveSheet()->setCellValue("C".$n,$v['screen']);
               break;
             case 1:
               $obj->getActiveSheet()->setCellValue("B".$n,'安排面试数');
               $obj->getActiveSheet()->setCellValue("C".$n,$v['arrange_interview']);
               break;
             case 2:
               $obj->getActiveSheet()->setCellValue("B".$n,'参加面试数');
               $obj->getActiveSheet()->setCellValue("C".$n,$v['arrive']);
               break;
             case 3:
               $obj->getActiveSheet()->setCellValue("B".$n,'通过面试数');
               $obj->getActiveSheet()->setCellValue("C".$n,$v['approved_interview']);
               break;
             case 4:
               $obj->getActiveSheet()->setCellValue("B".$n,'入职人数');
               $obj->getActiveSheet()->setCellValue("C".$n,$v['entry']);
               break;
           }
           $end = $n;
           $n++;
        }
        $obj->getActiveSheet()->setCellValue("A".$start,$v['client_name']);
        $obj->getActiveSheet()->mergeCells("A{$start}:A{$end}");
      }
      //背景颜色
      $obj->getActiveSheet()->getStyle('A1:A'.$end)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
      $obj->getActiveSheet()->getStyle('A1:A'.$end)->getFill()->getStartColor()->setARGB('FFDDAA');
      $obj->getActiveSheet()->getStyle('B1:B'.$end)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
      $obj->getActiveSheet()->getStyle('B1:B'.$end)->getFill()->getStartColor()->setARGB('FFDDAA');
      //边框线
      $style_array = array( 
                            'borders' => array( 
                                'allborders' => array( 
                                'style' => \PHPExcel_Style_Border::BORDER_THIN 
                              ) 
                            )
                          ); 

      $obj->getActiveSheet()->getStyle('A1:C'.$end)->applyFromArray($style_array);

      $filename = $input['dtfm'].'到'.$input['dtto'].'客户明细统计.xlsx';
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename='.$filename);
      ob_end_clean();//清除缓冲区,避免乱码
      $objWriter = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
      $objWriter->save('php://output');
      $obj->disconnectWorksheets();
    }

    public function clientRecDetail(){
      //查看客户统计详细内容
      $input = input('get.');
      $parm = ['dtfm' => '开始时间','dtto' => '结束时间','client_id' => '客户id','pageIndex' => '当前页数','pageSize' => '显示页数'];//定义参数

      foreach ($parm as $k => $v) {
        //参数检测
         if (!array_key_exists($k, $input)) {
            return json(['msg' => '缺少'.$v,'code' => 404]);
         }
      }
      $pageSize = $input['pageSize'];
      $pageIndex = $input['pageIndex'];
      $begin = ($pageIndex-1)*$pageSize;

      $where = "date(communicate_time) between '$input[dtfm]' and '$input[dtto]'";
      $input['client_id'] == 0?'':$where.=" and client_id=$input[client_id]";

      $resume_model = new Resume();
      $data = $resume_model->alias('a')
                   ->join('rs_communicate b','a.id=b.resume_id')
                   ->join('rs_client_communicate c','b.id=c.comm_id')
                   ->field('a.id,a.name,b.ct_user,b.communicate_time,a.expected_job,a.phone,a.educational,a.graduation_time,a.source,a.company_type,type,client_id')
                   ->where($where)
                   ->limit($begin,$pageSize)
                   ->order('communicate_time desc,name asc,type desc')
                   ->select()
                   ->toArray();
      $total = $resume_model->alias('a')
                   ->join('rs_communicate b','a.id=b.resume_id')
                   ->join('rs_client_communicate c','b.id=c.comm_id')
                   ->where($where)
                   ->count();
      return json(['msg' => '获取成功','code' => 0,'data' => ['row' => $data,'total' => $total]]);

    }

    public function candidateRec(){
      //候选人信息统计
      $input = input('get.');
      $parm = ['dtfm' => '开始时间','dtto' => '结束时间'];//定义参数

      foreach ($parm as $k => $v) {
        //参数检测
         if (!array_key_exists($k, $input)) {
            return json(['msg' => '缺少'.$v,'code' => 404]);
         }
      }
      $where = "date(communicate_time) between '$input[dtfm]' and '$input[dtto]'";//时间
      $where_ur = $input['ur'] != ''? implode("','",explode(',',$input['ur'])):'';//招聘负责人
      $where_ur == ''?'':$where.=" and c.ct_user in('$where_ur')";
      $input['client'] != ''?$where.=" and a.client_id in($input[client])":'';//客户

      // $comm = new Communicate();

      // $data = $comm->alias('a')->join('rs_resume b','a.resume_id=b.id')
      //                  ->join('rs_client_communicate c','a.id=c.comm_id')
      //                  ->join('rs_client d','c.client_id=d.id')
      //                  ->field('a.communicate_time,a.ct_user,a.screen,a.arrange_interview,a.arrive,a.approved_interview,a.entry,b.name,b.phone,b.expected_job,b.educational,b.graduation_time,b.school,b.company_type,d.client_name')
      //                  ->where($where)
      //                  ->order('communicate_time desc,name asc,type desc')
      //                  ->select();

      $comm_cli = new ClientComm();
      $data = $comm_cli->alias('a')->join('rs_client b','a.client_id=b.id')
                       ->join('rs_communicate c','a.comm_id=c.id')
                       ->join('rs_resume d','c.resume_id=d.id')
                       ->field('c.communicate_time,c.ct_user,c.screen,c.arrange_interview,c.arrive,c.approved_interview,c.entry,d.name,d.phone,d.expected_job,d.educational,d.graduation_time,d.school,d.company_type,b.client_name')
                       ->where($where)
                       ->order('communicate_time desc,name asc,type desc')
                       ->select();

      // $phone = [];//记录电话
      // $new_data = [];//新数据
      // foreach ($data as $k => $v) {
      //    if (in_array($v, haystack)) {
      //      # code...
      //    }
      // }
      return json(['msg' => '获取成功','code' => 0,'data' => $data]);

    }

    
}
