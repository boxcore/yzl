<?php

/**
 * ECSHOP 管理中心文章处理程序文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.xxoopp.org；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: article.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . '/' . ADMIN_PATH . '/includes/lib_goods.php');
require_once(ROOT_PATH . "includes/fckeditor/fckeditor.php");
require_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);

/*初始化数据交换对象 */
$exc   = new exchange($ecs->table("article"), $db, 'article_id', 'title');
//$image = new cls_image();

/* 允许上传的文件类型 */
$allow_file_types = '|GIF|JPG|PNG|BMP|SWF|DOC|XLS|PPT|MID|WAV|ZIP|RAR|PDF|CHM|RM|TXT|';

/*------------------------------------------------------ */
//-- 文章列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 取得过滤条件 */
    $filter = array();
    $smarty->assign('cat_select',  article_cat_list(0));
    $smarty->assign('ur_here',      $_LANG['03_article_list']);
    $smarty->assign('action_link',  array('text' => $_LANG['article_add'], 'href' => 'article.php?act=add'));
    $smarty->assign('full_page',    1);
    $smarty->assign('filter',       $filter);

    $article_list = get_articleslist();

    $smarty->assign('article_list',    $article_list['arr']);
    $smarty->assign('filter',          $article_list['filter']);
    $smarty->assign('record_count',    $article_list['record_count']);
    $smarty->assign('page_count',      $article_list['page_count']);

    $sort_flag  = sort_flag($article_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('article_list.htm');
}

