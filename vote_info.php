<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
if (!isset($_REQUEST['vote']))
{
    ecs_header("Location: ./\n");
    exit;
}
$vote_id    = intval($_REQUEST['vote']);
$list = array();
$total_p = $db->getOne('select count(distinct ip_address) from '.$ecs->table('vote_log').' where vote_sheet_id='.$vote_id);
$sql = 'select vote_id,vote_name from '.$ecs->table('vote').' where vote_sheet_id='.$vote_id.' and can_multi<>2 order by vote_id';
$arr = $db->getAll($sql);

//print_r($list);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="http://www.bjcang.cn/favicon.ico">
<link href="/themes/bjcang/css/vote_info.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="body">
<table style="visibility: visible;" id="hot" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td>&nbsp;</td><td id="border" width="50"><nobr><b>人气值：</b><span id="hot_value"><?php echo intval($total_p);?></span></nobr></td></tr></tbody></table>
<div id="main">
<?php foreach ($arr as $v){?>
<div class="question result">
	<div class="title f14px">
		<button class="icon_list">&nbsp;</button><?php echo $v['vote_name'];?> 
	</div>
	<div class="content line">
	<div class="nodetail"></div>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody>
		<?php 
		$sql = 'select option_name,option_count from '.$ecs->table('vote_option').' where vote_id='.$v['vote_id'].' order by option_order,option_id';
		$rss = $db->query($sql);
		while ($rows = $db->fetchRow($rss))
		{
		?>
		<tr>
			<td style="padding-right: 20px;" width="430">
				<span class="option"><?php echo $rows['option_name'];?></span>
			</td>
			<td width="170"><div class="process" style="display: block;"><div class="style<?php echo intval(rand(0, 9));?>" style="width:<?php if(intval($total_p)){echo round(intval($rows['option_count'])/intval($total_p)*100);}else{echo 0;};?>%;"></div></div></td>
			<td class="black" style="display: block;" width="110"><nobr><?php echo $rows['option_count'];?> (<?php if(intval($total_p)){echo round(intval($rows['option_count'])/intval($total_p)*100);}else{echo 0;};?>%)</nobr></td>
		</tr>
		<?php }?>
		</tbody>
	</table>
	</div>
</div>
<?php }?>
</div></div>
</body></html>