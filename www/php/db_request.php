<?php
include_once "{$_SERVER['DOCUMENT_ROOT']}/php/db_functions.php";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "patient_center";

//echo json_encode($_POST);

$conn = null;

try{
    $response = null;
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    switch($_POST['query']){
        case "insert":
            $response = insert($conn);
            break;

        case "update":
            $response = update($conn);
            break;

        case "remove":
            $response = remove($conn);
            break;

        case "selectById":
            $response = selectById($conn);
            break;

        case "selectCount":
            $response = selectCount($conn);
            break;

        case "selectAll":
            $response = selectAll($conn);
            break;

        case "selectWhere":
            $response = selectWhere($conn);
            break;

        case "selectWhere":
            $response = selectWhereActive($conn);
            break;

        case "selectWhereLessThan":
            $response = selectWhereLessThan($conn);
            break;
    }

    $conn = null;

    if($response == null){
        $response = [];
    }

    exit(json_encode($response));

} catch(PDOException | Exception $e) {

    $conn = null;
    exit(json_encode(buildResponse("error", $e->getMessage())));
}
?>