<?php
// We need to set the content type to application/json for the AFNetworking to
// work properly.
header('Content-type: application/json');
include_once 'Classes/ProfileManager.php';
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
$profileManager = new ProfileManager();
if (array_key_exists('username', $_GET) && array_key_exists('x_location', $_GET) && array_key_exists('y_location', $_GET) && array_key_exists('bar_id', $_GET))
{
    $username = $_GET['username'];
    $x_location = $_GET['x_location'];
    $y_location = $_GET['y_location'];
    $barID = $_GET['bar_id'];
    // The first thing we should do is update the location of our user.
    $profileManager->updateUserLocation($username, $x_location, $y_location, $barID);
    // Now that the location has been updated for the user lets get our friends locations.
    $profileManager->retrieveFriendLocations($username);
}
else
{
    // We should never get this error since we are providing the parameters and is not based
    // on user interaction
    $profileManager->status->setStatusCodeWithMessage(Status::ERROR, "Failed to update locations");
}
echo $profileManager->createJSONResponse();
unset($profileManager);
?>