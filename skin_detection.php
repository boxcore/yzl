<?php

/**
 * 皮肤检测中心
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

/* 载入语言文件 */
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/demo.php');

// 对页面进行赋值
assign_template();
$position = assign_ur_here(0, '皮肤检测中心');
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

$_SESSION['suvery'] = isset($_SESSION['suvery'])? $_SESSION['suvery']:array();
$_SESSION['suvery']['act']  = isset($_SESSION['suvery']['act'])?$_SESSION['suvery']['act']:'default';
$_SESSION['suvery']['act']  = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : $_SESSION['suvery']['act'];

if( $_REQUEST['act'] == 'redo' ){
    unset($_SESSION['suvery']);
    echo '<script>window.location.href="/skin_detection.php";</scirpt>';
}
elseif ( ($_SESSION['suvery']['act'] == 'default') ){
    $smarty->assign('action',     $action);
}
elseif($_SESSION['suvery']['act'] == "sd_sens"){
        $smarty->assign('action',     $action);
}
elseif($_SESSION['suvery']['act'] == "id_sens"){
    $_SESSION['suvery']['id_sens'] =  isset($_SESSION['suvery']['id_sens']) ? $_SESSION['suvery']['id_sens'] :  $_REQUEST['id_sens'];
    if(($_SESSION['suvery']['id_sens'] == 'Y')||($_REQUEST['id_sens'] == 'Y')){
        $_SESSION['suvery']['step'] = isset($_SESSION['suvery']['step'])?$_SESSION['suvery']['step']:1;

        $_SESSION['suvery']['qust'] = isset($_SESSION['suvery']['qust']) ? $_SESSION['suvery']['qust'] : 1;
        $_SESSION['suvery']['qust'] = isset($_REQUEST['qust']) ? $_REQUEST['qust'] : $_SESSION['suvery']['qust'];
        $prev_qust = $_SESSION['suvery']['qust'] - 1;
        if($_REQUEST['step_1']){
            $_SESSION['suvery']['log']['step_1'][$prev_qust] = $_REQUEST['step_1'][$prev_qust];
        }


        $smarty->assign('qust_next',     ($_SESSION['suvery']['qust']+1) );

    }
    elseif($_SESSION['suvery']['id_sens'] == 'N'){
        $smarty->assign('step',     2);
    }

}

//echo '<pre>';print_r($_REQUEST);echo '</pre>';
//echo '<hr>';print_r($_SESSION);echo '<hr>';
$smarty->display('skin_detection.dwt');

function get_step1_quest($id){

}


