<?php
// We need to set the content type to application/json for the AFNetworking to
// work properly.
header('Content-type: application/json');
include_once 'Classes/LogInManager.php';
// $db = new DatabaseManager();
// $db->connect();
// $requestUsername = "cfrosty13";
// $sql = 'SELECT * FROM profile WHERE username="'.$requestUsername.'"';
// $db->query($sql);
// $result = $db->getResult();
// $numResults = $db->numRows();
// $db->disconnect();
//
// echo $numResults;
// echo "<br />";
// print_r($result);

// Under testing
if (array_key_exists('username', $_GET) && array_key_exists('password', $_GET))
{
    $username = $_GET['username'];
    $password = $_GET['password'];
    $logInManager = new LogInManager();
    $result = $logInManager->login($username, $password);
    if ($result)
        echo $logInManager->createJSONResponse();
    else
        echo "Failed!";
    unset($logInManager);
}
else
{
    echo "Failed!";
}

?>