<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
  <meta charset="UTF-8">
  <meta name="Keywords" content="<?php echo $this->_var['keywords']; ?>" />
  <meta name="Description" content="<?php echo $this->_var['description']; ?>" />
  
  <title><?php echo $this->_var['page_title']; ?></title>
  
  
  
  <link rel="shortcut icon" href="favicon.ico" />
  <link rel="icon" href="animated_favicon.gif" type="image/gif" />
  <link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />
  <?php echo $this->smarty_insert_scripts(array('files'=>'common.js,index.js')); ?>
</head>
<body>
<?php echo $this->fetch('library/page_header.lbi'); ?>
<div class="banner"></div>
<div class="skin">
  <div><a href="#"><img src="themes/yzl_v2/images/pic1.jpg" width="330" height="140"></a></div>
  <div><a href="#"><img src="themes/yzl_v2/images/pic2.jpg" width="335" height="140"></a></div>
  <div><a href="#"><img src="themes/yzl_v2/images/pic3.jpg" width="303" height="141"></a></div>
</div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>
</html>
