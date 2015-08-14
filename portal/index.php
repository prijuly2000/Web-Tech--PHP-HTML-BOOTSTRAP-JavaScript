<?php
require("config.php");
$submitted_username = '';
if(!empty($_POST)){
    $query = "
            SELECT *
            FROM employees
            WHERE
                Username = :username
                AND Password=:password
        ";
    $query_params = array(':username' => $_POST['username'],
    				':password'=>$_POST['password']);

    try
    {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
        $row = $stmt->fetch();
        if(!empty($row))
        	$login_ok="true";
        else
        	$login_ok="false";
    }
    catch(PDOException $ex)
    {
    	$errorMsg="sdfsdf"; 
    	$login_ok = "false";
    	//die("Failed to run query: " . $ex->getMessage()); 
    }
    
   
   
    if($login_ok=="true")
    {
        unset($row['password']);
        $_SESSION['user'] = $row;
        header("Location: dashboard.php");
        //die("Redirecting to: register.php");
    }
    else
    {
        $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
</head>
<body>

 <div class="container">
     <nav class="navbar navbar-default" role="navigation">
        <div class="container">
             <div class="navbar-header">
                 <div class="navbar-brand navbar-brand-centered " >
                     <img src="images/Icon.png" style="height: 220%; width: 75%;position:relative; top:-15px; left: 20px" >
                 </div>
             </div>
             <div class="collapse navbar-collapse" id="navbar-brand-centered">
                 <ul class="nav navbar-nav">
                     <li><a href="http://www.rapidvoicesupport.com/">Home</a></li>
                     <li><a href="http://www.rapidvoicesupport.com/contact-us/">Contact Us</a></li>
                     <li><a href="http://www.rapidvoicesupport.com/about/">About Us</a></li>
                 </ul>
             </div>
         </div>
     </nav>

     <?php
     if(!empty($_POST) && !$login_ok ) {
     ?>
         <div class="form-group col-lg-12">
             <div class="alert alert-danger" id="error"  role="alert"><strong>Alert!!Username or Password
                     is wrong</strong></div>
         </div>
     <?php
     }
     ?>
     <?php
     if(!empty($errorMsg)) {
     ?>
         <div class="form-group col-lg-12">
             <div class="alert alert-danger" id="errorMsg"  role="alert"><strong>Server down !! Contact RVS</strong></div>
         </div>
     <?php
     }
     ?>
    <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info" >
            <div class="panel-heading">
                <div class="panel-title">Sign In</div>
                <!--<div style="float:right; font-size: 80%; position: relative; top:-15px"><a href="#">Forgot password?</a></div>-->
            </div>

            <div style="padding-top:30px" class="panel-body" >

                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

                <form id="loginform" method="post" class="form-horizontal" role="form" >

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="username" type="text" class="form-control" name="username" placeholder="Username">
                    </div>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="password" type="password" class="form-control" name="password" placeholder="Password">
                    </div>

                    <div style="margin-top:10px" class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <input id="btn-login" type="submit" class="btn btn-success" value="Login">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-12 control">
                            <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                Want to join?
                                <a href="register.php">Sign Up Here</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

  </div>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>

 <div class="container">
     <nav class="navbar navbar-default"  role="navigation">
         <div class="container">
             <div class="navbar-header" style="float: left; padding: 15px; text-align: center;width: 100%;">
                 <div class="navbar-brand navbar-brand-centered" style="float:none;">Copyright © 2015 | Rapid Voice Support</div>
             </div>

         </div>
     </nav>
 </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

</body>
</html>