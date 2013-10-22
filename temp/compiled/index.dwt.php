<!DOCTYPE HTML>
<html lang="en-US">
<head>
<base href="http://demo.yizhanglian.com/" />
    <meta charset="UTF-8">
    <meta name="keywords" content="<?php echo $this->_var['keywords']; ?>" />
    <meta name="description" content="<?php echo $this->_var['description']; ?>" />
    
    <title><?php echo $this->_var['page_title']; ?></title>
    
    
    
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="icon" href="animated_favicon.gif" type="image/gif" />
    <link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />
    <script src="<?php echo $this->_var['ecs_themes_path']; ?>/js/jquery-1.10.2.min.js" type="text/javascript"></script>
    <?php echo $this->smarty_insert_scripts(array('files'=>'common.js,index.js')); ?>
</head>
<body>
<?php echo $this->fetch('library/page_header.lbi'); ?>

<?php echo $this->fetch('library/index_ad.lbi'); ?>


<?php if ($this->_var['oldindex']): ?>
    <div class="block clearfix">
      
      <div class="AreaR">
       
       
        <div class="clearfix">
          
          <?php echo $this->fetch('library/recommend_promotion.lbi'); ?>
          
          <div class="box f_r brandsIe6">
           <div class="box_1 clearfix" id="brands">
            <?php echo $this->fetch('library/brands.lbi'); ?>
           </div>
          </div>
        </div>
        <div class="blank5"></div>
       
    <?php echo $this->fetch('library/recommend_best.lbi'); ?>
    <?php echo $this->fetch('library/recommend_new.lbi'); ?>


    <?php echo $this->fetch('library/group_buy.lbi'); ?>

    

      </div>
      
    </div>
<?php endif; ?>



<?php if ($this->_var['help'] - center): ?>
<div class="block">
  <div class="box">
   <div class="helpTitBg clearfix">
    <?php echo $this->fetch('library/help.lbi'); ?>
   </div>
  </div>
</div>
<?php endif; ?>

<div class="skin">
    <div><a href="skin_detection.php"><img src="themes/yzl_v1/images/pic1.jpg" width="330" height="140" alt="皮肤检测中心"></a></div>
    <div><a href="exp.php"><img src="themes/yzl_v1/images/pic2.jpg" width="335" height="140" alt="养肤体验馆"></a></div>
    <div><a href="/page/3"><img src="themes/yzl_v1/images/pic3.jpg" width="303" height="141" alt="易享"></a></div>
</div>



<?php if ($this->_var['img_links5'] || $this->_var['txt_links5']): ?>
    <?php $_from = $this->_var['img_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'link');if (count($_from)):
    foreach ($_from AS $this->_var['link']):
?>
    <a href="<?php echo $this->_var['link']['url']; ?>" target="_blank" title="<?php echo $this->_var['link']['name']; ?>"><img src="<?php echo $this->_var['link']['logo']; ?>" alt="<?php echo $this->_var['link']['name']; ?>" border="0" /></a>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <?php if ($this->_var['txt_links']): ?>
    <?php $_from = $this->_var['txt_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'link');if (count($_from)):
    foreach ($_from AS $this->_var['link']):
?>
    [<a href="<?php echo $this->_var['link']['url']; ?>" target="_blank" title="<?php echo $this->_var['link']['name']; ?>"><?php echo $this->_var['link']['name']; ?></a>]
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <?php endif; ?>
<?php endif; ?>



<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>
</html>
