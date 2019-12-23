<?php

require "init.php";
$rollNumber = $_POST["rollNumber"];
$date = $_POST["date"];
$curr = date("Y-m-d");


 $sqlsrv_query = "select [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_subjectname,
sum(CASE
    when [ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Statusid='1' then 1
    else 0
    end) as absent,
sum(CASE
    when [ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Statusid ='3' then 1
    else 0
    end) as present    
from [ERPSMS].[dbo].[T_Dept_Attendance] inner join [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION] on
[ERPSMS].[dbo].[T_Dept_Attendance].Fk_Subject = [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_sb_mid 
and [ERPSMS].[dbo].[T_Dept_Attendance].fk_course = [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_course 
and [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_sem =[ERPSMS].[dbo].[T_Dept_Attendance].Fk_sem
where T_Dept_Attendance.Fk_s_Rollnumber = '$rollNumber' and [ERPSMS].[dbo].[T_Dept_Attendance].Att_Date between '$date' and '$curr'
group by [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_subjectname"; 


$result = sqlsrv_query($conn,$sqlsrv_query);

if($result == false){
	die( print_r( "Server Error !!", true));
}

$response = array();

	while($row = sqlsrv_fetch_array($result))
	{		
		array_push($response,array('subjectname'=>utf8_encode($row['sa_subjectname']),'present'=>$row['present'],'absent'=>$row['absent']));
	
	}
		$jsonfile = json_encode(array("details"=>$response));
		echo $jsonfile;

?>
