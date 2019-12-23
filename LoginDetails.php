<?php
require "init.php";
$rollNumber = $_POST['rollNumber'];

$mysql_qry1 = "select * from [ERPSMS].[dbo].[t_adm_student] where [ERPSMS].[dbo].[t_adm_student].s_rollnumber = '$rollNumber'";
$mysql_qry2 = "select fk_sem,Att_Date from [ERPSMS].[dbo].[T_Dept_Attendance] where [ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Rollnumber = '$rollNumber' order by [ERPSMS].[dbo].[T_Dept_Attendance].Att_Date ";

$result1 = sqlsrv_query($conn,$mysql_qry1);

$result2 = sqlsrv_query($conn,$mysql_qry2);

if($result1 == false || $result2 == false){
	die( print_r( "Server Error !!", true));
}

$row1 = sqlsrv_fetch_array($result1);
$row2 = sqlsrv_fetch_array($result2);

$response = array();


if(isset($row1['s_first_name']))
	array_push($response,$row1['s_first_name'],"&");
else
	array_push($response,"NA","&");

if(isset($row1['pass']))
	array_push($response,$row1['pass'],"&");
else
	array_push($response,"NA","&");

if(isset($row2['fk_sem']))
	array_push($response,$row2['fk_sem'],"&");
else
	array_push($response,"NA","&");

if(isset($row1['p_pass']))
	array_push($response,$row1['p_pass'],"&");	
else
	array_push($response,"NA","&");

if(isset($row2['Att_Date']))
	array_push($response,$row2['Att_Date']->format("Y-m-d"),"&","ERP@2017");	
else{
	$starting_date = Date("Y-m-d");
	array_push($response,$starting_date,"&","ERP@2017");
}
	
for($i = 0; $i < count($response) ;$i++)
{
	echo $response[$i];	
}


?>