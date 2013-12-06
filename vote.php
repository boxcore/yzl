<?php

/**
 * ECSHOP 调查程序
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
$user_id = intval($_SESSION['user_id']);
$ip_address = real_ip();
if ($_REQUEST['act'] == 'vote')
{
	if (!isset($_REQUEST['vote']))
	{
	    ecs_header("Location: ./\n");
	    exit;
	}
	$vote_id    = intval($_POST['vote']);
	$sql = 'select vote_id, can_multi from '.$GLOBALS['ecs']->table('vote').' where vote_sheet_id='.$vote_id.' and start_time<='.time().' and end_time>='.time().' order by vote_id';
	$vote_list = $GLOBALS['db']->getAll($sql);
	foreach ($vote_list as $v)
	{
		if(vote_already_submited($v['vote_id'],$vote_id,$ip_address))
		{
			echo '您今天已经提交过了！';
			die;
		}
		if ($v['can_multi'] != 2){
			$o_id = substr($_POST['i_'.$v['vote_id']],0,-1);
			save_vote($v['vote_id'], $vote_id, $ip_address, $o_id);
		}else{
			$str_content = strip_tags(trim($_POST['i_'.$v['vote_id']]));
			$str_content .= "\t\n收货地址：".mysql_real_escape_string(strip_tags(trim($_POST['address'])));
			$str_content .= "\t\n联系方式：".mysql_real_escape_string(strip_tags(trim($_POST['mobil'])));
			if ($str_content)
			{
				$sql = "INSERT INTO " . $GLOBALS['ecs']->table('vote_log') . " (vote_id, vote_sheet_id, ip_address, vote_time, str_content, user_id) " .
			           "VALUES ('$v[vote_id]', '$vote_id', '$ip_address', " . gmtime() .", '$str_content', $user_id)";
			    $res = $GLOBALS['db']->query($sql);
			}
		}
		
	}
    echo '非常感谢您的投票！';
	die;
}
elseif ($_REQUEST['act'] == 'comment')
{
	if (!isset($_REQUEST['zt_id']))
	{
	    ecs_header("Location: ./\n");
	    exit;
	}
	$zt_id = intval($_REQUEST['zt_id']);
	$title = trim($_POST['title']);
	$content = trim($_POST['content']);
	if (!empty($title) && !empty($content))
	{
		if(comment_count($zt_id,$ip_address))
		{
			echo '您今天已经留言了5次，请明天再试！';
			die;
		}
		$sql = 'insert into '.$GLOBALS['ecs']->table('dczt_comment').' (zt_id, title, content, add_date, ip) values'."($zt_id,'$title','$content','".date('Y-m-d')."','$ip_address')";
		$db->query($sql);
		echo '谢谢您的留言，管理员审核完后就会出现在这里了！';
	}
	die;
}
/*------------------------------------------------------ */
//-- PRIVATE FUNCTION
/*------------------------------------------------------ */

/**
 * 检查是否已经提交过投票
 *
 * @access  private
 * @param   integer     $vote_id
 * @param   string      $ip_address
 * @return  boolean
 */
function vote_already_submited($vote_id,$vote_sheet_id, $ip_address)
{
    $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('vote_log')." ".
           "WHERE ip_address = '$ip_address' AND vote_id = '$vote_id' AND vote_sheet_id = $vote_sheet_id";

    return ($GLOBALS['db']->GetOne($sql) > 0);
}
/**
 * 检查是否已经提交留言超过5次
 *
 * @access  private
 * @param   integer     $vote_id
 * @param   string      $ip_address
 * @return  boolean
 */
function comment_count($zt_id, $ip_address)
{
    $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('dczt_comment')." ".
           "WHERE ip = '$ip_address' AND zt_id = $zt_id".' AND add_date="'.date('Y-m-d').'"';
    return ($GLOBALS['db']->GetOne($sql) >= 5);
}
/**
 * 保存投票结果信息
 *
 */
function save_vote($vote_id,$vote_sheet_id, $ip_address, $option_id)
{
	global $user_id;
    $sql = "INSERT INTO " . $GLOBALS['ecs']->table('vote_log') . " (vote_id, vote_sheet_id, ip_address, vote_time, str_content, user_id) " .
           "VALUES ('$vote_id', '$vote_sheet_id', '$ip_address', " . gmtime() .",'$option_id', $user_id)";
    $res = $GLOBALS['db']->query($sql);

    /* 更新投票主题的数量 */
    $sql = "UPDATE " .$GLOBALS['ecs']->table('vote'). " SET ".
           "vote_count = vote_count + 1 ".
           "WHERE vote_id = '$vote_id'";
    $GLOBALS['db']->query($sql);

    /* 更新投票选项的数量 */
    $sql = "UPDATE " . $GLOBALS['ecs']->table('vote_option') . " SET " .
           "option_count = option_count + 1 " .
           "WHERE " . db_create_in($option_id, 'option_id');
    $GLOBALS['db']->query($sql);
}

?>