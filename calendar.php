<?php
	$hostname = "localhost";
	$username = "root";
	$password ="";
	$dbname = "team21";
	$error = "Sorry cannot connect to the database please try again later";
	$connect = mysqli_connect($hostname,$username,$password) or die ($error);
	mysqli_select_db($connect , $dbname) or die($error);
?>
<html>
	<head>
		<script>
			function goBack(month , year){
				if(month == 1){
					--year;
					month = 13;
				}
				--month;
				
				var monthstring = ""+month+"";
				var monthlength = monthstring.length;
				if(monthlength <= 1){
					monthstring = "0"+monthstring;
				}
				document.location.href = "<?php $_SERVER['PHP_SELF'];?>?month="+monthstring/*(month-1)*/+"&year="+year;
			}
			function goNext(month , year){
				if(month == 12){
					++year;
					month = 0;
				}
				++month;
				
				var monthstring = ""+month+"";
				var monthlength = monthstring.length;
				if(monthlength <= 1){
					monthstring = "0"+monthstring;
				}	
				document.location.href = "<?php $_SERVER['PHP_SELF'];?>?month="+monthstring/*(month+1)*/+"&year="+year;
			}	
		</script>
		<style>
			.today{
				background-color : yellow;
			}
			.event{
				background-color : #ccffff;
			}
		</style>	
	</head>
	<body>
	<h1 align = 'center'>EVENT CALENDAR</h1>
	<p>CLICK ON PARTICULAR DATE TO ADD EVENTS</p>
		<?php
		if(isset($_GET['day'])){
			$day = $_GET['day'];
		}else{
				$day = date("j");
		}
		if(isset($_GET['month'])){
			$month = $_GET['month'];
		}else{
				$month = date("n");
		}
		if(isset($_GET['year'])){
			$year = $_GET['year'];
		}else{
				$year = date("Y");
		}
			 //echo $day."/".$month."/".$year;
			 $currentTimeStamp = strtotime("$year-$month-$day");
			 $monthName = date("F",$currentTimeStamp);//get current month name
			 $numDays = date("t",$currentTimeStamp);//to get how many days are there in the current month
			 $counter = 0;
		?>	
		<?php
			if(isset($_POST['btnadded'])){
				$title = $_POST['txttitle'];
				$detail = $_POST['txtdetail'];
				$eventdate = $month."/".$day."/".$year;
				echo $title;
				$sqlinsert = "INSERT INTO `eventcalendar`(`Title`, `Detail`, `eventDate`, `dateAdded`) VALUES ('$title','$detail','$eventdate',now())";
				$resultinsert = mysqli_query($connect,$sqlinsert);
				if($resultinsert){
					echo "Event was succesfully addad";
				}else{
					echo "Adding Event was failed";
				}
			}
		?>
		<table border='10'>
			<tr>
			<td align = 'center'><input style='width:100px;' type='button' value='<<<<<<BACK' name='previousbutton' onclick="goBack(<?php echo $month.",".$year?>)"></td>
			<td colspan='5' align = 'center'><?php echo $monthName.",".$year ?></td>
			<td align = 'center'><input style='width:100px;' type='button' value='NEXT>>>>>' name='nextbutton' onclick="goNext(<?php echo $month.",".$year?>)"></td>
			</tr>
			<tr>
			<td width='500px'>MON</td>
			<td width='500px'>TUE</td>
			<td width='500px'>WED</td>
			<td width='500px'>THU</td>
			<td width='500px'>FRI</td>
			<td width='500px'>SAT</td>
			<td width='500px'>SUN</td>
			</tr>
			<?php
			echo "<tr>";
			for($i = 1; $i < $numDays+1; $i++ , $counter++){
				$timeStamp = strtotime("$year-$month-$i");
				if($i == 1){
					$firstDay = date("w",$timeStamp);
					for($j = 1; $j < $firstDay; $j++, $counter++){
						//blank Space
						echo "<td>&nbsp;</td>";
					}
				}
				if($counter % 7 == 0){
					echo "<tr></tr>";
				}
				$monthstring=$month;
				$monthlength=strlen($monthstring);
				$daystring=$i;
				$daylength=strlen($daystring);
				if($monthlength <= 1){
					$monthstring="0".$monthstring;
				}
				if($daylength <= 1){
					$daystring="0".$daystring;
				}
				$todaysDate = date("m/d/Y");
				$dateToCompare = $monthstring .'/'. $daystring .'/'. $year;
				echo "<td align='center'";
				if($todaysDate == $dateToCompare){
					echo "class='today'";
				}else{
					$sqlConnect = "select * from eventcalendar where eventDate = '".$dateToCompare."'";
					$res=mysqli_query($connect,$sqlConnect);
					if(mysqli_num_rows($res) >= 0){
						echo "class = 'event'";
					}
				}
				echo "><a href='".$_SERVER['PHP_SELF']."?month=".$monthstring."&day=".$daystring."&year=".$year."&v=true'>".$i."</a></td>";
			}
			echo "</tr>";
			?> 
		</table>
			<?php
			if(isset($_GET['v'])){
				echo "<a href='".$_SERVER['PHP_SELF']."?month=".$month."&day=".$day."&year=".$year."&v=true&f=true'>Add Event</a>";
				if(isset($_GET['f'])){
					include("eventform.php");
				}
				
				$sqlEvent = "select * from eventcalendar where eventDate = '".$month."/".$day."/".$year."'";
				$resultEvent = mysqli_query($connect,$sqlEvent);
				echo "<hr>";
				while($events = mysqli_fetch_array($resultEvent)){
					echo "Title : ".$events['Title']."<br>";
					echo "Details : ".$events['Detail']."<br>";
				}
			}
				?>	
	</body>
</html>