<?php
include_once 'Beacon.php';
include_once 'DatabaseManager.php';

/**
 * Class: Map
 *
 * Author: Jordan Ross
 * 
 * Version History:
 * 8/19/14 - Created.
 */
class Map
{
    // The name for the bar map.
    private $mapName;
    
    // The path to the map image file
    private $mapImageFile;
    
    // The id for the bar in the DB
    private $mapId;
    
    // The beacons associated with the map.
    private $beacons;
    
    /// The constant for the folder in which all maps are stored on the server
    const FOLDER_NAME = "bar_maps";
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->mapName = "";
        $this->mapImageFile = "";
        $this->beacons = array();
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// loadMap()
    ///
    /// This method will load the members of the Map class from the database
    /// based on the bar name supplied to the method.
    ///
    /// @param requestBarName The bar name of the map to load.
    ///
    /// @return bool
    ///     @retval true - Map loaded successfully.
    ///     @retval false - Map load failed.
    ///////////////////////////////////////////////////////////////////////////
    public function loadMap($requestBarName)
    {
        if (strlen($requestBarName) > 0)
        {
            // The bar name is not null so search the DB.
            $this->db = new DatabaseManager();
            $this->db->connect();
            $sql = 'SELECT * FROM bars WHERE bar_name="'.$requestBarName.'"';
            $this->db->query($sql);
            $result = $this->db->getResult();
            $numResults = $this->db->numRows();
            $this->db->disconnect();
            if ($numResults == 1)
            {
                // We only have 1 entry which is what we expect for a valid result.
                $this->mapName = $result[0]['bar_name'];
                $this->mapImageFile = $result[0]['bar_map_name'];
                $this->mapId = $result[0]['bar_id'];
                
                // Now we need to get the beacons associtated with the map.
                return $this->loadBeaconsForMap($this->mapId);
            }
            else
            {
                // We should never get more than 1 entry since the bar name is suppose
                // to be unique. Thus it means that the bar name does not exist.
                return false;
            }
            
        }
        else
        {
            return false;
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// membersToJsonFormat()
    ///
    /// This method will prepare the members we want into a format we can
    /// encode to JSON.
    ///////////////////////////////////////////////////////////////////////////
    public function membersToJsonFormat()
    {
        $json_beacons = array();
        for ($i = 0; $i < count($this->beacons); $i++)
        {
            $json_beacons['Beacon'.$i] = $this->beacons[$i]->membersToJsonFormat();
        }
        
        $jsonFormat = array(
            array(
                'mapName' => $this->mapName,
                'mapId' => $this->mapId,
                'mapImageFile' => $this->mapImageFile,
                'beacons' => $json_beacons
            )
        );
        return $jsonFormat;
    }
    
    /* -----------------------   PRIVATE METHODS -------------------------- */
    
    ///////////////////////////////////////////////////////////////////////////
    /// loadBeaconsForMap()
    ///
    /// This method will take the bar id and will load the beacons associated
    /// with the ID into the beacons array member.
    ///
    /// @param requestMapId   The map id to load the beacons for.
    ///
    /// @return bool
    ///     @retval true - Beacons loaded successfully.
    ///     @retval false - Beacons load failed.
    ///////////////////////////////////////////////////////////////////////////
    private function loadBeaconsForMap($requestMapId)
    {
        $this->db = new DatabaseManager();
        $this->db->connect();
        $sql = 'SELECT * FROM beacon WHERE placed_bar_id="'.$requestMapId.'"';
        $this->db->query($sql);
        $result = $this->db->getResult();
        $numResults = $this->db->numRows();
        $this->db->disconnect();
        if ($numResults > 0)
        {
            for ($i = 0; $i < $numResults; $i++)
            {
                $tempBeacon = new Beacon();
                $tempBeacon->setUUID($result[$i]['uuid']);
                $tempBeacon->setLocationWithCoordinatesAndMapId($result[$i]['location_x'], $result[$i]['location_y'], $result[$i]['placed_bar_id']);
                array_push($this->beacons, $tempBeacon);
                // unset($tempBeacon);
            }
            
            return true;
        }
        else
        {
            // No beacons were loaded.
            return false;
        }
    }
}
?>