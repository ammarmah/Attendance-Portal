<?php

require "init.php";
$rollNumber = $_GET["rollNumber"];
$date = $_GET["date"];
$curr = date("Y-m-d");
/*work*/
//

$mysql_qry1 = "select count(distinct [ERPSMS].[dbo].[T_Dept_Attendance].Fk_period) as lectures,
[ERPSMS].[dbo].[T_Dept_Attendance].Att_Date from [ERPSMS].[dbo].[T_Dept_Attendance] where 
[ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Rollnumber = '$rollNumber' 
and [ERPSMS].[dbo].[T_Dept_Attendance].Att_Date between '$date' 
and '$curr' group by [ERPSMS].[dbo].[T_Dept_Attendance].Att_Date 
order by [ERPSMS].[dbo].[T_Dept_Attendance].Att_Date";

$mysql_qry2 = "select [ERPSMS].[dbo].[T_Dept_Attendance].Att_Date,[ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Statusid,[ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_subjectname,
[ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Statusid as status,[ERPSMS].[dbo].[T_Dept_Attendance].Fk_period 
from [ERPSMS].[dbo].[T_Dept_Attendance] inner join [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION] on
[ERPSMS].[dbo].[T_Dept_Attendance].Fk_Subject = [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_sb_mid and 
[ERPSMS].[dbo].[T_Dept_Attendance].Fk_sem = [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_sem and
[ERPSMS].[dbo].[T_Dept_Attendance].Fk_course = [ERPSMS].[dbo].[T_Reg_SUBJECTALLOCATION].sa_course
where [ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Rollnumber = '$rollNumber' and [ERPSMS].[dbo].[T_Dept_Attendance].Att_Date between '$date' and '$curr'
order by [ERPSMS].[dbo].[T_Dept_Attendance].Att_Date,[ERPSMS].[dbo].[T_Dept_Attendance].Fk_period,[ERPSMS].[dbo].[T_Dept_Attendance].Fk_s_Statusid DESC";

 $result1 = sqlsrv_query($conn,$mysql_qry1);
$result2 = sqlsrv_query($conn,$mysql_qry2);

if($result1 == false || $result2 == false){
	die( print_r( "Server Error !!", true));
}

$response = array();
$previous = null;

			while($row1 = sqlsrv_fetch_array($result1))
			{	
				$noOfLecture = $row1['lectures'];
				$a = array();
				$b = array();
				
				for($x = 0 ; $x < $noOfLecture ; $x++)
				{
					$row2 = sqlsrv_fetch_array($result2);
					
					while($row2 == $previous)
					{
						$row2 = sqlsrv_fetch_array($result2);
					}
					array_push($a,$row2['Fk_period']."&".utf8_encode(($row2['sa_subjectname'])));
					array_push($b,$row2['status']);
					$previous = $row2;
					unset($row2);
				}
				
				$c = array("noOfLecture" => $noOfLecture,"date"=> $row1['Att_Date']);
				$c = array_merge($c,$a,$b);
				
				array_push($response,$c);
				
				unset($a);
				unset($b);
				unset($c);
				unset($row2);
				unset($row1);
				
				
			}	
			
			$jsonfile = json_encode(array("calendar"=>$response));
				echo $jsonfile;
		

?>
