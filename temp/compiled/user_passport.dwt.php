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
        <div class="login_account">
            <h1>账号登陆</h1>
            <form name="formLogin" action="user.php" method="post" onSubmit="return userLogin()">
                <table border="0" cellspacing="0" cellpadding="0" class="input_box">
                    <tr>
                        <td><span>用户名：</span></td>
                        <td><input name="username" type="text" size="25" id="username" class="inputBg"></td>
                        <td><span id="username_notice" style="color:#FF0000"></span></td>
                    </tr>
                    <tr>
                        <td><span>密码：</span></td>
                        <td><input name="password" type="password" id="password1" onblur="check_password(this.value);" onkeyup="checkIntensity(this.value)" class="inputBg" style="width:179px;" /></td>
                        <td class="number"><span id="password_notice">包含6-16个数字或英文字母</span></td>
                    </tr>

                    <?php if ($this->_var['enabled_captcha']): ?>
                    <tr>
                        <td><span>验证码：</span></td>
                        <td class="auth"><input type="text" size="8" name="captcha" class="inputBg" /><img src="captcha.php?<?php echo $this->_var['rand']; ?>" alt="captcha" style="vertical-align: middle;cursor: pointer;" onClick="this.src='captcha.php?'+Math.random()" /></td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php endif; ?>
                    
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2" class="radius_input"><input type="checkbox" value="1" name="remember" id="remember" /><label for="remember"><?php echo $this->_var['lang']['remember']; ?></label></td>
                    </tr>
                    <tr>                     
                        <td>&nbsp;</td>
                        <input type="hidden" name="act" value="act_login" />
                        <input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
                         <td colspan="2" class="btn"><input name="Submit" type="submit" value="" /></td>
                    </tr>
                    <tr><td></td><td>忘记密码？点<a href="user.php?act=get_password" style="color:red;">这里</a>找回密码</td></tr>
                </table>
            </form>
        </div>
    </div>
    
<?php endif; ?>




    <?php if ($this->_var['action'] == 'register'): ?>
    <?php if ($this->_var['shop_reg_closed'] == 1): ?>
    <?php echo $this->_var['lang']['shop_register_closed']; ?>
    <?php else: ?>
    <?php echo $this->smarty_insert_scripts(array('files'=>'utils.js')); ?>
    <div class="inner_onebanner"></div>
    <div class="interspace"></div>
    <div class="experience_box">
        <div class="login_account">
            <h1>欢迎注册易张脸帐号！</h1>
            <p>注册属于您自己的账户，成为易张脸专属会员，以便登陆查看订单状态，
                迅速结账等各种福利哦。</p>
            <form action="user.php" method="post" name="formUser" onsubmit="return register();">
                <table border="0" cellspacing="0" cellpadding="0" class="input_box">
                    <tr>
                        <td><span>用户名：</span></td>
                        <td><input name="username" type="text" size="25" id="username" onblur="is_registered(this.value);" class="inputBg"></td>
                        <td><span id="username_notice" style="color:#FF0000"></span></td>
                    </tr>
                    <tr>
                        <td><span>电子邮箱：</span></td>
                        <td><input name="email" type="text" size="25" id="email" onblur="checkEmail(this.value);"  class="inputBg" /></td>
                        <td><span id="email_notice" style="color:#FF0000"></span></td>
                    </tr>
                    <tr>
                        <td><span>密码：</span></td>
                        <td><input name="password" type="password" id="password1" onblur="check_password(this.value);" onkeyup="checkIntensity(this.value)" class="inputBg" style="width:179px;" /></td>
                        <td class="number"><span id="password_notice">包含6-16个数字或英文字母</span></td>
                    </tr>
                    <tr>
                        <td><span>确认密码：</span></td>
                        <td><input name="confirm_password" type="password" id="conform_password" onblur="check_conform_password(this.value);"  class="inputBg" style="width:179px;" /></td>
                        <td><span style="color:#FF0000" id="conform_password_notice"></span></td>
                    </tr>

                    <?php if ($this->_var['enabled_captcha']): ?>
                    <tr>
                        <td><span>验证码：</span></td>
                        <td class="auth"><input type="text" size="8" name="captcha" class="inputBg" /><img src="captcha.php?<?php echo $this->_var['rand']; ?>" alt="captcha" style="vertical-align: middle;cursor: pointer;" onClick="this.src='captcha.php?'+Math.random()" /></td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php endif; ?>
                    
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="2" class="radius_input"><input name="agreement" type="checkbox" value="1" checked="checked" />
                            我已阅读并接受<a href="#">《易张脸用户协议》</a></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <input type="hidden" name="act" value="act_register" >
                        <input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
                        <td colspan="2" class="btn"><input name="Submit" type="submit" value="" /></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <?php endif; ?>
  <?php endif; ?>




