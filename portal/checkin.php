<?php
require("config.php");

if(!isset($_SESSION['user']))
{
    header("Location: index.php");
}
$user=$_SESSION["user"];

if(!$_POST) {
    // Add row to database
    $query = "
            INSERT INTO timesheet (
            Empid,
            Date,
            StartHour
            )
             VALUES (
                :empid,
                CURDATE(),
                HOUR(CURTIME())
            )";

    $query_params = array(
        ':empid' => $user["EmpId"]
         //H for 24 hours & h for 12 hours
    );

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
        $successMessage = "You are successfully checked In!! Dont forget to checkout to complete the work hours...";
        unset($_POST);
        $checked=True;
    } catch (PDOException $ex) {
        if($ex->getCode())
        {
            $successMessage= "You are successfully checked In!! Dont forget to checkout to complete the work hours...";
            $checked=True;
        }
        else {
            $errorMessage = "Please report this problem to RVS";

        }
        //die("Failed to run query: " . $ex->getMessage());
    }
}
else
{
    $query = "
            UPDATE timesheet
            SET EndHour=HOUR(CURTIME())
            WHERE
            EmpId=:empid
            AND (Date=CURDATE()
            OR Date=CURDATE()-1)";

    $query_params = array(
        ':empid' => $user["EmpId"]
         //H for 24 hours & h for 12 hours, i for minutes
    );

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
        $successMessage = "You are successfully checked Out!!";
        $checked=false;
        unset($_POST);
    } catch (PDOException $ex) {
        //$errorMessage = "Please report this problem to RVS";
        die("Failed to run query: " . $ex->getMessage());
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
</head>
<body>
<form name="form1" method="post" action="checkin.php" enctype="multipart/form-data">
    <div class="container" >
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <div class="navbar-brand navbar-brand-centered">
                        <img src="images/Icon.png" style="height: 220%; width: 75%;position:relative; top:-15px; left: 20px" >
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbar-brand-centered">
                    <ul class="nav navbar-nav">
                        <li><a href="http://www.rapidvoicesupport.com/">Home</a></li>
                        <li><a href="http://www.rapidvoicesupport.com/contact-us/">Contact Us</a></li>
                        <li><a href="http://www.rapidvoicesupport.com/about/">About Us</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right" style="float:right; position: relative;  right: 15px" >
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="panel panel-info">
            <div class="container-page">
                <div class="">
                    <div class="panel panel-info" >
                        <div class="panel-heading">
                            <div class="panel-title" align="center">Check In</div>
                        </div>

                        <div style="padding-top:30px" class="panel-body" align="center">
                            <div class="container" >
                                <div class="row" >
                                    <?php
                                    if(!empty($errorMessage))
                                    {
                                    ?>
                                        <div class="form-group col-lg-11" style=" margin: 20px ; left: -20px">
                                            <div class="alert alert-danger" align="center" id="error"  role="alert"><strong><?php echo $errorMessage ?> </strong></div>
                                        </div>
                                    <?php
                                    }
                                    else if(!empty($successMessage))
                                    {
                                    ?>
                                        <div class="form-group col-lg-11" style="margin: 20px;left: -20px">
                                            <div class="alert alert-success" id="success" role="alert">
                                                <strong><?php echo $successMessage ?> </strong></div>
                                        </div>
                                        <?php
                                        if (($checked))
                                        {
                                            ?>
                                            <br>
                                            <input type="hidden" name="check" value="checkedin">
                                            <button style="margin: 20px" type="submit" class="btn btn-primary">Check Out </button>
                                         <?php
                                        }
                                    }


                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="navbar navbar-default"  role="navigation">
            <div class="container">
                <div class="navbar-header" style="float: left; padding: 15px; text-align: center;width: 100%;">
                    <div class="navbar-brand navbar-brand-centered" style="float:none;">Copyright © 2015 | Rapid Voice Support</div>
                </div>

            </div>
        </nav>
    </div>
</form>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>
</html>