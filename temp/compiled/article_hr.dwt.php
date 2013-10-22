<!DOCTYPE HTML>
<html lang="en-US">
<head>
<base href="http://demo.yizhanglian.com/" />
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
<div class="inner_banner"></div>
<div class="inner_content">
    <div class="innernews_left">
        
        <?php if ($this->_var['article_categories']): ?>
        <?php echo $this->fetch('library/article_category_tree.lbi'); ?>
        <?php endif; ?>
        
    </div>
    <div class="innernews_right">
        <?php echo $this->fetch('library/ur_here.lbi'); ?>
        <div class="back"><a href="javascript:window.history.go(-1)">返回&nbsp;&lt;</a></div>
        <div class="innernews_right_text">
            <h1><strong><?php echo htmlspecialchars($this->_var['article']['title']); ?></strong><span><?php echo $this->_var['article']['add_time']; ?></span></h1>
            <?php if ($this->_var['article']['content']): ?>
            <?php echo $this->_var['article']['content']; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>
</html>
