<?php

/**
 * HTTP Class
 *
 * This is a wrapper HTTP class that uses either cURL or fsockopen to 
 * harvest resources from web. This can be used with scripts that need 
 * a way to communicate with various APIs who support REST.
 *
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @package     HTTP Library
 * @copyright   2007-2008 Md Emran Hasan
 * @link        http://www.phpfour.com/lib/http
 * @since       Version 0.1
 */

class Http
{
    /**
     * Contains the target URL
     *
     * @var string
     */
    protected $target;
    
    /**
     * Contains the target host
     *
     * @var string
     */
    protected $host;
    
    /**
     * Contains the target port
     *
     * @var integer
     */
    protected $port;
    
    /**
     * Contains the target path
     *
     * @var string
     */
    protected $path;
    
    /**
     * Contains the target schema
     *
     * @var string
     */
    protected $schema;
    
    /**
     * Contains the http method (GET or POST)
     *
     * @var string
     */
    protected $method;
    
    /**
     * Contains the parameters for request
     *
     * @var array
     */
    protected $params;
    
    /**
     * Contains the cookies for request
     *
     * @var array
     */
    protected $cookies;
    
    /**
     * Contains the cookies retrieved from response
     *
     * @var array
     */
    protected $_cookies;
    
    /**
     * Number of seconds to timeout
     *
     * @var integer
     */
    protected $timeout;
    
    /**
     * Whether to use cURL or not
     *
     * @var boolean
     */
    protected $useCurl;
    
    /**
     * Contains the referrer URL
     *
     * @var string
     */
    protected $referrer;
    
    /**
     * Contains the User agent string
     *
     * @var string
     */
    protected $userAgent;
    
    /**
     * Contains the cookie path (to be used with cURL)
     *
     * @var string
     */
    protected $cookiePath;
    
    /**
     * Whether to use cookie at all
     *
     * @var boolean
     */
    protected $useCookie;
    
    /**
     * Whether to store cookie for subsequent requests
     *
     * @var boolean
     */
    protected $saveCookie;
    
    /**
     * Contains the Username (for authentication)
     *
     * @var string
     */
    protected $username;
    
    /**
     * Contains the Password (for authentication)
     *
     * @var string
     */
    protected $password;
    
    /**
     * Contains the fetched web source
     *
     * @var string
     */
    protected $result;
    
    /**
     * Contains the last headers 
     *
     * @var string
     */
    protected $headers;
    
    /**
     * Contains the last call's http status code
     *
     * @var string
     */
    protected $status;
    
    /**
     * Whether to follow http redirect or not
     *
     * @var boolean
     */
    protected $redirect;
    
    /**
     * The maximum number of redirect to follow
     *
     * @var integer
     */
    protected $maxRedirect;
    
    /**
     * The current number of redirects
     *
     * @var integer
     */
    protected $curRedirect;
    
    /**
     * Contains any error occurred
     *
     * @var string
     */
    protected $error;
    
    /**
     * Store the next token
     *
     * @var string
     */
    protected $nextToken;
    
    /**
     * Whether to keep debug messages
     *
     * @var boolean
     */
    protected $debug;
    
    /**
     * Stores the debug messages
     *
     * @var array
     * @todo will keep debug messages
     */
    protected $debugMsg;
    
    /**
     * Constructor for initializing the class with default values.
     * 
     * @return void  
     */
    public function __construct()
    {
        $this->clear();    
    }
    
