<?php
/**
 * Class: Location
 *
 * Author: Jordan Ross
 * 
 * Version History:
 * 8/13/14 - Created.
 */
class Location
{
    /// The x coordinate of the location
    private $x_coordinate;
    
    /// The y coordinate of the location
    private $y_coordinate;
    
    /// The bar id of the location
    private $barId;
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->x_coordinate = 0.0;
        $this->y_coordinate = 0.0;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// setLocationWithCoordinatesAndMapId()
    ///
    /// The method will set the members using an x coordinate, y coordinate, 
    /// and the bar id.
    ///
    /// @param $x The x coordinate of the location.
    /// @param $y The y coordinate of the location.
    /// @param $mapId   The id of the bar
    ///////////////////////////////////////////////////////////////////////////
    public function setLocationWithCoordinatesAndMapId($x, $y, $mapId)
    {
        $this->x_coordinate = $x;
        $this->y_coordinate = $y;
        $this->barId = $mapId;
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
                'x_coor' => $this->x_coordinate,
                'y_coor' => $this->y_coordinate,
                'bar_id' => $this->barId
            )
        );
        return $jsonFormat;
    }
}
?>