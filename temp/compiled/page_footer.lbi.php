<div class="clear clearfix"></div>

   <?php if ($this->_var['navigator_list']['bottom1']): ?>
   <?php $_from = $this->_var['navigator_list']['bottom']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');$this->_foreach['nav_bottom_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_bottom_list']['total'] > 0):
    foreach ($_from AS $this->_var['nav']):
        $this->_foreach['nav_bottom_list']['iteration']++;
?>
        <a href="<?php echo $this->_var['nav']['url']; ?>" <?php if ($this->_var['nav']['opennew'] == 1): ?> target="_blank" <?php endif; ?>><?php echo $this->_var['nav']['name']; ?></a>
        <?php if (! ($this->_foreach['nav_bottom_list']['iteration'] == $this->_foreach['nav_bottom_list']['total'])): ?>
           -
        <?php endif; ?>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  <?php endif; ?>

<script src="<?php echo $this->_var['ecs_themes_path']; ?>/js/slides.jquery.js"></script>
<script src="<?php echo $this->_var['ecs_themes_path']; ?>/js/play.min.js"></script>
<div id="foot_inner">
    <div class="foot">
        <ul class="foot_box">
            <li><img src="themes/yzl_v1/images/tel.jpg" width="124" height="35"></li>
            <li>
                <ul class="foot_nav">
                    <?php $_from = $this->_var['navigator_list']['bottom']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');$this->_foreach['nav'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav']['total'] > 0):
    foreach ($_from AS $this->_var['nav']):
        $this->_foreach['nav']['iteration']++;
