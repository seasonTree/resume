<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/6
 * Time: 9:46
 */
require_once 'phpqrcode/phpqrcode.php';
class QueryingCode
{
    public function makeQueryingCode($url = '', $logoPath, $option = '', $filename = '')
    {
        $value = $url; //二维码内容
        $errorCorrectionLevel = 'H'; //容错级别
        $matrixPointSize = 6.8; //生成图片大小
        //生成二维码图片
        ob_start();
        QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize, 2);
        $ob_contents = ob_get_contents(); //读取缓存区数据
        ob_end_clean();
        $QR = imagecreatefromstring($ob_contents);
        $logo = $logoPath; //准备好的logo图片
        if ($logo !== false) {
            $QR = imagecreatefromstring($ob_contents); //目标图象连接资源。
            $logo = imagecreatefromstring(file_get_contents($logo)); //源图象连接资源。
            $QR_width = imagesx($QR); //二维码图片宽度
            $QR_height = imagesy($QR); //二维码图片高度
            $logo_width = imagesx($logo); //logo图片宽度
            $logo_height = imagesy($logo); //logo图片高度
            $logo_qr_width = $QR_width / 4; //组合之后logo的宽度(占二维码的1/5)
            $scale = $logo_width / $logo_qr_width; //logo的宽度缩放比(本身宽度/组合后的宽度)
            $logo_qr_height = $logo_height / $scale; //组合之后logo的高度
            $from_width = ($QR_width - $logo_qr_width) / 2; //组合之后logo左上角所在坐标点
            //重新组合图片并调整大小
            /*
             *    imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
             */
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

        }
        if ($option == '') {
            //输出图片
            Header("Content-type: image/png");
            imagepng($QR);
            exit;
        } else {
            if ($option == 'sales') {
                $bg = dirname(Env::get('ROOT_PATH')).config('template.tpl_replace_string.__basePath__').'/dist/image/sales_qrcode_bg.jpg';
            } else {
                $bg = dirname(Env::get('ROOT_PATH')).config('template.tpl_replace_string.__basePath__').'/dist/image/doctor_qrcode_bg.jpg';
            }
            $img_bg = imagecreatefromstring(file_get_contents($bg));
            //list($qCodeWidth, $qCodeHight, $qCodeType) = getimagesize($QR);
            imagecopymerge($img_bg, $QR, 85, 270, 0, 0, $QR_width, $QR_height, 100);
            $mime = 'application/force-download';
            header('Pragma: public'); // required
            header('Expires: 0'); // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);
            header('Content-Disposition: attachment; filename=' . $filename . '.jpg');
            header('Content-Transfer-Encoding: binary');
            header('Connection: close');
            echo imagejpeg($img_bg); // push it out
            exit;
        }
    }

    public function createQrCode($url, $logo_url, $option = '')
    {
        //生成二维码
        $value = $url; //二维码内容
        $errorCorrectionLevel = 'H'; //容错级别
        $matrixPointSize = 6.8; //生成图片大小
        if (!is_dir('.' . DIRECTORY_SEPARATOR . 'qr_code')) {
            $file_path = './qr_code';
            mkdir($file_path);
            chown($file_path, 777);
        }

        $file = 'qr_code/' . time() . mt_rand(0, 9999) . '.png';

        //生成二维码图片
        QRcode::png($value, $file, $errorCorrectionLevel, $matrixPointSize, 2);
        if (!file_exists($file)) {
            return false;
        }
        $QR = $file;
        $logo = $logo_url; //准备好的logo图片
        if ($logo !== false) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR); //二维码图片宽度
            $QR_height = imagesy($QR); //二维码图片高度
            $logo_width = imagesx($logo); //logo图片宽度
            $logo_height = imagesy($logo); //logo图片高度
            $logo_qr_width = $QR_width / 4;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);

        }
        //输出图片
        imagepng($QR, $file);
        if ($option == '') {
            return "http://$_SERVER[SERVER_NAME]/$file";
        } else {
            return $file;
        }

        // echo "<img src='http://$_SERVER[SERVER_NAME]/$file'>";
    }

    public function makeQrCode($url = '')
    {
        QRcode::png($url);
    }
}