/*------------------------------------------------------ */
//-- 翻页，排序
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    check_authz_json('article_manage');

    $article_list = get_articleslist();

    $smarty->assign('article_list',    $article_list['arr']);
    $smarty->assign('filter',          $article_list['filter']);
    $smarty->assign('record_count',    $article_list['record_count']);
    $smarty->assign('page_count',      $article_list['page_count']);

    $sort_flag  = sort_flag($article_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('article_list.htm'), '',
        array('filter' => $article_list['filter'], 'page_count' => $article_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加文章
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'add')
{
    /* 权限判断 */
    admin_priv('article_manage');

    /* 创建 html editor */
    create_html_kindeditor('FCKeditor1');

    /*初始化*/
    $article = array();
    $article['is_open'] = 1;

    /* 取得分类、品牌 */
    $smarty->assign('goods_cat_list', cat_list());
    $smarty->assign('brand_list',     get_brand_list());

    /* 清理关联商品 */
    $sql = "DELETE FROM " . $ecs->table('goods_article') . " WHERE article_id = 0";
    $db->query($sql);


    /*文章图片相册功能 start by bocxore*/

    /* 如果是安全模式，检查目录是否存在 */
    if (ini_get('safe_mode') == 1 && (!file_exists('../' . IMAGE_DIR . '/'.date('Ym')) || !is_dir('../' . IMAGE_DIR . '/'.date('Ym'))))
    {
        if (@!mkdir('../' . IMAGE_DIR . '/'.date('Ym'), 0777))
        {
            $warning = sprintf($_LANG['safe_mode_warning'], '../' . IMAGE_DIR . '/'.date('Ym'));
            $smarty->assign('warning', $warning);
        }
    }

    /* 如果目录存在但不可写，提示用户 */
    elseif (file_exists('../' . IMAGE_DIR . '/'.date('Ym')) && file_mode_info('../' . IMAGE_DIR . '/'.date('Ym')) < 2)
    {
        $warning = sprintf($_LANG['not_writable_warning'], '../' . IMAGE_DIR . '/'.date('Ym'));
        $smarty->assign('warning', $warning);
    }

    /*初始文章图片列表*/
    $img_list = array();

    /*文章图片相册功能 end by bocxore*/


    if (isset($_GET['id']))
    {
        $smarty->assign('cur_id',  $_GET['id']);
    }
    $smarty->assign('article',     $article);
    $smarty->assign('cat_select',  article_cat_list(0));
    $smarty->assign('ur_here',     $_LANG['article_add']);
    $smarty->assign('action_link', array('text' => $_LANG['03_article_list'], 'href' => 'article.php?act=list'));
    $smarty->assign('form_action', 'insert');

    assign_query_info();
    $smarty->display('article_info.htm');
}

/*------------------------------------------------------ */
//-- 添加文章
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'insert')
{

    /* 权限判断 */
    admin_priv('article_manage');

    /* 是否处理缩略图 文章图片相册 by boxcore */
    $proc_thumb = (isset($GLOBALS['shop_id']) && $GLOBALS['shop_id'] > 0)? false : true;

    /* 取得文件地址 */
    $file_url = '';
    if ((isset($_FILES['file']['error']) && $_FILES['file']['error'] == 0) || (!isset($_FILES['file']['error']) && isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] != 'none'))
    {
        // 检查文件格式
        if (!check_file_type($_FILES['file']['tmp_name'], $_FILES['file']['name'], $allow_file_types))
        {
            sys_msg($_LANG['invalid_file']);
        }

        // 复制文件
        $res = upload_article_file($_FILES['file']);
        if ($res != false)
        {
            $file_url = $res;
        }
    }

    if ($file_url == '')
    {
        $file_url = $_POST['file_url'];
    }

    /* 计算文章打开方式 */
    if ($file_url == '')
    {
        $open_type = 0;
    }
    else
    {
        $open_type = $_POST['FCKeditor1'] == '' ? 1 : 2;
    }


    /*文章图片相册功能 start by bocxore*/

    /* 检查图片：如果有错误，检查尺寸是否超过最大值；否则，检查文件类型 */
    
    if (isset($_FILES['article_img']['error'])) // php 4.2 版本才支持 error
    {
        // 最大上传文件大小
        $php_maxsize = ini_get('upload_max_filesize');
        $htm_maxsize = '2M';

        // 商品图片
        if ($_FILES['article_img']['error'] == 0)
        {
            if (!$image->check_img_type($_FILES['article_img']['type']))
            {
                sys_msg($_LANG['invalid_article_img'], 1, array(), false);
            }
        }
        elseif ($_FILES['article_img']['error'] == 1)
        {
            sys_msg(sprintf($_LANG['article_img_too_big'], $php_maxsize), 1, array(), false);
        }
        elseif ($_FILES['article_img']['error'] == 2)
        {
            sys_msg(sprintf($_LANG['article_img_too_big'], $htm_maxsize), 1, array(), false);
        }

        // 商品缩略图
        if (isset($_FILES['article_thumb']))
        {
            if ($_FILES['article_thumb']['error'] == 0)
            {
                if (!$image->check_img_type($_FILES['article_thumb']['type']))
                {
                    sys_msg($_LANG['invalid_article_thumb'], 1, array(), false);
                }
            }
            elseif ($_FILES['article_thumb']['error'] == 1)
            {
                sys_msg(sprintf($_LANG['article_thumb_too_big'], $php_maxsize), 1, array(), false);
            }
            elseif ($_FILES['article_thumb']['error'] == 2)
            {
                sys_msg(sprintf($_LANG['article_thumb_too_big'], $htm_maxsize), 1, array(), false);
            }
        }

        // 相册图片
        foreach ($_FILES['img_url']['error'] AS $key => $value)
        {
            if ($value == 0)
            {
                if (!$image->check_img_type($_FILES['img_url']['type'][$key]))
                {
                    sys_msg(sprintf($_LANG['invalid_img_url'], $key + 1), 1, array(), false);
                }
            }
            elseif ($value == 1)
            {
                sys_msg(sprintf($_LANG['img_url_too_big'], $key + 1, $php_maxsize), 1, array(), false);
            }
            elseif ($_FILES['img_url']['error'] == 2)
            {
                sys_msg(sprintf($_LANG['img_url_too_big'], $key + 1, $htm_maxsize), 1, array(), false);
            }
        }
    }
    

    /* 处理商品图片 */
    $article_img        = '';  // 初始化商品图片
    $article_thumb      = '';  // 初始化商品缩略图
    $original_img     = '';  // 初始化原始图片
    $old_original_img = '';  // 初始化原始图片旧图


    // 如果上传了商品图片，相应处理
    if (($_FILES['article_img']['tmp_name'] != '' && $_FILES['article_img']['tmp_name'] != 'none') or (($_POST['article_img_url'] != $_LANG['lab_picture_url'] && $_POST['article_img_url'] != 'http://') && $is_url_article_img = 1))
    {

        if (empty($is_url_article_img))
        {
            $original_img   = $image->upload_image($_FILES['article_img']); // 原始图片

        }
        elseif (copy(trim($_POST['article_img_url']), ROOT_PATH . 'temp/' . basename($_POST['article_img_url'])))
        {
            $original_img = 'temp/' . basename($_POST['article_img_url']);
        }

        if ($original_img === false)
        {
            sys_msg($image->error_msg(), 1, array(), false);
        }
        $article_img      = $original_img;   // 商品图片

        /* 复制一份相册图片 */
        /* 添加判断是否自动生成相册图片 */
        if ($_CFG['auto_generate_gallery'])
        {
            $img        = $original_img;   // 相册图片
            $pos        = strpos(basename($img), '.');
            $newname    = dirname($img) . '/' . $image->random_filename() . substr(basename($img), $pos);
            if (!copy('../' . $img, '../' . $newname))
            {
                sys_msg('fail to copy file: ' . realpath('../' . $img), 1, array(), false);
            }
            $img        = $newname;

            $gallery_img    = $img;
            $gallery_thumb  = $img;
        }

        // 如果系统支持GD，缩放商品图片，且给商品图片和相册图片加水印
        if ($proc_thumb && $image->gd_version() > 0 && $image->check_img_function($_FILES['article_img']['type']) || $is_url_article_img)
        {

            if (empty($is_url_article_img))
            {
                // 如果设置大小不为0，缩放图片  文章图片缩放处理
                if ($_CFG['image_width'] != 0 || $_CFG['image_height'] != 0)
                {
                    $article_img = $image->make_thumb('../'. $article_img , $GLOBALS['_CFG']['image_width'],  $GLOBALS['_CFG']['image_height']);
                    if ($article_img === false)
                    {
                        sys_msg($image->error_msg(), 1, array(), false);
                    }
                }

                /* 添加判断是否自动生成相册图片 */
                if ($_CFG['auto_generate_gallery'])
                {
                    $newname    = dirname($img) . '/' . $image->random_filename() . substr(basename($img), $pos);
                    if (!copy('../' . $img, '../' . $newname))
                    {
                        sys_msg('fail to copy file: ' . realpath('../' . $img), 1, array(), false);
                    }
                    $gallery_img        = $newname;
                }

                // 加水印
                if (intval($_CFG['watermark_place']) > 0 && !empty($GLOBALS['_CFG']['watermark']))
                {
                    if ($image->add_watermark('../'.$article_img,'',$GLOBALS['_CFG']['watermark'], $GLOBALS['_CFG']['watermark_place'], $GLOBALS['_CFG']['watermark_alpha']) === false)
                    {
                        sys_msg($image->error_msg(), 1, array(), false);
                    }
                    /* 添加判断是否自动生成相册图片 */
                    if ($_CFG['auto_generate_gallery'])
                    {
                        if ($image->add_watermark('../'. $gallery_img,'',$GLOBALS['_CFG']['watermark'], $GLOBALS['_CFG']['watermark_place'], $GLOBALS['_CFG']['watermark_alpha']) === false)
                        {
                            sys_msg($image->error_msg(), 1, array(), false);
                        }
                    }
                }
            }

            // 相册缩略图
            /* 添加判断是否自动生成相册图片 */
            if ($_CFG['auto_generate_gallery'])
            {
                if ($_CFG['thumb_wall_width'] != 0 || $_CFG['thumb_wall_height'] != 0)
                {
                    $gallery_thumb = $image->make_thumb('../' . $img, $GLOBALS['_CFG']['thumb_wall_width'],  $GLOBALS['_CFG']['thumb_wall_height']);
                    if ($gallery_thumb === false)
                    {
                        sys_msg($image->error_msg(), 1, array(), false);
                    }
                }
            }
        }
    }

    // 是否上传商品缩略图
    if (isset($_FILES['article_thumb']) && $_FILES['article_thumb']['tmp_name'] != '' &&
        isset($_FILES['article_thumb']['tmp_name']) &&$_FILES['article_thumb']['tmp_name'] != 'none')
    {
        // 上传了，直接使用，原始大小
        $article_thumb = $image->upload_image($_FILES['article_thumb']);
        if ($article_thumb === false)
        {
            sys_msg($image->error_msg(), 1, array(), false);
        }
    }
    else
    {
        // 未上传，如果自动选择生成，且上传了商品图片，生成所略图
        if ($proc_thumb && isset($_POST['auto_thumb']) && !empty($original_img))
        {
            // 如果设置缩略图大小不为0，生成缩略图
            if ($_CFG['thumb_wall_width'] != 0 || $_CFG['thumb_wall_height'] != 0)
            {
                $article_thumb = $image->make_thumb('../' . $original_img, $GLOBALS['_CFG']['thumb_wall_width'],  $GLOBALS['_CFG']['thumb_wall_height']);
                if ($article_thumb === false)
                {
                    sys_msg($image->error_msg(), 1, array(), false);
                }
            }
            else
            {
                $article_thumb = $original_img;
            }
        }
    }

    /* 删除下载的外链原图 */
    if (!empty($is_url_article_img))
    {
        unlink(ROOT_PATH . $original_img);
        empty($newname) || unlink(ROOT_PATH . $newname);
        $url_article_img = $article_img = $original_img = htmlspecialchars(trim($_POST['article_img_url']));
    }

    $article_thumb = (empty($article_thumb) && !empty($_POST['article_thumb_url']) && goods_parse_url($_POST['article_thumb_url'])) ? htmlspecialchars(trim($_POST['article_thumb_url'])) : $article_thumb;
    $article_thumb = (empty($article_thumb) && isset($_POST['auto_thumb']))? $article_img : $article_thumb;

    /*文章图片相册功能 end by bocxore*/


    /*插入数据*/
    $add_time = gmtime();
    if (empty($_POST['cat_id']))
    {
        $_POST['cat_id'] = 0;
    }
    $sql = "INSERT INTO ".$ecs->table('article')."(title, cat_id, article_type, is_open, author, ".
                "author_email, keywords, content, add_time, file_url, article_img, article_thumb, original_img, open_type, link, description, vedio_link) ".
            "VALUES ('$_POST[title]', '$_POST[article_cat]', '$_POST[article_type]', '$_POST[is_open]', ".
                "'$_POST[author]', '$_POST[author_email]', '$_POST[keywords]', '$_POST[FCKeditor1]', ".
                "'$add_time', '$file_url', '$article_img', '$article_thumb', '$original_img', '$open_type', '$_POST[link_url]', '$_POST[description]', '$_POST[vedio_link]')";
    $db->query($sql);

    /* 处理关联商品 */
    $article_id = $db->insert_id();
    $sql = "UPDATE " . $ecs->table('goods_article') . " SET article_id = '$article_id' WHERE article_id = 0";
    $db->query($sql);

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'article.php?act=add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'article.php?act=list';

    admin_log($_POST['title'],'add','article');


    /*文章图片相册功能 start by bocxore*/

    /* 重新格式化图片名称 */
    $original_img = reformat_image_name('goods', $article_id, $original_img, 'source');
    $article_img = reformat_image_name('goods', $article_id, $article_img, 'goods');
    $article_thumb = reformat_image_name('goods_thumb', $article_id, $article_thumb, 'thumb');
    echo "<pre>";
    print_r($original_img.'+'.$article_img.'+'.$article_thumb);
    echo "</pre>";
    echo '<hr>';
    if ($article_img !== false)
    {
        $db->query("UPDATE " . $ecs->table('article') . " SET article_img = '$article_img' WHERE article_id='$article_id'");
    }

    if ($original_img !== false)
    {
        $db->query("UPDATE " . $ecs->table('article') . " SET original_img = '$original_img' WHERE article_id='$article_id'");
    }

    if ($article_thumb !== false)
    {
        $db->query("UPDATE " . $ecs->table('article') . " SET article_thumb = '$article_thumb' WHERE article_id='$article_id'");
    }


    /* 如果有图片，把商品图片加入图片相册 */
    if (isset($img))
    {
        /* 重新格式化图片名称 */
        if (empty($is_url_article_img))
        {
            $img = reformat_image_name('gallery', $article_id, $img, 'source');
            $gallery_img = reformat_image_name('gallery', $article_id, $gallery_img, 'goods');
        }
        else
        {
            $img = $url_article_img;
            $gallery_img = $url_article_img;
        }

        $gallery_thumb = reformat_image_name('gallery_thumb', $article_id, $gallery_thumb, 'thumb');
        $sql = "INSERT INTO " . $ecs->table('article_wallpaper') . " (article_id, img_url, img_desc, thumb_url, img_original) " .
                "VALUES ('$article_id', '$gallery_img', '', '$gallery_thumb', '$img')";
        $db->query($sql);
    }

    /* 处理相册图片 */
    handle_wallpaper_image($article_id, $_FILES['img_url'], $_POST['img_desc'], $_POST['img_file']);

    /* 编辑时处理相册图片描述 */
    if (!$is_insert && isset($_POST['old_img_desc']))
    {
        foreach ($_POST['old_img_desc'] AS $img_id => $img_desc)
        {
            $sql = "UPDATE " . $ecs->table('article_wallpaper') . " SET img_desc = '$img_desc' WHERE img_id = '$img_id' LIMIT 1";
            $db->query($sql);
        }
    }

    /* 不保留商品原图的时候删除原图 */
    if ($proc_thumb && !$_CFG['retain_original_img'] && !empty($original_img))
    {
        $db->query("UPDATE " . $ecs->table('article') . " SET original_img='' WHERE `article_id`='{$article_id}'");
        $db->query("UPDATE " . $ecs->table('article_gallery') . " SET img_original='' WHERE `article_id`='{$article_id}'");
        @unlink('../' . $original_img);
        @unlink('../' . $img);
    }

    /*文章图片相册功能 end by bocxore*/

    clear_cache_files(); // 清除相关的缓存文件

    sys_msg($_LANG['articleadd_succeed'],0, $link);
}

