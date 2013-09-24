<div class="foot">
  <ul class="foot_box"> 
      <li><img src="themes/yzl_v2/images/tel.jpg" width="124" height="35"></li>
        <li>
          <ul class="foot_nav">
            <?php $_from = $this->_var['navigator_list']['bottom']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_0_22471700_1380008695');$this->_foreach['nav'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav']['total'] > 0):
    foreach ($_from AS $this->_var['nav_0_22471700_1380008695']):
        $this->_foreach['nav']['iteration']++;
?>
            <li><a<?php if ($this->_var['nav_0_22471700_1380008695']['opennew'] == 1): ?> target="_blank"<?php endif; ?> href="<?php echo $this->_var['nav_0_22471700_1380008695']['url']; ?>"><?php echo $this->_var['nav_0_22471700_1380008695']['name']; ?></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          </ul>
          <p>渝ICP证030173号 ©2013 重庆欧菲诗化妆品有限公司 版权所有</p>
        </li>
        <li class="online_wall">
          <p><a href="#">网上商城  Online wall</a><span><a href="#"><img src="themes/yzl_v2/images/arrow.jpg" width="13" height="13"></a></span>
            </p>
          <ul class="part">
              <li><a href="#"><img src="themes/yzl_v2/images/club.jpg" width="147" height="35"></a></li>
                <li><a href="#"><img src="themes/yzl_v2/images/webo.jpg" width="115" height="35"></a></li>
            </ul>
        </li>
    </ul>
</div>