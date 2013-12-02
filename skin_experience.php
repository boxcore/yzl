<?php

/**
 * 养肤体验馆
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

/* 载入语言文件 */
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/demo.php');

// 对页面进行赋值
assign_template();
$position = assign_ur_here(0, '养肤体验馆');
$smarty->assign('page_title', $position['title']); // 页面标题
$smarty->assign('keywords', '自定义关键字'); // 页面标题
$smarty->assign('description', '自定义描述'); // 页面标题
$smarty->assign('ur_here',    $position['ur_here']);

/* 常用到的调用全局数据 */
if (!empty($_CFG['points_rule']) && unserialize($_CFG['points_rule'])){// 是否显示积分兑换
    $smarty->assign('show_transform_points',     1);
}

$smarty->assign('helps',      get_shop_help());        // 网店帮助
$smarty->assign('data_dir',   DATA_DIR);   // 数据目录

$smarty->assign('lang',       $_LANG);

$smarty->display('skin_experience.dwt');


