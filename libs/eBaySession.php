<?php
/**
 * @author cl
 * @copyright 2013
 */
set_time_limit(50000);
class eBaySession
{
	private $requestToken;
	private $devID;
	private $appID;
	private $certID;
	private $serverUrl;
	private $compatLevel;
	private $siteID;
	private $callName;
	private $header;
	
	/**	__construct
		Constructor to make a new instance of eBaySession with the details needed to make a call
		Input:	$userRequestToken - the authentication token fir the user making the call
				$developerID - Developer key obtained when registered at http://developer.ebay.com
				$applicationID - Application key obtained when registered at http://developer.ebay.com
				$certificateID - Certificate key obtained when registered at http://developer.ebay.com
				$useTestServer - Boolean, if true then Sandbox server is used, otherwise production server is used
				$compatabilityLevel - API version this is compatable with
				$siteToUseID - the Id of the eBay site to associate the call iwht (0 = US, 2 = Canada, 3 = UK, ...)
				$callName  - The name of the call being made (e.g. 'GeteBayOfficialTime')
		Output:	Response string returned by the server
	*/
	public function __construct($token, $devID, $appID, $certID, $serverUrl,
								$compatLevel, $siteID, $callName)
	{
		$this->requestToken = $token;
		$this->devID = $devID;
		$this->appID = $appID;
		$this->certID = $certID;
        $this->serverUrl = $serverUrl;	
		$this->compatLevel = $compatLevel;
		$this->siteID = $siteID;
// 		echo $siteToUseID;
		$this->callName = $callName;
	}
	
	
	/**	sendHttpRequest
		Sends a HTTP request to the server for this session
		Input:	$requestBody
		Output:	The HTTP Response as a String
	*/
	public function sendHttpRequest($requestBody)
	{
		//build eBay headers using variables passed via constructor
		$headers = $this->buildEbayHeaders();
		
		//initialise a CURL session
		$connection = curl_init();
		//set the server we are using (could be Sandbox or Production server)
		curl_setopt($connection, CURLOPT_URL, $this->serverUrl);
		$xml = preg_replace('/>\s+</', '><', $requestBody);
		Common_ApiProcess::log("请求地址:".$this->serverUrl."\n".$xml);
		//stop CURL from verifying the peer's certificate
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		
		//set the headers using the array of headers
		curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
		
		//set method as POST
		curl_setopt($connection, CURLOPT_POST, 1);
		
		//set the XML body of the request
		curl_setopt($connection, CURLOPT_POSTFIELDS, $requestBody);
		
		//set it to return the transfer as a string from curl_exec
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($connection, CURLOPT_TIMEOUT,60*3);
		Common_ApiProcess::log("请求开始");
		//Send the Request
		$response = curl_exec($connection);

		$errno = curl_errno($connection);
		$error = curl_error($connection);
		//close the connection
		curl_close($connection);
		Common_ApiProcess::log("请求结束");
		if($errno){
			throw new Exception($error, $errno);
		}

		if(empty($response)){
			throw new Exception('Http Error',50000);
		}
		//return the response
		return $response;
	}
	
	
	
	/**	buildEbayHeaders
		Generates an array of string to be used as the headers for the HTTP request to eBay
		Output:	String Array of Headers applicable for this call
	*/
	private function buildEbayHeaders()
	{
		$headers = array (
			//Regulates versioning of the XML interface for the API
			'X-EBAY-API-COMPATIBILITY-LEVEL: ' . $this->compatLevel,			
			//set the keys
			'X-EBAY-API-DEV-NAME: ' . $this->devID,
			'X-EBAY-API-APP-NAME: ' . $this->appID,
			'X-EBAY-API-CERT-NAME: ' . $this->certID,
			
			//the name of the call we are requesting
			'X-EBAY-API-CALL-NAME: ' . $this->callName,			
			
			//SiteID must also be set in the Request's XML
			//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
			//SiteID Indicates the eBay site to associate the call with
			'X-EBAY-API-SITEID: ' . $this->siteID,
		);
		$this->header = $headers;
		return $headers;
	}
	

	/**	buildEbayHeaders
	 Generates an array of string to be used as the headers for the HTTP request to eBay
	 Output:	String Array of Headers applicable for this call
	 */
	public function getEbayHeaders()
	{		
		return $this->header;
	}

}
?>