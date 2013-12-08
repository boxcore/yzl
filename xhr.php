<?php

/**
 * 皮肤检测中心
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

/* 载入语言文件 */
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/demo.php');

if($_REQUEST['act'] == 'get_maps')
{
    $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
    if($city){
        $sql = 'select map_id, map_name, map_point, address from '.$GLOBALS['ecs']->table('maps').' where city="'.$city.'"';
        $rows = $GLOBALS['db']->getAll($sql);
        if(!empty($rows)){
            $result['flag'] = 1;
            $result['count'] = count($rows);
            $result['map_list']= $rows;
            echo json_encode($result);
        }
    }
}


/** --------------------   处理函数      --------------------------- */



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



