<?php

/**
 * 小滨汽配 生成验证码
 * ============================================================================
 * 版权所有 2015-2016 小滨汽配科技有限公司，并保留所有权利。
 * 网站地址: www.xiaobinqipei.com；
 * ----------------------------------------------------------------------------
 * 仅供学习交流使用，如需商用请购买正版版权。小滨汽配不承担任何法律责任。
 * 踏踏实实做事，堂堂正正做人。
 * ============================================================================
 * $Author: Shadow & 小滨汽配
 * $Id: captcha.php 17217 2016-01-19 06:29:08Z Shadow & 小滨汽配
*/

define('IN_ECS', true);
define('INIT_NO_SMARTY', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/cls_captcha.php');

$img = new captcha(ROOT_PATH . 'data/captcha/', $_CFG['captcha_width'], $_CFG['captcha_height']);
@ob_end_clean(); //清除之前出现的多余输入
if (isset($_REQUEST['is_login']))
{
    $img->session_word = 'captcha_login';
}
$img->generate_image();

?>