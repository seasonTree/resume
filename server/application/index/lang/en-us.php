<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27
 * Time: 18:05
 */
return [
//    '按钮1'=>'Chinese',//表示模板内使用{:lang('按钮1')}获得的字符在英文状态下显示为button1
//    '按钮2'=>'English',
//    '按钮3'=>'complex',
    'test'=>'TEST',
    'name'=>'NAME',
//login controller
    'no_active' => 'If you have not received the email, you can click to resend it.',
    'user' => 'User',
    'login' => 'Log In',
    'register' => ' Registration',
    'username' => 'UserName',
    'email' => 'Email',
    'phone'	=> 'Phone',
    'password' => 'Password',
    'password2' => 'Confirm password',
    'forgot' => 'Find it here?',
    'user_not_null' => 'The username can not be empty',
    'pass_not_null' => 'The password can not be empty',
    'pass2_not_null' => 'The confirm password can not be empty',
    'reset_pass' => 'Please reset your password',
    'reset_pass2' => 'Please confirm your password',
    'email_not_null' => 'The email can not be empty', 
    'phone_not_null' => 'The phone can not be empty',
    'verify_code' => 'Verification Code',
    'send_email_code' => 'Send Email',
    'commit' => 'Submit',
    'forgot_info' => 'Please enter your email and we will send Verification Code to reset your password.',
    'check_email_user' => 'Mismatch between username and mailbox',
    'send_email_again' => 'Resend',
    'verify_code_error' => 'Verification code error',
    'two_pass_differ' => 'Two passwords do not match',
    'pass_reset_success' => 'Password reset success',
    'pass_reset_fail' => 'Password reset fail',
    'send_email_success' => 'Send the mail successfully, please go to your mailbox to complete the password recovery operation.',
    'send_email_fail' => 'Sending mail failure',
    'email_body_one' => 'Your validation code is',
    'email_body_two' => 'The validity period is one hour, please input the authentication code to the web page as soon as possible to complete the password retrieval operation, if this information is not your own operation please ignore!',
    'email_title' => 'Retrieve the password',
    'user_not_exist' => 'Username does not exist ',
	'user_not_activate' => 'Account is not activated. Please go to your mailbox to activate.',
	'user_frozen' => 'Your account has been frozen ',
	'user_frozen_seconds' => 'ten minutes retry ',
	'password_error' => 'Password is incorrect, more than five times the account will be frozen for ten minutes.',
	'verify_success' => 'Verifies success',
	'send_phone_code' => 'Send Verification Code',
	'phone_error' => 'Phone Error',

	//end login controller

	//register controller

	'inviting_link_not_exist' => 'invitation link does not exist ',
	'inviting_link_invalid' => 'inviting link invalid ',
	'unlawful_request' => 'unlawful request ',
	'user_error' => 'user information is incorrect ',
	'register_fail' => 'registration failure please register ',
	'register_title' =>'get activation code ',
	'register_email_body' => 'registration is successful, your activation code is',
	'register_email_body2' => 'please click on this address to activate your user',
	'register_success' => 'registration is successful',
	'register_fail' => 'Mail failed. Please re register',
    'country_code'=>'Country Code',
	'user_length' => 'Username must be 6 to 18 characters',
	'user_rule' => 'Username can only contain uppercase, lowercase and numeric characters',
	'email_rule' => 'Invalid email format',
	'user_exist' => 'Username already used',
	'email_exist' => 'mailbox has been used ',
	'phone_rule' => 'the phone number is not properly format ',
	'pass_length' => 'Password must be 6 to 30 characters',
	'user_both' => 'can not be the same as the username ',
	'pass_rule' => 'password is underlined by digital letters',

	//end register controller

	//index controller
	'home' => 'Home',
	'language' => 'Language',
	'contact' => 'Contact',
	'home_title' => 'Web page',
	'admin_code' => 'Administrator recommends the registration code',
	'user_code' => 'User recommended registration code',
	'login_title' => 'Registered please login',

	//end index controller


	//home controller

	'first_name' => 'First Name',
	'last_name' => 'Last Name',
	'home_address' => 'Home Address',
	'wechat' => 'Wechat',
	'gender' => 'Gender',
	'male' => 'Male',
	'female' => 'Female',
	'you_profile' => 'You Profile',
	'channel_manager' => 'Channel Master',
	'login_out' => 'LoginOut',
	'profile' => 'Profile',
	'channel' => 'Channel',
	'referral' => 'Sales Network',
	'report' => 'Transaction Report',
	'logout' => 'Logout',
	'sales' => 'Sales',
	'doctors' => 'Doctors',
	'role' => 'Role',
	'click_to_copy' => 'Click to copy',
	'click_to_download' => 'Click to download',
	'qc_code' => 'QR_Code',
	'signup_date' => 'Signup Date',
	'export' => 'Export',

	//end home controller

	//channel controller

	'channel_name' => 'Channel Name',
	'url_code' => 'URL',
	'recommended_type' => 'Create Time',
	'operation' => 'Operation',
	'add' => 'Add',
	'del' => 'Delete',
	'channel_check' => 'Create 10 channels at most',
	'channel_exists' =>'The channel already exists and cannot be added repeatedly',
	'copy' => 'Copy',
	'success' => 'Success',
	'fail' => 'Fail',
	'new_channel' => 'New Channel',

	//end channel controller

	//end home controller
    'search'=>'Search',
    'referrer_manager'=>'Referrer Manager',
    'create_time'=>'Create Time',
    'add_success'=>'add success',
    'channel_empty'=>'channel name is not empty',
    'update_success'=>'Your Profile update success',
    'short_message'=>'message authentication code',
    'send_message'=>'Send SMS verification code',
    'phone_exist'=>'The phone number is already exist',
    'short_message_reg'=>'Up to once a minute, 10 times a day',
    'short_message_success'=>'The verification code was sent successfully, please check!',
    'forgot_pass'=>'Lost your username or password?',
    'url_cancel'=>'This channel is deactivated',


];