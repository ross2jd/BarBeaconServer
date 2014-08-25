<?php
include_once 'Profile.php';
include_once 'Status.php';

/**
 * Class: LogInManager
 *
 * Author: Jordan Ross
 * 
 * Version History:
 * 8/13/14 - Created.
 */
class LogInManager
{
    /// The profile of the user to login
    private $user;
    
    /// The status object for the manager
    public $status;
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->user = new Profile();
        $this->status = new Status();
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// login()
    ///
    /// This method will attempt to login a user give a username and password.
    /// If the login is successful it will load the user profile. Otherwise the
    /// user profile will remain empty.
    ///
    /// @param username The username of the requested profile
    /// @param password The password of the requested profile
    ///
    /// @return bool
    ///     @retval true - login successful.
    ///     @retval false - login failed.
    ///////////////////////////////////////////////////////////////////////////
    public function login($username, $password)
    {
        if (strlen($username) > 0 && strlen($password) > 0)
        {
            // If values are supplied for both parameters then attempt to login.
            $this->user->loadProfile($username);
            $correctPassword = $this->user->getPassword();
            if ($correctPassword == $password)
            {
                $this->user->loadFriends($username);
                return true;
            }
            else
            {
                unset($this->user);
                $this->user = new Profile();
                return false;
            }
        }
        else
        {
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
        }
        return json_encode($json_array);
    }
}
?>