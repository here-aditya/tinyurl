<?php
class ShortUrl
{
    // string: characters used in building the tiny URL
    protected static $chars = "123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
    // boolean: Confugurabale if URL checking is required (Y/N)
    protected static $checkUrlExists = true; 
    // store in database as created time, set when tiny URL is generated / accessed
    protected $timestamp;
    // holds CI instance for library
    protected static $CI;


    /**
    * Constructior of library class for initialization of basic property
    * 
    * Set timestamp to current server time for DB transaction, future use possible
    * initialize var with current instance of CI, DB query model usage
    */
    public function __construct()
    {
        $this->timestamp = $_SERVER["REQUEST_TIME"];
        self::$CI = &get_instance();
        self::$CI->load->model('urlmodel');
    }


    /**
    * Create a short code from a long URL.
    * 
    * Delegates validating the URLs format, validating it, optionally
    * connecting to the URL to make sure it exists, and checking the database
    * to see if the URL is already there. If so, the cooresponding short code
    * is returned. Otherwise, createShortCode() is called to handle the tasks.
    * 
    * @param string: Method POST long URL to be shortened  
    * @return JSON: short code on success, error and message in case of failure
    */    
    public function urlToShortCode() 
    {
        $sata = array();
        $data['is_error'] = false;
        $data['status_msg'] = $data['short_code'] = null;

        $url = $this->input->post('long_url');
        if (empty($url)) {
            $data['is_error'] = true;
            $data['status_msg'] = "No URL was supplied.";
        } else {
            if ($this->validateUrlFormat($url) == false) {
             $data['is_error'] = true;
             $data['status_msg'] = "URL does not have a valid format.");
            } else {
                if (self::$checkUrlExists) {
                    if (!$this->verifyUrlExists($url)) {
                        $data['is_error'] = true;
                        $data['status_msg'] = "URL does not appear to exist.";
                    }
                }
                if( ! $data['is_error']) {
                    if(! $shortCode = $this->urlExistsInDb($url))
                        $data['short_code'] = $this->createShortCode($url);
                    else
                        $data['short_code'] = $shortCode;
                }
            }
        }

        echo json_encode($data);
    }


    /**
    * Retrieve a long URL from a short code.
    * 
    * Deligates validating the supplied short code, getting the long URL from
    * the database, and incrementing the URL's access counter.
    * 
    * @param string: Method POST / GET the short code associated with a long URL
    * @return JSON: in case of POST containg the long URL on success, error and message in case of failure
    * @return 302 redirect in case of GET
    */
    public function shortCodeToUrl($code = null) 
    {
        $sata = array();
        $data['is_error'] = false;
        $data['status_msg'] = $data['long_url'] = null;
        $code = ($input_method = $this->input->method() == 'POST') ? $this->input->post('tiny_url') : $code;

        if (empty($code)) {
            $data['status_msg'] = "No short code was supplied.";
            if($input_method != 'POST') {
                throw new \Exception($data['status_msg']);
            } else {
                 $data['is_error'] = true;
            }
        } else {
            if ($this->validateShortCode($code) == false) {
                $data['status_msg'] = "Short code does not have a valid format.";
                if($input_method != 'POST') {
                    throw new \Exception($data['status_msg']);
                } else {
                    $data['is_error'] = true;
                }
            } else {
                $urlRow = $this->getUrlFromDb($code);
                if (empty($urlRow)) {
                    $data['status_msg'] = "Short code does not appear to exist.";
                    if($input_method != 'POST') {
                        throw new \Exception($data['status_msg']);
                    } else {
                        $data['is_error'] = true;
                    }
                } else {
                    // if first time access of tiny URL set counter to 1
                    // if accessessed tiny URL more than once update counter by 1 
                    self::$CI->urlmodel->insertCounter($urlRow["id"], $this->timestamp);  
                    if($input_method != 'POST') {
                        header('Location: ' . $urlRow["long_url"]);
                        exit;
                    } else {
                        $data['long_url'] = $urlRow["long_url"];
                    }
                }
            }
        }

        echo json_encode($data);
    }


    /**
    * Check to see if the supplied URL is a valid format
    * 
    * @param string: the long URL
    * @return boolean: whether URL is a valid format
    */
    protected function validateUrlFormat($url) 
    {
        return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    }


    /** Check to see if the URL exists
    * 
    * Uses cURL to access the URL and make sure a 404 error is not returned
    * 
    * @param string: the long URL
    * @return boolean: whether the URL does not return a 404 code
    */
    protected function verifyUrlExists($url) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (!empty($response) && $response != 404);
    }


    /**
    * Check the database for the long URL.
    * 
    * If the URL is already in the database then the short code for it is
    * returned.  If the URL is not, false is returned.  An exception is thrown
    * if there is a database error.
    *  
    * @param string $url the long URL
    * @return string|boolean the short code if it exists - false if it does not
    */
    protected function urlExistsInDb($url) 
    {
        $result = self::$CI->urlmodel->fetchShortCode($url);
        return (empty($result)) ? false : $result;
    }


    /**
     * Delegates creating a short code from a long URL.
     * 
     * Delegates inserting the URL into the database, converting the integer
     * of the row's ID column into a short code, and updating the database with
     * the code. If successful, it returns the short code. If there is an error,
     * an exception is thrown.
     * 
     * @param string $url the long URL
     * @return string the created short code
     * @throws Exception if an error occurs 
     */
    protected function createShortCode($url) 
    {
        $id = self::$CI->urlmodel->insertLongUrl($url, $this->timestamp);
        $shortCode = $this->convertIntToShortCode($id); 
        self::$CI->urlmodel->updateShortUrl($shortCode, $id);
        return $shortCode;
    }


    /**
    * Convert an integer to a short code.
    * 
    * This method does the actual conversion of the ID integer to a short code.
    * If successful, it returns the created code. If there is an error, an
    * exception is thrown.
    * 
    * @param int $id the integer to be converted
    * @return string the created short code
    * @throws Exception if an error occurs
    */
    protected function convertIntToShortCode($id) 
    {
        $id = intval($id);
        if ($id < 1) {
            throw new \Exception("The ID is not a valid integer");
        }

        $length = strlen(self::$chars);
        // least 10 characters
        if ($length < 10) {
            throw new \Exception("Length of chars is too small");
        }

        $code = "";
        while ($id > $length - 1) {
            // determine the value of the next higher character
            // in the short code should be and prepend
            $code = self::$chars[fmod($id, $length)] . $code;
            // reset $id to remaining value to be converted
            $id = floor($id / $length);
        }

        // remaining value of $id is less than the length of self::$chars
        $code = self::$chars[$id] . $code;

        return $code;
    }


    /**
    * Check to see if the supplied short code is a valid format
    * 
    * @param string: the short code
    * @return boolean: whether the short code is in a valid format
    */
    protected function validateShortCode($code)
    {
        return preg_match("|[" . self::$chars . "]+|", $code);
    }


    /**
    * Get the long URL from the database.
    * 
    * Retrieve the URL associated with the short code from the database. If
    * there is an error, an exception is thrown.
    * 
    * @param string $code the short code to look for in the database
    * @return string|boolean the long URL or false if it does not exist
    */
    protected function getUrlFromDb($code) 
    {
        $result = self::$CI->urlmodel->fetchLongUrl($code);
        return (empty($result)) ? false : $result;
    }
}