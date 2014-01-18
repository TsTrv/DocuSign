<?php 

class DocuSign {

private $integratorKey;
private $username;
private $password;


public function __construct() {
	$this->integratorKey= 'Integrator Key';
	$this->username='Username';
	$this->password='Password';
} 

/**
 * Set IntegratorKey
 * @param [str] $key [Integrtor Key]
 */
public function setIntegratorKey($key){
    $this->integratorKey = $key;
}

/**
 * Set Username
 * @param [str] $username [Username or Email]
 */
public function setUsername($username){
    $this->username = $username;
}

/**
 * Set Password
 * @param [str] $password [Password]
 */
public function setPassword($password){
    $this->password = $password;
}

/**
 * Function used for connection with the DocuSign Service
 * @return [arr] [response]
 */
private function connect() {
	// construct the authentication header:
	$header=array('Username'=>$this->username,'Password'=>$this->password, 'IntegratorKey'=>$this->integratorKey);
	$header=json_encode($header);

	$url = "https://demo.docusign.net/restapi/v2/login_information";
	$curl = curl_init($url);
	
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));

	$json_response = curl_exec($curl);
	$response=json_decode($json_response);

	return $response;
}

/**
 * Function used for DocuSign User validation
 * @param  [int] $integratorKey [Integrator Key - get it from DocuSing application]
 * @param  [str] $username      [Username]
 * @param  [str] $password      [Password]
 * @return [bool]               [TRUE/FALSE]
 */
public function checkUser(){

	$response=$this->connect();
	
	if(!isset($response->loginAccounts)){
		$ret=false;
	} else {
		$ret=true;
	}
	return $ret;
}


/**
 * Docusign Sending Signature Request 
 * @param  [string] $email         [User Email]
 * @param  [string] $recipientName [User Name]
 * @return [type]                [description]
 */