/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit')
{
    /* 权限判断 */
    admin_priv('article_manage');

    /* 取文章数据 */
    $sql = "SELECT * FROM " .$ecs->table('article'). " WHERE article_id='$_REQUEST[id]'";
    $article = $db->GetRow($sql);

    /*文章图片相册功能 start by bocxore*/

    /* 如果是安全模式，检查目录是否存在 */
    if (ini_get('safe_mode') == 1 && (!file_exists('../' . IMAGE_DIR . '/'.date('Ym')) || !is_dir('../' . IMAGE_DIR . '/'.date('Ym'))))
    {
        if (@!mkdir('../' . IMAGE_DIR . '/'.date('Ym'), 0777))
        {
            $warning = sprintf($_LANG['safe_mode_warning'], '../' . IMAGE_DIR . '/'.date('Ym'));
            $smarty->assign('warning', $warning);
        }
    }

    /* 如果目录存在但不可写，提示用户 */
    elseif (file_exists('../' . IMAGE_DIR . '/'.date('Ym')) && file_mode_info('../' . IMAGE_DIR . '/'.date('Ym')) < 2)
    {
        $warning = sprintf($_LANG['not_writable_warning'], '../' . IMAGE_DIR . '/'.date('Ym'));
        $smarty->assign('warning', $warning);
    }

    /* 文章图片路径 */
    if (isset($GLOBALS['shop_id']) && ($GLOBALS['shop_id'] > 10) && !empty($goods['original_img']))
    {
        $goods['article_img'] = get_image_path($_REQUEST['article_id'], $goods['article_img']);
        $goods['article_thumb'] = get_image_path($_REQUEST['article_id'], $goods['article_thumb'], true);
    }

    /* 文章图片列表 */
    $sql = "SELECT * FROM " . $ecs->table('article_wallpaper') . " WHERE article_id = '$article[article_id]'";
    $img_list = $db->getAll($sql);

    /* 格式化文章相册图片路径 */
    if (isset($GLOBALS['shop_id']) && ($GLOBALS['shop_id'] > 0))
    {
        foreach ($img_list as $key => $gallery_img)
        {
            $gallery_img[$key]['img_url'] = get_image_path($gallery_img['article_id'], $gallery_img['img_original'], false, 'gallery');
            $gallery_img[$key]['thumb_url'] = get_image_path($gallery_img['article_id'], $gallery_img['img_original'], true, 'gallery');
        }
    }
    else
    {
        foreach ($img_list as $key => $gallery_img)
        {
            $gallery_img[$key]['thumb_url'] = '../' . (empty($gallery_img['thumb_url']) ? $gallery_img['img_url'] : $gallery_img['thumb_url']);
        }
    }

    /*文章图片相册功能 end by bocxore*/

    



    /* 创建 html editor */
    create_html_kindeditor('FCKeditor1',$article['content']);

    /* 取得分类、品牌 */
    $smarty->assign('goods_cat_list', cat_list());
    $smarty->assign('brand_list', get_brand_list());

    /* 取得关联商品 */
    $goods_list = get_article_goods($_REQUEST['id']);
    $smarty->assign('goods_list', $goods_list);

    $smarty->assign('article',     $article);


    /* 文章图片相册功能 start by bocxore */
    $smarty->assign('img_list', $img_list);
    $smarty->assign('gd', gd_version());
    $smarty->assign('thumb_wall_width', $_CFG['thumb_wall_width']);
    $smarty->assign('thumb_wall_height', $_CFG['thumb_wall_height']);
    /* 文章图片相册功能 end by bocxore */


    $smarty->assign('cat_select',  article_cat_list(0, $article['cat_id']));
    $smarty->assign('ur_here',     $_LANG['article_edit']);
    $smarty->assign('action_link', array('text' => $_LANG['03_article_list'], 'href' => 'article.php?act=list&' . list_link_postfix()));
    $smarty->assign('form_action', 'update');

    assign_query_info();
    $smarty->display('article_info.htm');
}

