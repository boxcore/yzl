<?php

/**
 * ECSHOP 文章分类
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.xxoopp.org；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: article_cat.php 17217 2011-01-19 06:29:08Z liubo $
*/


define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}

/* 清除缓存 */
clear_cache_files();

/*------------------------------------------------------ */
//-- INPUT
/*------------------------------------------------------ */

/* 获得指定的分类ID */
if (!empty($_GET['id']))
{
    $cat_id = intval($_GET['id']);
}
elseif (!empty($_GET['category']))
{
    $cat_id = intval($_GET['category']);
}

/* 代码增加_start  By thunje#URLdf */
elseif(!empty($_REQUEST['defurl']))
{
	$define_url=trim($_REQUEST['defurl']);
	$cat_id=$db->getOne("select cat_id from ". $ecs->table('article_cat') ." where define_url='$define_url'  limit 0,1");
	$cat_id=$cat_id ? $cat_id : intval($define_url);
    $cat_info = $db->getRow("SELECT * FROM " . $ecs->table('article_cat') . " WHERE cat_id = '$cat_id'");//boxcore 获取分类信息

}
/* 代码增加_end  By thunje#URLdf */

else
{
    ecs_header("Location: ./\n");

    exit;
}

/* 获得当前页码 */
$page   = !empty($_REQUEST['page'])  && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;

/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */

/* 获得页面的缓存ID */
$cache_id = sprintf('%X', crc32($cat_id . '-' . $page . '-' . $_CFG['lang']));

if (!$smarty->is_cached('article_cat.dwt', $cache_id))
{
    /* 如果页面没有被缓存则重新获得页面的内容 */

    assign_template('a', array($cat_id));
    $position = assign_ur_here($cat_id);
    $smarty->assign('page_title',           $position['title']);     // 页面标题
    $smarty->assign('ur_here',              $position['ur_here']);   // 当前位置

    $smarty->assign('categories',           get_categories_tree(0)); // 分类树
    $smarty->assign('article_categories',   article_categories_tree1($cat_id)); //文章分类树
    $smarty->assign('helps',                get_shop_help());        // 网店帮助
    $smarty->assign('top_goods',            get_top10());            // 销售排行
    $smarty->assign('cat_info',             $cat_info);            // 获取分类信息 by boxcore

    $smarty->assign('best_goods',           get_recommend_goods('best'));
    $smarty->assign('new_goods',            get_recommend_goods('new'));
    $smarty->assign('hot_goods',            get_recommend_goods('hot'));
    $smarty->assign('promotion_goods',      get_promote_goods());
    $smarty->assign('promotion_info', get_promotion_info());

    /* Meta */
    $meta = $db->getRow("SELECT keywords, cat_desc, cat_detail, cat_type FROM " . $ecs->table('article_cat') . " WHERE cat_id = '$cat_id'");
    $cat_type = $meta['cat_type'];

    if ($meta === false || empty($meta))
    {
        /* 如果没有找到任何记录则返回首页 */
        ecs_header("Location: ./\n");
        exit;
    }

    $smarty->assign('keywords',    htmlspecialchars($meta['keywords']));
    $smarty->assign('description', htmlspecialchars($meta['cat_desc']));
    $smarty->assign('cat_detail', $meta['cat_detail']);

    /* 获得文章总数 */
    $size   = isset($_CFG['article_page_size']) && intval($_CFG['article_page_size']) > 0 ? intval($_CFG['article_page_size']) : 20;
    $count  = get_article_count($cat_id);
    $pages  = ($count > 0) ? ceil($count / $size) : 1;

    if ($page > $pages)
    {
        $page = $pages;
    }
    $pager['search']['id'] = $cat_id;
    $keywords = '';
    $goon_keywords = ''; //继续传递的搜索关键词

    /* 获得文章列表 */
    if (isset($_REQUEST['keywords']))
    {
        $keywords = addslashes(htmlspecialchars(urldecode(trim($_REQUEST['keywords']))));
        $pager['search']['keywords'] = $keywords;
        $search_url = substr(strrchr($_POST['cur_url'], '/'), 1);

        $smarty->assign('search_value',    stripslashes(stripslashes($keywords)));
        $smarty->assign('search_url',       $search_url);
        $count  = get_article_count($cat_id, $keywords);
        $pages  = ($count > 0) ? ceil($count / $size) : 1;
        if ($page > $pages)
        {
            $page = $pages;
        }

        $goon_keywords = urlencode($_REQUEST['keywords']);
    }
    // echo $page,$count,$size;
    $cat_page_info['tot_page'] = ceil($count/$size);
    $cat_page_info['size'] = $size;
    $cat_page_info['page'] = $page;
    $smarty->assign('cat_page_info', $cat_page_info);
    $smarty->assign('artciles_list',    get_cat_articles($cat_id, $page, $size ,$keywords));
    
    $smarty->assign('cat_id',    $cat_id);
    /* 分页 */
    assign_pager('article_cat', $cat_id, $count, $size, '', '', $page, $goon_keywords);
    assign_dynamic('article_cat');
}

$smarty->assign('feed_url',         ($_CFG['rewrite'] == 1) ? "feed-typearticle_cat" . $cat_id . ".xml" : 'feed.php?type=article_cat' . $cat_id); // RSS URL
// echo '<pre>';print_r($cat_info);echo '</pre>';exit;

/* use themes */
if( isset($cat_info['define_theme']) && !empty($cat_info['define_theme']) ){
    $article_cat_themes = $cat_info['define_theme'];
} else {
    switch($cat_info['cat_type']){
        // case 1:
        //     $article_cat_themes = 'article_cat_culture.dwt';
        //     break;
        case 7://壁纸下载模板
            $article_cat_themes = 'article_cat_wallpaper.dwt';
            break;
        case 8://视频广告模板
            $article_cat_themes = 'article_cat_vedio.dwt';
            break;
        case 9://招聘分类模板
            $article_cat_themes = 'article_cat_hr.dwt';
            break;
        case 10://新闻分类模板
            $article_cat_themes = 'article_cat_news.dwt';
            break;
        default:
            $article_cat_themes = 'article_cat.dwt';
    }
}
echo "<!-- \ncat_info:\n";print_r($cat_info);echo "-->";
$article_cat_themes = $article_cat_themes?$article_cat_themes:'article_cat.dwt';
$smarty->display($article_cat_themes, $cache_id);
?>