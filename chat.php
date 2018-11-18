<?php 
session_start();
	$mysqli= @new mysqli('sql300.epizy.com','epiz_23003962','lqH3LaW9y6ss','epiz_23003962_thejoint');
	if ($mysqli->connect_error) 
	{
		die("Couldn't Connect to the Database, Please Try again Later!");
	}
		if (isset($_POST['send'])) 
	{
		$name=$_SESSION['name'];
		$msg=$_POST['message'];
		$msg=strip_tags(stripslashes(htmlspecialchars($msg)));
		$info = getdate();
		$date = $info['mday'];
		$month = $info['mon'];
		$year = $info['year'];
		$hour = $info['hours'];
		$min = $info['minutes'];
		$sec = $info['seconds'];

				$current_date = "$year-$month-$date $hour:$min:$sec";
				$query="INSERT INTO message (Name,Message,Date_time) values ('$name','$msg','$current_date')";
				$result= $mysqli->query($query);
				if(!$result) echo "Failed to Insert in The database";

	}
if (isset($_POST['logout'])) 
{
	session_destroy();
	header("Location:../index.php");
}

 ?>


<!DOCTYPE html>
<html>
<head>
	<title>The Joint.</title>
</head>
<link rel="stylesheet" type="text/css" href="style2.css">
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet"> 
<body>
	<div id="header">
		<h1>The Joint.</h1>
    </div>
    <div id="whitpag">
    	<div id="dabba">
		    		<div id="opt">
		    			<p><strong><?php 
		    	$lgq="SELECT * FROM message ORDER BY DATE_TIME ASC";
				$log=$mysqli->query($lgq);
				$rows=$log->num_rows;
				$rem=100-$rows;
		    			echo "Auto Delete In ".$rem." Messages, Reload Not refresh to see New Texts"; ?></strong></p>
		    			<p><b><form action="chat.php" method="Post">
		    				
		    				<input type="submit" name="logout" value="Log Out">
		    			</form>
		    			</b></p>
		    		</div>
			    		<div id="act">
							    		<div id="msgbox">
							    		<p>
							    			<?php
												if($rows>=100)
												{
													$del="DELETE FROM message where message LIKE '%%'";
													$mysqli->query($del);
												}
												else
												{
													for($j=0;$j<$rows;$j++)//how come entering -1 made the extra name go away
														{
															$log->data_seek($j);
															echo $log->fetch_assoc()['Name']."::::".$log->fetch_assoc()['Message'].'<br>';
														}
												
												}
												
							    			 ?>
							    		</p>
							    			
							    		</div>
				    		<div id="inpt">
					    			<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					    				<input type="text" name="message">
					    	            <input type="submit" name="send" value="send">
					    			</div>
					    			</form>
		    			</div>
    		</div>
    	</div>
    </div>

</body>
</html>

