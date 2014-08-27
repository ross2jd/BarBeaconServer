<?php
/**
 * Class: DatabaseManager
 *
 * Author: Jordan Ross
 * 
 * Version History:
 * 8/12/14 - Created.
 */
class DatabaseManager
{
    
    private $db_host;
    private $db_user;
    private $db_password;
    private $db_name;
    private $con;
    
    // Holds the result of the most recent query
    private $result;
    private $myQuery;
    private $numResults;
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->db_host = "localhost";
        $this->db_user = "root";
        $this->db_password = "_ath2StaQafr";
        $this->db_name = "BAR_BEACON_TEST";
        $this->con = NULL;
        $this->result = array();
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// connect()
    ///
    /// This method will create a connection to the database and select the
    /// database to be used for queries. Queries can be made if the connection
    /// is successful.
    ///
    /// @return bool
    ///     @retval true - Connection successful. Can make queries now.
    ///     @retval false - Connection failed. Can't make queries.
    ///////////////////////////////////////////////////////////////////////////
    public function connect()
    {
        if (!$this->con)
        {
            // We do not have an existing connection, create a new one.
            $this->con = mysqli_connect($this->db_host, $this->db_user, $this->db_password, $this->db_name);
            // Check connection
            if (mysqli_connect_errno()) {
                $this->con = NULL;
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// disconnect()
    ///
    /// This method will severe the connection to the database. Queries will no
    /// longer be able to be made after.
    ///
    /// @return bool
    ///     @retval true - Disconnect successful.
    ///     @retval false - Disconnect failed.
    ///////////////////////////////////////////////////////////////////////////
    public function disconnect()
    {
        if ($this->con)
        {
            // We have a connection to close
            mysqli_close($this->con);
            if (mysqli_connect_errno()) {
                return false;
            }
            $this->con = NULL;
            return true;
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// query()
    ///
    /// This method takes an SQL statement and attempts to execute it. It then
    /// stores the results to an array if successful. If it is not then it will
    /// store the error message.
    ///
    /// @param $sql The SQL statement to execute
    ///
    /// @return bool
    ///     @retval true - The query was successful.
    ///     @retval false - The query failed.
    ///////////////////////////////////////////////////////////////////////////
    public function query($sql){
    	$query = mysqli_query($this->con, $sql);
        $this->myQuery = $sql; // Pass back the SQL
    	if($query) {
    		// If the query returns >= 1 assign the number of rows to numResults
    		$this->numResults = mysqli_num_rows($query);
    		// Loop through the query results by the number of rows returned
    		for($i = 0; $i < $this->numResults; $i++){
    			$r = mysqli_fetch_array($query);
               	$key = array_keys($r);
               	for($x = 0; $x < count($key); $x++){
               		// Sanitizes keys so only alphavalues are allowed
                   	if(!is_int($key[$x])){
                   		if(mysqli_num_rows($query) >= 1){
                   			$this->result[$i][$key[$x]] = $r[$key[$x]];
    					} else {
    						$this->result = null;
    					}
    				}
    			}
    		}
            mysqli_free_result($query);
    		return true; // Query was successful
    	} else {
    		array_push($this->result,mysqli_error($this->con));
            mysqli_free_result($query);
    		return false; // No rows where returned
    	}
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// insertQuery()
    ///
    /// This method takes an insert SQL statement and attempts to execute it. 
    /// If it fails then it will store the error message;
    ///
    /// @param $sql The SQL statement to execute
    ///
    /// @return bool
    ///     @retval true - The query was successful.
    ///     @retval false - The query failed.
    ///////////////////////////////////////////////////////////////////////////
    public function insertQuery($sql)
    {
        $query = mysqli_query($this->con, $sql);
        if (!$query) {
    		array_push($this->result,mysqli_error($this->con));
            mysqli_free_result($query);
    		return false; // Insert failed!
        }
        return true;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// getResult()
    ///
    /// This method will retrieve the result stored in the result member.
    ///
    ///////////////////////////////////////////////////////////////////////////
    public function getResult(){
        $val = $this->result;
        $this->result = array();
        return $val;
    }

    ///////////////////////////////////////////////////////////////////////////
    /// getSql()
    ///
    /// This method will retrieve the SQL statement executed last.
    ///
    ///////////////////////////////////////////////////////////////////////////
    public function getSql(){
        $val = $this->myQuery;
        $this->myQuery = array();
        return $val;
    }

    //////////////////////////////////////////////////////////////////////////
    /// numRows()
    ///
    /// This method will retrieve the number of rows in the result.
    ///
    ///////////////////////////////////////////////////////////////////////////
    public function numRows(){
        $val = $this->numResults;
        $this->numResults = array();
        return $val;
    }
}

?>