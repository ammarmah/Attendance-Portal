<?php
require "init.php";
$rollNumber = $_POST['rollNumber'];

$mysql_qry3 = "select fk_sem,Att_Date from [ERPSMS].[dbo].[T_Dept_Attendance] where [ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Rollnumber = '$rollNumber' order by [ERPSMS].[dbo].[T_Dept_Attendance].Att_Date desc ";


$result3 = sqlsrv_query($conn,$mysql_qry3);

if($result3 == false){
	die( print_r( "", true));
}


$row3 = sqlsrv_fetch_array($result3);



if(isset($row3['Att_Date']))
	echo ($row3['Att_Date']->format("Y-m-d"));
else
	echo "NA";

?>