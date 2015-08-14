<?php
require("../config.php");
if(!isset($_SESSION['user']))
{
    header("Location: ../login.php");
}

$user = $_SESSION['user'];
if($user['Role']=='employee')
{

    header("Location: ../logout.php");
}
$data = $_SERVER['QUERY_STRING'];
$deleteFailed = substr($data, strpos($data, "=") + 1);
if(!empty($deleteFailed))
    $errorMesssage='Failed to delete the employee information';

$query = "  SELECT *
            FROM employees";


try
{
    $stmt = $db->prepare($query);
    $stmt->execute();
}
catch(PDOException $ex)
{ //die("Failed to run query: " . $ex->getMessage());
    $errorMesssage='Sory failed to retrieve data.';
}


?>
<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
</head>
<body>
<form name="form1"  enctype="multipart/form-data">
    <div class="container" >
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <div class="navbar-brand navbar-brand-centered">
                        <img src="../images/Icon.png" style="height: 220%; width: 75%;position:relative; top:-15px; left: 20px" >
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbar-brand-centered">
                    <ul class="nav navbar-nav">
                        <li><a href="http://www.rapidvoicesupport.com/">Home</a></li>
                        <li><a href="http://www.rapidvoicesupport.com/contact-us/">Contact Us</a></li>
                        <li><a href="http://www.rapidvoicesupport.com/about/">About Us</a></li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right" style="float:right; position: relative;  right: 15px" >
                        <li><a href="../dashboard.php">Dashboard</a></li>
                        <li><a href="../logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="panel panel-info">
            <div class="container-page">
                <div class="">
                    <div class="panel panel-info" >
                        <div class="panel-heading" align="center">
                            <div class="panel-title">Employee Information</div>
                        </div>
                        <?php
                        if(!empty($errorMessage))
                        {
                            ?>
                            <div class="form-group col-lg-12">
                                <div class="alert alert-danger" id="error"  role="alert"><strong><?php echo $errorMessage ?> </strong></div>
                            </div>
                        <?php
                        }
                        ?>
                        <div style="padding-top:30px" class="panel-body" >
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12" data-spy="scroll" style="height: 100%;width: 98%; left: -20px">
                                        <div class="table-responsive" >
                                            <table id="mytable"  class="table table-bordered table-striped table-hover">
                                                <thead>
                                                <?php
                                                foreach($user as $column=>$value)
                                                {
                                                    /*if($column=="Department")
                                                        continue;*/
                                                    $parts = preg_split('/(?=[A-Z])/', $column, -1, PREG_SPLIT_NO_EMPTY);
                                                    $title='';
                                                    foreach($parts as $part)
                                                        $title=$title." ".$part;

                                                    echo "<th style='text-align: center'>$title</th>";
                                                }
                                                ?>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach($stmt as $row)
                                                {
                                                    ?>
                                                    <tr>
                                                        <?php
                                                        foreach($row as $column=>$value)
                                                        {
                                                            /*if($column=="Department")
                                                                continue;*/
                                                            echo "<td align='center'>";
                                                            if($column=="ProfilePic")
                                                            {
                                                                if($value=="" || !file_exists('../'.$value))
                                                                    echo "No Picture";
                                                                else
                                                                    echo "<img src='../$value' height='60px' width='60px'/>";
                                                            }
                                                            else
                                                                echo "$value";
                                                            echo "</td>";

                                                        }
                                                        ?>
                                                        <td><p data-placement="top" data-toggle="tooltip" title="Edit">
                                                                <a href="editInfo.php?q=<?php echo $row['EmpId'] ?>" class="btn btn-primary btn-xs"  >
                                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                                </a></p></td>
                                                        <td><p data-placement="top" data-toggle="tooltip" title="Delete">
                                                                <a href="deleteInfo.php?q=<?php echo $row['EmpId'] ?>" class="btn btn-danger btn-xs" >
                                                                    <span class="glyphicon glyphicon-trash"></span>
                                                                </a></p></td>
                                                    </tr>
                                                <?php } ?>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>
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