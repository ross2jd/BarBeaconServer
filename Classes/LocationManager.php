<?php
include_once 'Map.php';
include_once 'Status.php';

/**
 * Class: LocationManager
 *
 * Author: Jordan Ross
 * 
 * Version History:
 * 8/19/14 - Created.
 */
class LocationManager
{
    /// The profile of the user to login
    private $map;
    
    /// The status object for the manager
    public $status;
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->map = new Map();
        $this->status = new Status();
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// getMap()
    ///
    /// This method will attempt to get the map object based on the bar name
    /// provided as the parameter. If this method is successful the map member
    /// will have all members loaded and can be encoded with JSON.
    ///
    /// @param barName The name of the bar for the requsted map
    ///
    /// @return bool
    ///     @retval true - load successful.
    ///     @retval false - load failed.
    ///////////////////////////////////////////////////////////////////////////
    public function getMap($barName)
    {
        if (strlen($barName) > 0)
        {
            // If barName value is supplied then attempt to load.
            return $this->map->loadMap($barName);
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
            $json_array['Map'] = $this->map->membersToJsonFormat();
            $json_array['Status'] = $this->status->membersToJsonFormat();
        }
        return json_encode($json_array);
    }
}
?>