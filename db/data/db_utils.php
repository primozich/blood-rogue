<?php
function db_connect()
{
        //$connection = mysql_connect('localhost', 'fshfdr', '#fishfeeder!');
        $connection = mysql_connect(':/Applications/MAMP/tmp/mysql/mysql.sock', 'fshfdr', '#fishfeeder!');
        if (!$connection)
        {
                print('No db connection');exit;
        }
        mysql_select_db('cprimozi_fishfeeder', $connection);
        return $connection;
}
function db_connect_cron()
{
        $connection = mysql_connect(':/Applications/MAMP/tmp/mysql/mysql.sock', 'fshfdr', '#fishfeeder!');
        if (!$connection)
        {
                print('No db connection');exit;
        }
        mysql_select_db('cprimozi_fishfeeder', $connection);
        return $connection;
}
function db_execute_other($dml_command,$db)
{
	 $conn = db_connect_other($db);
	 mysql_query($dml_command);
	 mysql_close($conn);
}
//function db_execute($dml_command)
function db_execute($dml_command, $isCronJob = 0)
{
	//$conn = db_connect();
	if ($isCronJob == 1)
	{
		$conn = db_connect_cron();
	}
	else
	{
		$conn = db_connect();
	}
	$var = mysql_query($dml_command);
	mysql_close($conn);
	return $var;
}
function db_execute_return($sql)
{
	$conn = db_connect();
	$var = mysql_query($sql);
	mysql_close($conn);
	return $var;
}
function db_execute_count($sql)
{
	$conn = db_connect();
	$var = @mysql_num_rows(mysql_query($sql));
	mysql_close($conn);
	return $var;
}
function replaceOps($string)
{
	return (str_replace("'", "&#39", $string));
}
function getNextId($table, $column)
{
	$query = db_execute_return("SELECT MAX($column) as Value FROM $table");
	if(mysql_num_rows($query) > 0)
	{
		$result = mysql_fetch_array($query);
		$nextval = $result[0] + 1;
		return $nextval;
	}
	else
		return 1;
}
function Duration($s){
	
	list($year,$month,$day,$hour,$minute,$second) = split('[:-]' ,date('Y-m-d H:i:s'));
    $e = mktime($hour, $minute, $second, $month, $day, $year);
	
	/* Find out the seconds between each dates */
    $timestamp = $e - $s;
    
    /* Cleaver Maths! */
    $years=floor($timestamp/(60*60*24*365));$timestamp%=60*60*24*365;
    $weeks=floor($timestamp/(60*60*24*7));$timestamp%=60*60*24*7;
    $days=floor($timestamp/(60*60*24));$timestamp%=60*60*24;
    $hrs=floor($timestamp/(60*60));$timestamp%=60*60;
    $mins=floor($timestamp/60);$secs=$timestamp%60;
   
    /* Display for date, can be modified more to take the S off */
    if ($years >= 1) { $str.= $years.' years '; }
    if ($weeks >= 1) { $str.= $weeks.' weeks '; }
    if ($days >= 1) { $str.=$days.' days '; }
    if ($hrs >= 1) { $str.=$hrs.' hours '; }
    if ($mins >= 0) { $str.=$mins.' minutes '; }
   
     return $str;
        
}
function DateToNumber($s)
{
    list($year,$month,$day,$hour,$minute,$second) = split('[:-]' ,$s);
    return mktime($hour, $minute, $second, $month, $day, $year);
}
function ShowDate($sd)
{
		$output2="";$output="";$ampm=" am";
		$date1 = explode(" ",$sd);
		$timex = $date1[1];
		$time = explode(":",$timex);
		if($time[0]>=12){$ampm=" pm";$time[0]=$time[0]-12;}
		$output2.=$time[0].":".$time[1].$ampm;
		$datex = $date1[0];
		$date = explode("-",$datex);$dd=$date[2];$mm=$date[1];$yy=$date[0];
		if($mm==1)$output.='January';if($mm==2)$output.='February';if($mm==3)$output.='March';if($mm==4)$output.='April';if($mm==5)$output.='May';
		if($mm==6)$output.='June';if($mm==7)$output.='July';if($mm==8)$output.='August';if($mm==9)$output.='September';if($mm==10)$output.='October';
		if($mm==11)$output.='November';if($mm==12)$output.='December';
		$output.=", ".$dd.", ".$yy;
		$output.=", ".$output2;
		return($output);
}

function setPaging($table,$whereClause,$perPage,$currPage)
{
    
	 $conn = db_connect();
	 $query=" SELECT * FROM  $table $whereClause";
	
	$rs=mysql_query($query);
	$num_rows=mysql_num_rows($rs);
	$perPage = $perPage;
	$totalPages = ceil($num_rows/ $perPage);
	$page=$currPage;
	$start = ($page - 1)* $perPage;
	
	$query2=" SELECT * FROM  $table $whereClause limit $start ,$perPage";
	
	$rs2=mysql_query($query2);
	
	$returValue = array();
	$returValue['start']=$start;
	$returValue['page']=$page;
	$returValue['result']=$rs2;
	$returValue['totalPages']=$totalPages;
	mysql_close($conn);
	return $returValue;
}
?>
