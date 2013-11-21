<?php
//ecshop公共函数库,    由 /includes/init.php       映入

//数据库函数

/**
 * 格式化sql
 * @param $str
 * @return string
 */
function prepare( $sql, $array, $db = NULL ) {
	if( !is_array( $array ) ) {
		$array = func_get_args();
		$sql   = array_shift( $args );
	}
	
	$sql = str_replace( array( '?i', '?s' ), array( '%d', '"%s"' ), $sql );
	foreach( $array as $k => $v ) {
		$array[$k] = s( $v, $db );
	}
	return vsprintf( $sql, $array );
}

/**
 * 获取多行记录
 * @param $sql
 * @return array
 */
function get_data( $sql, $db = NULL ) {
	$result = run_sql( $sql, $db );
	$data = array();
	while( $array = mysql_fetch_assoc($result) ) {
		$data[] = $array;
	}
	mysql_free_result( $result );
	return !empty( $data ) ? $data : FALSE;
}


/**
 * 获取一行记录
 * @param $sql
 * @return array
 */
function get_line( $sql, $db = NULL ) {
	$result = run_sql( $sql, $db );
	$data = mysql_fetch_assoc($result);
	mysql_free_result($result);
	return $data;
}

/**
 * 以变量方式获取记录
 * @param $sql
 * @return mixed
 */
function get_var( $sql, $db = NULL ) {
	$data = get_line( $sql, $db );
	return ! is_array( $data ) ? NULL : @array_shift( $data );
}

/**
 * 获取上次INSERT_ID
 * @param $db
 * @return number
 */
function last_id( $db = NULL ) {
	return get_var( 'SELECT LAST_INSERT_ID() ', $db );
}

//获取名称相关
