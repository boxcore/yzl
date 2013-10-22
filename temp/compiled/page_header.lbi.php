<script src="<?php echo $this->_var['ecs_themes_path']; ?>/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
var process_request = "<?php echo $this->_var['lang']['process_request']; ?>";
</script>

<div id="head">
    <div class="vitta"></div>
    <div class="head_box">
        <div class="top">
            <div class="logo"><a href="index.php"><img src="themes/yzl_v1/images/logo.jpg" width="303" height="76"></a></div>
            <div class="right">
              <?php echo $this->smarty_insert_scripts(array('files'=>'transport1.js,utils.js')); ?>
                <div class="right_top_box"> <span  class="loading"><?php 
$k = array (
  'name' => 'member_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?></span>
                    <span class="collect_box"><a href="user.php">用户中心</a><a href="#">订购帮助</a><a class="shop_cart" href="#">我的购物车</a></span>
                    
                    <script>
                    
                    <!--
                    function checkSearchForm()
                    {
                        if(document.getElementById('keyword').value)
                        {
                            return true;
                        }
                        else
                        {
                            alert("<?php echo $this->_var['lang']['no_keywords']; ?>");
                            return false;
                        }
                    }
                    -->
                    
                    </script> 
                    <form id="searchForm" name="searchForm" method="get" action="search.php" onSubmit="return checkSearchForm()" class="f_r"  style="_position:relative; top:5px;">
                      <div class="search">
                          <input name="keywords" type="text" id="keyword" value="<?php echo htmlspecialchars($this->_var['search_keywords']); ?>" />
                          <input type="submit" class="btn" value="" class="go" style="cursor:pointer;" />
                      </div>
                    </form>
                    
                </div>
                <div class="right_bottom_box"> <span class="first_aid"><a href="/topic.php?topic_id=2">皮肤急救中心</a></span>
                    <div id="lang" class="ranklist">
                        <p><a class="tg0">畅销排行</a></p>
                        <p><a class="tg1">畅销排行</a></p>
                        <ul>
                            <li class="last"><a href="#">眼部护理</a></li>
                            <li><a href="http://www.baidu.com/">养肤面贴膜</a></li>
                            <li><a href="http://www.g.cn/">养肤水</a></li>
                            <li><a href="#">精华液</a></li>
                            <li><a href="#"> 养肤面霜</a></li>
                            <li><a href="#">面膜泥/冻膜 </a></li>
                            <li ><a href="http://www.baidu.com/">养肤洁面乳</a></li>
                            
                        </ul>
                    </div>
                    <script src="<?php echo $this->_var['ecs_themes_path']; ?>/js/best_sale.js" type="text/javascript"></script>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="nav_one">
    <ul class="nav_one_box">
         
        <li>
            <a href="page/9">花本养肤</a>
            <div class="nav_one_container">
                <span class="line"></span>
                <div class="nav_item" id="nav_item_bg_1">
                    <ul class="left">
                        <li ><a href="/page/10">花神之说</a></li>
                        <li><a href="/page/11">传承千年</a></li>
                    </ul>
                    <div class="right">
                        <h1>花神不老颜，<br>掀起以花养颜千年历史。</h1>
                        <P>以天然珍稀花卉作为护肤上品，一直是古今中外宫廷贵族口口相传的驻颜秘笈。借由花神之说，易张脸深度挖掘花本养颜悠久历史，缔造天然花本精粹护肤的奢华传奇…</P>
                    </div>
                </div>
            </div>
        </li>
         
        <li>
            <a href="page/myjt">美颜讲堂</a>
            <div class="nav_one_container">
                <span class="line"></span>
                <div class="nav_item" id="nav_item_bg_2">
                    <ul class="left">
                        <li ><a href="#">肤质解析堂</a></li>
                        <li><a href="#">养肤常识堂</a></li>
                        <li><a href="#">花本养肤堂</a></li>

                    </ul>
                    <div class="right">
                        <h1>" 花养颜娇颜如花 "</h1>
                        <P>以天然珍稀花卉作为护肤上品，一直是古今中外宫廷贵族口口相传的驻颜秘笈。借由花神之说，易张脸深度挖掘花本养颜悠久历史，缔造天然花本精粹护肤的奢华传奇…</P>
                    </div>
                </div>
            </div>
        </li>
         
        <li>
            <a href="page/13">易颜之法</a>
            <div class="nav_one_container nav_one_container_last">
                <span class="line2"></span>
                <div class="nav_item" id="nav_item_bg_3">
                    <ul class="left">
                        <li ><a href="#">公司介绍</a></li>
                        <li><a href="#">科研实力</a></li>
                        <li><a href="#">生产工艺</a></li>
                    </ul>
                    <div class="right">
                        <h1>自然、安全、化繁为简</h1>
                        <P>以天然珍稀花卉作为护肤上品，一直是古今中外宫廷贵族口口相传的驻颜秘笈。借由花神之说，易张脸深度挖掘花本养颜悠久历史，缔造天然花本精粹护肤的奢华传奇…</P>
                    </div>
                </div>
            </div>
        </li>
         
        <li class="no_bg">
            <a href="page/14">美自天然</a>
            <div class="nav_one_container nav_one_container_last">
                <span class="line2"></span>
                <div class="nav_item" id="nav_item_bg_4">
                    <ul class="left">
                        <li ><a href="#">公司介绍</a></li>
                        <li><a href="#">科研实力</a></li>
                        <li><a href="#">生产工艺</a></li>
                    </ul>
                    <div class="right">
                        <h1>自然、安全、化繁为简</h1>
                        <P>以天然珍稀花卉作为护肤上品，一直是古今中外宫廷贵族口口相传的驻颜秘笈。借由花神之说，易张脸深度挖掘花本养颜悠久历史，缔造天然花本精粹护肤的奢华传奇…</P>
                    </div>
                </div>
            </div>
        </li>
            </ul>
    
</div>
<script>
    $(function() {
        $(".nav_one_box > li ").hover(function(){
            $(this).find(".nav_one_container").attr({style:"display:block"});
        },function(){
            $(this).find(".nav_one_container").css("display","none");
        });

        $(".nav_item .left > li ").hover(function(){
            $(this).addClass("current");
        },function(){
            $(this).removeClass("current");
        });
    });
</script>
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