<?php if ($this->_var['action'] == 'oldlogin'): ?>
<div class="usBox clearfix">
  <div class="usBox_1 f_l">
   <div class="logtitle"></div>
   <form name="formLogin" action="user.php" method="post" onSubmit="return userLogin()">
        <table width="100%" border="0" align="left" cellpadding="3" cellspacing="5">
          <tr>
            <td width="15%" align="right"><?php echo $this->_var['lang']['label_username']; ?></td>
            <td width="85%"><input name="username" type="text" size="25" class="inputBg" /></td>
          </tr>
          <tr>
            <td align="right"><?php echo $this->_var['lang']['label_password']; ?></td>
            <td>
            <input name="password" type="password" size="15"  class="inputBg"/>
            </td>
          </tr>
          <?php if ($this->_var['enabled_captcha']): ?>
          <tr>
            <td align="right"><?php echo $this->_var['lang']['comment_captcha']; ?></td>
            <td><input type="text" size="8" name="captcha" class="inputBg" />
            <img src="captcha.php?is_login=1&<?php echo $this->_var['rand']; ?>" alt="captcha" style="vertical-align: middle;cursor: pointer;" onClick="this.src='captcha.php?is_login=1&'+Math.random()" /> </td>
          </tr>
          <?php endif; ?>
          <tr>
            <td colspan="2"><input type="checkbox" value="1" name="remember" id="remember" /><label for="remember"><?php echo $this->_var['lang']['remember']; ?></label></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="left">
            <input type="hidden" name="act" value="act_login" />
            <input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
            <input type="submit" name="submit" value="" class="us_Submit" />
            </td>
          </tr>
    <tr><td></td><td><a href="user.php?act=qpassword_name" class="f3"><?php echo $this->_var['lang']['get_password_by_question']; ?></a>&nbsp;&nbsp;&nbsp;<a href="user.php?act=get_password" class="f3"><?php echo $this->_var['lang']['get_password_by_mail']; ?></a></td></tr>
      </table>
    </form>
  </div>
  <div class="usTxt">
    <strong><?php echo $this->_var['lang']['user_reg_info']['0']; ?></strong>  <br />
    <strong class="f4"><?php echo $this->_var['lang']['user_reg_info']['1']; ?>：</strong><br />
    <?php if ($this->_var['car_off'] == 1): ?>
    <?php echo $this->_var['lang']['user_reg_info']['2']; ?><br />
    <?php endif; ?>
    <?php if ($this->_var['car_off'] == 0): ?>
    <?php echo $this->_var['lang']['user_reg_info']['8']; ?><br />
    <?php endif; ?>
    <?php echo $this->_var['lang']['user_reg_info']['3']; ?>：<br />
    1. <?php echo $this->_var['lang']['user_reg_info']['4']; ?><br />
    2. <?php echo $this->_var['lang']['user_reg_info']['5']; ?><br />
    3. <?php echo $this->_var['lang']['user_reg_info']['6']; ?><br />
    4. <?php echo $this->_var['lang']['user_reg_info']['7']; ?>  <br />
    <a href="user.php?act=register"><img src="themes/yzl_v1/images/bnt_ur_reg.gif" /></a>
  </div>
