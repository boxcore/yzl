<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="http://demo.yizhanglian.com/" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?php echo $this->_var['keywords']; ?>" />
<meta name="Description" content="<?php echo $this->_var['description']; ?>" />

<title><?php echo $this->_var['page_title']; ?></title>

<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="image/gif" />
<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />

<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,user.js,transport.js')); ?>

<body>
<?php echo $this->fetch('library/page_header.lbi'); ?>


<?php if ($this->_var['action'] == 'login'): ?>
    <div class="inner_onebanner"></div>
    <div class="interspace"></div>
    <div class="experience_box">
        <h1 class="Skin_home_tittle"></h1>
        <div class="experience_text">
            <h2>填写过联系方式的朋友将得到养肤团队定制专属你的私人养肤方案，以及免费的养肤秘籍哦！</h2>
            <ul class="text_method">
                <li>您只需动手填写您的基本个人信息，便可有机会获得易张脸新品体验装一份；</li>
                <li>关注易张脸官方微信和微博，将可获得易张脸推出的100套新产品体验装中的任一一份。</li>
            </ul>
            <ul class="data">
                <li class="current">请先登录/注册</li>
                <li>填写完整资料</li>
                <li>提交确认</li>
            </ul>
            <div class="news_box box_one">
                <div class="register">
                    <form name="formLogin" action="user.php" method="post" onSubmit="return userLogin()">
                        <table width="340" border="0" cellspacing="0" cellpadding="0" class="input_box change_password  change_passwordbox">
                            <tr>
                                <td width="132">电子邮箱/用户名：</td>
                                <td><input name="username" type="text" size="25" id="username" class="inputBg"></td>
                                <!-- <td><span id="username_notice" style="color:#FF0000"></span></td> -->
                            <tr>
                                <td>密码：</td>
                                <td><input name="password" type="password" id="password1" onblur="check_password(this.value);" onkeyup="checkIntensity(this.value)" class="inputBg" style="width:179px;" /></td>
                                <!-- <td class="number"><span id="password_notice">包含6-16个数字或英文字母</span></td> -->
                            </tr>
                            <?php if ($this->_var['enabled_captcha']): ?><?php endif; ?>
                            <tr>
                                <td><span>验证码：</span></td>
                                <td class="auth"><input type="text" size="8" name="captcha" class="inputBg" /><img src="captcha.php?<?php echo $this->_var['rand']; ?>" alt="captcha" style="vertical-align: middle;cursor: pointer;" onClick="this.src='captcha.php?'+Math.random()" /></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div class="pass_btn">
                                        <input name="Submit" type="submit" value="确  认" />
                                    </div>
                                    <span><a href="user.php?act=get_password">忘记密码？</a></span>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="act" value="act_login" />
                        <input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
                    </form>
                </div>
                <div class="text">
                    <p class="register_text">注册属于您自己的账户，成为易张脸专属会员，以便登陆查看订单状态，迅速结账等各种福利哦。</p>
                    <span class="atonce"><a href="user.php?act=register&rel=exp">马上注册 >></a></span> </div>
            </div>
        </div>
    </div>
    
<?php endif; ?>



    <?php if ($this->_var['action'] == 'default'): ?>
    <div class="inner_fourbanner"></div>
    <div class="interspace"></div>
    <div class="experience_box">
        <h1 class="Skin_home_tittle"></h1>
        <div class="experience_text">
            <h2>填写过联系方式的朋友将得到养肤团队定制专属你的私人养肤方案，以及免费的养肤秘籍哦！</h2>
            <ul class="text_method">
                <li>您只需动手填写您的基本个人信息，便可有机会获得易张脸新品体验装一份；</li>
                <li>关注易张脸官方微信和微博，将可获得易张脸推出的100套新产品体验装中的任一一份。</li>
            </ul>
            <ul class="data_two">
                <li>请先登录/注册</li>
                <li class="current">填写完整资料</li>
                <li>提交确认</li>
            </ul>
            <div class="news_box">
                <table width="880" border="0" cellspacing="0" cellpadding="0" class="data_box data_box_one">
                    <tr>
                        <td width="61">*<strong>姓名：</strong></td>
                        <td width="245"><form>
                                <input name="" type="text" class="name">
                            </form></td>
                        <td width="86">* <strong>性别：</strong></td>
                        <td width="196"><input name="gender" type="radio" value="male">
                            男   &nbsp;  &nbsp;
                            <input name="gender" type="radio" value="famal">
                            女</td>
                        <td width="292">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="61">*<strong>手机：</strong></td>
                        <td width="245"><form>
                                <input name="" type="text" class="name">
                            </form></td>
                        <td width="86">*<strong>生日：</strong></td>
                        <td width="196"><form>
                                <input name="" type="text" class="name">
                            </form></td>
                        <td class="notes"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="mailing_address
    "><strong class="">邮寄地址：</strong>
                            <form>
                                <input name="" type="text" class="name_add">
                            </form></td>
                    </tr>
                </table>
                <div class="which_brand" >
                    <h2>您目前经常使用哪些护肤品牌： </h2>
                    <form>
                        <textarea name="" cols="" rows=""></textarea>
                    </form>
                </div>
                <div class="person_skin">
                    <h2>护肤品购买额度/月： </h2>
                    <form>
                        <span>
                        <input name="skin" type="radio" value="person">
                        300以下 </span><span>
                        <input name="skin" type="radio" value="person">
                        300-500 </span><span>
                        <input name="skin" type="radio" value="person">
                        500-800 </span><span>
                        <input name="skin" type="radio" value="person">
                        1000以上</span>
                    </form>
                </div>
            </div>
            <div class="person_submit">
                <input type="button" value="确 认" name="">
            </div>
        </div>
    </div>
    <?php endif; ?>



<div class="blank"></div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>
<script type="text/javascript">
var process_request = "<?php echo $this->_var['lang']['process_request']; ?>";
<?php $_from = $this->_var['lang']['passport_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
var username_exist = "<?php echo $this->_var['lang']['username_exist']; ?>";
</script>
</html>
