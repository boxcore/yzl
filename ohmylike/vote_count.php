<?php

/**
 * ECSHOP  调查管理程序
 * ============================================================================
 * 版权所有 2005-2010 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: vote.php 17063 2010-03-25 06:35:46Z liuhui $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(dirname(__FILE__) . '/includes/cls_IpLocation.php');
$get_ip_loc = new IpLocation();
/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}

$exc = new exchange($ecs->table("vote"), $db, 'vote_id', 'vote_name');
$exc_opn = new exchange($ecs->table("vote_option"), $db, 'option_id', 'option_name');

/*------------------------------------------------------ */
//-- 投票列表页面
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
	$pid = !empty($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;
    /* 模板赋值 */
	$smarty->assign('sheet_option',      get_vote_sheet_option($pid));
    //$smarty->assign('ur_here',      $_LANG['list_vote']);
    //$smarty->assign('action_link',  array('text' => $_LANG['add_vote'], 'href'=>'vote_count.php?act=add'));
    $smarty->assign('full_page',    1);

    $vote_list = get_votelist($pid);
    //print_r($vote_list);
    $smarty->assign('list',            $vote_list['list']);
    $smarty->assign('filter',          $vote_list['filter']);
    $smarty->assign('record_count',    $vote_list['record_count']);
    $smarty->assign('page_count',      $vote_list['page_count']);

    /* 显示页面 */
    assign_query_info();
    $smarty->display('vote_count_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $vote_list = get_votelist();

    $smarty->assign('list',            $vote_list['list']);
    $smarty->assign('filter',          $vote_list['filter']);
    $smarty->assign('record_count',    $vote_list['record_count']);
    $smarty->assign('page_count',      $vote_list['page_count']);

    make_json_result($smarty->fetch('vote_count_list.htm'), '',
        array('filter' => $vote_list['filter'], 'page_count' => $vote_list['page_count']));
}


/* 获取在线调查数据列表 */
function get_votelist($pid=0)
{
	global $get_ip_loc;
	$where = '';
	if ($pid != 0)
	{
		$where = ' and a.vote_sheet_id='.$pid;
	}
	if ($filter['pid'] != 0)
	{
		$where = ' and a.vote_sheet_id='.intval($filter['pid']);
	}
	$filter['pid'] = $pid;
    
    /* 记录总数以及页数 */
    $sql = 'select count(distinct a.ip_address) from '.$GLOBALS['ecs']->table('vote_log'). ' as a where 1'.$where;
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);
	$filter['page_size'] = 3;
    $filter = page_and_size($filter);

    /* 查询数据 */
    $sql = "select a.log_id, GROUP_CONCAT( concat(b.vote_name , '*', a.str_content) ORDER BY a.vote_id SEPARATOR '|') as vote_info, c.name, a.ip_address, a.vote_time from ".$GLOBALS['ecs']->table('vote_log').' as a,'.$GLOBALS['ecs']->table('vote').' as b,'.$GLOBALS['ecs']->table('vote_sheet').' as c where a.vote_id=b.vote_id and a.vote_sheet_id=c.id'.$where.' group by a.ip_address order by a.vote_time desc';
    //echo $sql;die;
    $res  = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $list = array();
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
    	$info_arr = explode('|', $rows['vote_info']);
    	$opt_info = array();
    	$str_info = '';
    	$i = 1;
    	foreach ($info_arr as $k=>$v)
    	{
    		$opt_arr = explode('*', $v);
    		$opt_info[$k]['vote_title'] = $opt_arr[0];
    		$opt_arr[1] = $opt_arr_bak = explode(',', $opt_arr[1]);
    		$str_info .= "$i.<font color='red'>$opt_arr[0]</font>（";
    		$i++;
    		foreach ($opt_arr[1] as $kk=>$vv)
    		{
    			$opt_arr[1][$kk] = intval($vv);
    		}
    		$opt_arr[1] = implode(',', $opt_arr[1]);
			if ($opt_arr[1] != 0)
			{
    			$opt_info_arr = $GLOBALS['db']->getAll('select option_name from '.$GLOBALS['ecs']->table('vote_option')." where option_id in($opt_arr[1])");
    			foreach ($opt_info_arr as $value)
    			{
    				$str_info .= "【$value[option_name]】";
    			}
    			$str_info .= '）<br/>';
			}else 
			{
				$str_info .= "【$opt_arr_bak[0]】）<br/>";
			}
    	}
    	$loc_arr = $get_ip_loc->getaddress($rows['ip_address']);
    	$rows['loc'] = iconv('GBK', 'UTF-8', $loc_arr['area1']).iconv('GBK', 'UTF-8', $loc_arr['area2']);
    	$rows['vote_info'] = $str_info;
    	$rows['vote_time'] = date('Y-m-d',$rows['vote_time']);
        $list[] = $rows;
    }
	
    return array('list' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

/* 获取调查选项列表 */
function get_optionlist($id)
{
    $list = array();
    $sql  = 'SELECT option_id, vote_id, option_name, option_count, option_order'.
            ' FROM ' .$GLOBALS['ecs']->table('vote_option').
            " WHERE vote_id = '$id' ORDER BY option_order ASC, option_id DESC";
    $res  = $GLOBALS['db']->query($sql);
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $list[] = $rows;
    }

    return $list;
}
/* 获取调查问卷选项列表 */
function get_vote_sheet_option($id=0)
{
	$id = intval($id);
	$list = '';
	$sql = 'SELECT id,name FROM ' . $GLOBALS['ecs']->table('vote_sheet') . ' ORDER BY add_time desc';
	$res  = $GLOBALS['db']->query($sql);
	while ($rows = $GLOBALS['db']->fetchRow($res))
    {
    	$selected = $id == $rows['id']?'selected=1':'';
        $list .= '<option '.$selected.' value='.$rows['id'].'>'.$rows['name'].'</option>';
    }
    return $list;
}
?>