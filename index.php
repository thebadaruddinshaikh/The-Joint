<?php 
session_start();
$con=@new mysqli('sql300.epizy.com','epiz_23003962','lqH3LaW9y6ss','epiz_23003962_thejoint');
$salt1="Who!$tHeL0ved0ne";
$salt2="@!!@h";
if($con->connect_error) die("Failed to connect to the database");

if (isset($_POST['register'])) 
{
	$nam=$_POST['name'];
	$sur=$_POST['sur'];
	$email=$_POST['email'];
	$pass=$_POST['pass'];
	$cpass=$_POST['cpass'];
	if ($nam!="" && $sur!="" && $email!="" && $pass!="" && $cpass!="") 
	{
		if ($pass==$cpass) 
		{
			$userinfo=sanitise($nam,$sur,$email,$pass);
			if (validate($userinfo[0],$userinfo[1],$userinfo[2],$userinfo[3])) 
			{
				if(dataentry($userinfo[0],$userinfo[1],$userinfo[2],$userinfo[3]))
				{
					echo "Account Create Successfully";
				}
				else
				{
					echo "Failed to Create the account, Please try again After Some Time";
				}
			}
		}
		else
		{
			echo "The Entered Password do not Match";
		}
	}
	else
	{
		echo "Please fill all the fields";
	}
}
if (isset($_POST['login'])) 
{
	
	$id=$_POST['email'];
	$pin=$_POST['lpass'];
	$id=ucfirst(strtolower(stripslashes(strip_tags(htmlentities($id)))));
	$pin=stripcslashes(strip_tags(htmlentities($pin)));
	$pin=hash('ripemd128', $pin);
	if (isuser($id)) 
	{
		if (verify($id,$pin)) 
		{
			$_SESSION['email']=$id;
			header("Location:homepage/homepage.php");
		}
		else
		{
			echo "The Password And Email Do not Match";
		}
	}
	else
	{
		echo "The Email Id is Not Registered";
	}
}
function verify($email,$pass)
{
	global $con;
	$query="SELECT * FROM Users WHERE email='$email' AND password='$pass'";
	$result=$con->query($query);
	$rows=$result->num_rows;
	if ($rows==1) 
	{
		return 1;
	}
	else
	{
		return 0;
	}
}
function isuser($email)
{
	global $con;
	$query="SELECT * FROM Users WHERE Email='$email'";
	$result=$con->query($query);
	$rows=$result->num_rows;
	if ($rows==1) 
	{
		return 1;
	}
	else
	{
		return 0;
	}
}


function sanitise($name,$surname,$email,$password)
{

	$name=ucfirst(strtolower(stripslashes(strip_tags(htmlentities($name)))));
	$surname=ucfirst(strtolower(stripslashes(strip_tags(htmlentities($surname)))));
	$email=ucfirst(strtolower(stripslashes(strip_tags(htmlentities($email)))));
	$password=stripcslashes(strip_tags(htmlentities($password)));
	$password=hash('ripemd128',$password);
	return array($name,$surname,$email,$password);
}
function validate($name,$surname,$email,$password)
{
	global $con;
	$query="SELECT * FROM Users WHERE email='$email'";
	$result=$con->query($query);
	$rows=$result->num_rows;
	if ($rows==0) 
	{
		return 1;
	}
	else
	{
		echo "Email Address is already in use";
	}
}
function dataentry($name,$surname,$email,$password)
{
	global $con;
	$query="INSERT INTO Users (FirstName,Surname,Email,Password) VALUES ('$name','$surname','$email','$password')";
	$result=$con->query($query);
	if ($result) 
	{
		return 1;
	}
	else
	{
		return 0;
	}
}
 ?>




 <!DOCTYPE html>
 <html>
 <head>
 	<title>The Joint.</title>
 	<link rel="stylesheet" type="text/css" href="style.css">
<link href="https://fonts.googleapis.com/css?family=Great+Vibes|Slabo+27px" rel="stylesheet"> 
 </head>
 <body>
 	<div id="head">
 		<h1>The Joint.</h1>
 	</div>
 	<div id="whtpg">
 		<div class="cont">
		 		<div id="register">
			 		<h3>Register</h3>
					 		<form action="index.php" method="POST">
					 			<input type="text" name="name" placeholder="Name"><br>
					 			<input type="text" name="sur" placeholder="Surname"><br>
					 			<input type="email" name="email" placeholder="Email"><br>
					 			<input type="password" name="pass" placeholder="Password"><br>
					 			<input type="password" name="cpass" placeholder="Confirm Password"><br>
					 			<input type="submit" name="register" value="Register">
					 		</form>
			 	</div>
			 	<div id="line"></div>
		 	    <div id="login">
				 		<h3>Login</h3>
				 		<form action="index.php" method="POST">
				 			<input type="email" name="email" placeholder="Email"><br>
				 			<input type="password" name="lpass" placeholder="Password"><br>
				 			<input type="submit" name="login" value="Log In">
				 		</form>
		 		</div>
		 	</div>
		 	
 	</div>
 </body>
 </html>