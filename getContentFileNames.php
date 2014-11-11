<?php
// We need to set the content type to application/json for the AFNetworking to
// work properly.
header('Content-type: application/json');
include_once 'Classes/ContentManager.php';
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
$contentManager = new ContentManager();
if (array_key_exists('barId', $_GET))
{
    $barName = $_GET['barId'];
    $result = $contentManager->getContentFiles($barName);
    if ($result)
        $contentManager->status->setStatusCode(Status::SUCCESS);
    else
        $contentManager->status->setStatusCodeWithMessage(Status::ERROR, "No content found!");
}
else
{
    $contentManager->status->setStatusCodeWithMessage(Status::ERROR, "No bar id provided!");
}
echo $contentManager->createJSONResponse();
unset($contentManager);
?>