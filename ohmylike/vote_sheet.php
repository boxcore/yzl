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

/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}

$exc = new exchange($ecs->table("vote_sheet"), $db, 'id', 'name');
//$exc_opn = new exchange($ecs->table("vote_option"), $db, 'option_id', 'option_name');

/*------------------------------------------------------ */
//-- 调查列表页面
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
	//$pid = !empty($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;
    /* 模板赋值 */
    $smarty->assign('ur_here',      '调查表');
    $smarty->assign('action_link',  array('text' => '添加在线调查问卷', 'href'=>'vote_sheet.php?act=add'));
    $smarty->assign('full_page',    1);

    $vote_list = get_vote_sheet();

    $smarty->assign('list',            $vote_list['list']);
    $smarty->assign('filter',          $vote_list['filter']);
    $smarty->assign('record_count',    $vote_list['record_count']);
    $smarty->assign('page_count',      $vote_list['page_count']);

    /* 显示页面 */
    assign_query_info();
    $smarty->display('vote_sheet_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $vote_list = get_vote_sheet();

    $smarty->assign('list',            $vote_list['list']);
    $smarty->assign('filter',          $vote_list['filter']);
    $smarty->assign('record_count',    $vote_list['record_count']);
    $smarty->assign('page_count',      $vote_list['page_count']);

    make_json_result($smarty->fetch('vote_sheet_list.htm'), '',
        array('filter' => $vote_list['filter'], 'page_count' => $vote_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加新的投票页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 权限检查 */
    admin_priv('vote_priv');

    /* 日期初始化 */
    $vote = array('start_time' => local_date('Y-m-d'), 'end_time' => local_date('Y-m-d', local_strtotime('+2 weeks')));

    /* 模板赋值 */
    $smarty->assign('ur_here',      '添加在线调查问卷');
    $smarty->assign('action_link',  array('href'=>'vote_sheet.php?act=list', 'text' => '返回调查问卷列表'));

    $smarty->assign('action',       'add');
    $smarty->assign('form_act',     'insert');
    $smarty->assign('vote_arr',     $vote);
    $smarty->assign('cfg_lang',     $_CFG['lang']);

    /* 显示页面 */
    assign_query_info();
    $smarty->display('vote_sheet_info.htm');
}
elseif ($_REQUEST['act'] == 'insert')
{
    admin_priv('vote_priv');

    /* 获得广告的开始时期与结束日期 */
    //$start_time = local_strtotime($_POST['start_time']);
    //$end_time   = local_strtotime($_POST['end_time']);

    /* 查看广告名称是否有重复 */
    $sql = "SELECT COUNT(*) FROM " .$ecs->table('vote_sheet'). " WHERE name='$_POST[vote_name]'";
    if ($db->getOne($sql) == 0)
    {
        /* 插入数据 */
        $sql = "INSERT INTO ".$ecs->table('vote_sheet')." (name, add_time, is_open)
        VALUES ('$_POST[vote_name]', ".time().", '$_POST[is_open]')";
        $db->query($sql);

        //$new_id = $db->Insert_ID();

        /* 记录管理员操作 */
        //admin_log($_POST['vote_name'], 'add', 'vote');

        /* 清除缓存 */
        clear_cache_files();

        /* 提示信息 */
        $link[0]['text'] = '立即向问卷添加问题';
        $link[0]['href'] = 'vote.php?act=add';

        $link[1]['text'] = '继续添加问卷';
        $link[1]['href'] = 'vote_sheet.php?act=add';

        $link[2]['text'] = '返回问卷列表';
        $link[2]['href'] = 'vote_sheet.php?act=list';

        sys_msg($_LANG['add'] . "&nbsp;" .$_POST['vote_name'] . "&nbsp;" . $_LANG['attradd_succed'],0, $link);

    }
    else
    {
        $link[] = array('text' => $_LANG['go_back'], 'href'=>'javascript:history.back(-1)');
        sys_msg($_LANG['vote_name_exist'], 0, $link);
    }
}
/*------------------------------------------------------ */
//-- 在线调查编辑页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{
    admin_priv('vote_priv');

    /* 获取数据 */
    $vote_arr = $db->GetRow("SELECT * FROM ".$ecs->table('vote_sheet')." WHERE id='$_REQUEST[id]'");

    /* 模板赋值 */
    $smarty->assign('ur_here',      '修改在线调查问卷');
    $smarty->assign('action_link',  array('href'=>'vote_sheet.php?act=list', 'text' => '返回调查问卷列表'));
    $smarty->assign('form_act',     'update');
    $smarty->assign('vote_arr',     $vote_arr);

    assign_query_info();
    $smarty->display('vote_sheet_info.htm');
}
elseif ($_REQUEST['act'] == 'update')
{

    /* 更新信息 */
    $sql = "UPDATE " .$ecs->table('vote_sheet'). " SET ".
            "name     = '$_POST[vote_name]', ".
            "is_open     = '$_POST[is_open]' ".
            "WHERE id = '$_REQUEST[id]'";
    $db->query($sql);

    /* 清除缓存 */
    clear_cache_files();

    /* 记录管理员操作 */
    //admin_log($_POST['vote_name'], 'edit', 'vote');

    /* 提示信息 */
    $link[] = array('text' => '返回问卷列表', 'href'=>'vote_sheet.php?act=list');
    sys_msg($_LANG['edit'] .' '.$_POST['vote_name'].' '. $_LANG['attradd_succed'], 0, $link);
}

/*------------------------------------------------------ */
//-- 编辑调查主题
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_vote_sheet_name')
{
    check_authz_json('vote_sheet_priv');

    $id        = intval($_POST['id']);
    $vote_name = json_str_iconv(trim($_POST['val']));

    /* 检查名称是否重复 */
    if ($exc->num("name", $vote_name, $id) != 0)
    {
        make_json_error(sprintf($_LANG['vote_name_exist'], $vote_name));
    }
    else
    {
        if ($exc->edit("name = '$vote_name'", $id))
        {
            //admin_log($vote_name, 'edit', 'vote');
            make_json_result(stripslashes($vote_name));
        }
    }
}

/*------------------------------------------------------ */
//-- 删除在线调查主题
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('vote_priv');

    $id = intval($_GET['id']);

    if ($exc->drop($id))
    {
        /* 同时删除调查选项 */
        $db->query("DELETE FROM " .$ecs->table('vote_sheet'). " WHERE id = '$id'");
        $db->query("DELETE FROM " .$ecs->table('vote_option'). " WHERE vote_id in (select vote_id from ".$ecs->table('vote')." where vote_sheet_id=$id)");
        $db->query("DELETE FROM " .$ecs->table('vote'). " WHERE vote_sheet_id=$id");
        clear_cache_files();
        //admin_log('', 'remove', 'ads_position');
    }

    $url = 'vote_sheet.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}
elseif ($_REQUEST['act'] == 'toggle_show')
{
	$id     = intval($_POST['id']);
    $val    = intval($_POST['val']);

    $exc->edit("is_open = '$val'", $id);
    clear_cache_files();

    make_json_result($val);
}

/* 获取在线调查数据列表 */
function get_vote_sheet()
{
    $filter   = array();

    /* 记录总数以及页数 */
    $sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('vote_sheet');
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    $filter = page_and_size($filter);

    /* 查询数据 */
    $sql  = 'SELECT * FROM ' .$GLOBALS['ecs']->table('vote_sheet').' ORDER BY add_time DESC';
    $res  = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $list = array();
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $rows['add_time'] = local_date('Y-m-d', $rows['add_time']);
        $list[] = $rows;
    }

    return array('list' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}


?>