if ($_REQUEST['act'] =='update')
{
    /* 权限判断 */
    admin_priv('article_manage');

    /* 是否处理缩略图 文章图片相册 by boxcore */
    $proc_thumb = (isset($GLOBALS['shop_id']) && $GLOBALS['shop_id'] > 0)? false : true;

    /*检查文章名是否相同*/
    // $is_only = $exc->is_only('title', $_POST['title'], $_POST['id'], "cat_id = '$_POST[article_cat]'");
    // $is_only = '';

    // if (!$is_only)
    // {
    //     sys_msg(sprintf($_LANG['title_exist'], stripslashes($_POST['title'])), 1);
    // }


    if (empty($_POST['cat_id']))
    {
        $_POST['cat_id'] = 0;
    }

    /* 取得文件地址 */
    $file_url = '';
    if (empty($_FILES['file']['error']) || (!isset($_FILES['file']['error']) && isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] != 'none'))
    {
        // 检查文件格式
        if (!check_file_type($_FILES['file']['tmp_name'], $_FILES['file']['name'], $allow_file_types))
        {
            sys_msg($_LANG['invalid_file']);
        }

        // 复制文件
        $res = upload_article_file($_FILES['file']);
        if ($res != false)
        {
            $file_url = $res;
        }
    }

    if ($file_url == '')
    {
        $file_url = $_POST['file_url'];
    }

    /* 计算文章打开方式 */
    if ($file_url == '')
    {
        $open_type = 0;
    }
    else
    {
        $open_type = $_POST['FCKeditor1'] == '' ? 1 : 2;
    }

    /* 如果 file_url 跟以前不一样，且原来的文件是本地文件，删除原来的文件 */
    $sql = "SELECT file_url FROM " . $ecs->table('article') . " WHERE article_id = '$_POST[id]'";
    $old_url = $db->getOne($sql);
    if ($old_url != '' && $old_url != $file_url && strpos($old_url, 'http://') === false && strpos($old_url, 'https://') === false)
    {
        @unlink(ROOT_PATH . $old_url);
    }

    /*文章图片相册功能 start by bocxore*/

    /* 检查图片：如果有错误，检查尺寸是否超过最大值；否则，检查文件类型 */
    if (isset($_FILES['article_img']['error'])) // php 4.2 版本才支持 error
    {
        // 最大上传文件大小
        $php_maxsize = ini_get('upload_max_filesize');
        $htm_maxsize = '2M';

        // 商品图片
        if ($_FILES['article_img']['error'] == 0)
        {
            if (!$image->check_img_type($_FILES['article_img']['type']))
            {
                sys_msg($_LANG['invalid_article_img'], 1, array(), false);
            }
        }
        elseif ($_FILES['article_img']['error'] == 1)
        {
            sys_msg(sprintf($_LANG['article_img_too_big'], $php_maxsize), 1, array(), false);
        }
        elseif ($_FILES['article_img']['error'] == 2)
        {
            sys_msg(sprintf($_LANG['article_img_too_big'], $htm_maxsize), 1, array(), false);
        }

        // 商品缩略图
        if (isset($_FILES['article_thumb']))
        {
            if ($_FILES['articl_thumb']['error'] === 0)
            {
                if (!$image->check_img_type($_FILES['articl_thumb']['type']))
                {
                    sys_msg($_LANG['invalid_goods_thumb'], 1, array(), false);
                }
            }
            elseif ($_FILES['articl_thumb']['error'] == 1)
            {
                sys_msg(sprintf($_LANG['articl_thumb_too_big'], $php_maxsize), 1, array(), false);
            }
            elseif ($_FILES['articl_thumb']['error'] == 2)
            {
                sys_msg(sprintf($_LANG['articl_thumb_too_big'], $htm_maxsize), 1, array(), false);
            }
        }

        // 相册图片
        foreach ($_FILES['img_url']['error'] AS $key => $value)
        {
            if ($value == 0)
            {
                if (!$image->check_img_type($_FILES['img_url']['type'][$key]))
                {
                    sys_msg(sprintf($_LANG['invalid_img_url'], $key + 1), 1, array(), false);
                }
            }
            elseif ($value == 1)
            {
                sys_msg(sprintf($_LANG['img_url_too_big'], $key + 1, $php_maxsize), 1, array(), false);
            }
            elseif ($_FILES['img_url']['error'] == 2)
            {
                sys_msg(sprintf($_LANG['img_url_too_big'], $key + 1, $htm_maxsize), 1, array(), false);
            }
        }
    }

    /* 处理文章图片 */
    $article_img        = '';  // 初始化文章图片
    $article_thumb      = '';  // 初始化文章缩略图
    $original_img     = '';  // 初始化文章原始图片
    $old_original_img = '';  // 初始化文章原始图片旧图

    // 如果上传了文章图片，相应处理
    if (($_FILES['article_img']['tmp_name'] != '' && $_FILES['article_img']['tmp_name'] != 'none') or (($_POST['article_img_url'] != $_LANG['lab_picture_url'] && $_POST['article_img_url'] != 'http://') && $is_url_article_img = 1))
    {
        if ($_REQUEST['article_id'] > 0)
        {
            print_r($_REQUEST['article_id']);exit;
            /* 删除原来的图片文件 */
            $sql = "SELECT article_thumb, article_img, original_img " .
                    " FROM " . $ecs->table('article') .
                    " WHERE article_id = '$_REQUEST[article_id]'";
            $row = $db->getRow($sql);
            if ($row['article_thumb'] != '' && is_file('../' . $row['article_thumb']))
            {
                @unlink('../' . $row['article_thumb']);
            }
            if ($row['article_img'] != '' && is_file('../' . $row['article_img']))
            {
                @unlink('../' . $row['article_img']);
            }
            if ($row['original_img'] != '' && is_file('../' . $row['original_img']))
            {
                /* 先不处理，以防止程序中途出错停止 */
                //$old_original_img = $row['original_img']; //记录旧图路径
            }
            /* 清除原来商品图片 */
            if ($proc_thumb === false)
            {
                get_image_path($_REQUEST[article_id], $row['article_img'], false, 'goods', true);
                get_image_path($_REQUEST[article_id], $row['article_thumb'], true, 'goods', true);
            }
        }

        if (empty($is_url_article_img))
        {
            $original_img   = $image->upload_image($_FILES['article_img']); // 原始图片
        }
        elseif (copy(trim($_POST['article_img_url']), ROOT_PATH . 'temp/' . basename($_POST['article_img_url'])))
        {
            $original_img = 'temp/' . basename($_POST['article_img_url']);
        }

        if ($original_img === false)
        {
            sys_msg($image->error_msg(), 1, array(), false);
        }
        $article_img      = $original_img;   // 商品图片

        /* 复制一份相册图片 */
        /* 添加判断是否自动生成相册图片 */
        if ($_CFG['auto_generate_gallery'])
        {
            $img        = $original_img;   // 相册图片
            $pos        = strpos(basename($img), '.');
            $newname    = dirname($img) . '/' . $image->random_filename() . substr(basename($img), $pos);
            if (!copy('../' . $img, '../' . $newname))
            {
                sys_msg('fail to copy file: ' . realpath('../' . $img), 1, array(), false);
            }
            $img        = $newname;

            $gallery_img    = $img;
            $gallery_thumb  = $img;
        }

        // 如果系统支持GD，缩放商品图片，且给商品图片和相册图片加水印
        if ($proc_thumb && $image->gd_version() > 0 && $image->check_img_function($_FILES['article_img']['type']) || $is_url_article_img)
        {

            if (empty($is_url_article_img))
            {
                // 如果设置大小不为0，缩放图片
                if ($_CFG['image_width'] != 0 || $_CFG['image_height'] != 0)
                {
                    $article_img = $image->make_thumb('../'. $article_img , $GLOBALS['_CFG']['image_width'],  $GLOBALS['_CFG']['image_height']);
                    if ($article_img === false)
                    {
                        sys_msg($image->error_msg(), 1, array(), false);
                    }
                }

                /* 添加判断是否自动生成相册图片 */
                if ($_CFG['auto_generate_gallery'])
                {
                    $newname    = dirname($img) . '/' . $image->random_filename() . substr(basename($img), $pos);
                    if (!copy('../' . $img, '../' . $newname))
                    {
                        sys_msg('fail to copy file: ' . realpath('../' . $img), 1, array(), false);
                    }
                    $gallery_img        = $newname;
                }

                // 加水印
                if (intval($_CFG['watermark_place']) > 0 && !empty($GLOBALS['_CFG']['watermark']))
                {
                    if ($image->add_watermark('../'.$article_img,'',$GLOBALS['_CFG']['watermark'], $GLOBALS['_CFG']['watermark_place'], $GLOBALS['_CFG']['watermark_alpha']) === false)
                    {
                        sys_msg($image->error_msg(), 1, array(), false);
                    }
                    /* 添加判断是否自动生成相册图片 */
                    if ($_CFG['auto_generate_gallery'])
                    {
                        if ($image->add_watermark('../'. $gallery_img,'',$GLOBALS['_CFG']['watermark'], $GLOBALS['_CFG']['watermark_place'], $GLOBALS['_CFG']['watermark_alpha']) === false)
                        {
                            sys_msg($image->error_msg(), 1, array(), false);
                        }
                    }
                }
            }

            // 相册缩略图
            /* 添加判断是否自动生成相册图片 */
            if ($_CFG['auto_generate_gallery'])
            {
                if ($_CFG['thumb_wall_width'] != 0 || $_CFG['thumb_wall_height'] != 0)
                {
                    $gallery_thumb = $image->make_thumb('../' . $img, $GLOBALS['_CFG']['thumb_wall_width'],  $GLOBALS['_CFG']['thumb_wall_height']);
                    if ($gallery_thumb === false)
                    {
                        sys_msg($image->error_msg(), 1, array(), false);
                    }
                }
            }
        }
    }


    // 是否上传商品缩略图
    if (isset($_FILES['article_thumb']) && $_FILES['article_thumb']['tmp_name'] != '' &&
        isset($_FILES['article_thumb']['tmp_name']) &&$_FILES['article_thumb']['tmp_name'] != 'none')
    {
        // 上传了，直接使用，原始大小
        $article_thumb = $image->upload_image($_FILES['article_thumb']);
        if ($article_thumb === false)
        {
            sys_msg($image->error_msg(), 1, array(), false);
        }
    }
    else
    {
        // 未上传，如果自动选择生成，且上传了商品图片，生成所略图
        if ($proc_thumb && isset($_POST['auto_thumb']) && !empty($original_img))
        {
            // 如果设置缩略图大小不为0，生成缩略图
            if ($_CFG['thumb_wall_width'] != 0 || $_CFG['thumb_wall_height'] != 0)
            {
                $article_thumb = $image->make_thumb('../' . $original_img, $GLOBALS['_CFG']['thumb_wall_width'],  $GLOBALS['_CFG']['thumb_wall_height']);
                if ($article_thumb === false)
                {
                    sys_msg($image->error_msg(), 1, array(), false);
                }
            }
            else
            {
                $article_thumb = $original_img;
            }
        }
    }


    /* 删除下载的外链原图 */
    if (!empty($is_url_article_img))
    {
        unlink(ROOT_PATH . $original_img);
        empty($newname) || unlink(ROOT_PATH . $newname);
        $url_article_img = $article_img = $original_img = htmlspecialchars(trim($_POST['article_img_url']));
    }

    /*文章图片相册功能 end by bocxore*/


    if ($exc->edit("title='$_POST[title]', cat_id='$_POST[article_cat]', article_type='$_POST[article_type]', is_open='$_POST[is_open]', author='$_POST[author]', author_email='$_POST[author_email]', keywords ='$_POST[keywords]', file_url ='$file_url', open_type='$open_type', content='$_POST[FCKeditor1]', link='$_POST[link_url]', description = '$_POST[description]', vedio_link = '$_POST[vedio_link]'", $_POST['id']))
    {
        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'article.php?act=list&' . list_link_postfix();

        $note = sprintf($_LANG['articleedit_succeed'], stripslashes($_POST['title']));
        admin_log($_POST['title'], 'edit', 'article');


        /*文章图片相册功能 start by bocxore*/
        
        $article_id = $_REQUEST['id'];
        /* 重新格式化图片名称 */
        $original_img = reformat_image_name('goods', $article_id, $original_img, 'source');
        $article_img = reformat_image_name('goods', $article_id, $article_img, 'goods');
        $article_thumb = reformat_image_name('article_thumb', $article_id, $article_thumb, 'thumb');
        if ($article_img !== false)
        {
            $db->query("UPDATE " . $ecs->table('article') . " SET article_img = '$article_img' WHERE article_id='$article_id'");
        }

        if ($original_img !== false)
        {
            $db->query("UPDATE " . $ecs->table('article') . " SET original_img = '$original_img' WHERE article_id='$article_id'");
        }

        if ($article_thumb !== false)
        {
            $db->query("UPDATE " . $ecs->table('article') . " SET article_thumb = '$article_thumb' WHERE article_id='$article_id'");
        }

        /* 如果有图片，把商品图片加入图片相册 */
        if (isset($img))
        {
            /* 重新格式化图片名称 */
            if (empty($is_url_article_img))
            {
                $img = reformat_image_name('gallery', $article_id, $img, 'source');
                $gallery_img = reformat_image_name('gallery', $article_id, $gallery_img, 'goods');
            }
            else
            {
                $img = $url_article_img;
                $gallery_img = $url_article_img;
            }

            $gallery_thumb = reformat_image_name('gallery_thumb', $article_id, $gallery_thumb, 'thumb');
            $sql = "INSERT INTO " . $ecs->table('article_wallpaper') . " (article_id, img_url, img_desc, thumb_url, img_original) " .
                    "VALUES ('$article_id', '$gallery_img', '', '$gallery_thumb', '$img')";
            $db->query($sql);
        }

        /* 处理相册图片 */
        handle_wallpaper_image($article_id, $_FILES['img_url'], $_POST['img_desc'], $_POST['img_file']);

        /* 编辑时处理相册图片描述 */
        if (!$is_insert && isset($_POST['old_img_desc']))
        {
            foreach ($_POST['old_img_desc'] AS $img_id => $img_desc)
            {
                $sql = "UPDATE " . $ecs->table('article_wallpaper') . " SET img_desc = '$img_desc' WHERE img_id = '$img_id' LIMIT 1";
                $db->query($sql);
            }
        }

        /* 不保留商品原图的时候删除原图 */
        if ($proc_thumb && !$_CFG['retain_original_img'] && !empty($original_img))
        {
            $db->query("UPDATE " . $ecs->table('article') . " SET original_img='' WHERE `article_id`='{$article_id}'");
            $db->query("UPDATE " . $ecs->table('article_wallpaper') . " SET img_original='' WHERE `article_id`='{$article_id}'");
            @unlink('../' . $original_img);
            @unlink('../' . $img);
        }

        /*文章图片相册功能 end by bocxore*/


        clear_cache_files();

        sys_msg($note, 0, $link);
    }
    else
    {
        die($db->error());
    }
}

