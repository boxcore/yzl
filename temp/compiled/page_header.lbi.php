<div id="head">
  <div class="vitta"></div>
<div class="head_box">
      <div class="top">
        <div class="logo"><a href="#"><img src="themes/yzl_v2/images/logo.jpg" width="303" height="76"></a></div>
<div class="right">
                <div class="right_top_box">
                  <span  class="loading"><a href="#" class="loading_text">登陆</a>  <a href="#">  注册</a></span>
                  <span class="collect_box"><a href="#">用户中心</a><a href="#">订购帮助</a><a class="shop_cart" href="#">我的购物车</a></span>
                    <div class="search"><input name="" type="text"><input name="" type="button" class="btn">                    </div>
                </div> 
                <div class="right_bottom_box">
                  <span class="first_aid"><a href="#">皮肤急救中心</a></span>
                    <span class="ranklist"><a href="#">畅销排行</a></span>
               </div>
            </div>
    </div>
    </div>
</div>  
<div id="nav_one">
  <ul class="nav_one_box">
    <?php $_from = $this->_var['navigator_list']['middle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');$this->_foreach['nav'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav']['total'] > 0):
    foreach ($_from AS $this->_var['nav']):
        $this->_foreach['nav']['iteration']++;
?>
    <li<?php if (($this->_foreach['nav']['iteration'] == $this->_foreach['nav']['total'])): ?> class="no_bg"<?php endif; ?>><a<?php if ($this->_var['nav']['opennew'] == 1): ?> target="_blank"<?php endif; ?> href="<?php echo $this->_var['nav']['url']; ?>"><?php echo $this->_var['nav']['name']; ?></a></li>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  </ul>
</div>
<div id="nav_two">
    <ul>
        <li><a href="#">品牌宣言</a></li>
          <li><a href="#">清洁控油</a></li>
          <li><a href="#">补水锁湿</a></li>
          <li><a href="#">美白提亮</a></li>
          <li><a href="#">抗敏修复</a></li>
          <li><a href="#">逆颜紧致</a></li>
          <li class="selectbox">
              <form>
                  <div class="hide_box">
                  <select>
                    <option value="name">活动专区</option>
                    <option value="content">活动专区</option>
                  </select>
                  </div>
              </form>
          </li>
      </ul> 
</div>