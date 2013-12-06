<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

//初始化session
$_SESSION['survey'][]

$vote_id =  get_vote_id('3-1');
$vote_info = get_vote_info($vote_id);
//$vote_sheet_id = 3;
//print_r(get_vote_sheet_info(3));
//$smarty->assign('vote_arr',     get_vote_sheet_info($vote_sheet_id));
//$smarty->assign('vote_id',     intval($vote_sheet_id));
//$smarty->assign('is_news_index',     1);
//$smarty->assign('sjs',     rand(0, 1000000)/1000000);//随机数
//$ip = real_ip(); //用户IP
//if (vote_already_submited($vote_sheet_id,$ip)){
//	$smarty->assign('submited',     1);
//}
$smarty->assign('vote_info', $vote_info);
$smarty->display('survey.dwt');

/**
 * 获取问题和问题选项
 * @param $vote_id
 * @return array
 */
function get_vote_info($vote_id){
    $vote_id = intval($vote_id);
    $row = $GLOBALS['db']->getRow('select * from '.$GLOBALS['ecs']->table('vote').' where vote_id='.$vote_id);
    $sql = 'select * from '.$GLOBALS['ecs']->table('vote_option').' where vote_id='.$vote_id.' order by option_mark ,option_id';
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

/**
 * 获取调查主题
 * @param $sheet_id
 * @return array
 */
function get_vote_sheet_info($sheet_id)
{
	$sheet_id = intval($sheet_id);
	$is_open = $GLOBALS['db']->getOne('select is_open from '.$GLOBALS['ecs']->table('vote_sheet').' where id='.$sheet_id);
	$list = array();
	if ($is_open)
	{
		$sql = 'select * from '.$GLOBALS['ecs']->table('vote').' where vote_sheet_id='.$sheet_id.' and start_time<='.time().' and end_time>='.time().' order by vote_id';
		$vote_list = $GLOBALS['db']->getAll($sql);
		$list['js_str'] = "option = ''";
		$i = 0;
		foreach ($vote_list as $v)
		{
			if ($v['can_multi'] != 2){
				$list['js_checked'] .= "var q_$v[vote_id]='';\t\n $(\"input[name='q-$v[vote_id]']:checked\").each(function(){q_$v[vote_id] += $(this).val()+',';});\t\n";
			}else{
				$list['js_checked'] .= "var q_$v[vote_id]='';\t\n q_$v[vote_id] = $(\"#q-$v[vote_id]\").val();\t\n";
			}
			$list['js_str'] .= "+'&i_".$v['vote_id']."='+q_$v[vote_id]";
			$list['opt']['i_'.$v['vote_id']]['title'] = $v['vote_name'];
			switch ($v['can_multi']){
				case 0:
					$list['opt']['i_'.$v['vote_id']]['type'] = $type = 2;
					break;
				case 1:
					$list['opt']['i_'.$v['vote_id']]['type'] = $type = 1;
					break;
				case 2:
					$list['opt']['i_'.$v['vote_id']]['type'] = $type = 3;
                case 3:
                    $list['opt']['i_'.$v['vote_id']]['type'] = $type = 4;
			}
			$list['opt']['i_'.$v['vote_id']]['name'] = 'q-'.$v['vote_id'];
			$list['js_str2'] .= "ids[$i]=new Array($v[vote_id],$type);";
			$i++;
			//$list['js_checked'] .= "var i_$v[vote_id]=''; $(\"input[name='i_$v[vote_id]']:checked\").each(function(){i_$v[vote_id] += $(this).val()+',';});\t\n";
			//$list['js_str'] .= "+'&i_".$v['vote_id']."='+i_$v[vote_id]";
			$sql = 'select * from '.$GLOBALS['ecs']->table('vote_option').' where vote_id='.$v['vote_id'].' order by option_order,option_id';
			$res = $GLOBALS['db']->query($sql);
			while ($rows = $GLOBALS['db']->fetchRow($res))
		    {
		    	if ($v['can_multi']==0)
		    	{
		    		$rows['option_name'] = "<label for='o_$rows[option_id]'><input type='checkbox' value='$rows[option_id]' name='q-$v[vote_id]' id='o_$rows[option_id]'/>&nbsp;$rows[option_name]</label>";
		    	}
		    	elseif($v['can_multi']==1)
                {
                    $rows['option_name'] = "<label for='o_$rows[option_id]'><input type='radio' value='$rows[option_id]' name='q-$v[vote_id]' id='o_$rows[option_id]'/>&nbsp;{$rows[option_mark]}、$rows[option_name]</label>";
                }
                elseif($v['can_multi']==3)
                {
                    $rows['option_name'] = "<label for='o_$rows[option_id]'><input type='radio' value='$rows[option_id]' name='q-$v[vote_id]' id='o_$rows[option_id]'/>&nbsp;$rows[option_name]</label>";
                }
		    	elseif ($v['can_multi']==1)
		    	{
		    		
		    	}
		    	$list['opt']['i_'.$v['vote_id']]['items'][$rows['option_id']] = $rows['option_name'];
		    }
		}
		return $list;
	}
	else 
	{
		return ;
	}
}
/**
 * 检查是否已经提交过投票
 *
 * @access  private
 * @param   integer     $vote_id
 * @param   string      $ip_address
 * @return  boolean
 */
function vote_already_submited($vote_sheet_id, $ip_address)
{
    $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('vote_log')." ".
           "WHERE ip_address = '$ip_address' AND vote_sheet_id = $vote_sheet_id";

    return ($GLOBALS['db']->GetOne($sql) > 0);
}
?>