<?php
include_once 'Status.php';
include_once 'DatabaseManager.php';

/**
 * Class: ContentManager
 *
 * Author: Jordan Ross
 * 
 * Version History:
 * 8/27/14 - Created.
 */
class ContentManager
{
    /// The status object for the manager
    public $status;
    
    /// The array of file names with the content for the bar
    public $files;
    
    /// The database
    private $db;
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->status = new Status();
        $this->files = array();
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// getContentFiles()
    ///
    /// This method will get the names of all the content files associated with
    /// the given bar id and will save them to the files member.
    ///
    /// @param $requestMapId The id of the bar for the requsted content.
    ///
    /// @return bool
    ///     @retval true - load successful.
    ///     @retval false - load failed.
    ///////////////////////////////////////////////////////////////////////////
    public function getContentFiles($requestMapId)
    {
        $this->db = new DatabaseManager();
        $this->db->connect();
        $sql = 'SELECT content_image_name FROM content WHERE bar_id="'.$requestMapId.'"';
        $this->db->query($sql);
        $result = $this->db->getResult();
        $numResults = $this->db->numRows();
        $this->db->disconnect();
        if ($numResults > 0)
        {
            for ($i = 0; $i < $numResults; $i++)
            {
                array_push($this->files, $result[$i]['content_image_name']);
            }
            
            return true;
        }
        else
        {
            // No content was found.
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
        $json_array['Content'] = array(
            array(
                'files' => $this->files
            )
        );
        $json_array['Status'] = $this->status->membersToJsonFormat();
        
        return json_encode($json_array);
    }
}
?>