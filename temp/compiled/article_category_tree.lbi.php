<?php if ($this->_var['article_categories']): ?>
<div class="innernews_left_box">
    <?php $_from = $this->_var['article_categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
?>
    <h1><?php echo htmlspecialchars($this->_var['cat']['name']); ?></h1>
    <ul class="innernews_list">
        <?php $_from = $this->_var['cat']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');$this->_foreach['nav'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav']['total'] > 0):
    foreach ($_from AS $this->_var['child']):
        $this->_foreach['nav']['iteration']++;
?>
        <li<?php if (($this->_foreach['nav']['iteration'] == $this->_foreach['nav']['total'])): ?> class="no_border"<?php endif; ?>><a href="<?php echo $this->_var['child']['url']; ?>"><?php echo htmlspecialchars($this->_var['child']['name']); ?></a></li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<?php endif; ?>