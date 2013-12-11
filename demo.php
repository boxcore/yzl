<?php

/**
 * ec基本页面
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

/* 载入语言文件 */
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/demo.php');

// 对页面进行赋值
assign_template();  //初始公共模板信息（必须，否则导航帮助等信息不能调用）
$position = assign_ur_here(0, 'demo页面');
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
$smarty->assign('action',     $action);
$smarty->assign('lang',       $_LANG);

$a = get_categories_tree1(3);
print_r($a);

$smarty->display('demo.dwt');