/*------------------------------------------------------ */
//-- 编辑文章主题
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_title')
{
    check_authz_json('article_manage');

    $id    = intval($_POST['id']);
    $title = json_str_iconv(trim($_POST['val']));

    /* 检查文章标题是否重复 */
    if ($exc->num("title", $title, $id) != 0)
    {
        // make_json_error(sprintf($_LANG['title_exist'], $title));
        if ($exc->edit("title = '$title'", $id))
        {
            clear_cache_files();
            admin_log($title, 'edit', 'article');
            make_json_result(stripslashes($title));
        }
        else
        {
            make_json_error($db->error());
        }
    }
    else
    {
        if ($exc->edit("title = '$title'", $id))
        {
            clear_cache_files();
            admin_log($title, 'edit', 'article');
            make_json_result(stripslashes($title));
        }
        else
        {
            make_json_error($db->error());
        }
    }
}

/*------------------------------------------------------ */
//-- 切换是否显示
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_show')
{
    check_authz_json('article_manage');

    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);

    $exc->edit("is_open = '$val'", $id);
    clear_cache_files();

    make_json_result($val);
}

/*------------------------------------------------------ */
//-- 切换文章重要性
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_type')
{
    check_authz_json('article_manage');

    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);

    $exc->edit("article_type = '$val'", $id);
    clear_cache_files();

    make_json_result($val);
}



