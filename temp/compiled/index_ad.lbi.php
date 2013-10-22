<?php if ($this->_var['index_ad'] == 'sys'): ?>
<link href="data/flashdata/<?php echo $this->_var['flash_theme']; ?>/css/foucs.css" rel="stylesheet" />
<script src="data/flashdata/<?php echo $this->_var['flash_theme']; ?>/js/jquery.foucs.js" type="text/javascript"></script>

<div id="main">
    <div id="index_b_hero">
        <div class="hero-wrap">
            <ul class="heros clearfix">
                 <?php $_from = $this->_var['playerdb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');if (count($_from)):
    foreach ($_from AS $this->_var['row']):
?>
                <li class="hero">
                    <a href="<?php echo $this->_var['row']['url']; ?>" target="_blank" title="<?php echo $this->_var['row']['text']; ?>">
                        <img src="<?php echo $this->_var['row']['src']; ?>" class="thumb" alt="<?php echo $this->_var['row']['text']; ?>" />
                    </a>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <div class="helper">
            <div class="mask-left">
            </div>
            <div class="mask-right">
            </div>
            <a class="prev icon-arrow-a-left"></a>
            <a class="next icon-arrow-a-right"></a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $.foucs({direction: 'left'});
    $(function () {
        var $w = $(window);
        $w.scrollLeft($w.outerWidth() * 0.25);
    });
</script>


<?php elseif ($this->_var['index_ad'] == 'cus'): ?>
<?php endif; ?>