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
if (array_key_exists('profile_username', $_GET) && array_key_exists('friend_username', $_GET))
{
    $profileUsername = $_GET['profile_username'];
    $friendUsername = $_GET['friend_username'];
    $profileManager->removeFriend($profileUsername, $friendUsername);
}
else
{
    $profileManager->status->setStatusCodeWithMessage(Status::ERROR, "Friend username not provided");
}
echo $profileManager->createJSONResponse();
unset($profileManager);
?>