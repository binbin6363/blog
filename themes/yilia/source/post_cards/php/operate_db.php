<?php
include "config.php"

function CreateTable()
{
	global $app;
	$create_db = 'CREATE DATABASE '.$app['db_name']
	$create_table = $app['create_table']
	$con = mysql_connect($app['db_host'],$app['db_user_name'],$app['db_user_passwd']);
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
		return 0;
	}

	// Create database
	if (mysql_query($create_db,$con))
	{
		echo "Database created";
	}
	else
	{
		echo "Error creating database: " . mysql_error();
		mysql_close($con);
		return 0;
	}

	// Create table  database
	mysql_select_db($app['db_name'], $con);
	
	mysql_query($create_table, $con);

	mysql_close($con);

}


CreateTable();

?>