    /**
     * Initialize preferences
     * 
     * This function will take an associative array of config values and 
     * will initialize the class variables using them. 
     * 
     * Example use:
     * 
     * <pre>
     * $httpConfig['method']     = 'GET';
     * $httpConfig['target']     = 'http://www.somedomain.com/index.html';
     * $httpConfig['referrer']   = 'http://www.somedomain.com';
     * $httpConfig['user_agent'] = 'My Crawler';
     * $httpConfig['timeout']    = '30';
     * $httpConfig['params']     = array('var1' => 'testvalue', 'var2' => 'somevalue');
     * 
     * $http = new Http();
     * $http->initialize($httpConfig);
     * </pre>
     *
     * @param array Config values as associative array
     * @return void
     */    
    public function initialize($config = array())
    {
        $this->clear();
        foreach ($config as $key => $val)
        {
            if (isset($this->$key))
            {
                $method = 'set' . ucfirst(str_replace('_', '', $key));
                
                if (method_exists($this, $method))
                {
                    $this->$method($val);
                }
                else
                {
                    $this->$key = $val;
                }            
            }
        }
    }
    
    /**
     * Clear Everything
     * 
     * Clears all the properties of the class and sets the object to
     * the beginning state. Very handy if you are doing subsequent calls 
     * with different data.
     *
     * @return void
     */
    public function clear()
    {
        // Set the request defaults
        $this->host         = '';
        $this->port         = 0;
        $this->path         = '';
        $this->target       = '';
        $this->method       = 'GET';
        $this->schema       = 'http';
        $this->params       = array();
        $this->headers      = array();
        $this->cookies      = array();
        $this->_cookies     = array();
        
        // Set the config details        
        $this->debug        = FALSE;
        $this->error        = '';
        $this->status       = 0;
        $this->timeout      = '25';
        $this->useCurl      = TRUE;
        $this->referrer     = '';
        $this->username     = '';
        $this->password     = '';
        $this->redirect     = TRUE;
        
        // Set the cookie and agent defaults
        $this->nextToken    = '';
        $this->useCookie    = TRUE;
        $this->saveCookie   = TRUE;
        $this->maxRedirect  = 3;
        $this->cookiePath   = 'cookie.txt';
        $this->userAgent    = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.9';
    }
    
    /**
     * Set target URL
     *
     * @param string URL of target resource
     * @return void
     */
    public function setTarget($url)
    {
        if ($url)
        {
            $this->target = $url;
        }   
    }
    
    /**
     * Set http method
     *
     * @param string HTTP method to use (GET or POST)
     * @return void
     */
    public function setMethod($method)
    {
        if ($method == 'GET' || $method == 'POST')
        {
            $this->method = $method;
        }   
    }
    
    /**
     * Set referrer URL
     *
     * @param string URL of referrer page
     * @return void
     */
    public function setReferrer($referrer)
    {
        if ($referrer)
        {
            $this->referrer = $referrer;
        }   
    }
    
    /**
     * Set User agent string
     *
     * @param string Full user agent string
     * @return void
     */
    public function setUseragent($agent)
    {
        if ($agent)
        {
            $this->userAgent = $agent;
        }   
    }
    
    /**
     * Set timeout of execution
     *
     * @param integer Timeout delay in seconds
     * @return void
     */
    public function setTimeout($seconds)
    {
        if ($seconds > 0)
        {
            $this->timeout = $seconds;
        }   
    }
    
    /**
     * Set cookie path (cURL only)
     *
     * @param string File location of cookiejar
     * @return void
     */
    public function setCookiepath($path)
    {
        if ($path)
        {
            $this->cookiePath = $path;
        }   
    }
    
    /**
     * Set request parameters
     *
     * @param array All the parameters for GET or POST
     * @return void
     */
    public function setParams($dataArray)
    {
        if (is_array($dataArray))
        {
            $this->params = array_merge($this->params, $dataArray);
        }   
    }
    
    /**
     * Set basic http authentication realm
     *
     * @param string Username for authentication
     * @param string Password for authentication
     * @return void
     */
    public function setAuth($username, $password)
    {
        if (!empty($username) && !empty($password))
        {
            $this->username = $username;
            $this->password = $password;
        }
    }
    
    /**
     * Set maximum number of redirection to follow
     *
     * @param integer Maximum number of redirects
     * @return void
     */
    public function setMaxredirect($value)
    {
        if (!empty($value))
        {
            $this->maxRedirect = $value;
        }
    }
    
