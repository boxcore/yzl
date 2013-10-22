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
            <h1 class="wall_paper"></h1>
            <ul class="wall-box">
                <?php $_from = $this->_var['artciles_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');$this->_foreach['lista'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['lista']['total'] > 0):
    foreach ($_from AS $this->_var['article']):
        $this->_foreach['lista']['iteration']++;
?>
                <li <?php if ($this->_foreach['lista']['iteration'] / 2 != 0): ?>class="no-margin"<?php endif; ?>><img src="<?php if ($this->_var['article']['article_wallpaper']): ?><?php echo $this->_var['article']['article_wallpaper']['0']['thumb_url']; ?><?php else: ?>未上传图片<?php endif; ?>" width="300" height="197" title="<?php echo htmlspecialchars($this->_var['article']['title']); ?>">
                    <div class="wall-text">
                        <?php $_from = $this->_var['article']['article_wallpaper']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'wallpaper');if (count($_from)):
    foreach ($_from AS $this->_var['wallpaper']):
?>
                        <p><a href="download.php?name=<?php echo $this->_var['wallpaper']['img_desc']; ?>&url=<?php echo $this->_var['wallpaper']['img_original']; ?>&"><?php echo $this->_var['wallpaper']['img_desc']; ?></a></p>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <!--{*<div class="job-list">
                <?php $_from = $this->_var['artciles_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');if (count($_from)):
    foreach ($_from AS $this->_var['article']):
?>
                <dl<?php if ($this->_var['article']['article_type'] == 1): ?> class="red"<?php endif; ?>><dt><a href="<?php echo $this->_var['article']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['article']['title']); ?>"><?php echo $this->_var['article']['short_title']; ?></a></dt><dd>重庆</dd></dl>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>*}-->
            <?php echo $this->fetch('library/pages_article_cat.lbi'); ?>
        </div>
    </div>
</div>
</div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>
</html>
