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
        <div class="companynew_list">
            <h1 class="video_download"></h1>
            <div class="video_box">
                <div class="flv"><img src="themes/yzl_v1/images/tv.jpg" width="640" height="360"></div>
                <dl>
                    <dt><a href="#">2014新品 — 荷花养肤面膜</a></dt>
                    <dd><a href="#">下载</a><span></span></dd>
                </dl>
                <div class="roll"><img src="themes/yzl_v1/images/gundong.jpg" width="642" height="81"></div>
            </div>
        </div>

        <div class="companynew_list">
            <?php echo $this->_var['cat_detail']; ?>
            <div class="job-list">
                <?php $_from = $this->_var['artciles_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');if (count($_from)):
    foreach ($_from AS $this->_var['article']):
?>
                <dl<?php if ($this->_var['article']['article_type'] == 1): ?> class="red"<?php endif; ?>><dt><a href="<?php echo $this->_var['article']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['article']['title']); ?>"><?php echo $this->_var['article']['short_title']; ?></a></dt><dd>重庆</dd></dl>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php echo $this->fetch('library/pages_article_cat.lbi'); ?>
        </div>
    </div>
</div>
</div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>
</html>