/*------------------------------------------------------ */
//-- 删除文章主题
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('article_manage');

    $id = intval($_GET['id']);

    /* 删除原来的文件 */
    $sql = "SELECT file_url FROM " . $ecs->table('article') . " WHERE article_id = '$id'";
    $old_url = $db->getOne($sql);
    if ($old_url != '' && strpos($old_url, 'http://') === false && strpos($old_url, 'https://') === false)
    {
        @unlink(ROOT_PATH . $old_url);
    }

    $name = $exc->get_name($id);
    if ($exc->drop($id))
    {
        $db->query("DELETE FROM " . $ecs->table('comment') . " WHERE " . "comment_type = 1 AND id_value = $id");
        
        admin_log(addslashes($name),'remove','article');
        clear_cache_files();
    }

    $url = 'article.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 将商品加入关联
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add_link_goods')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;

    check_authz_json('article_manage');

    $add_ids = $json->decode($_GET['add_ids']);
    $args = $json->decode($_GET['JSON']);
    $article_id = $args[0];

    if ($article_id == 0)
    {
        $article_id = $db->getOne('SELECT MAX(article_id)+1 AS article_id FROM ' .$ecs->table('article'));
    }

    foreach ($add_ids AS $key => $val)
    {
        $sql = 'INSERT INTO ' . $ecs->table('goods_article') . ' (goods_id, article_id) '.
               "VALUES ('$val', '$article_id')";
        $db->query($sql, 'SILENT') or make_json_error($db->error());
    }

    /* 重新载入 */
    $arr = get_article_goods($article_id);
    $opt = array();

    foreach ($arr AS $key => $val)
    {
        $opt[] = array('value'  => $val['goods_id'],
                        'text'  => $val['goods_name'],
                        'data'  => '');
    }

    make_json_result($opt);
}

