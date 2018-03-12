<?php 
   require_once('config.php');
   if(isset($_POST) &  !empty($_POST)){
        $user = mysqli_real_escape_string($connect,$_POST["username"]);//$user = $_POST["username"]
        $email = mysqli_real_escape_string($connect,$_POST["email"]);
        $pass = md5($_POST["pswd"]);
        //echo $pass;
        $sql = "INSERT INTO `register`( `username`, `email`, `pass`) VALUES ('$user','$email','$pass')";
        $res = mysqli_query($connect,$sql);
        if($res){
            echo "Success";
        }
        else{
            echo "Error";
        }
   }
   
    
?>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<form method="post">
    <?php if(isset($fail)){?><div><?php echo $fail;?></div><?php }?>
    <h2 style="color:blue;">Register</h2>
    
        
        <input type="text"  name="username" placeholder="username" required autofocus><br /><br />
    
        <input type="email"  name="email" placeholder="email" required autofocus><br /><br />
        
        <input type="password" name="pswd" placeholder="password" value required><br /><br />
   
    
    
        <button type="submit"   id="log">Register</button><br><br>
        <a href="login.php"   >Login</a>
    
</form>
</body>
</html>