</div>
<?php endif; ?>



    <?php if ($this->_var['action'] == 'get_password'): ?>
    <?php echo $this->smarty_insert_scripts(array('files'=>'utils.js')); ?>
    <script type="text/javascript">
    <?php $_from = $this->_var['lang']['password_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
      var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </script>
<div class="usBox">
  <div class="usBox_2 clearfix">
    <form action="user.php" method="post" name="getPassword" onsubmit="return submitPwdInfo();">
        <br />
        <table width="70%" border="0" align="center">
          <tr>
            <td colspan="2" align="center"><strong><?php echo $this->_var['lang']['username_and_email']; ?></strong></td>
          </tr>
          <tr>
            <td width="29%" align="right"><?php echo $this->_var['lang']['username']; ?></td>
            <td width="61%"><input name="user_name" type="text" size="30" class="inputBg" /></td>
          </tr>
          <tr>
            <td align="right"><?php echo $this->_var['lang']['email']; ?></td>
            <td><input name="email" type="text" size="30" class="inputBg" /></td>
          </tr>
          <tr>
            <td></td>
            <td><input type="hidden" name="act" value="send_pwd_email" />
              <input type="submit" name="submit" value="<?php echo $this->_var['lang']['submit']; ?>" class="bnt_blue" style="border:none;" />
              <input name="button" type="button" onclick="history.back()" value="<?php echo $this->_var['lang']['back_page_up']; ?>" style="border:none;" class="bnt_blue_1" />
	    </td>
          </tr>
        </table>
        <br />
      </form>
  </div>
</div>
<?php endif; ?>


    <?php if ($this->_var['action'] == 'qpassword_name'): ?>
<div class="usBox">
  <div class="usBox_2 clearfix">
    <form action="user.php" method="post">
        <br />
        <table width="70%" border="0" align="center">
          <tr>
            <td colspan="2" align="center"><strong><?php echo $this->_var['lang']['get_question_username']; ?></strong></td>
          </tr>
          <tr>
            <td width="29%" align="right"><?php echo $this->_var['lang']['username']; ?></td>
            <td width="61%"><input name="user_name" type="text" size="30" class="inputBg" /></td>
          </tr>
          <tr>
            <td></td>
            <td><input type="hidden" name="act" value="get_passwd_question" />
              <input type="submit" name="submit" value="<?php echo $this->_var['lang']['submit']; ?>" class="bnt_blue" style="border:none;" />
              <input name="button" type="button" onclick="history.back()" value="<?php echo $this->_var['lang']['back_page_up']; ?>" style="border:none;" class="bnt_blue_1" />
	    </td>
          </tr>
        </table>
        <br />
      </form>
  </div>
</div>
<?php endif; ?>


    <?php if ($this->_var['action'] == 'get_passwd_question'): ?>
<div class="usBox">
  <div class="usBox_2 clearfix">
    <form action="user.php" method="post">
        <br />
        <table width="70%" border="0" align="center">
          <tr>
            <td colspan="2" align="center"><strong><?php echo $this->_var['lang']['input_answer']; ?></strong></td>
          </tr>
          <tr>
            <td width="29%" align="right"><?php echo $this->_var['lang']['passwd_question']; ?>：</td>
            <td width="61%"><?php echo $this->_var['passwd_question']; ?></td>
          </tr>
          <tr>
            <td align="right"><?php echo $this->_var['lang']['passwd_answer']; ?>：</td>
            <td><input name="passwd_answer" type="text" size="20" class="inputBg" /></td>
          </tr>
          <?php if ($this->_var['enabled_captcha']): ?>
          <tr>
            <td align="right"><?php echo $this->_var['lang']['comment_captcha']; ?></td>
            <td><input type="text" size="8" name="captcha" class="inputBg" />
            <img src="captcha.php?is_login=1&<?php echo $this->_var['rand']; ?>" alt="captcha" style="vertical-align: middle;cursor: pointer;" onClick="this.src='captcha.php?is_login=1&'+Math.random()" /> </td>
          </tr>
          <?php endif; ?>
          <tr>
            <td></td>
            <td><input type="hidden" name="act" value="check_answer" />
              <input type="submit" name="submit" value="<?php echo $this->_var['lang']['submit']; ?>" class="bnt_blue" style="border:none;" />
              <input name="button" type="button" onclick="history.back()" value="<?php echo $this->_var['lang']['back_page_up']; ?>" style="border:none;" class="bnt_blue_1" />
	    </td>
          </tr>
        </table>
        <br />
      </form>
  </div>
</div>
<?php endif; ?>

<?php if ($this->_var['action'] == 'reset_password'): ?>
    <script type="text/javascript">
    <?php $_from = $this->_var['lang']['password_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
      var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </script>
<div class="usBox">
  <div class="usBox_2 clearfix">
    <form action="user.php" method="post" name="getPassword2" onSubmit="return submitPwd()">
      <br />
      <table width="80%" border="0" align="center">
        <tr>
          <td><?php echo $this->_var['lang']['new_password']; ?></td>
          <td><input name="new_password" type="password" size="25" class="inputBg" /></td>
        </tr>
        <tr>
          <td><?php echo $this->_var['lang']['confirm_password']; ?>:</td>
          <td><input name="confirm_password" type="password" size="25"  class="inputBg"/></td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input type="hidden" name="act" value="act_edit_password" />
            <input type="hidden" name="uid" value="<?php echo $this->_var['uid']; ?>" />
            <input type="hidden" name="code" value="<?php echo $this->_var['code']; ?>" />
            <input type="submit" name="submit" value="<?php echo $this->_var['lang']['confirm_submit']; ?>" />
          </td>
        </tr>
      </table>
      <br />
    </form>
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