/*------------------------------------------------------ */
//-- 将商品删除关联
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'drop_link_goods')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;

    check_authz_json('article_manage');

    $drop_goods     = $json->decode($_GET['drop_ids']);
    $arguments      = $json->decode($_GET['JSON']);
    $article_id     = $arguments[0];

    if ($article_id == 0)
    {
        $article_id = $db->getOne('SELECT MAX(article_id)+1 AS article_id FROM ' .$ecs->table('article'));
    }

    $sql = "DELETE FROM " . $ecs->table('goods_article').
            " WHERE article_id = '$article_id' AND goods_id " .db_create_in($drop_goods);
    $db->query($sql, 'SILENT') or make_json_error($db->error());

    /* 重新载入 */
    $arr = get_article_goods($article_id);
    $opt = array();

    foreach ($arr AS $key => $val)
    {
        $opt[] = array('value'  => $val['goods_id'],
                        'text'  => $val['goods_name'],
                        'data'  => '');
    }

    make_json_result($opt);
}

/*------------------------------------------------------ */
//-- 搜索商品
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'get_goods_list')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;

    $filters = $json->decode($_GET['JSON']);

    $arr = get_goods_list($filters);
    $opt = array();

    foreach ($arr AS $key => $val)
    {
        $opt[] = array('value' => $val['goods_id'],
                        'text' => $val['goods_name'],
                        'data' => $val['shop_price']);
    }

    make_json_result($opt);
}
/*------------------------------------------------------ */
//-- 批量操作
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'batch')
{
    /* 批量删除 */
    if (isset($_POST['type']))
    {
        if ($_POST['type'] == 'button_remove')
        {
            admin_priv('article_manage');

            if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
            {
                sys_msg($_LANG['no_select_article'], 1);
            }

            /* 删除原来的文件 */
            $sql = "SELECT file_url FROM " . $ecs->table('article') .
                    " WHERE article_id " . db_create_in(join(',', $_POST['checkboxes'])) .
                    " AND file_url <> ''";

            $res = $db->query($sql);
            while ($row = $db->fetchRow($res))
            {
                $old_url = $row['file_url'];
                if (strpos($old_url, 'http://') === false && strpos($old_url, 'https://') === false)
                {
                    @unlink(ROOT_PATH . $old_url);
                }
            }

            foreach ($_POST['checkboxes'] AS $key => $id)
            {
                if ($exc->drop($id))
                {
                    $name = $exc->get_name($id);
                    admin_log(addslashes($name),'remove','article');
                }
            }

        }

        /* 批量隐藏 */
        if ($_POST['type'] == 'button_hide')
        {
            check_authz_json('article_manage');
            if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
            {
                sys_msg($_LANG['no_select_article'], 1);
            }

            foreach ($_POST['checkboxes'] AS $key => $id)
            {
              $exc->edit("is_open = '0'", $id);
            }
        }

        /* 批量显示 */
        if ($_POST['type'] == 'button_show')
        {
            check_authz_json('article_manage');
            if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
            {
                sys_msg($_LANG['no_select_article'], 1);
            }

            foreach ($_POST['checkboxes'] AS $key => $id)
            {
              $exc->edit("is_open = '1'", $id);
            }
        }

        /* 批量移动分类 */
        if ($_POST['type'] == 'move_to')
        {
            check_authz_json('article_manage');
            if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']) )
            {
                sys_msg($_LANG['no_select_article'], 1);
            }

            if(!$_POST['target_cat'])
            {
                sys_msg($_LANG['no_select_act'], 1);
            }
            
            foreach ($_POST['checkboxes'] AS $key => $id)
            {
              $exc->edit("cat_id = '".$_POST['target_cat']."'", $id);
            }
        }
    }

    /* 清除缓存 */
    clear_cache_files();
    $lnk[] = array('text' => $_LANG['back_list'], 'href' => 'article.php?act=list');
    sys_msg($_LANG['batch_handle_ok'], 0, $lnk);
}