?>
                    <li><a<?php if ($this->_var['nav']['opennew'] == 1): ?> target="_blank"<?php endif; ?> href="<?php echo $this->_var['nav']['url']; ?>"><?php echo $this->_var['nav']['name']; ?></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
                <p><?php if ($this->_var['icp_number']): ?><?php echo $this->_var['icp_number']; ?> <?php endif; ?>©2013 重庆欧菲诗化妆品有限公司 版权所有<?php if ($this->_var['stats_code']): ?><span><?php echo $this->_var['stats_code']; ?></span><?php endif; ?></p>
            </li>



            <div class="pop_box">
                <div class="web_shop" style="display: none;">
                    <div class="web_shop_left">
                        <h1>欢迎进入易张脸官方旗舰店铺</h1>
                        <div class="web_shop_box" id="slidesSkincare">
                            <!-- <span class="left_arrows"><img src="themes/yzl_v1/images/web-city-allowsleft.jpg" width="27" height="36"></span> -->

                            <div class="slides_container">
                                <ul class="slide brands">
                                    <li><a href="#"><img src="themes/yzl_v1/images/taobao_wall.jpg" width="192" height="77"></a>
                                        <p><a href="#">天猫商城-易张脸官方旗舰店</a></p>
                                    </li>
                                    <li><a href="#"><img src="themes/yzl_v1/images/taobao_wall.jpg" width="192" height="77"></a>
                                        <p><a href="#">天猫商城-易张脸官方旗舰店</a></p>
                                    </li>
                                    <li><a href="#"><img src="themes/yzl_v1/images/taobao_wall.jpg" width="192" height="77"></a>
                                        <p><a href="#">天猫商城-易张脸官方旗舰店</a></p>
                                    </li>
                                </ul>
                                <ul class="slide brands">
                                    <li><a href="#"><img src="themes/yzl_v1/images/taobao_wall.jpg" width="192" height="77"></a>
                                        <p><a href="#">1111</a></p>
                                    </li>
                                    <li><a href="#"><img src="themes/yzl_v1/images/taobao_wall.jpg" width="192" height="77"></a>
                                        <p><a href="#">2222天猫商城-易张脸官方旗舰店</a></p>
                                    </li>
                                    <li><a href="#"><img src="themes/yzl_v1/images/taobao_wall.jpg" width="192" height="77"></a>
                                        <p><a href="#">333天猫商城-易张脸官方旗舰店</a></p>
                                    </li>
                                </ul>
                                <ul class="slide brands">
                                    <li><a href="#"><img src="themes/yzl_v1/images/taobao_wall.jpg" width="192" height="77"></a>
                                        <p><a href="#">444天猫商城-易张脸官方旗舰店</a></p>
                                    </li>
                                    <li><a href="#"><img src="themes/yzl_v1/images/taobao_wall.jpg" width="192" height="77"></a>
                                        <p><a href="#">555天猫商城-易张脸官方旗舰店</a></p>
                                    </li>
                                    <li><a href="#"><img src="themes/yzl_v1/images/taobao_wall.jpg" width="192" height="77"></a>
                                        <p><a href="#">666天猫商城-易张脸官方旗舰店</a></p>
                                    </li>
                                </ul>
                            </div>
                            <div class="arrow prev skinPrev"><a class="bgpng" href="javascript:void(0);"><img src="themes/yzl_v1/images/web-city-allowsleft.jpg" width="27" height="36"></a></div>
                            <div class="arrow next skinNext"><a class="bgpng" href="javascript:void(0);"><img src="themes/yzl_v1/images/web-city-allowsleft-02.jpg" width="27" height="36"></a></div>    
                            <!-- <span class="right_arrows"><img src="themes/yzl_v1/images/web-city-allowsleft-02.jpg" width="27" height="36"></span> -->
                        </div>
                    </div>
                    <div class="web_shop_right">
                        <h1><strong>欢迎扫描二维码</strong><span><a href="#">MORE&gt;</a></span></h1>
                        <div class="web_code_box fxplay">
                            <div class="web_show_code play-box">
                                <div class="play-item">
                                    <img src="themes/yzl_v1/images/web-code.jpg" width="72" height="72">
                                    <a href="#">天猫商城-易张脸官方旗舰店</a>
                                </div>
                                 <div class="play-item">
                                   <img src="themes/yzl_v1/images/web-code.jpg" width="72" height="72">
                                    <a href="#">222</a>
                                </div>
                                <div class="play-item">
                                    <img src="themes/yzl_v1/images/web-code.jpg" width="72" height="72">
                                    <a href="#">333</a>
                                </div>
                            </div>

                            <div id="playNo"></div>

                            <!-- <ul class="web_spot"> -->
                                <!-- <li><a href="#"></a></li>
                                <li><a href="#"><img src="themes/yzl_v1/images/web_yellow.jpg" width="9" height="9"></a></li>
                                <li><a href="#"><img src="themes/yzl_v1/images/web_yellow.jpg" width="9" height="9"></a></li> -->
                            <!-- </ul> -->
                        </div>
                        <script>
                            //
                            $(function() {
                                $(".web_spot li").hover(function(){
                                    //alert(1111);

                                },function(){
                                    //alert(222222);
                                });
                            });

                            //网店轮播
                            $(function() {       
                              $('#slidesSkincare').slides({
                                prev: 'skinPrev',
                                next: 'skinNext',
                                generatePagination: false
                              });
                            });

                            //二维码轮播区域设置
                            $('.fxplay').fxuiPlay({
                                qq:1144042682,          //作者QQ号
                                prev:$('#prev'),     //上一张
                                next:$('#next'),     //下一张
                                no:$('#playNo'),     //是否开启数字
                                auto:false,           //是否自动播放
                                autotime:3000,       //自动播放间隔
                                effect:0,            //特效类型 0：渐变；1：变小 ;2:左右; 3:上下;
                                efftime:400,         //渐变时间
                                ismobi:false,         //如果手机端请传ture,会开启划动的操作。
                                evt:'click'          //click(默认)和hover/mouserover
                            });

                        </script>
                    </div>
                </div>
            </div>



            <li class="online_wall">
                <p><a class="pointer" href="javascript:;">网上商城  Online wall</a><span><a href="javascript:;" class="pointer"><img src="themes/yzl_v1/images/arrow.jpg" width="13" height="13"></a></span> </p>
                <ul class="part">
                    <li><a href="#"><img src="themes/yzl_v1/images/club.jpg" width="147" height="35"></a></li>
                    <li><a href="#"><img src="themes/yzl_v1/images/webo.jpg" width="115" height="35"></a></li>
                </ul>
            </li>
            <script>
                $(function() {
                    $('.online_wall a.pointer').click(function() {
                        $('.web_shop').slideToggle();
                    });
                });
            </script>
        </ul>
    </div>
</div>