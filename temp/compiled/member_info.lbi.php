<?php if ($this->_var['user_info']): ?>
欢迎<font class="f4_b"></font>&nbsp;|&nbsp;<a href="user.php?act=logout"><?php echo $this->_var['lang']['user_logout']; ?></a>
<?php else: ?>
<a href="user.php" class="loading_text">登陆</a><a href="user.php?act=register">  注册</a>
<?php endif; ?>