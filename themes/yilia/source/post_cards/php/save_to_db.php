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

function AddRecord($recv_name,$recv_addr,$message, $send_name, $send_phone, $card_id)
{
	global $app;
	$return_array = array();
    $return_array['success'] = true;
    $return_array['msg'] = 'ok';
	$con = mysqli_connect($app['db_host'],$app['db_user_name'],$app['db_user_passwd']);
	if (mysqli_connect_errno())
	{
		writeLog("connect db failed".mysqli_error($con));
		//$return_array['msg'] = 'Could not connect: ' . mysqli_error($con);
		die('Could not connect: ' . mysqli_error($con));
		$return_array['msg'] = "数据库错误，明信片寄送失败，请稍后重试!";
		echo json_encode($return_array);
		return;
	}
	
	mysqli_set_charset($con,"utf8");

	mysqli_select_db($con, $app['db_name']);
	$sql="update t_card_num set FCardNum=FCardNum-1 where FCardId='$card_id' limit 1";
	//writeLog("update db:".$sql);

	if (!mysqli_query($con, $sql))
	{
		writeLog("update t_card_num failed".mysqli_error($con));
		$err_msg = mysqli_error($con);
		// 回滚
		//mysqli_select_db($con, $app['db_name']);
		$sql="update t_card_num set FCardNum=FCardNum+1 where FCardId='.$card_id.' limit 1";
		mysqli_query($con, $sql);
		mysqli_close($con);
		$return_array['msg'] = "明信片寄送失败，请稍后重试!";
		echo json_encode($return_array);
		die('Error: ' . $err_msg);
		return;
	}

	//mysqli_select_db($con, $app['db_name']);
	$sql="INSERT INTO t_card_list(FCardId, FRecvName, FRecvAddr, FMessage, FSendName, FSendPhone, FCreateTime, FUpdateTime)
	VALUES
	('$card_id', '$recv_name','$recv_addr','$message', '$send_name', '$send_phone', null, null)";

	if (!mysqli_query($con, $sql))
	{
		writeLog("insert t_card_list failed, ".mysqli_error($con));
		mysqli_close($con);
		$return_array['msg'] = "明信片寄送失败，请稍后重试!";
		echo json_encode($return_array);
		die('Error: ' . mysqli_error($con));
		return;
	}

	//echo "请求写入成功";

	mysqli_close($con);

	echo json_encode($return_array);
}


function QueryRecord($condition, $limit)
{
	global $app;
	$con = mysqli_connect($app['db_host'],$app['db_user_name'],$app['db_user_passwd']);
	if (!$con)
	{
		die('Could not connect: ' . mysqli_error($con));
		writeLog("connect db failed".mysqli_connect_error());
		return;
	}

	/* check connection */
	if (mysqli_connect_errno()) {
	    //printf("Connect failed: %s\n", mysqli_connect_error());
	    die('Could not connect: ' . mysqli_connect_error());
		writeLog("connect db failed".mysqli_connect_error());
	    return ;
	}

	mysql_query("set character set 'utf8'");//读库 
	mysql_query("set names 'utf8'");//写库 
	mysqli_set_charset($con,"utf8");

	mysqli_select_db($con, $app['db_name']);

	/* check connection */
	if (mysqli_connect_errno()) {
	    //printf("Connect failed: %s\n", mysqli_connect_error());
	    die('Could not select db: ' . mysqli_connect_error());
		writeLog("select db failed".mysqli_connect_error());
    	mysqli_close($con);
		return;
	}


	$sql="SELECT Fid, FRecvName, FRecvAddr, FMessage, FSendName, FSendPhone, FCreateTime, FUpdateTime, FCardStatus
		FROM t_card_list ";
	if($condition != '')
	{
		$sql = $sql.$condition;
	}

	$result = mysqli_query($con, $sql);
	/* check connection */
	if (mysqli_connect_errno()) {
	    //printf("Connect failed: %s\n", mysqli_connect_error());
	    die('Could not query db: ' . mysqli_connect_error());
		writeLog("query db failed".mysqli_connect_error());
    	mysqli_close($con);
		return;
	}

	//writeLog("query db result:".$result);

	echo "<table class=\"table table-bordered\">
	<tr>
	<th>序号</th>
	<th>发件人</th>
	<th>创建时间</th>
	<th>寄送状态</th>
	</tr>";

	while($row = mysqli_fetch_array($result, MYSQLI_BOTH))
	{
		echo "<tr>";
		echo "<td>" . $row['Fid'] . "</td>";
		echo "<td>" . $row['FSendName'] . "</td>";
		echo "<td>" . $row['FCreateTime'] . "</td>";
		$status = $row['FCardStatus'];
		$status_str = '';
		if ($status == '0') {
			$status_str = '未寄送';
		} else if ($status == '1') {
			$status_str = '已投递';
		} else if ($status == '2') {
			$status_str = '被拒绝';
		}
		echo "<td>" . $status_str . "</td>";
		echo "</tr>";
	}
	echo "</table>";

	/* free result set */
	mysqli_free_result($result);
	mysqli_close($con);
}

