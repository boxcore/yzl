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
$vote_sheet_id = 3;  //所属调查问卷组
$position = assign_ur_here(0, '皮肤检测中心');
$smarty->assign('page_title', $position['title']); // 页面标题
$smarty->assign('keywords', '自定义关键字'); // 页面标题
$smarty->assign('description', '自定义描述'); // 页面标题
$smarty->assign('ur_here',    $position['ur_here']);

//  已登陆用户信息调用
if( $_SESSION['user_id'] > 0 ){
    $smarty->assign('user_info', get_user_info($_SESSION['user_id']))  ;
}

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

$_REQUEST['act'] = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'default';


if ( ($_REQUEST['act'] == 'default') || ($_REQUEST['act'] == 'redo') ){
    if($_REQUEST['act'] == 'redo'){
        unset($_SESSION['survey']);
    }
    $smarty->assign('action',     $action);
    $smarty->display('skin_detection_default.dwt');
}
elseif($_REQUEST['act'] == 'qust_0'){
    //存用户调查基础信息
    $_SESSION['survey']['user_info'] = array(
        'user_id' => (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0),
        'real_name' => $_POST['real_name'],
        'sex' => $_POST['sex'],
        'email' => $_POST['email'],
        'birthday' => $_POST['birthday'],
        'used_yzl' => $_POST['used_yzl'],
        'time' => time()
    );

    //添加问题
    $qust = get_vote_info( get_vote_id('s_0') );
    $qust['html'] = format_qust($qust);
    $smarty->assign('qust',  $qust);

    $smarty->assign('action',     $action);
    $smarty->display('skin_detection_0.dwt');
}
elseif($_REQUEST['act'] == 'qust_1'){
    //存问题0答案
    $qust_mark = $_POST['qust_mark'];
    if($qust_mark == 's_0'){
        $_SESSION['survey']['qust']['s_0'][$qust_mark] = $_POST[$qust_mark];
        $qust_mark = 's_1_1';
    } else {
        $_SESSION['survey']['qust']['s_1'][$qust_mark] = $_POST[$qust_mark];
        $qust_list = get_qust_next($qust_mark);
        $qust_mark = $qust_list['next'];
    }

    $qust_list2 = get_qust_next($qust_mark);
    if(empty($qust_list2['next'])){
        $smarty->assign('action',     'deal');
    }

    //添加问题
    $qust = get_vote_info( get_vote_id($qust_mark) );
    $qust['html'] = format_qust($qust);
    $smarty->assign('qust',  $qust);
    $smarty->assign('quet_list', $qust_list  );
    $smarty->display('skin_detection_1.dwt');
}

elseif($_REQUEST['act'] == 'qust_2'){
    //存问题1答案
    $qust_mark = isset($_POST['qust_mark']) ? $_POST['qust_mark'] : '';
    if($qust_mark){
        if($qust_mark == 's_0'){
            $_SESSION['survey']['qust']['s_0'][$qust_mark] = $_POST[$qust_mark];
            $qust_mark = 's_2_1';
        }else {
            $_SESSION['survey']['qust']['s_2'][$qust_mark] = $_POST[$qust_mark];
            $qust_list = get_qust_next($qust_mark);
            $qust_mark = $qust_list['next'];
        }

    }else {
        $qust_mark = 's_2_1';
    }

    $qust_list2 = get_qust_next($qust_mark);
    if(empty($qust_list2['next'])){
        $smarty->assign('action',     'deal');
    }

//    if(  stristr($qust_mark, 's_1_') === FALSE ){

    //添加问题
    $qust = get_vote_info( get_vote_id($qust_mark) );
    $qust['html'] = format_qust($qust);
    $smarty->assign('qust',  $qust);
    $smarty->assign('quet_list', $qust_list  );
    $smarty->display('skin_detection_2.dwt');
}
elseif($_REQUEST['act'] == 'deal'){
    //判断来源
    $qust_mark = $_POST['qust_mark'];
    if($qust_mark == 's_1_8'){
        $_SESSION['survey']['qust']['s_1'][$qust_mark] = $_POST[$qust_mark];

        foreach($_SESSION['survey']['qust']['s_1'] as $v){
            $count_s_1 += $v;
        }
        if($count_s_1<3){
            $url = "?act=qust_2";
            echo "<script language='javascript' type='text/javascript'>window.location.href='$url'</script>";
        }
        elseif( ($count_s_1>=3) && ($count_s_1<=5) ){
            $skin_type = 'A';
            $skin_info = get_skin_type_info($skin_type);
            $smarty->assign('skin_info', $skin_info);
            $smarty->assign('skin_type', $skin_type);
            $smarty->display('skin_detection_deal.dwt');
        }
        elseif( $count_s_1>5 ){
            $skin_type = 'F';
            $skin_info = get_skin_type_info($skin_type);
            $smarty->assign('skin_info', $skin_info);
            $smarty->assign('skin_type', $skin_type);
            $smarty->display('skin_detection_deal.dwt');
        }
    }
    elseif($qust_mark == 's_2_10'){
        $_SESSION['survey']['qust']['s_2'][$qust_mark] = $_POST[$qust_mark];

        $qust_2_count = 0;
        foreach($_SESSION['survey']['qust']['s_2'] as $v){
            switch($v){
                case 1:
                    $qust_2_count += 1;
                    break;
                case 2:
                    $qust_2_count += 3;
                    break;
                case 3:
                    $qust_2_count += 5;
                    break;
                case 4:
                    $qust_2_count += 7;
                    break;
            }
        }
        if( ($qust_2_count>=10) && ($qust_2_count<=15) ){
            $skin_type = 'C';
        }
        elseif( ($qust_2_count>=16) && ($qust_2_count<=35) ){
            $skin_type = 'D';
        }
        elseif( ($qust_2_count>=36) && ($qust_2_count<=50) ){
            $skin_type = 'E';
        }
        elseif( ($qust_2_count>=51) && ($qust_2_count<=70) ){
            $skin_type = 'B';
        }

        $skin_info = get_skin_type_info($skin_type);
        $smarty->assign('skin_info', $skin_info);
        $smarty->assign('skin_type', $skin_type);

        $smarty->display('skin_detection_deal.dwt');

    }
}
elseif($_REQUEST['act'] == 'result'){
    echo 'deal by result;';
    //计算问题1
    foreach($_SESSION['survey']['qust']['s_1'] as $v){
        $count_s_1 += $v;
    }

    //计算问题2
    foreach($_SESSION['survey']['qust']['s_2'] as $v){
        $count_s_1 += $v;
    }

}


