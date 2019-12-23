<?php  $serverName = "192.168.6.6";
       $connectionInfo = array( "Database"=>"ERPSMS","Database"=>"UMS","UID"=>"sa","PWD"=>"identech");
	   $conn = sqlsrv_connect( $serverName, $connectionInfo);
	 
	   if($conn == null || False)
	   {
		   
		    $serverName = "192.168.6.3";
			$connectionInfo = array( "Database"=>"ERPSMS","Database"=>"UMS","UID"=>"sa","PWD"=>"identech");
			$conn = sqlsrv_connect( $serverName, $connectionInfo);
	   }
	   ?>