/*------------------------------------------------------ */
//-- 显示图片
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'show_image')
{

    if (isset($GLOBALS['shop_id']) && $GLOBALS['shop_id'] > 0)
    {
        $img_url = $_GET['img_url'];
    }
    else
    {
        if (strpos($_GET['img_url'], 'http://') === 0)
        {
            $img_url = $_GET['img_url'];
        }
        else
        {
            $img_url = '../' . $_GET['img_url'];
        }
    }
    $smarty->assign('img_url', $img_url);
    $smarty->display('goods_show_image.htm');
}

/*------------------------------------------------------ */
//-- 删除图片
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'drop_image')
{
    check_authz_json('article_manage');

    $img_id = empty($_REQUEST['img_id']) ? 0 : intval($_REQUEST['img_id']);

    /* 删除图片文件 */
    $sql = "SELECT img_url, thumb_url, img_original " .
            " FROM " . $GLOBALS['ecs']->table('article_wallpaper') .
            " WHERE img_id = '$img_id'";
    $row = $GLOBALS['db']->getRow($sql);

    if ($row['img_url'] != '' && is_file('../' . $row['img_url']))
    {
        @unlink('../' . $row['img_url']);
    }
    if ($row['thumb_url'] != '' && is_file('../' . $row['thumb_url']))
    {
        @unlink('../' . $row['thumb_url']);
    }
    if ($row['img_original'] != '' && is_file('../' . $row['img_original']))
    {
        @unlink('../' . $row['img_original']);
    }

    /* 删除数据 */
    $sql = "DELETE FROM " . $GLOBALS['ecs']->table('article_wallpaper') . " WHERE img_id = '$img_id' LIMIT 1";
    $GLOBALS['db']->query($sql);

    clear_cache_files();
    make_json_result($img_id);
}

/* 把商品删除关联 */
function drop_link_goods($goods_id, $article_id)
{
    $sql = "DELETE FROM " . $GLOBALS['ecs']->table('goods_article') .
            " WHERE goods_id = '$goods_id' AND article_id = '$article_id' LIMIT 1";
    $GLOBALS['db']->query($sql);
    create_result(true, '', $goods_id);
}

/* 取得文章关联商品 */
function get_article_goods($article_id)
{
    $list = array();
    $sql  = 'SELECT g.goods_id, g.goods_name'.
            ' FROM ' . $GLOBALS['ecs']->table('goods_article') . ' AS ga'.
            ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.goods_id = ga.goods_id'.
            " WHERE ga.article_id = '$article_id'";
    $list = $GLOBALS['db']->getAll($sql);

    return $list;
}

/* 获得文章列表 */
function get_articleslist()
{
    $result = get_filter();
    if ($result === false)
    {
        $filter = array();
        $filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keyword'] = json_str_iconv($filter['keyword']);
        }
        $filter['cat_id'] = empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']);
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'a.article_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $where = '';
        if (!empty($filter['keyword']))
        {
            $where = " AND a.title LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
        }
        if ($filter['cat_id'])
        {
            $where .= " AND a." . get_article_children($filter['cat_id']);
        }

        /* 文章总数 */
        $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('article'). ' AS a '.
               'LEFT JOIN ' .$GLOBALS['ecs']->table('article_cat'). ' AS ac ON ac.cat_id = a.cat_id '.
               'WHERE 1 ' .$where;
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 获取文章数据 */
        $sql = 'SELECT a.* , ac.cat_name '.
               'FROM ' .$GLOBALS['ecs']->table('article'). ' AS a '.
               'LEFT JOIN ' .$GLOBALS['ecs']->table('article_cat'). ' AS ac ON ac.cat_id = a.cat_id '.
               'WHERE 1 ' .$where. ' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];

        $filter['keyword'] = stripslashes($filter['keyword']);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $arr = array();
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $rows['date'] = local_date($GLOBALS['_CFG']['time_format'], $rows['add_time']);

        $arr[] = $rows;
    }
    return array('arr' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

/* 上传文件 */
function upload_article_file($upload)
{
    if (!make_dir("../" . DATA_DIR . "/article"))
    {
        /* 创建目录失败 */
        return false;
    }

    $filename = cls_image::random_filename() . substr($upload['name'], strpos($upload['name'], '.'));
    $path     = ROOT_PATH. DATA_DIR . "/article/" . $filename;

    if (move_upload_file($upload['tmp_name'], $path))
    {
        return DATA_DIR . "/article/" . $filename;
    }
    else
    {
        return false;
    }
}

?>