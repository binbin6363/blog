<?php
include "config.php"


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
	$con = mysql_connect($app['db_host'],$app['db_user_name'],$app['db_user_passwd']);
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
		return 0;
	}

	mysql_select_db($app['db_name'], $con);
	$sql="INSERT INTO t_card_list(FRecvName, FRecvAddr, FMessage, FSendName, FSendPhone, FCreateTime, FUpdateTime)
	VALUES
	('$recv_name','$recv_addr','$message', '$send_name', '$send_phone', null, null)";

	if (!mysql_query($sql,$con))
	{
		die('Error: ' . mysql_error());
	}

	echo "请求写入成功";

	mysql_close($con)
}


function QueryRecord($condition, $limit)
{
	global $app;
	$con = mysql_connect($app['db_host'],$app['db_user_name'],$app['db_user_passwd']);
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	mysql_select_db($app['db_name'], $con);
	$sql="SELECT Fid, FRecvName, FRecvAddr, FMessage, FSendName, FSendPhone, FCreateTime, FUpdateTime 
		FROM t_card_list 
		WHERE $condition 
		LIMIT $limit";

	$result = mysql_query($sql);

	echo "<table border='1'>
	<tr>
	<th>序号</th>
	<th>收件人</th>
	<th>收件地址</th>
	<th>留言信息</th>
	<th>发件人</th>
	<th>发件人手机</th>
	<th>创建时间</th>
	<th>更新时间</th>
	</tr>";

	while($row = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo "<td>" . $row['Fid'] . "</td>";
		echo "<td>" . $row['FRecvName'] . "</td>";
		echo "<td>" . $row['FRecvAddr'] . "</td>";
		echo "<td>" . $row['FMessage'] . "</td>";
		echo "<td>" . $row['FSendName'] . "</td>";
		echo "<td>" . $row['FSendPhone'] . "</td>";
		echo "<td>" . $row['FCreateTime'] . "</td>";
		echo "<td>" . $row['FUpdateTime'] . "</td>";
		echo "</tr>";
	}
	echo "</table>";

	mysql_close($con)
}

$type = $_POST['request_type'];
if ($type == 'query') {
	$condition = ''
	$send_name = $_POST['send_name'];
	if ($send_name != '') {
		$condition .= ' where FSendName='.$send_name
	}
	$send_name = $_POST['recv_name'];
	if ($send_name != '') {
		$condition .= ' and FRecvName='.$recv_name
	}
	$limit = 100;
	QueryRecord($condition, $limit)
} 

if ($type == 'insert') {
	$recv_name = $_POST['recv_name']; // 明信片收件人作为发送标题
	$recv_addr = $_POST['recv_addr']; // 收件人地址
	$send_name = $_POST['send_name']; // 明信片收件人作为发送标题
	$email = '1366666@qq.com';//$_POST['email'];    // 请求寄送明信片发起者的邮箱
	$message = $_POST['message'];// 想说的话
	$send_phone = $_POST['send_phone'];    // 寄件人的电话

	AddRecord($recv_name,$recv_addr,$message, $send_name, $send_phone)
}

?>