    /**
     * Add request parameters
     *
     * @param string Name of the parameter
     * @param string Value of the parameter
     * @return void
     */
    public function addParam($name, $value)
    {
        if (!empty($name) && !empty($value))
        {
            $this->params[$name] = $value;
        }   
    }
    
    /**
     * Add a cookie to the request
     *
     * @param string Name of cookie
     * @param string Value of cookie
     * @return void
     */
    public function addCookie($name, $value)
    {
        if (!empty($name) && !empty($value))
        {
            $this->cookies[$name] = $value;
        }   
    }
    
    /**
     * Whether to use cURL or not
     *
     * @param boolean Whether to use cURL or not
     * @return void
     */
    public function useCurl($value = TRUE)
    {
        if (is_bool($value))
        {
            $this->useCurl = $value;
        }   
    }
    
    /**
     * Whether to use cookies or not
     *
     * @param boolean Whether to use cookies or not
     * @return void
     */
    public function useCookie($value = TRUE)
    {
        if (is_bool($value))
        {
            $this->useCookie = $value;
        }   
    }
    
    /**
     * Whether to save persistent cookies in subsequent calls
     *
     * @param boolean Whether to save persistent cookies or not
     * @return void
     */
    public function saveCookie($value = TRUE)
    {
        if (is_bool($value))
        {
            $this->saveCookie = $value;
        }   
    }
    
    /**
     * Whether to follow HTTP redirects
     *
     * @param boolean Whether to follow HTTP redirects or not
     * @return void
     */
    public function followRedirects($value = TRUE)
    {
        if (is_bool($value))
        {
            $this->redirect = $value;
        }   
    }
    
    /**
     * Get execution result body
     *
     * @return string output of execution
     */
    public function getResult()
    {
        return $this->result;
    }
    
    /**
     * Get execution result headers
     *
     * @return array last headers of execution
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get execution status code
     *
     * @return integer last http status code
     */
    public function getStatus()
    {
        return $this->status;
    }
        
