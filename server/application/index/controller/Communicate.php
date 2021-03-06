<?php
namespace app\index\controller;

use think\facade\Cookie;
use think\facade\Log;
use think\facade\Request;
use think\facade\Session;
use app\index\model\User;
use app\index\model\Communicate as CommunicateModel;
use app\index\model\ClientComm;
use app\index\model\Client;
use think\Db;

class Communicate
{

    public function commList(){
      //获取沟通列表
       $id = input('resume_id');
       $comm = new CommunicateModel();
       $data = $comm->get(['resume_id' => $id]);
       if ($data) {
         $cli_comm_model = new ClientComm();
         $cli_model = new Client();
         $cli_name = [];
         $cli_res = $cli_model->field('id,client_name')->select()->toArray();
         $comm_data = [];//沟通数据
         $comm_user = [];//沟通人
         $del_ids = [];//处理多余没用的数据的id，mysql里面删掉(针对批量导入多次重复的沟通记录)
         foreach ($cli_res as $k => $v) {
           $cli_name[$v['id']] = $v['client_name'];
         }
         
         foreach ($data as $k => $v) {

            if (in_array($v['content'], $comm_data)) {
              //判断该沟通记录是否已经存在
                $comm_key = array_search($v['content'],$comm_data);
                //把存在对应的key取出来
                // dump($comm_key);
                if($comm_user[$comm_key] == strtolower($v['ct_user'])){
                  //再查看对应的沟通记录的沟通人和这一条是否一致
                  if (!$data[$k]['screen'] && !$data[$k]['arrange_interview'] && !$data[$k]['arrive'] && !$data[$k]['approved_interview'] && !$data[$k]['entry']) {
                        //判断重复数据里面是否有全零，如果有删除并跳过
                        $del_ids[] = $data[$k]['id'];//记录应该要删除记录的id
                        unset($data[$k]);
                        continue;
                   }

                   if (!$data[$comm_key]['screen'] && !$data[$comm_key]['arrange_interview'] && !$data[$comm_key]['arrive'] && !$data[$comm_key]['approved_interview'] && !$data[$comm_key]['entry']) {
                        //判断全部为0的时候，表示是重复数据，不输出并删除
                        $del_ids[] = $data[$comm_key]['id'];//记录应该删除的记录id
                        unset($data[$comm_key]);
                        continue;
                   }
                }
            }
            $comm_user[$k] = strtolower($v['ct_user']);//存储每一次的沟通人记录
            $comm_data[$k] = $v['content'];

            $cli_comm_list = $cli_comm_model->where(['comm_id' => $v['id']])->field('type,client_id')->select()->toArray();

            foreach ($cli_comm_list as $a => $b) {
              //把沟通列表进行处理，加入客户名
               switch ($b['type']) {
                 case 1:
                   $data[$k]['screen_name'] = isset($data[$k]['screen_name'])?$data[$k]['screen_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                   break;
                 
                 case 2:
                   $data[$k]['arrange_interview_name'] = isset($data[$k]['arrange_interview_name'])?$data[$k]['arrange_interview_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                   break;

                 case 3:
                   $data[$k]['arrive_name'] = isset($data[$k]['arrive_name'])?$data[$k]['arrive_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                   break;

                 case 4:
                   $data[$k]['approved_interview_name'] = isset($data[$k]['approved_interview_name'])?$data[$k]['approved_interview_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                   break;

                 case 5:
                   $data[$k]['entry_name'] = isset($data[$k]['entry_name'])?$data[$k]['entry_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                   break;
               }

            }
            //如果不存在客户名，直接返回空
            !isset($data[$k]['screen_name'])?$data[$k]['screen_name']='':'';
            !isset($data[$k]['arrange_interview_name'])?$data[$k]['arrange_interview_name']='':'';
            !isset($data[$k]['arrive_name'])?$data[$k]['arrive_name']='':'';
            !isset($data[$k]['approved_interview_name'])?$data[$k]['approved_interview_name']='':'';
            !isset($data[$k]['entry_name'])?$data[$k]['entry_name']='':'';

         }

         if (!empty($del_ids)) {
          //删除多余的数据
            $del_ids = implode(',',$del_ids);
            $comm->where("id in($del_ids)")->delete();
         }
         
         return json(['msg' => '获取成功','code' => 0,'data' => $data]);
       }
       else{
         return json(['msg' => '无数据','code' => 1,'data' => []]);
       }
    }

    public function addComm(){
      //添加沟通
      $data = input('post.');
      if (!isset($data['resume_id'])) {
        return json(['msg' => '简历id不存在','code' => 3,'data' => []]);
      }
      $data['ct_user'] = Session::get('user_info')['uname'];

      /****************************************沟通，客户信息***************************************/
      $cli_comm = [];
      isset($data['screen_client'])?$cli_comm[1]=$data['screen_client']:'';
      isset($data['arrange_interview_client'])?$cli_comm[2]=$data['arrange_interview_client']:'';
      isset($data['arrive_client'])?$cli_comm[3]=$data['arrive_client']:'';
      isset($data['approved_interview_client'])?$cli_comm[4]=$data['approved_interview_client']:'';
      isset($data['entry_client'])?$cli_comm[5]=$data['entry_client']:'';

      unset($data['screen_client']);
      unset($data['arrange_interview_client']);
      unset($data['arrive_client']);
      unset($data['approved_interview_client']);
      unset($data['entry_client']);

      /*********************************************************************************************/
      $comm = new CommunicateModel();
      Db::startTrans();//开始事务
      $comm_id = $comm->add($data);//写入沟通信息并返回id

      if (!empty($cli_comm)) {//处理沟通客户中间表
        $cli_comm_list = [];
        $keys = 0;//key
        foreach ($cli_comm as $k => $v) {
          foreach ($v as $a => $b) {
             $cli_comm_list[$keys]['type'] = $k;
             $cli_comm_list[$keys]['client_id'] = $b;
             $cli_comm_list[$keys]['comm_id'] = $comm_id;
             $keys++;
          }
        }
      }
      $cli_comm_model = new ClientComm();
      $res = $cli_comm_model->insertAll($cli_comm_list);

      if ($comm_id) {
         Db::commit();
         $data['id'] = $comm_id;

         $cli_model = new Client();
         $cli_name = [];
         $cli_res = $cli_model->field('id,client_name')->select()->toArray();
         foreach ($cli_res as $k => $v) {
           $cli_name[$v['id']] = $v['client_name'];
         }

         $cli_comm_list = $cli_comm_model->where(['comm_id' => $comm_id])->field('type,client_id')->select()->toArray();

            foreach ($cli_comm_list as $a => $b) {

               switch ($b['type']) {
                 case 1:
                   $data['screen_name'] = isset($data['screen_name'])?$data['screen_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                   break;
                 
                 case 2:
                   $data['arrange_interview_name'] = isset($data['arrange_interview_name'])?$data['arrange_interview_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                   break;

                 case 3:
                   $data['arrive_name'] = isset($data['arrive_name'])?$data['arrive_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                   break;

                 case 4:
                   $data['approved_interview_name'] = isset($data['approved_interview_name'])?$data['approved_interview_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                   break;

                 case 5:
                   $data['entry_name'] = isset($data['entry_name'])?$data['entry_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                   break;
               }

            }

         return json(['msg' => '添加成功','code' => 0,'data' => $data]);
       }
       else{
         Db::rollback();
         return json(['msg' => '添加失败','code' => 1,'data' => []]);
       }
    }

    public function editComm(){
      //修改沟通
      $data = input('post.');
      $data['mfy_user'] = Session::get('user_info')['uname'];
      $comm = new CommunicateModel();
      $cli_comm_model = new ClientComm();
 /****************************************沟通，客户信息***************************************/
      $cli_comm = [];
      isset($data['screen_client'])?$cli_comm[1]=$data['screen_client']:'';
      isset($data['arrange_interview_client'])?$cli_comm[2]=$data['arrange_interview_client']:'';
      isset($data['arrive_client'])?$cli_comm[3]=$data['arrive_client']:'';
      isset($data['approved_interview_client'])?$cli_comm[4]=$data['approved_interview_client']:'';
      isset($data['entry_client'])?$cli_comm[5]=$data['entry_client']:'';

      unset($data['screen_client']);
      unset($data['arrange_interview_client']);
      unset($data['arrive_client']);
      unset($data['approved_interview_client']);
      unset($data['entry_client']);

      /*********************************************************************************************/

      if (!empty($cli_comm)) {//处理沟通客户中间表
        $cli_comm_list = [];
        $keys = 0;//key
        foreach ($cli_comm as $k => $v) {
          foreach ($v as $a => $b) {
             $cli_comm_list[$keys]['type'] = $k;
             $cli_comm_list[$keys]['client_id'] = $b;
             $cli_comm_list[$keys]['comm_id'] = $data['id'];
             $keys++;
          }
        }
      }

      Db::startTrans();//开始事务
      if (Session::get('user_info')['id'] == 1) {
            //超级管理员不做任何验证可以直接修改

            $cli_comm_model->where(['comm_id' => $data['id']])->delete();
            $cli_comm_model->insertAll($cli_comm_list);
            $res = $comm->edit($data);
            
      }
      else{
          $ct_user = $comm->where(['id' => $data['id']])->value('ct_user');
          if (strtolower($ct_user) != strtolower(Session::get('user_info')['uname'])) {
             return json(['msg' => '没有权限修改别人的沟通记录.','code' => 500,'data' => []]);
          }
          else{

            $cli_comm_model->where(['comm_id' => $data['id']])->delete();
            $cli_comm_model->insertAll($cli_comm_list);
            $res = $comm->edit($data);
          }
      }

      if ($res) {
         Db::commit();
           $data = $comm->getOne(['a.id' => $data['id']]);

           $cli_model = new Client();
           $cli_name = [];
           $cli_res = $cli_model->field('id,client_name')->select()->toArray();

           foreach ($cli_res as $k => $v) {
             $cli_name[$v['id']] = $v['client_name'];
           }

           $cli_comm_list = $cli_comm_model->where(['comm_id' => $data['id']])->field('type,client_id')->select()->toArray();


          $data['screen_name'] = '';
          $data['arrange_interview_name'] = '';
          $data['arrive_name'] = '';
          $data['approved_interview_name'] = '';
          $data['entry_name'] = '';

          foreach ($cli_comm_list as $a => $b) {

              switch ($b['type']) {
                case 1:
                  $data['screen_name'] = strlen($data['screen_name'])?$data['screen_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                  break;
                
                case 2:
                  $data['arrange_interview_name'] = strlen($data['arrange_interview_name'])?$data['arrange_interview_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                  break;

                case 3:
                  $data['arrive_name'] = strlen($data['arrive_name'])?$data['arrive_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                  break;

                case 4:
                  $data['approved_interview_name'] = strlen($data['approved_interview_name'])?$data['approved_interview_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                  break;

                case 5:
                  $data['entry_name'] = strlen($data['entry_name'])?$data['entry_name'].','.$cli_name[$b['client_id']]:$cli_name[$b['client_id']];
                  break;
              }

          }

           return json(['msg' => '修改成功','code' => 0,'data' => $data]);
       }else{
         Db::rollback();
          return json(['msg' => '修改失败,请刷新','code' => 1,'data' => []]);
      }

    }

    public function getComm(){
      //根据id获取沟通
      $id = input('id');
      $comm = new CommunicateModel();
      $data = $comm->getOne(['a.id' => $id])->toArray();
      $cli_comm_model = new ClientComm();
      $cli_comm_list = $cli_comm_model->where(['comm_id' => $id])->select()->toArray();
      foreach ($cli_comm_list as $a => $b) {

         switch ($b['type']) {
           case 1:
           !isset($data['screen_client'])?$data['screen_client'] = []:'';
             $data['screen_client'][] = $b['client_id'];
             break;
           
           case 2:
           !isset($data['arrange_interview_client'])?$data['arrange_interview_client'] = []:'';
             $data['arrange_interview_client'][] = $b['client_id'];
             break;

           case 3:
           !isset($data['arrive_client'])?$data['arrive_client'] = []:'';
             $data['arrive_client'][] = $b['client_id'];
             break;

           case 4:
           !isset($data['approved_interview_client'])?$data['approved_interview_client'] = []:'';
             $data['approved_interview_client'][] = $b['client_id'];
             break;

           case 5:
           !isset($data['entry_client'])?$data['entry_client'] = []:'';
             $data['entry_client'][] = $b['client_id'];
             break;
         }

      }

      if ($data) {
         return json(['msg' => '获取成功','code' => 0,'data' => $data]);
      }
      else{
         return json(['msg' => '获取失败','code' => 0,'data' => []]);
      }
    }

    public function getByUname(){
      //查询招聘负责人列表
      $input = input('get.');
      $comm = new CommunicateModel();
      $where = "date(communicate_time) between '$input[dtfm]' and '$input[dtto]' and ct_user = '$input[uname]'";

      $data = $comm->where($where)->select()->toArray();

      //获取一次数据，处理重复
      if ($data) {
         $comm_data = [];//沟通数据
         $comm_resume_id = [];//是否同一份简历
         $del_ids = [];//处理多余没用的数据的id，mysql里面删掉(针对批量导入多次重复的沟通记录)
      
         foreach ($data as $k => $v) {

            if (in_array($v['content'], $comm_data)) {
              //判断该沟通记录是否已经存在
                $comm_key = array_search($v['content'],$comm_data);
                //把存在对应的key取出来
                // dump($comm_key);
                if($comm_resume_id[$comm_key] == $v['resume_id']){
                  //再查看对应的沟通记录的沟通人和这一条是否一致
                  if (!$data[$k]['screen'] && !$data[$k]['arrange_interview'] && !$data[$k]['arrive'] && !$data[$k]['approved_interview'] && !$data[$k]['entry']) {
                        //判断重复数据里面是否有全零，如果有删除并跳过
                        $del_ids[] = $data[$k]['id'];//记录应该要删除记录的id
                        continue;
                   }

                   if (!$data[$comm_key]['screen'] && !$data[$comm_key]['arrange_interview'] && !$data[$comm_key]['arrive'] && !$data[$comm_key]['approved_interview'] && !$data[$comm_key]['entry']) {
                        //判断全部为0的时候，表示是重复数据，不输出并删除
                        $del_ids[] = $data[$comm_key]['id'];//记录应该删除的记录id
                        continue;
                   }
                }
            }
            $comm_resume_id[$k] = $v['resume_id'];//存储每一次的沟通人记录
            $comm_data[$k] = $v['content'];
         }

         if (!empty($del_ids)) {
          //删除多余的数据
            $del_ids = implode(',',$del_ids);
            $comm->where("id in($del_ids)")->delete();
         }
         
      }

      $where = "date(communicate_time) between '$input[dtfm]' and '$input[dtto]' and a.ct_user = '$input[uname]'";
      $data = $comm->getCommByUname($where);

      return json(['msg' => '获取成功','code' => 0,'data' => $data]);
    }

}
