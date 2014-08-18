<?php
// We need to set the content type to application/json for the AFNetworking to
// work properly.
header('Content-type: application/json');
include_once 'Classes/LogInManager.php';
include_once 'Classes/Status.php';
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
$logInManager = new LogInManager();
if (array_key_exists('username', $_GET) && array_key_exists('password', $_GET))
{
    $username = $_GET['username'];
    $password = $_GET['password'];
    $result = $logInManager->login($username, $password);
    if ($result)
        $logInManager->status->setStatusCode(Status::SUCCESS);
    else
        $logInManager->status->setStatusCodeWithMessage(Status::ERROR, "Authentication failed!");
}
else
{
    $logInManager->status->setStatusCodeWithMessage(Status::ERROR, "Username and/or password not provided");
}
echo $logInManager->createJSONResponse();
unset($logInManager);
?>