    /**
     * Get last execution error
     *
     * @return string last error message (if any)
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Execute a HTTP request
     * 
     * Executes the http fetch using all the set properties. Intellegently
     * switch to fsockopen if cURL is not present. And be smart to follow
     * redirects (if asked so).
     * 
     * @param string URL of the target page (optional)
     * @param string URL of the referrer page (optional)
     * @param string The http method (GET or POST) (optional)
     * @param array Parameter array for GET or POST (optional)
     * @return string Response body of the target page
     */    
    public function execute($target = '', $referrer = '', $method = '', $data = array())
    {
        // Populate the properties
        $this->target = ($target) ? $target : $this->target;
        $this->method = ($method) ? $method : $this->method;
        
        $this->referrer = ($referrer) ? $referrer : $this->referrer;
        
        // Add the new params
        if (is_array($data) && count($data) > 0) 
        {
            $this->params = array_merge($this->params, $data);
        }
        
        // Process data, if presented
        if(is_array($this->params) && count($this->params) > 0)
        {
            // Get a blank slate
            $tempString = array();
            
            // Convert data array into a query string (ie animal=dog&sport=baseball)
            foreach ($this->params as $key => $value) 
            {
                if(strlen(trim($value))>0)
                {
                    $tempString[] = $key . "=" . urlencode($value);
                }
            }
            
            $queryString = join('&', $tempString);
        }
        
        // If cURL is not installed, we'll force fscokopen
        $this->useCurl = $this->useCurl && in_array('curl', get_loaded_extensions());
        
        // GET method configuration
        if($this->method == 'GET')
        {
            if(isset($queryString))
            {
                $this->target = $this->target . "?" . $queryString;
            }
        }
        
        // Parse target URL
        $urlParsed = parse_url($this->target);
        
        // Handle SSL connection request
        if ($urlParsed['scheme'] == 'https')
        {
            $this->host = 'ssl://' . $urlParsed['host'];
            $this->port = ($this->port != 0) ? $this->port : 443;
        }
        else
        {
            $this->host = $urlParsed['host'];
            $this->port = ($this->port != 0) ? $this->port : 80;
        }
        
        // Finalize the target path
        $this->path   = (isset($urlParsed['path']) ? $urlParsed['path'] : '/') . (isset($urlParsed['query']) ? '?' . $urlParsed['query'] : '');
        $this->schema = $urlParsed['scheme'];
        
        // Pass the requred cookies
        $this->_passCookies();
        
        // Process cookies, if requested
        if(is_array($this->cookies) && count($this->cookies) > 0)
        {
            // Get a blank slate
            $tempString   = array();
            
            // Convert cookiesa array into a query string (ie animal=dog&sport=baseball)
            foreach ($this->cookies as $key => $value) 
            {
                if(strlen(trim($value)) > 0)
                {
                    $tempString[] = $key . "=" . urlencode($value);
                }
            }
            
            $cookieString = join('&', $tempString);
        }
        
        // Do we need to use cURL
        if ($this->useCurl)
        {
            // Initialize PHP cURL handle
            $ch = curl_init();
    
            // GET method configuration
            if($this->method == 'GET')
            {
                curl_setopt ($ch, CURLOPT_HTTPGET, TRUE); 
                curl_setopt ($ch, CURLOPT_POST, FALSE); 
            }
            // POST method configuration
            else
            {
                if(isset($queryString))
                {
                    curl_setopt ($ch, CURLOPT_POSTFIELDS, $queryString);
                }
                
                curl_setopt ($ch, CURLOPT_POST, TRUE); 
                curl_setopt ($ch, CURLOPT_HTTPGET, FALSE); 
            }
            
            // Basic Authentication configuration
            if ($this->username && $this->password)
            {
                curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
            }
            
            // Custom cookie configuration
            if($this->useCookie && isset($cookieString))
            {
                curl_setopt ($ch, CURLOPT_COOKIE, $cookieString);
            }
            
            curl_setopt($ch, CURLOPT_HEADER,         TRUE);                 // No need of headers
            curl_setopt($ch, CURLOPT_NOBODY,         FALSE);                // Return body
                
            curl_setopt($ch, CURLOPT_COOKIEJAR,      $this->cookiePath);    // Cookie management.
            curl_setopt($ch, CURLOPT_TIMEOUT,        $this->timeout);       // Timeout
            curl_setopt($ch, CURLOPT_USERAGENT,      $this->userAgent);     // Webbot name
            curl_setopt($ch, CURLOPT_URL,            $this->target);        // Target site
            curl_setopt($ch, CURLOPT_REFERER,        $this->referrer);      // Referer value
            
            curl_setopt($ch, CURLOPT_VERBOSE,        FALSE);                // Minimize logs
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);                // No certificate
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->redirect);      // Follow redirects
            curl_setopt($ch, CURLOPT_MAXREDIRS,      $this->maxRedirect);   // Limit redirections to four
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);                 // Return in string
            
            // Get the target contents
            $content = curl_exec($ch);
            $contentArray = preg_split("/\r\n\r\n/", $content, 2); // PATCH: only split once
            
            // Get the request info 
            $status  = curl_getinfo($ch);
            
            // Store the contents
            $this->result = $contentArray[1]; // PATCH: second part is the content

            // Parse the headers
            $this->_parseHeaders($contentArray[0]); // PATCH: first part is the header
                        
            // Store the error (is any)
            $this->_setError(curl_error($ch));
            
            // Close PHP cURL handle
            curl_close($ch);
        }
        else
        {
            // Get a file pointer
            $filePointer = fsockopen($this->host, $this->port, $errorNumber, $errorString, $this->timeout);
       
            // We have an error if pointer is not there
            if (!$filePointer)
            {
                $this->_setError('Failed opening http socket connection: ' . $errorString . ' (' . $errorNumber . ')');
                return FALSE;
            }

            // Set http headers with host, user-agent and content type
            $requestHeader  = $this->method . " " . $this->path . "  HTTP/1.1\r\n";
            $requestHeader .= "Host: " . $urlParsed['host'] . "\r\n";
            $requestHeader .= "User-Agent: " . $this->userAgent . "\r\n";
            $requestHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
            
            // Specify the custom cookies
            if ($this->useCookie && $cookieString != '')
            {
                $requestHeader.= "Cookie: " . $cookieString . "\r\n";
            }

            // POST method configuration
            if ($this->method == "POST")
            {
                $requestHeader.= "Content-Length: " . strlen($queryString) . "\r\n";
            }
            
            // Specify the referrer
            if ($this->referrer != '')
            {
                $requestHeader.= "Referer: " . $this->referrer . "\r\n";
            }
            
            // Specify http authentication (basic)
            if ($this->username && $this->password)
            {
                $requestHeader.= "Authorization: Basic " . base64_encode($this->username . ':' . $this->password) . "\r\n";
            }
       
            $requestHeader.= "Connection: close\r\n\r\n";
       
            // POST method configuration
            if ($this->method == "POST")
            {
                $requestHeader .= $queryString;
            }           

            // We're ready to launch
            fwrite($filePointer, $requestHeader);
       
            // Clean the slate
            $responseHeader = '';
            $responseContent = '';

            // 3...2...1...Launch !
            do
            {
                $responseHeader .= fread($filePointer, 1);
            }
            while (!preg_match('/\\r\\n\\r\\n$/', $responseHeader));
            
            // Parse the headers
            $this->_parseHeaders($responseHeader);
            
            // Do we have a 301/302 redirect ?
            if (($this->status == '301' || $this->status == '302') && $this->redirect == TRUE)
            {
                if ($this->curRedirect < $this->maxRedirect)
                {
                    // Let's find out the new redirect URL
                    $newUrlParsed = parse_url($this->headers['location']);
                    
                    if ($newUrlParsed['host'])
                    {
                        $newTarget = $this->headers['location'];    
                    }
                    else
                    {
                        $newTarget = $this->schema . '://' . $this->host . '/' . $this->headers['location'];
                    }
                    
                    // Reset some of the properties
                    $this->port   = 0;
                    $this->status = 0;
                    $this->params = array();
                    $this->method = 'GET';
                    $this->referrer = $this->target;
                    
                    // Increase the redirect counter
                    $this->curRedirect++;
                    
                    // Let's go, go, go !
                    $this->result = $this->execute($newTarget);
                }
                else
                {
                    $this->_setError('Too many redirects.');
                    return FALSE;
                }
            }
            else
            {
                // Nope...so lets get the rest of the contents (non-chunked)
                if ($this->headers['transfer-encoding'] != 'chunked')
                {
                    while (!feof($filePointer))
                    {
                        $responseContent .= fgets($filePointer, 128);
                    }
                }
                else
                {
                    // Get the contents (chunked)
                    while ($chunkLength = hexdec(fgets($filePointer)))
                    {
                        $responseContentChunk = '';
                        $readLength = 0;
                       
                        while ($readLength < $chunkLength)
                        {
                            $responseContentChunk .= fread($filePointer, $chunkLength - $readLength);
                            $readLength = strlen($responseContentChunk);
                        }

                        $responseContent .= $responseContentChunk;
                        fgets($filePointer);  
                    }
                }
                
                // Store the target contents
                $this->result = chop($responseContent);
            }
        }
        
        // There it is! We have it!! Return to base !!!
        return $this->result;
    }
    
    /**
     * Parse Headers (internal)
     * 
     * Parse the response headers and store them for finding the response 
     * status, redirection location, cookies, etc. 
     *
     * @param string Raw header response
     * @return void
     * @access private
     */
    protected function _parseHeaders($responseHeader)
    {
        // Break up the headers
        $headers = explode("\r\n", $responseHeader);
        
        // Clear the header array
        $this->_clearHeaders();
        
        // Get response status
        if($this->status == 0)
        {
            // Oooops !
            if(!eregi($match = "^http/[0-9]+\\.[0-9]+[ \t]+([0-9]+)[ \t]*(.*)\$", $headers[0], $matches))
            {
                $this->_setError('Unexpected HTTP response status');
                return FALSE;
            }
            
            // Gotcha!
            $this->status = $matches[1];
            array_shift($headers);
        }
        
        // Prepare all the other headers
        foreach ($headers as $header)
        {
            // Get name and value
            $headerName  = strtolower($this->_tokenize($header, ':'));
            $headerValue = trim(chop($this->_tokenize("\r\n")));
            
            // If its already there, then add as an array. Otherwise, just keep there
            if(isset($this->headers[$headerName]))
            {
                if(gettype($this->headers[$headerName]) == "string")
                {
                    $this->headers[$headerName] = array($this->headers[$headerName]);
                }
                    
                $this->headers[$headerName][] = $headerValue;
            }
            else
            {
                $this->headers[$headerName] = $headerValue;
            }
        }
            
        // Save cookies if asked 
        if ($this->saveCookie && isset($this->headers['set-cookie']))
        {
            $this->_parseCookie();
        }
    }
    
    /**
     * Clear the headers array (internal)
     *
     * @return void
     * @access private
     */
    protected function _clearHeaders()
    {
        $this->headers = array();
    }
    
    /**
     * Parse Cookies (internal)
     * 
     * Parse the set-cookie headers from response and add them for inclusion.
     *
     * @return void
     * @access private
     */
    protected function _parseCookie()
    {
        // Get the cookie header as array
        if(gettype($this->headers['set-cookie']) == "array")
        {
            $cookieHeaders = $this->headers['set-cookie'];
        }
        else
        {
            $cookieHeaders = array($this->headers['set-cookie']);
        }

        // Loop through the cookies
        for ($cookie = 0; $cookie < count($cookieHeaders); $cookie++)
        {
            $cookieName  = trim($this->_tokenize($cookieHeaders[$cookie], "="));
            $cookieValue = $this->_tokenize(";");
            
            $urlParsed   = parse_url($this->target);
            
            $domain      = $urlParsed['host'];
            $secure      = '0';
            
            $path        = "/";
            $expires     = "";
            
            while(($name = trim(urldecode($this->_tokenize("=")))) != "")
            {
                $value = urldecode($this->_tokenize(";"));
                
                switch($name)
                {
                    case "path"     : $path     = $value; break;
                    case "domain"   : $domain   = $value; break;
                    case "secure"   : $secure   = ($value != '') ? '1' : '0'; break;
                }
            }
            
            $this->_setCookie($cookieName, $cookieValue, $expires, $path , $domain, $secure);
        }
    }
    
    /**
     * Set cookie (internal)
     * 
     * Populate the internal _cookies array for future inclusion in 
     * subsequent requests. This actually validates and then populates 
     * the object properties with a dimensional entry for cookie.
     *
     * @param string Cookie name
     * @param string Cookie value
     * @param string Cookie expire date
     * @param string Cookie path
     * @param string Cookie domain
     * @param string Cookie security (0 = non-secure, 1 = secure)
     * @return void
     * @access private
     */
    protected function _setCookie($name, $value, $expires = "" , $path = "/" , $domain = "" , $secure = 0)
    {
        if(strlen($name) == 0)
        {
            return($this->_setError("No valid cookie name was specified."));
        }

        if(strlen($path) == 0 || strcmp($path[0], "/"))
        {
            return($this->_setError("$path is not a valid path for setting cookie $name."));
        }
            
        if($domain == "" || !strpos($domain, ".", $domain[0] == "." ? 1 : 0))
        {
            return($this->_setError("$domain is not a valid domain for setting cookie $name."));
        }
        
        $domain = strtolower($domain);
        
        if(!strcmp($domain[0], "."))
        {
            $domain = substr($domain, 1);
        }
            
        $name  = $this->_encodeCookie($name, true);
        $value = $this->_encodeCookie($value, false);
        
        $secure = intval($secure);
        
        $this->_cookies[] = array( "name"      =>  $name,
                                   "value"     =>  $value,
                                   "domain"    =>  $domain,
                                   "path"      =>  $path,
                                   "expires"   =>  $expires,
                                   "secure"    =>  $secure
                                 );
    }
    
    /**
     * Encode cookie name/value (internal)
     *
     * @param string Value of cookie to encode
     * @param string Name of cookie to encode
     * @return string encoded string
     * @access private
     */
    protected function _encodeCookie($value, $name)
    {
        return($name ? str_replace("=", "%25", $value) : str_replace(";", "%3B", $value));
    }
    
    /**
     * Pass Cookies (internal)
     * 
     * Get the cookies which are valid for the current request. Checks 
     * domain and path to decide the return.
     *
     * @return void
     * @access private
     */
    protected function _passCookies()
    {
        if (is_array($this->_cookies) && count($this->_cookies) > 0)
        {
            $urlParsed = parse_url($this->target);
            $tempCookies = array();
            
            foreach($this->_cookies as $cookie)
            {
                if ($this->_domainMatch($urlParsed['host'], $cookie['domain']) && (0 === strpos($urlParsed['path'], $cookie['path']))
                    && (empty($cookie['secure']) || $urlParsed['protocol'] == 'https')) 
                {
                    $tempCookies[$cookie['name']][strlen($cookie['path'])] = $cookie['value'];
                }
            }
            
            // cookies with longer paths go first
            foreach ($tempCookies as $name => $values) 
            {
                krsort($values);
                foreach ($values as $value) 
                {
                    $this->addCookie($name, $value);
                }
            }
        }
    }
    
    /**
    * Checks if cookie domain matches a request host (internal)
    * 
    * Cookie domain can begin with a dot, it also must contain at least
    * two dots.
    * 
    * @param string Request host
    * @param string Cookie domain
    * @return bool Match success
     * @access private
    */
    protected function _domainMatch($requestHost, $cookieDomain)
    {
        if ('.' != $cookieDomain{0}) 
        {
            return $requestHost == $cookieDomain;
        } 
        elseif (substr_count($cookieDomain, '.') < 2) 
        {
            return false;
        } 
        else 
        {
            return substr('.'. $requestHost, - strlen($cookieDomain)) == $cookieDomain;
        }
    }
    
    /**
     * Tokenize String (internal)
     * 
     * Tokenize string for various internal usage. Omit the second parameter 
     * to tokenize the previous string that was provided in the prior call to 
     * the function.
     *
     * @param string The string to tokenize
     * @param string The seperator to use
     * @return string Tokenized string
     * @access private
     */
    protected function _tokenize($string, $separator = '')
    {
        if(!strcmp($separator, ''))
        {
            $separator = $string;
            $string = $this->nextToken;
        }
        
        for($character = 0; $character < strlen($separator); $character++)
        {
            if(gettype($position = strpos($string, $separator[$character])) == "integer")
            {
                $found = (isset($found) ? min($found, $position) : $position);
            }
        }
        
        if(isset($found))
        {
            $this->nextToken = substr($string, $found + 1);
            return(substr($string, 0, $found));
        }
        else
        {
            $this->nextToken = '';
            return($string);
        }
    }
    
    /**
     * Set error message (internal)
     *
     * @param string Error message
     * @return string Error message
     * @access private
     */
    protected function _setError($error)
    {
        if ($error != '')
        {
            $this->error = $error;
            return $error;
        }
    }
}

?>