public function docusign($email, $recipientName=false){

	$response=$this->connect();

	$accountId = $response->loginAccounts[0]->accountId;
	$baseUrl = $response->loginAccounts[0]->baseUrl;
	    
	//Create an envelope with one recipient, one document, 3 signature requests and send
	$data = array (
	        "emailSubject" => 'Signature Request',
	        "documents" => array( array( "documentId" => "1", "name" => 'DocumentName')),
	        "recipients" => array( "signers" => array(
		        array(  "email" => 'jondoe@gmail.com',
		                "name" => 'Jon Doe',
		                "recipientId" => "1",
		                "tabs" => array(
		                "signHereTabs" => array(
		                    array(  "xPosition" => "250",
		                        	"yPosition" => "650",
		                        	"documentId" => "1",
		                        	"pageNumber" => "8" ),
		                           
		                    array(  "xPosition" => "250",
		                           	"yPosition" => "150",
		                            "documentId" => "1",
		                            "pageNumber" => "11" ),
		                            
		                    array(  "xPosition" => "250",
		                            "yPosition" => "250",
		                            "documentId" => "1",
		                            "pageNumber" => "13" )
		                    ),
		                    
		                "textTabs" => array(
		                    array(  "name"=> "Text", 
            			     	    "value"=> "Company Name", 
		                            "tabLabel"=>"Company Name",
		                            "xPosition" => "75",
		                            "yPosition" => "75",
		                            "documentId" => "1",
		                            "pageNumber" => "9" ),
		                            
		                            // First signature details
		                            array( "name"=> "Text", 
            							   "value"=> date('m/d/Y', time()), 
		                            	   "tabLabel"=>"Date",
		                            	   "xPosition" => "450",
		                                   "yPosition" => "680",
		                                   "documentId" => "1",
		                                   "pageNumber" => "8" ),
		                            array( "name"=> "Text", 
            							   "value"=> "Full Name",
            							   "tabLabel"=>"Full Name",
		                            	   "xPosition" => "75",
		                                   "yPosition" => "680",
		                                   "documentId" => "1",
		                                   "pageNumber" => "8" ),
		                            
		                            // Second signature details
		                            array( "name"=> "Text", 
            							   "value"=> "Company Name", 
		                            	   "tabLabel"=>"Company Name",
		                            	   "xPosition" => "75",
		                                   "yPosition" => "250",
		                                   "documentId" => "1",
		                                   "pageNumber" => "11" ),
		                            array( "name"=> "Text", 
            							   "value"=> date('m/d/Y', time()), 
		                            	   "tabLabel"=>"Date",
		                            	   "xPosition" => "450",
		                                   "yPosition" => "190",
		                                   "documentId" => "1",
		                                   "pageNumber" => "11" ),
		                            array( "name"=> "Text", 
            							   "value"=> "Full Name",
            							   "tabLabel"=>"Full Name",
		                            	   "xPosition" => "75",
		                                   "yPosition" => "190",
		                                   "documentId" => "1",
		                                   "pageNumber" => "11" ),

		                            // Third signature details
		                            array( "name"=> "Text", 
            							   "value"=> "Company Name", 
		                            	   "tabLabel"=>"Company Name",
		                            	   "xPosition" => "75",
		                                   "yPosition" => "370",
		                                   "documentId" => "1",
		                                   "pageNumber" => "13" ),
		                            array( "name"=> "Text", 
            							   "value"=> date('m/d/Y', time()), 
		                            	   "tabLabel"=>"Date",
		                            	   "xPosition" => "450",
		                                   "yPosition" => "290",
		                                   "documentId" => "1",
		                                   "pageNumber" => "13" ),
		                            array( "name"=> "Text", 
            							   "value"=> "Full Name",
            							   "tabLabel"=>"Full Name",
		                            	   "xPosition" => "75",
		                                   "yPosition" => "290",
		                                   "documentId" => "1",
		                                   "pageNumber" => "13" )
		                           )
		                    )
		                ))
		            ),
			    "status" => "sent"
			);
		
		// Locate the attachment file on server
		$data_string = json_encode($data);  
		$file_contents = file_get_contents('http://www.qualitylogic.com/tuneup/uploads/docfiles/Effective-PDF-Testing-Strategy.pdf');
	
		$requestBody = "\r\n"
			."\r\n"
			."--myboundary\r\n"
			."Content-Type: application/json\r\n"
			."Content-Disposition: form-data\r\n"
			."\r\n"
			."$data_string\r\n"
			."--myboundary\r\n"
			."Content-Type:application/pdf\r\n"
			."Content-Disposition: file; filename=\”document.pdf\"; documentid=1 \r\n"
			."\r\n"
			."$file_contents\r\n"
			."--myboundary--\r\n"
			."\r\n";

		// *** append "/envelopes" to baseUrl and as signature request endpoint
		$curl = curl_init($baseUrl . "/envelopes" );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);                                                                  
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: multipart/form-data;boundary=myboundary',
			'Content-Length: ' . strlen($requestBody),
			"X-DocuSign-Authentication: $header" )                                                                       
		);
			
		$json_response = curl_exec($curl);
		$response = json_decode($json_response, true);

		if($response["envelopeId"]){
			$ret=true;
		} else {
			$ret=false;
		}
		return $ret; 
	}

	/**
	 * Function used for checking Signature status
	 * @param  [str] $checkemail  [Receiver Email]
	 * @param  [str] $envelope_id [Envelope ID]
	 * @return [bool]             [TRUE/FALSE]
	 */
	public function checkSignatureStatus($checkemail, $envelope_id){
    
        // copy the envelopeId from an existing envelope in your account that you want to query:
        $envelopeId = $envelope_id;

        $response=$this->connect();
		$accountId = $response->loginAccounts[0]->accountId;
		$baseUrl = $response->loginAccounts[0]->baseUrl;
        
        // construct the authentication header:
        $header = "<DocuSignCredentials><Username>" . $this->username . "</Username><Password>" . $this->password . "</Password><IntegratorKey>" . $this->integratorKey . "</IntegratorKey></DocuSignCredentials>";
        
		//Get envelope information
        $curl = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/recipients" );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
            "X-DocuSign-Authentication: $header" )                                                                       
        );
        
        $json_response = curl_exec($curl);
        var_dump($json_response); exit;
        $response = json_decode($json_response, true);
            foreach($response['signers'] as $signers){
                if($checkemail == $signers['email']){
                    $ret=true;
                } else {
                    $ret=false;
                }
            }

            return $ret;
        }

}
?>