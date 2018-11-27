<?php
/**
 * Created by PhpStorm.
 * User: MAIBENBEN
 * Date: 2018/6/3
 * Time: 14:25
 */
use think\Config;

class Mailer
{
    public function send($to,$subject,$body)
    {
        require_once (ROOT_PATH  . 'extend/phpmailer/PHPMailer.php');
        require_once (ROOT_PATH  . 'extend/phpmailer/SMTP.php');
        require_once (ROOT_PATH  . 'extend/phpmailer/Exception.php');
        $mailer = new \PHPMailer\PHPMailer\PHPMailer();
        $mailer->IsSMTP(); // 启用SMTP
        //$mailer->SMTPSecure ='ssl';
        $mailer->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mailer->SMTPSecure= Config::get('mail.smtpsecure');
        $mailer->Port = Config::get('mail.port');
        $mailer->Host=Config::get('mail.host'); //smtp服务器的名称（这里以QQ邮箱为例）
        $mailer->SMTPAuth = Config::get('mail.smtpauth'); //启用smtp认证
        $mailer->Username = Config::get('mail.username'); //你的邮箱名
        $mailer->Password = Config::get('mail.password') ; //邮箱授权码（注意：这不是邮箱登录密码！）
        $mailer->From = Config::get('mail.from'); //发件人地址（也就是你的邮箱地址）
        $mailer->FromName = Config::get('mail.fromname'); //发件人姓名
        $mailer->AddAddress($to,"尊敬的客户");
        $mailer->WordWrap = 50; //设置每行字符长度
       // $mailer->Port = 465;//端口
        // $mailer->SMTPSecure = "ssl";
        $mailer->IsHTML(Config::get('mail.ishtml')); // 是否HTML格式邮件
        $mailer->CharSet=Config::get('mail.charset'); //设置邮件编码
        $mailer->Subject =$subject; //邮件主题
        $mailer->Body = $body; //邮件内容
        //$mailer->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示

        $mailer->SMTPDebug = 2;

        $mailer->SMTPDebug =Config::get('mail.smtpdebug');

        return $mailer->Send();
    }
}
