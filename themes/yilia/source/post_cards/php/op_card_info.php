<?php
include "config.php";
include "log.php";


function Reply($ret_val, $err_msg)
{
	$return_array = array();
	$return_array['ret'] = $ret_val;
	$return_array['err_msg'] = $err_msg;
	echo json_encode($return_array);
}

function AddRecord($recv_name,$recv_addr,$message, $send_name, $send_phone)
{
	global $app;
	$con = mysqli_connect($app['db_host'],$app['db_user_name'],$app['db_user_passwd']);
	if (!$con)
	{
		die('Could not connect: ' . mysqli_error($con));
		return 0;
	}
	mysql_query("set character set 'utf8'");//读库 
	mysql_query("set names 'utf8'");//写库 

	mysqli_select_db($con, $app['db_name']);
	$sql="INSERT INTO t_card_list(FRecvName, FRecvAddr, FMessage, FSendName, FSendPhone, FCreateTime, FUpdateTime)
	VALUES
	('$recv_name','$recv_addr','$message', '$send_name', '$send_phone', null, null)";

	if (!mysqli_query($con, $sql))
	{
		die('Error: ' . mysqli_error($con));
	}

	echo "请求写入成功";

	mysqli_close($con);
}


function QueryCardInfo($condition, $limit)
{
	global $app;
	$return_array = array();
    $return_array['success'] = true;
    $return_array['msg'] = 'ok';

	$con = mysqli_connect($app['db_host'],$app['db_user_name'],$app['db_user_passwd']);
	if (!$con)
	{
		die('Could not connect: ' . mysqli_error($con));
		writeLog("connect db failed".mysqli_connect_error());
	    $return_array['success'] = false;
	    $return_array['msg'] = "connect db failed, ".mysqli_connect_error();
		return $return_array;
	}
	mysql_query("set character set 'utf8'");//读库 
	mysql_query("set names 'utf8'");//写库 

	/* check connection */
	if (mysqli_connect_errno()) {
	    //printf("Connect failed: %s\n", mysqli_connect_error());
	    die('Could not connect: ' . mysqli_connect_error());
		writeLog("connect db failed, ".mysqli_connect_error());
	    $return_array['success'] = false;
	    $return_array['msg'] = "connect db failed, ".mysqli_connect_error();
	    return $return_array;
	}

	mysqli_select_db($con, $app['db_name']);

	/* check connection */
	if (mysqli_connect_errno()) {
	    //printf("Connect failed: %s\n", mysqli_connect_error());
	    die('Could not select db: ' . mysqli_connect_error());
		writeLog("select db failed".mysqli_connect_error());
    	mysqli_close($con);
	    $return_array['success'] = false;
	    $return_array['msg'] = "select db failed, ".mysqli_connect_error();
	    return $return_array;
	}

	$sql="SELECT Fid, FCardId, FCardName, FCardNum, FStatus
		FROM ".$app['table_card_info_name']." ";
	if($condition != '')
	{
		$sql = $sql.$condition;
	}

    $return_array['res'] = array();

	$result = mysqli_query($con, $sql);
	/* check connection */
	if (mysqli_connect_errno()) {
	    //printf("Connect failed: %s\n", mysqli_connect_error());
	    die('Could not query db: ' . mysqli_connect_error());
		writeLog("query db failed".mysqli_connect_error());
    	mysqli_close($con);
	    $return_array['success'] = false;
	    $return_array['msg'] = "query db failed, ".mysqli_connect_error();
	    return $return_array;
	}

	$index = 0;
	while($row = mysqli_fetch_array($result, MYSQLI_BOTH))
	{
		$row_array = array();
		$row_array['FCardId'] = $row['FCardId'];
		$row_array['FCardName'] = $row['FCardName'];
		$row_array['FCardNum'] = $row['FCardNum'];
		$row_array['FStatus'] = $row['FStatus'];

		$return_array['res'][$index] = $row_array;
		$index += 1;
	}

	/* free result set */
	mysqli_free_result($result);
	mysqli_close($con);

	echo json_encode($return_array);
}

$type = $_POST['request_type'];
if ($type == 'query_card_info') {
	$condition = '';
	$send_name = '';
	$limit = 100;
	QueryCardInfo($condition, $limit);
} 

die();
?>
