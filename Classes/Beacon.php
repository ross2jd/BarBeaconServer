<?php
include_once 'Location.php';

/**
 * Class: Beacon
 *
 * Author: Jordan Ross
 * 
 * Version History:
 * 8/19/14 - Created.
 */
class Beacon
{
    // The unique identifier of the beacon
    private $uuid;
    
    // The location of the beacon
    private $location;
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->uuid = "";
        $this->location = new Location();
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// setUUID()
    ///
    /// The method will set the uuid of the beacon.
    ///
    /// @param $requestUUID The uuid to set to.
    ///////////////////////////////////////////////////////////////////////////
    public function setUUID($requestUUID)
    {
        $this->uuid = $requestUUID;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// setLocationWithCoordinatesAndMapId()
    ///
    /// The method will set the location of the beacon using an x coordinate,
    /// y coordinate, and the bar id.
    ///
    /// @param $x The x coordinate of the location.
    /// @param $y The y coordinate of the location.
    /// @param $mapId   The id of the bar
    ///////////////////////////////////////////////////////////////////////////
    public function setLocationWithCoordinatesAndMapId($x, $y, $mapId)
    {
        $this->location->setLocationWithCoordinatesAndMapId($x, $y, $mapId);
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
                'UUID' => $this->uuid,
                'location' => $this->location->membersToJsonFormat()
            )
        );
        return $jsonFormat;
    }
}
?>