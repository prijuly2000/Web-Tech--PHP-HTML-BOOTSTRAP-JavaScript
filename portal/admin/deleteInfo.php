<?php

require("../config.php");
if(!isset($_SESSION['user']))
{
    //header("Location: ../login.php");
}

$user = $_SESSION['user'];
/*if($user['Role']=='employee')
{

    header("Location: ../logout.php");
}*/
$data = $_SERVER['QUERY_STRING'];
$EmpId = substr($data, strpos($data, "=") + 1);
$query = "
            DELETE FROM employees
            WHERE
                EmpId= :EmpId
        ";
$query_params = array(':EmpId' => $EmpId);

try
{
    $stmt = $db->prepare($query);
    $stmt->execute($query_params);

    header("Location: employeeinfo.php");
}
catch (PDOException $ex)
{
    header("Location: employeeinfo.php?q=failed");
}

?>