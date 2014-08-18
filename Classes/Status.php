<?php
/**
 * Class: Status
 *
 * Author: Jordan Ross
 * 
 * Version History:
 * 8/18/14 - Created.
 */
class Status
{
    /// The code of the status
    private $code;
    
    /// The profile of the user to login
    private $message;
    
    // The status codes
    const SUCCESS = 0;
    const ERROR = 1;
    
    ///////////////////////////////////////////////////////////////////////////
    /// Constructor
    ///
    ///////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->message = "";
        $this->code = self::SUCCESS;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// setStatusCode()
    ///
    /// This method will set the status code of the class without specifiying a
    /// message. If a message did exist before it will be cleared out. This method
    /// should usually be used with a succeess code.
    /// 
    /// @param statusCode   The code to be set.
    ///////////////////////////////////////////////////////////////////////////
    public function setStatusCode($statusCode)
    {
        // Clear out the message since we are just giving a code and we don't
        // want mixed messages.
        $this->message = "";
        $this->code = $statusCode;
    }
    
    
    ///////////////////////////////////////////////////////////////////////////
    /// setStatusCodeWithMessage()
    ///
    /// This method will set the status code of the class with a message.
    /// 
    /// @param statusCode   The code to be set.
    /// @param statusMessage    The message to be set.
    ///////////////////////////////////////////////////////////////////////////
    public function setStatusCodeWithMessage($statusCode, $statusMessage)
    {
        $this->message = $statusMessage;
        $this->code = $statusCode;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /// getCode()
    ///
    /// This method will return the code of the object.
    ///
    /// @return code    The status code of the object.
    ///////////////////////////////////////////////////////////////////////////
    public function getCode()
    {
        return $this->code;
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
                'code' => $this->code,
                'message' => $this->message
            )
        );
        return $jsonFormat;
    }
}
?>