/** --------------------   处理函数      --------------------------- */

/**
 * 输出问题html
 * @param array $qust
 * @return string
 */
function format_qust($qust=array()){
    $str = '';
    switch($qust['can_multi']){
        case 1://单选题目
            $str .= '<p class="step"><span>'.$qust['vote_sort'].'、'.$qust['vote_name'].'</span></p>';
            $str .= '<div class="judge">';
            foreach($qust['opt'] as $v){
                $str .= '<div class="vote_opt2"><input type="radio" name="'.$qust['vote_mark'].'" value="'.$v['option_mark'].'"><span>'.get_dic_name('vote_alpha', $v['option_mark']).'、'.$v['option_name'].'</span></div>';
            }
            $str .= '</div>';
            break;
        case 3://判断题
            $str .= '<p class="step"><span>'.$qust['vote_sort'].'、'.$qust['vote_name'].'</span></p>';
            $str .= '<div class="judge">';
            $str .= '<div class="vote_opt"><input type="radio" name="'.$qust['vote_mark'].'" value="1"><span>是</span></div>';
            $str .= '<div class="vote_opt"><input type="radio" name="'.$qust['vote_mark'].'" value="0"><span>不是</span></div>';
            $str .= '</div>';
            break;
        case 5://判断题
            $str .= '<p class="step"><span>'.$qust['vote_name'].'</span></p>';
            $str .= '<div class="judge">';
            foreach($qust['opt'] as $v){
                $str .= '<div class="vote_opt2"><input type="radio" name="'.$qust['vote_mark'].'" value="'.$v['option_mark'].'"><span>'.$v['option_name'].'</span></div>';
            }
            $str .= '</div>';
            break;

    }

    return $str;
}

function get_qust_next($vote_mark=''){
    $arr = explode('_', $vote_mark);
    $cur = array_pop($arr);
    $group = join('_',$arr);

    $row['cur'] = $group.'_'.$cur;

    $vote_sort = $GLOBALS['db']->getOne('select vote_sort from '.$GLOBALS['ecs']->table('vote').' where vote_mark ="'.$vote_mark.'"');

    $row['prev'] = $GLOBALS['db']->getOne('select vote_mark from '.$GLOBALS['ecs']->table('vote').' where vote_mark like("'.$group.'%") and vote_sort<'.$vote_sort.' order by vote_sort DESC limit 1 ');
    $row['next'] = $GLOBALS['db']->getOne('select vote_mark from '.$GLOBALS['ecs']->table('vote').' where vote_mark like("'.$group.'%") and vote_sort>'.$vote_sort.' order by vote_sort ASC limit 1 ');

    return $row;
}

/**
 * @param string $skin_mark
 * @return array
 */
function get_skin_type_info($skin_mark=''){
    if(!$skin_mark) return array();
    return $GLOBALS['db']->getRow('select * from '.$GLOBALS['ecs']->table('skin_type').' where skin_mark="'.$skin_mark.'"');
}



