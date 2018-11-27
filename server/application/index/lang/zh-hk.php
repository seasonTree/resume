<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27
 * Time: 18:05
 */
return [
//    '按钮1'=>'中文',//表示模板内使用{:lang('按钮1')}获得的字符在中文状态下显示为中文按钮1
//    '按钮2'=>'英文',
//    '按钮3'=>'繁體',
    'test'=>'測試',
    'name'=>'名稱',

//login controller
    'no_active' => '如果您沒有收到郵件，可以點擊重新發送',
    'user' => '用戶',
    'login' => '登錄',
    'register' => '註冊',
    'username' => '用戶名',
    'email' => '郵箱',
    'phone'	=> '手機號',
    'password' => '密碼',
    'password2' => '確認密碼',
    'forgot' => '点击找回密碼?',
    'user_not_null' => '用戶名不能為空',
    'pass_not_null' => '密碼不能為空',
    'pass2_not_null' => '確認密碼不能為空',
    'email_not_null' => '郵箱不能為空',
    'phone_not_null' => '手機不能為空',
    'reset_pass' => '請重置您的密碼',
    'reset_pass2' => '請確認您的重置密碼',
    'verify_code' => '驗證碼',
    'send_email_code' => '發送郵件',
    'commit' => '提交',
    'forgot_info' => '請輸入您的郵箱發送驗證碼去進行重置密碼',
    'check_email_user' => '用戶名和郵箱不匹配',
    'send_email_again' => '重新發送',
    'verify_code_error' => '驗證碼錯誤',
    'two_pass_differ' => '兩次輸入密碼不一致',
    'pass_reset_success' => '密碼重置成功',
    'pass_reset_fail' => '密碼重置失敗',
    'send_email_success' => '發送郵件成功,請轉到郵箱完成密碼恢復操作。',
    'send_email_fail' => '發送郵件失敗',
    'email_body_one' => '您的驗證碼是',
    'email_body_two' => '有效期一個小時,請儘快把驗證碼輸入到網頁上完成找回密碼操作,如果這條資訊不是您本人操作請忽視！',
    'email_title' => '找回密碼',
    'user_not_exist' => '用戶名不存在',
	'user_not_activate' => '帳號未啟動,請到您的郵箱啟動',
	'user_frozen' => '您的帳號已被凍結',
	'user_frozen_seconds' => '10分钟後重試',
	'password_error' => '密碼不正確,超過五次帳號將被凍結十分鐘',
	'verify_success' => '驗證成功',
	'send_phone_code' => '發送手機驗證碼',
	'phone_error' => '手機格式錯誤',

//end login controller 

	//register controller

	'inviting_link_not_exist' => '邀請連結不存在',
	'inviting_link_invalid' => '邀請連結無效',
	'unlawful_request' => '非法請求',
	'user_error' => '用戶資訊不正確',
	'register_fail' => '注册失敗請重新注册',
	'register_title' => '獲取啟動碼',
	'register_email_body' => '注册成功,您的啟動碼是',
	'register_email_body2' => '請點擊該地址啟動您的用戶',
	'register_success' => '注册成功',
	'register_fail' => '郵件發送失敗,請重新注册',
    'country_code'=>'区号',
	'user_length' => '用戶名必須是6到18個字元',
	'user_rule' => '用戶名只能包含大寫字母、小寫字母和數位字元',
	'email_rule' => '郵箱地址格式有誤',
	'user_exist' => '用戶已存在',
	'email_exist' => '郵箱已使用',
	'phone_rule' => '電話號碼格式不正確',
	'pass_length' => '密碼長度必須在6到30之間',
	'user_both' => '不能和用戶名相同',
	'pass_rule' => '密碼由數位字母底線和.組成',

	//end register controller

	//index controller
	'home' => '主頁',
	'language' => '語言',
	'contact' => '聯繫',
	'home_title' => '網站首頁',
	'admin_code' => '管理員推薦註冊碼',
	'user_code' => '用戶推薦註冊碼',
	'login_title' => '已注册請登入',

	//end index controller

	//home controller

	'first_name' => '名字',
	'last_name' => '姓',
	'home_address' => '家庭地址',
	'wechat' => '微信',
	'gender' => '性別',
	'male' => '男',
	'female' => '女',
	'you_profile' => '您的簡介',
	'channel_manager' => '渠道資訊',
	'login_out' => '登出',
	'profile' => '簡介',
	'channel' => '渠道',
	'referral' => '銷售網絡',
	'report' => '交易報告',
	'logout' => '登出',
	'sales' => '銷售',
	'doctors' => '醫生',
	'role' => '角色',
	'click_to_copy' => '點擊複製',
	'click_to_download' => '點擊下載',
	'qc_code' => '二維碼',
	'signup_date' => '註冊日期',
	'export' => '導出',
	

	//end home controller

	//channel controller

	'channel_name' => '渠道名',
	'url_code' => '邀請連結',
	'recommended_type' => '创建时间',
	'operation' => '操作',
	'add' => '添加',
	'del' => '刪除',
	'channel_check' => '最多只能創建10個渠道',
	'channel_exists' => '該渠道已經存在，不能重複添加',
	'copy' => '複製',
	'success' => '成功',
	'fail' => '失敗',
	'new_channel' => '添加',

	//end channel controller
	//end home controller
    'search'=>'搜 索',
    'referrer_manager'=>'推廣管理',
    'create_time'=>'創建時間',
    'add_success'=>'添加成功',
    'channel_empty'=>'渠道名不能為空',
    'update_success'=>'更新成功',
    'short_message'=>'短信驗證碼',
    'send_message'=>'发送短信驗證碼',
    'phone_exist'=>'手機號碼已經存在',
    'short_message_reg'=>'每分鐘最多發壹次，每天最多發十次',
    'short_message_success'=>'驗證碼發送成功，請查收!',
    'forgot_pass'=>'忘記用戶名或密碼',
    'url_cancel'=>'注册链接已经失效',



];