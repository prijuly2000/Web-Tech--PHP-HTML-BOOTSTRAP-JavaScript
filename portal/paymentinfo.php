<?php
require("config.php");
if(!isset($_SESSION['user']))
{
    header("Location: index.php");
}

$user = $_SESSION['user'];

if(!empty($_POST))
{

    $query="SELECT pph
                FROM departments
                WHERE Department=:department";
    $query_param=array(':department'=>$user["Department"]);
    $pph=0;
    try
    {
        $stmt= $db->prepare($query);
        $row=$stmt->execute($query_param);
        $pph=$row["pph"];
    }
    catch (PDOException $ex)
    { //die("Failed to run query: " . $ex->getMessage());
        $errorMesssage = 'Server down!! Contact RVS.';
    }

    $query = "  SELECT *
                FROM timesheet
                WHERE
                Date>=STR_TO_DATE(:startdate,'%Y-%m-%d') AND
                 Date<=STR_TO_DATE(:enddate,'%Y-%m-%d')  AND
                 EmpId=:empid" ;
    $query_param=array(
        ':startdate'=>$_POST["startdate"],
        ':enddate'=>$_POST["enddate"],
        ':empid'=>$user["EmpId"]
    );

    try {
        $stmt = $db->prepare($query);
        $stmt->execute($query_param);

    } catch (PDOException $ex) { //die("Failed to run query: " . $ex->getMessage());
        $errorMesssage = 'Server down!! Contact RVS.';
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="bootstrap-editable/css/bootstrap-responsive.css">
</head>
<body>
<form name="form1" method="post"  enctype="multipart/form-data">
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
                <div align="center">
                    <div class="panel panel-info" >
                        <div class="panel-heading">
                            <div class="panel-title">Payment Information</div>
                        </div>
                        <?php
                        if(!empty($errorMessage))
                        {
                            ?>
                            <div class="form-group" style="right: -10px" >
                                <div class="alert alert-danger" id="error"  role="alert"><strong><?php echo $errorMessage ?> </strong></div>
                            </div>
                        <?php

                        }
                        ?>
                        <div class="row" style="margin: 10px" >
                            <br>
                            <div class="col-lg-6">
                                <label class="col-md-5">Select start date:</label>
                                <div  class="input-group  form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input  class="form-control datepicker " name="startdate" size="16" type="text" readonly>
                                    <span class="input-group-addon "><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="col-md-5">Select end date:</label>
                                <div class="input-group  form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control datepicker" name="enddate" size="16" type="text"  readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div>
                            <button style="margin: 20px"  type="submit" class="btn btn-primary" >View</button>
                            </div>
                        </div>
                        <div style="padding-top:30px" class="panel-body" >
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12" data-spy="scroll" style="height: 100%;width: 98%; left: -20px">
                                        <?php
                                        if(!empty($_POST) && $stmt->rowCount()==0)
                                        {
                                            echo"<div class='alert alert-warning'role='alert'><strong>No work done in time period!!! </strong></div>";
                                        }
                                        elseif($_POST)
                                        {
                                        ?>
                                        <strong> Note :&nbsp; Date format : YYYY-MM-DD &nbsp;&nbsp; Hours format: 24 Hours </strong>

                                        <div class="table-responsive " style="margin: 20px">
                                            <table id="mytable"  class="table table-bordered table-striped table-hover">
                                                <thead align="right" >
                                                <?php

                                                for($i=0;$i<$stmt->columnCount();$i++)
                                                {
                                                    $column=$stmt->getColumnMeta($i)["name"];
                                                    if($column=="EmpId")
                                                        continue;
                                                    $parts = preg_split('/(?=[A-Z])/', $column, -1, PREG_SPLIT_NO_EMPTY);
                                                    $title='';
                                                    foreach($parts as $part)
                                                        $title=$title." ".$part;

                                                    echo "<th class='col-md-2' style='text-align:center'>$title</th>";
                                                }
                                                ?>
                                                <th class='col-md-2' style='text-align:center'>Hours Worked</th>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $total_hours=0;
                                                foreach($stmt as $row)
                                                {
                                                    ?>
                                                    <tr>
                                                        <?php

                                                        foreach($row as $column=>$value)
                                                        {
                                                            if($column=="EmpId")
                                                                continue;

                                                            echo "<td class='col-md-3' align='center'>$value</td>";

                                                        }
                                                        $currentShift=0;
                                                        if($row["StartHour"]>$row["EndHour"])
                                                        {
                                                            $currentShift=24-$row["StartHour"];
                                                            $currentShift+=$row["EndHour"];
                                                        }
                                                        else
                                                            $currentShift=$row["StartHour"]-$row["EndHour"];
                                                        echo "<td class='col-md-3' align='center'>$currentShift</td>";
                                                        $total_hours+=$currentShift;
                                                        ?>

                                                    </tr>
                                                <?php
                                                }
                                                ?>

                                                </tbody>

                                            </table>

                                        </div>
                                            <div class='alert alert-success'role='alert'><strong>You earned : $ <?php echo $total_hours*$pph ?></strong></div>
                                        <?php } ?>


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
<script src="bootstrap-editable/js/jquery-2.1.3.min.js"></script>
<script src="bootstrap-editable/js/bootstrap-datepicker.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(function()
    {
        $('.datepicker').datepicker({format: 'yyyy-mm-dd' });
        $('.glyphicon-calendar').datepicker({format: 'yyyy-mm-dd'});
    });
</script>

</body>
</html>