function QueryLeftCard()
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
		return;
	}
	mysql_query("set character set 'utf8'");//读库 
	mysql_query("set names 'utf8'");//写库 
	mysqli_set_charset($con,"utf8");

	/* check connection */
	if (mysqli_connect_errno()) {
	    //printf("Connect failed: %s\n", mysqli_connect_error());
	    die('Could not connect: ' . mysqli_connect_error());
		writeLog("connect db failed".mysqli_connect_error());
		$return_array['msg'] = "connect db failed".mysqli_connect_error();
		echo json_encode($return_array);
	    return ;
	}

	mysqli_select_db($con, $app['db_name']);

	/* check connection */
	if (mysqli_connect_errno()) {
	    //printf("Connect failed: %s\n", mysqli_connect_error());
	    die('Could not select db: ' . mysqli_connect_error());
		writeLog("select db failed".mysqli_connect_error());
    	mysqli_close($con);
		$return_array['msg'] = "select db failed, ".mysqli_connect_error();
		echo json_encode($return_array);
		return;
	}


	$sql="SELECT SUM(FTotalCardNum) as \"TotalNum\", SUM(FCardNum) as \"LeftNum\" FROM t_card_num";

	$return_array['res'] = array();

	$result = mysqli_query($con, $sql);
	/* check connection */
	if (mysqli_connect_errno()) {
	    //printf("Connect failed: %s\n", mysqli_connect_error());
	    die('Could not query db: ' . mysqli_connect_error());
		writeLog("query db failed".mysqli_connect_error());
    	mysqli_close($con);
		return;
	}

	while($row = mysqli_fetch_array($result, MYSQLI_BOTH))
	{
		$return_array['res']['TotalNum'] = $row['TotalNum'];
		$return_array['res']['LeftNum'] = $row['LeftNum'];
		break;
	}

	/* free result set */
	mysqli_free_result($result);
	mysqli_close($con);
	echo json_encode($return_array);
}

$type = $_POST['request_type'];
if ($type == 'query') {
	$condition = '';
	$send_name = '';//$_POST['send_name'];
	if ($send_name != '') {
		$condition .= ' where FSendName='.$send_name;
	}
	$send_name = '';//$_POST['recv_name'];
	if ($send_name != '') {
		$condition .= ' and FRecvName='.$recv_name;
	}
	$limit = 100;
	QueryRecord($condition, $limit);
} 

if ($type == 'insert') {
	$recv_name = $_POST['recv_name']; // 明信片收件人作为发送标题
	$recv_addr = $_POST['recv_addr']; // 收件人地址
	$send_name = $_POST['send_name']; // 明信片收件人作为发送标题
	$email = '1366666@qq.com';//$_POST['email'];    // 请求寄送明信片发起者的邮箱
	$message = $_POST['message'];// 想说的话
	$send_phone = $_POST['send_phone'];    // 寄件人的电话
	$card_id = $_POST['card_id'];

	AddRecord($recv_name,$recv_addr,$message, $send_name, $send_phone, $card_id);

}

if ($type == 'query_left') {
	QueryLeftCard();
}

die();
?>
