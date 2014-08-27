<?php
/**
 * Class: Friend
 *
 * Author: Jordan Ross
 * 
 * Version History:
 * 8/24/14 - Created.
 */
class Friend
{   
    /// The name of the friend
    private $name;
    
    // The username of the friend
    private $username;
    
    /// The last known location of the friend
    private $curLocation;
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct($friendName, $friendUsername)
    {
        $this->name = $friendName;
        $this->username = $friendUsername;
        $this->curLocation = new Location();
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// setName()
    ///
    /// This method will set the name for the friend
    /// 
    /// @param $newName Name to set as.
    ///////////////////////////////////////////////////////////////////////////
    public function setName($newName)
    {
        $this->name = $newName;
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
            array(
                'name' => $this->name,
                'username' => $this->username
            )
        );
        return $jsonFormat;
    }
}
?>