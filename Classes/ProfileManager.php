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
 * 11/11/14 - Added updating locations.
 */
class ProfileManager
{
    /// The profile of the user to login
    private $user;
    
    /// The status object for the manager
    public $status;
    
    /// The friend object for a recently added friend.
    public $friend;
    
    /// An array of all of our friends locations
    private $friend_locations;
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->user = new Profile();
        $this->status = new Status();
        $this->friend = new Friend("","");
        $this->friend_locations = array();
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
    /// updateUserLocation()
    ///
    /// This method will update the location of the user in the database if
    /// successful.
    ///
    /// @param $username  The username of the profile being updated.
    /// @param $x_location The x coordinate of the profile to update to.
    /// @param $y_location The y coordinate of the profile to update to.
    /// @param $barID   The id of the bar that the user is currently in.
    ///
    /// @return bool
    ///     @retval true - User location updated successfuly
    ///     @retval false - User location was not updated.
    ///////////////////////////////////////////////////////////////////////////
    public function updateUserLocation($username, $x_coordinate, $y_coordinate, $barID)
    {
        // We want to call down to the user profile and have it update the users location and
        // check the response.
        $returnCode = $this->user->updateLocation($username, $x_coordinate, $y_coordinate, $barID);
        if ($returnCode == 1) {
            $this->status->setStatusCode(Status::SUCCESS);
            return true;
        } elseif ($returnCode == 2) {
            $this->status->setStatusCodeWithMessage(Status::ERROR, "Database Error!");
            return false;
        } else {
            // We should never get this error since we are providing the parameters and is not based
            // on user interaction
            $this->status->setStatusCodeWithMessage(Status::ERROR, "Failed to update location!");
            return false;
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// retrieveFriendLocations()
    ///
    /// This method will populate our friend_locations array with the current
    /// location of all of the provided users friends..
    ///
    /// @param $username  The username of the profile being updated.
    /// @param $x_location The x coordinate of the profile to update to.
    /// @param $y_location The y coordinate of the profile to update to.
    ///
    /// @return bool
    ///     @retval true - User location updated successfuly
    ///     @retval false - User location was not updated.
    ///////////////////////////////////////////////////////////////////////////
    public function retrieveFriendLocations($username)
    {
        // We simply just need to load the friends list since we update the location as
        // part of this method.
        $returnCode = $this->user->loadFriends($username);
        
        if ($returnCode) {
            $this->status->setStatusCode(Status::SUCCESS);
            return true;
        } else {
            // We should never get this error since we are providing the parameters and is not based
            // on user interaction
            $this->status->setStatusCodeWithMessage(Status::ERROR, "Failed to retrieve friend locations!");
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
            // Send the profile, friend, friend locations, and the error
            $json_array['Profile'] = $this->user->membersToJsonFormat();
            $json_array['Status'] = $this->status->membersToJsonFormat();
            $json_array['Friend'] = $this->friend->membersToJsonFormat();
        }
        return json_encode($json_array);
    }
}
?>