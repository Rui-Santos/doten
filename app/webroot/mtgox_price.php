<?php
// Set the content-type
header('Content-Type: image/png');

srand(time());
$bg_color_num = rand() % 2;
$text_color_num = $bg_color_num + 1;
if(rand() % 2)
  $text_color_num = 0;

$text_color[0][0] = 0;
$text_color[0][1] = 0;
$text_color[0][2] = 0;
$text_color[1][0] = 4;
$text_color[1][1] = 99;
$text_color[1][2] = 128;
$text_color[2][0] = 153;
$text_color[2][1] = 105;
$text_color[2][2] = 69;

$bg_color[0][0] = 239;
$bg_color[0][1] = 236;
$bg_color[0][2] = 202;
$bg_color[1][0] = 182;
$bg_color[1][1] = 255;
$bg_color[1][2] = 210;

// Create the image and water
$ground_im = imagecreatetruecolor(800, 200);
$water_im = imagecreatetruecolor(400, 65);

// Create some colors
$white = imagecolorallocate($ground_im, 255, 255, 255);
$grey_0 = imagecolorallocate($ground_im, 128, 128, 128);
$grey_1 = imagecolorallocate($ground_im, 144, 144, 144);
$grey_2 = imagecolorallocate($ground_im, 160, 160, 160);
$textcolor = imagecolorallocate($ground_im, $text_color[$text_color_num][0], $text_color[$text_color_num][1], $text_color[$text_color_num][2]);

imagefilledrectangle($ground_im, 0, 0, 799, 199, $white);
imagefilledrectangle($water_im, 0, 0, 399, 64, $white);

$bgcolor = imagecolorallocate($ground_im, $bg_color[$bg_color_num][0], $bg_color[$bg_color_num][1], $bg_color[$bg_color_num][2]);
imagefill($ground_im, 0, 0, $bgcolor);

$bgcolor = imagecolorallocate($water_im, 255, 255, 255);
imagefill($water_im, 0, 0, $bgcolor);
imagecolortransparent($water_im, $bgcolor);


$url = "https://mtgox.com/code/data/ticker.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
// Set so curl_exec returns the result instead of outputting it.
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:5.0) Gecko/20100101 Firefox/5.0');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
// Get the response and close the channel.
$content = curl_exec($ch);
$response = curl_getinfo($ch);
curl_close($ch);
$test = $content;
$len = strlen($content);
$find = strstr($content, "{");
$content = substr($find, 0);
$content = json_decode($content, 1);

// The text to draw ground
$sell = number_format($content['ticker']['sell'], 3);
$price = number_format($content['ticker']['sell'] * 1.0065 * 6.7 * 1.03, 1);
$ground_text = "实时价格 : " . $sell . "   x   1.0065   x   6.7   x   1.03   =   " . $price . "   RMB/BTC\n";
$ground_text .= "             USD/BTC        点差    RMB/USD  三分利      价格\n";
$ground_text .= "                点拾 doTen      Bitcoin交易平台     SSL 安全连接\n";
$ground_text .= "                               请认准 https://doten.co   \n";


// The text to draw water
$water_text = "【点拾 doTen】【完全面向国内用户】\n          请认准 https://doten.co";

// Replace path by your own font path
$font = './MSYHBD.TTF';

// Add some shadow to the text
@imagettftext($ground_im, 18, 0, 11, 71, $grey_0, $font, $ground_text);
@imagettftext($ground_im, 18, 0, 12, 72, $grey_1, $font, $ground_text);
@imagettftext($ground_im, 18, 0, 13, 73, $grey_2, $font, $ground_text);

// Add the text
@imagettftext($ground_im, 18, 0, 10, 70, $textcolor, $font, $ground_text);
@imagettftext($water_im, 16, 0, 11, 31, $textcolor, $font, $water_text);


$water_w     = 400;//取得水印图片的宽
$water_h     = 65;//取得水印图片的高

$ground_w     = 800;//取得背景图片的宽
$ground_h     = 200;//取得背景图片的高

$w = $water_w;
$h = $water_h;
//随机位置
$posX = rand(0,($ground_w - $w));
$posY = rand(0,($ground_h - $h));

//设定图像的混色模式
imagealphablending($ground_im, 0);
imagecopymerge($ground_im, $water_im, $posX, $posY, 0, 0, $water_w,$water_h, rand() % 20 + 30);
imagepng($ground_im);

// Using imagepng() results in clearer text compared with imagejpeg()
imagepng($ground_im);
imagedestroy($ground_im);
imagedestroy($water_im);

?>