<?php
include_once 'Location.php';
include_once 'DatabaseManager.php';

/**
 * Class: Profile
 *
 * Author: Jordan Ross
 * 
 * Version History:
 * 8/13/14 - Created.
 */
class Profile
{
    /// The username of the profile
    private $username;
    
    /// The name of the user
    private $name;
    
    /// The encrypted password for the user
    private $password;
    
    /// An array of friends for this user
    private $friends;
    
    /// The last known location of the user
    private $curLocation;
    
    /// The database object
    private $db;
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->username = "";
        $this->password = "";
        $this->friends = array();
        $this->curLocation = new Location();
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// loadProfile()
    ///
    /// This method will load the members of the Profile class from the database
    /// based on the username supplied to the method.
    ///
    /// @param username The username of the profile to load.
    ///
    /// @return bool
    ///     @retval true - Profile loaded successfully.
    ///     @retval false - Profile load failed.
    ///////////////////////////////////////////////////////////////////////////
    public function loadProfile($requestUsername)
    {
        if (strlen($requestUsername) > 0)
        {
            // The username is not null so search the DB.
            $this->db = new DatabaseManager();
            $this->db->connect();
            $sql = 'SELECT * FROM profile WHERE username="'.$requestUsername.'"';
            $this->db->query($sql);
            $result = $this->db->getResult();
            $numResults = $this->db->numRows();
            $this->db->disconnect();
            if ($numResults == 1)
            {
                // We only have 1 entry which is what we expect for a valid result.
                $this->username = $result[0]['username'];
                $this->password = $result[0]['password'];
                $this->name = $result[0]['name'];           
                return true;
            }
            else
            {
                // We should never get more than 1 entry since the username is the
                // primary key. Thus it means that the user does not exist.
                return false;
            }
            
        }
        else
        {
            return false;
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// getUsername()
    ///
    /// Getter method for username.
    ///////////////////////////////////////////////////////////////////////////
    public function getUsername()
    {
        return $this->username;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// getPassword()
    ///
    /// Getter method for the encrypted password.
    ///////////////////////////////////////////////////////////////////////////
    public function getPassword()
    {
        return $this->password;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// membersToJsonFormat()
    ///
    /// This method will prepare the members we want into a format we can
    /// encode to JSON.
    ///////////////////////////////////////////////////////////////////////////
    public function membersToJsonFormat()
    {
        $jsonFormat = array(
            'Profile' => array(
                array(
                    'username' => $this->username,
                    'password' => $this->password,
                    'name' => $this->name
                )
            )
        );
        return $jsonFormat;
    }
}
?>