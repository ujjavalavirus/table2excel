<?php
ob_clean();
ob_start();
$dbHost =  $_POST["selected_dbhost"] ;  		//	database host
$dbUser	 =  $_POST["selected_dbuserer"] ;		//	database user
$dbPass	 = $_POST["selected_dbpassword"] ;		//	database password
$dbName	 = $_POST["selected_dbname"] ;		//	database name
$tablename = $_POST["selected_table"];
$heading = $tablename ;
$connection = @mysql_connect($dbHost, $dbUser, $dbPass) or die("Couldn't connect.");
$db = mysql_select_db($dbName, $connection) or die("Couldn't select database.");
$sql = "SELECT * FROM $tablename";
$result = @mysql_query($sql)	or die("Couldn't execute query:<br>".mysql_error().'<br>'.mysql_errno());
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=".$tablename."-".date('Ymd').".xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<body>";
echo "<table>";
echo "<tr><th colspan='".(mysql_num_fields($result))."'><h2>".$heading."</h2></th></tr>";
print("<tr>");
for ($i = 0; $i < mysql_num_fields($result); $i++)	 // show column names as names of MySQL fields
echo  "<th>".mysql_field_name($result, $i)."</th>";
print("</tr>");
while($row = mysql_fetch_row($result))
{
	$output = '';
	$output = "<tr>";
	for($j=0; $j<mysql_num_fields($result); $j++)
	{
		if(!isset($row[$j]))
			$output .= "<td>NULL\t</td>";
		else
			$output .= "<td>$row[$j]\t</td>";
	}
	$output .= "</tr>";
	$output = preg_replace("/\r\n|\n\r|\n|\r/", ' ', $output);
	print(trim($output));
}
echo "</table>";
echo "</body>";
echo "</html>"; 
?>