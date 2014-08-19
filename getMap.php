<?php
// We need to set the content type to application/json for the AFNetworking to
// work properly.
header('Content-type: application/json');
include_once 'Classes/LocationManager.php';
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
$locationManager = new LocationManager();
if (array_key_exists('barName', $_GET))
{
    $barName = $_GET['barName'];
    $result = $locationManager->getMap($barName);
    if ($result)
        $locationManager->status->setStatusCode(Status::SUCCESS);
    else
        $locationManager->status->setStatusCodeWithMessage(Status::ERROR, "Map failed to load!");
}
else
{
    $locationManager->status->setStatusCodeWithMessage(Status::ERROR, "No bar name provided!");
}
echo $locationManager->createJSONResponse();
unset($locationManager);
?>