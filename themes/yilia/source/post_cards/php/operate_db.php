<?php
include "config.php";

function CreateTable()
{
	global $app;
	$create_db = 'CREATE DATABASE '.$app['db_name'];
	$create_table = $app['create_table'];
	$con = mysqli_connect($app['db_host'],$app['db_user_name'],$app['db_user_passwd']);
	if (!$con)
	{
		die('Could not connect: ' . mysqli_error($con));
		return 0;
	}

	// Create database
	//if (mysqli_query($con, $create_db))
	if (1)
	{
		echo "Database created\n";
	}
	else
	{
		echo "Error creating database: " . mysqli_error($con);
		mysqli_close($con);
		return 0;
	}

	echo "select db\n";
	// Create table  database
	mysqli_select_db($con, $app['db_name']);
	
	echo "query db:$create_table\n";
	mysqli_query($con, $create_table);
	echo "select database: " . mysqli_error($con)."\n";

	echo "close db\n";
	mysqli_close($con);

}


CreateTable();

?>
