<?php

require "init.php";
$rollNumber = $_POST["rollNumber"];

$mysql_qry0 = "select fk_sem,Att_Date from [ERPSMS].[dbo].[T_Dept_Attendance] where [ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Rollnumber = '$rollNumber' order by [ERPSMS].[dbo].[T_Dept_Attendance].Att_Date ";
$result0 = sqlsrv_query($conn,$mysql_qry0);
$row0 = sqlsrv_fetch_array($result0);
$date = $row0['Att_Date']->format("Y-m-d");
$date2 = date( "Y-m-d", strtotime( "$date +6 day" ) );

$mysql_qry = "select [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_subjectname,
count([ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Statusid) as total 
from [ERPSMS].[dbo].[T_Dept_Attendance] inner join [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION] on
[ERPSMS].[dbo].[T_Dept_Attendance].Fk_Subject = [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_sb_mid and
[ERPSMS].[dbo].[T_Dept_Attendance].Fk_sem = [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_sem and
[ERPSMS].[dbo].[T_Dept_Attendance].Fk_course = [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_course
where [ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Rollnumber = '$rollNumber'
group by [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_subjectname";

$mysql_qry2 = "select [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_subjectname,
sum(CASE
    when [ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Statusid ='3' then 1
    else 0
    end) as present,
sum(CASE
    when [ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Statusid='1' then 1
    else 0
    end) as absent 
from [ERPSMS].[dbo].[T_Dept_Attendance] inner join [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION] on
[ERPSMS].[dbo].[T_Dept_Attendance].Fk_Subject = [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_sb_mid and
[ERPSMS].[dbo].[T_Dept_Attendance].Fk_sem = [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_sem and
[ERPSMS].[dbo].[T_Dept_Attendance].Fk_course = [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_course
where [ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Rollnumber = '$rollNumber' 
group by [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_subjectname";

$result = sqlsrv_query($conn,$mysql_qry);
$result2 = sqlsrv_query($conn,$mysql_qry2);

if($result == false || $result2 == false){
	die( print_r( "Server Error !!", true));
}

$response = array();

	while(($row = sqlsrv_fetch_array($result))&&($row2 = sqlsrv_fetch_array($result2)))
	{
			array_push($response,array('subjectname'=>utf8_encode($row['sa_subjectname']),'total'=>($row['total']),"present"=>$row2['present'],"leaves"=>$row2['absent']));
	}
	
		$jsonfile = json_encode(array("details"=>$response));
		
		echo $jsonfile;

?>