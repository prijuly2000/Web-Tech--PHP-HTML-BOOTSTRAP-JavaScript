<?php
require("config.php");
if(!isset($_SESSION['user']))
{
    //header("Location: ../index.php");
}

$user = $_SESSION['user'];
/*if($user['Role']=='employee')
{

    header("Location: ../logout.php");
}*/




$query = "  SELECT *
            FROM employees
            WHERE
                Department= 'p'";

try{
    $stmt = $db->prepare($query);
    $stmt->execute();
}
catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
</head>

<body>
<div class="container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="mytable" class="table table-bordered table-striped table-hover">

                        <thead>
                            <?php
                                foreach($user as $column=>$value)
                                {
                                    if($column=="Department")
                                        continue;
                                    $parts = preg_split('/(?=[A-Z])/', $column, -1, PREG_SPLIT_NO_EMPTY);
                                    $title='';
                                    foreach($parts as $part)
                                        $title=$title." ".$part;

                                    echo "<th>$title</th>";
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
                                    foreach($user as $column=>$value)
                                    {
                                        if($column=="Department")
                                            continue;
                                        echo "<td align='center'>";
                                        if($column=="ProfilePic")
                                        {
                                            if($value=="" || !file_exists($value))
                                                echo "No Picture";
                                            else
                                                echo "<img src='$value' height='60px' width='60px'/>";
                                        }
                                        else
                                            echo "$value";
                                        echo "</td>";

                                    }
                                ?>
                                <td><p data-placement="top" data-toggle="tooltip" title="Edit"><a class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="glyphicon glyphicon-pencil"></span></a></p></td>
                                <td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
                            </tr>
                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title custom_align" id="Heading">Edit Details</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input class="form-control " type="text" placeholder="Mohsin">
                    </div>
                    <div class="form-group">
                        <input class="form-control " type="text" placeholder="Irshad">
                    </div>
                    <div class="form-group">
                        <textarea rows="2" class="form-control" placeholder="CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan"></textarea>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-warning btn-lg" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
                </div>
                <div class="modal-body">

                    <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Record?</div>

                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-success" ><span class="glyphicon glyphicon-ok-sign"></span> Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>

</body>
</html>