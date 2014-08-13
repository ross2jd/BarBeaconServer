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
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->x_coordinate = 0.0;
        $this->y_coordinate = 0.0;
    }
}
?>