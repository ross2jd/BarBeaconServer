<?php
include_once 'Profile.php';
include_once 'Status.php';
include_once 'Friend.php';

/**
 * Class: ProfileManager
 *
 * Author: Jordan Ross
 * 
 * Version History:
 * 8/26/14 - Created.
 */
class ProfileManager
{
    /// The profile of the user to login
    private $user;
    
    /// The status object for the manager
    public $status;
    
    /// The friend object for a recently added friend.
    public $friend;
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->user = new Profile();
        $this->status = new Status();
        $this->friend = new Friend("","");
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// addFriend()
    ///
    /// This method will add a friend to the user profile if successful. If it
    /// is successful it will also load the Friend object of the friend into the
    /// friend member.
    ///
    /// @param $requestProfile  The username for the requested profile.
    /// @param $requestFriend   The username for the friend to add.
    ///
    /// @return bool
    ///     @retval true - Friend added successfuly
    ///     @retval false - Friend not added.
    ///////////////////////////////////////////////////////////////////////////
    public function addFriend($requestProfile, $requestFriend)
    {
        $returnCode = $this->user->addFriend($requestProfile, $requestFriend);
        if ($returnCode == 1) {
            $this->status->setStatusCode(Status::SUCCESS);
            $friendProfile = new Profile();
            $friendProfile->loadProfile($requestFriend);
            $this->friend = new Friend($friendProfile->getName(), $friendProfile->getUsername());
            unset($friendProfile);
            return true;
        } elseif ($returnCode == 2) {
            $this->status->setStatusCodeWithMessage(Status::ERROR, "Already a friend!");
            return false;
        } elseif ($returnCode == 3) {
            $this->status->setStatusCodeWithMessage(Status::ERROR, "User does not exist!");
            return false;
        } elseif ($returnCode == 4) {
            $this->status->setStatusCodeWithMessage(Status::ERROR, "Database Error!");
            return false;
        } else {
            $this->status->setStatusCodeWithMessage(Status::ERROR, "No username provided!");
            return false;
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// removeFriend()
    ///
    /// This method will remove a friend from the user profile if successful.
    ///
    /// @param $requestProfile  The username for the requested profile.
    /// @param $requestFriend   The username for the friend to remove.
    ///
    /// @return bool
    ///     @retval true - Friend removed successfuly
    ///     @retval false - Friend not removed.
    ///////////////////////////////////////////////////////////////////////////
    public function removeFriend($requestProfile, $requestFriend)
    {
        $returnCode = $this->user->removeFriend($requestProfile, $requestFriend);
        if ($returnCode == 1) {
            $this->status->setStatusCode(Status::SUCCESS);
            return true;
        } elseif ($returnCode == 2) {
            $this->status->setStatusCodeWithMessage(Status::ERROR, "Database Error!");
            return false;
        } else {
            $this->status->setStatusCodeWithMessage(Status::ERROR, "No username provided!");
            return false;
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// createJSONResponse()
    ///
    /// This method will return the JSON response to output so that the iPhone
    /// application can parse the profile along with status object. If the
    /// operation was not successul (i.e. the status object holds anything but
    /// SUCCESS) then it will only send the error object.
    ///////////////////////////////////////////////////////////////////////////
    public function createJSONResponse()
    {
        $json_array = array();
        if ($this->status->getCode() != Status::SUCCESS)
        {
            // The operation failed. Send only the error
            $json_array['Status'] = $this->status->membersToJsonFormat();
        }
        else
        {
            // Send the profile and the error
            $json_array['Profile'] = $this->user->membersToJsonFormat();
            $json_array['Status'] = $this->status->membersToJsonFormat();
            $json_array['Friend'] = $this->friend->membersToJsonFormat();
        }
        return json_encode($json_array);
    }
}
?>