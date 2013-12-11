<?php


define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$exc = new exchange($ecs->table('maps'), $db, 'map_id', 'map_name');

/*------------------------------------------------------ */
//-- 地图列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $smarty->assign('ur_here',      $_LANG['maps_list']);
    $smarty->assign('action_link',  array('text' => $_LANG['add_maps'], 'href' => 'maps.php?act=add'));
    $smarty->assign('full_page',    1);

    $map_list = get_maplist();
    $smarty->assign('map_list',  $map_list['map']);
    $smarty->assign('filter',       $map_list['filter']);
    $smarty->assign('record_count', $map_list['record_count']);
    $smarty->assign('page_count',   $map_list['page_count']);

    /* 排序标记 */
    $sort_flag  = sort_flag($map_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('maps_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $map_list = get_maplist();
    $smarty->assign('map_list',  $map_list['map']);
    $smarty->assign('filter',       $map_list['filter']);
    $smarty->assign('record_count', $map_list['record_count']);
    $smarty->assign('page_count',   $map_list['page_count']);

    /* 排序标记 */
    $sort_flag  = sort_flag($map_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('maps_list.htm'), '',
        array('filter' => $map_list['filter'], 'page_count' => $map_list['page_count']));
}

/*------------------------------------------------------ */
//-- 列表页编辑名称
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_map_name')
{
    check_authz_json('maps_manage');

    $id     = intval($_POST['id']);
    $name   = json_str_iconv(trim($_POST['val']));

    /* 检查名称是否重复 */
    if ($exc->num("map_name", $name, $id) != 0)
    {
        make_json_error(sprintf($_LANG['maps_name_exist'], $name));
    }
    else
    {
        if ($exc->edit("map_name = '$name'", $id))
        {
            admin_log($name, 'edit', 'map');
            clear_cache_files();
            make_json_result(stripslashes($name));
        }
        else
        {
            make_json_result(sprintf($_LANG['maps_edit_fail'], $name));
        }
    }
}

/*------------------------------------------------------ */
//-- 编辑坐标
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_map_point')
{
    check_authz_json('maps_manage');

    $id     = intval($_POST['id']);
    $point   = json_str_iconv(trim($_POST['val']));

    /* 检查名称是否重复 */
    if ($exc->num("map_point", $point, $id) != 0)
    {
        make_json_error(sprintf('标注点重复', $point));
    }
    else
    {
        if ($exc->edit("map_point = '$point'", $id))
        {
            admin_log($point, 'edit', 'map');
            clear_cache_files();
            make_json_result(stripslashes($point));
        }
        else
        {
            make_json_result(sprintf($_LANG['maps_edit_fail'], $point));
        }
    }
}

/*------------------------------------------------------ */
//-- 列表页编辑地址
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_address')
{
    check_authz_json('maps_manage');

    $id     = intval($_POST['id']);
    $address   = json_str_iconv(trim($_POST['val']));

    if ($exc->edit("address = '$address'", $id))
    {
        admin_log($address, 'edit', 'map');
        clear_cache_files();
        make_json_result(stripslashes($address));
    }
    else
    {
        make_json_result(sprintf($_LANG['maps_edit_fail'], $address));
    }

}

/*------------------------------------------------------ */
//-- 列表页编辑电话
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_tel')
{
    check_authz_json('maps_manage');

    $id     = intval($_POST['id']);
    $tel   = json_str_iconv(trim($_POST['val']));

    if ($exc->edit("tel = '$tel'", $id))
    {
        admin_log($tel, 'edit', 'map');
        clear_cache_files();
        make_json_result(stripslashes($tel));
    }
    else
    {
        make_json_result(sprintf($_LANG['maps_edit_fail'], $tel));
    }

}

/*------------------------------------------------------ */
//-- 删除地图
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    admin_priv('map_remove');
    check_authz_json('maps_manage');

    $id = intval($_GET['id']);
    $name = $exc->get_name($id);
    $exc->drop($id);

    /* 记日志 */
    admin_log($name, 'remove', 'agency');

    /* 清除缓存 */
    clear_cache_files();

    $url = 'maps.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 批量操作
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch')
{
    /* 取得要操作的记录编号 */
    if (empty($_POST['checkboxes']))
    {
        sys_msg($_LANG['no_record_selected']);
    }
    else
    {
        /* 检查权限 */
        admin_priv('map_remove');

        $ids = $_POST['checkboxes'];

        if (isset($_POST['remove']))
        {
            /* 删除记录 */
            $sql = "DELETE FROM " . $ecs->table('maps') .
                    " WHERE map_id " . db_create_in($ids);
            $db->query($sql);

            /* 记日志 */
            admin_log('', 'batch_remove', 'agency');

            /* 清除缓存 */
            clear_cache_files();

            sys_msg($_LANG['batch_drop_ok']);
        }
    }
}

/*------------------------------------------------------ */
//-- 添加、编辑地图
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit')
{
    /* 检查权限 */
    admin_priv('map_manage');

    /* 是否添加 */
    $is_add = $_REQUEST['act'] == 'add';
    $smarty->assign('form_action', $is_add ? 'insert' : 'update');

    /* 初始化、取得地图信息 */
    if ($is_add)
    {
        $map = array(
            'map_id'     => 0,
            'map_name'   => '',
            'map_desc'   => '',
            'region_list'   => array()
        );
    }
    else
    {
        if (empty($_GET['id']))
        {
            sys_msg('invalid param');
        }

        $id = $_GET['id'];
        $sql = "SELECT * FROM " . $ecs->table('maps') . " WHERE map_id = '$id'";
        $map = $db->getRow($sql);
        if (empty($map))
        {
            sys_msg('agency does not exist');
        }

        /* 关联的地区 */
//        $sql = "SELECT region_id, region_name FROM " . $ecs->table('region') .
//                " WHERE agency_id = '$id'";
//        $map['region_list'] = $db->getAll($sql);
    }

    /* 取得所有管理员，标注哪些是该地图的('this')，哪些是空闲的('free')，哪些是别的地图的('other') */
//    $sql = "SELECT user_id, user_name, CASE " .
//            "WHEN agency_id = 0 THEN 'free' " .
//            "WHEN agency_id = '$agency[agency_id]' THEN 'this' " .
//            "ELSE 'other' END " .
//            "AS type " .
//            "FROM " . $ecs->table('admin_user');
//    $agency['admin_list'] = $db->getAll($sql);

    $smarty->assign('map', $map);
    $smarty->assign('act', $_REQUEST['act']);

    /* 取得地区 */
    $country_list = get_regions();
    $smarty->assign('countries', $country_list);


//    $consignee['country']  = isset($consignee['country'])  ? intval($consignee['country'])  : 0;
//    $consignee['province'] = isset($consignee['province']) ? intval($consignee['province']) : 0;
//    $consignee['city']     = isset($consignee['city'])     ? intval($consignee['city'])     : 0;

//    $province_list[$region_id] = get_regions(1, $consignee['country']);
//    $city_list[$region_id]     = get_regions(2, $consignee['province']);
//    $district_list[$region_id] = get_regions(3, $consignee['city']);

    /* 显示模板 */
    if ($is_add)
    {
        $smarty->assign('ur_here', $_LANG['add_maps']);
    }
    else
    {
        $smarty->assign('ur_here', $_LANG['edit_maps']);
    }
    if ($is_add)
    {
        $href = 'maps.php?act=list';
    }
    else
    {
        $href = 'maps.php?act=list&' . list_link_postfix();
    }
    $smarty->assign('action_link', array('href' => $href, 'text' => $_LANG['maps_list']));
    assign_query_info();
    $smarty->display('maps_info.htm');
}

/*------------------------------------------------------ */
//-- 提交添加、编辑地图
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
    /* 检查权限 */
    admin_priv('map_manage');

    /* 是否添加 */
    $is_add = $_REQUEST['act'] == 'insert';

    /* 提交值 */
    $map = array(
        'map_id'     => intval($_POST['id']),
        'map_name'   => sub_str($_POST['map_name'], 255, false),
        'map_desc'   => $_POST['map_desc'],
        'map_point'=> $_POST['map_point'],
        'country' => $_POST['country'],
        'province' => $_POST['province'],
        'city'=> $_POST['city'],
        'district' => $_POST['district'],
        'address' => $_POST['address'],
        'tel' => $_POST['tel'],
    );

    /* 判断名称是否重复 */
    if (!$exc->is_only('map_name', $map['map_name'], $map['map_id']))
    {
        sys_msg($_LANG['maps_name_exist']);
    }

    /* 检查是否选择了地区 */
    if (empty($_POST['city']))
    {
        sys_msg($_LANG['no_regions']);
    }

    /* 保存地图信息 */
    if ($is_add)
    {
        $db->autoExecute($ecs->table('maps'), $map, 'INSERT');
        $map['map_id'] = $db->insert_id();
    }
    else
    {
        $db->autoExecute($ecs->table('maps'), $map, 'UPDATE', "map_id = '$map[map_id]'");
    }

    /* 更新管理员表和地区表 */
//    if (!$is_add)
//    {
//        $sql = "UPDATE " . $ecs->table('admin_user') . " SET agency_id = 0 WHERE agency_id = '$agency[agency_id]'";
//        $db->query($sql);
//
//        $sql = "UPDATE " . $ecs->table('region') . " SET agency_id = 0 WHERE agency_id = '$agency[agency_id]'";
//        $db->query($sql);
//    }



    /* 记日志 */
    if ($is_add)
    {
        admin_log($map['map_name'], 'add', 'map');
    }
    else
    {
        admin_log($map['map_name'], 'edit', 'map');
    }

    /* 清除缓存 */
    clear_cache_files();

    /* 提示信息 */
    if ($is_add)
    {
        $links = array(
            array('href' => 'maps.php?act=add', 'text' => $_LANG['continue_add_maps']),
            array('href' => 'maps.php?act=list', 'text' => $_LANG['back_maps_list'])
        );
        sys_msg($_LANG['add_maps_ok'], 0, $links);
    }
    else
    {
        $links = array(
            array('href' => 'maps.php?act=list&' . list_link_postfix(), 'text' => $_LANG['back_maps_list'])
        );
        sys_msg($_LANG['edit_maps_ok'], 0, $links);
    }
}

/**
 * 取得地图列表
 * @return  array
 */
function get_maplist()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 初始化分页参数 */
        $filter = array();
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'map_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        /* 查询记录总数，计算分页数 */
        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('maps');
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);
        $filter = page_and_size($filter);

        /* 查询记录 */
        $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('maps') . " ORDER BY $filter[sort_by] $filter[sort_order]";

        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $arr = array();
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $arr[] = $rows;
    }

    return array('map' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

?>