<?php
include_once 'Profile.php';

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
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->user = new Profile();
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
    /// application can pase the profile along with a status/error object.
    ///////////////////////////////////////////////////////////////////////////
    public function createJSONResponse()
    {
        // TODO: More to happen here. We need to add an error object passback as well.
        return json_encode($this->user->membersToJsonFormat());
    }
}
?>