<?php

/**
 * 字典处理
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/*
 * 获取字典
 */
function get_dic_name($group='', $value='',$retrun=true){
    if( (!$group) || (!$value) ) return '';
    $sql  = 'SELECT dic_name FROM ' .$GLOBALS['ecs']->table('dic'). ' WHERE dic_group = "'.$group.'" and dic_value = "'.$value.'"';
    $dic_name = $GLOBALS['db']->getOne($sql);
    if($retrun){
        return $dic_name;
    }else{
        echo $dic_name;
    }
}

/**
 * 获取问题和问题选项
 * @param $vote_id
 * @return array
 */
function get_vote_info($vote_id){
    $vote_id = intval($vote_id);
    $row = $GLOBALS['db']->getRow('select * from '.$GLOBALS['ecs']->table('vote').' where vote_id='.$vote_id);
    $sql = 'select * from '.$GLOBALS['ecs']->table('vote_option').' where vote_id='.$vote_id.' order by option_mark ASC, option_order DESC ';
    $row[opt] = $GLOBALS['db']->getAll($sql);
    return $row ? $row : array();
}

/**
 * 获取问题ID
 * @param $vote_mark
 * @return int
 */
function get_vote_id($vote_mark){
    $vote_id =  $GLOBALS['db']->getOne('select vote_id from '.$GLOBALS['ecs']->table('vote').' where vote_mark="'.$vote_mark.'"');
    return $vote_id ? $vote_id : 0;
}

function get_vote_mark_group($mark_group=''){
    $sql = 'select vote_sort,vote_mark from '.$GLOBALS['ecs']->table('vote').' where vote_mark like("'.$mark_group.'%") order by vote_sort ';
    $rows = $GLOBALS['db']->getAll($sql);
    foreach($rows as $v){
        $row[$v['vote_sort']] = $v['vote_mark'];
    }
    return $row;
//    $arr = explode('_', $mark);
//    $cur = array_pop($arr);
//    print_r($arr);
}

/*
 * 获取地区名
 */
function get_region_name($id){
    if( (!$id) || (!$id) ) return '';
    $sql  = 'SELECT region_name FROM ' .$GLOBALS['ecs']->table('region'). ' WHERE region_id = '.$id.' ';
    $region_name = $GLOBALS['db']->getOne($sql);
        return $region_name;
}

/**
 * 获取地区
 * @param int $type 0：国家||1：省份|| 2：城市 || 3：区
 * @return string
 */
function get_region($type=0){
    if( (!$type) || (!$type) ) return '';
    $sql  = 'SELECT regin_id, region_name FROM ' .$GLOBALS['ecs']->table('region'). ' WHERE region_type = '.$type.' ';
    $region = $GLOBALS['db']->getAll($sql);
    return $region;
}

/**
 * 获取文章简介
 * @param string $str
 * @param int $lenth
 * @param string $addstr
 * @return string
 */
function get_brief($str='', $lenth=60, $addstr='...'){
    $str = strip_tags($str);
    $count_length = mb_strlen($str,'utf-8');
    if($count_length > $lenth){
        $str = mb_substr($str,0,$lenth,'utf-8') . $addstr;
    }
    return $str;
}

/**
 * 获取上一级文章分类id
 * @param $id
 * @return int
 */
function get_article_pid($id){
    if( (!$id) || (!$id) ) return 0;
    $sql  = 'SELECT parent_id FROM ' .$GLOBALS['ecs']->table('article_cat'). ' WHERE cat_id = '.$id.' ';
    return $GLOBALS['db']->getOne($sql);
}