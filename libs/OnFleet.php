<?php

// NOTES: 
// PSR1.0 etc... http://www.php-fig.org/psr/
// http://docs.onfleet.com - API Documentation. 
// http://guzzle.readthedocs.org/en/latest/index.html# - GUZZLE (http request) Documentation
// TEST TOOL: http://requestmaker.com/ 
// API: ('name'=>'FN Site','key'=>'fc3e8b909b6dd29882679cde7df2cc5b')
// NOTE: Have to be VERY careful with workers and recipients. Both use phone number as a unique identifier. This cannot be duplicated! 




class OnFleet {

	const BASE_URL = 'https://onfleet.com/api/v2/';
	
	public $guzzle; // (obj) Guzzle instance.  
	public $showPayloads = false; // (bool) Purely for testing. Echo out JSON payload string for debug. 
	private $apiKey = ''; // (string) API key. 
	private $allowedEndPoints = array(
		'organization','admins','workers','teams','destinations','recipients','tasks','webhooks'
	); 
	
	

	public function __construct($apiKey,$debugMode=false){

		if(!$apiKey) 
			exit('API Key Missing');
		 
		$this->apiKey = $apiKey; 
		$this->guzzle = new GuzzleHttp\Client([
			'base_uri' => self::BASE_URL,
			//'timeout'  => 2.0,
			'headers' => [
				'User-Agent' => 'freshnation/1.0',
				'Content-Type' => 'application/json'
			],			
			'auth' => [$this->apiKey,''],
			'verify' => true,
			'http_errors' => false
			//'debug' => true
		]);

	}
	
	// GC stuff. 
	public function __destruct(){}
	
	
	// Simple method to test API cURL -u against basic HTTP authentication.  
	public function testAuth(){

		$response = $this->guzzle->get('auth/test');
		if($response->getStatusCode() == 200)
			echo $response->getBody(); 

	}
	
	public function doAction($args){
		
		if(!isset($args['verb']) || !isset($args['endpoint'])) 
			return 'Verb and Endpoint are Required';  // Must have verb and endpoint as minimum. 
		if(!in_array($args['endpoint'],$this->allowedEndPoints))
			return 'Not a valid endpoint'; 
		
		
		$endPoint = $args['endpoint']; 
		if(!is_null($args['id']) && !empty($args['id']))
			$endPoint .= '/'.$args['id'];
		
		// Special formatting for specific endpoints. 
		switch($args['endpoint']){
			case 'workers': 
				if(count($args['data'])>0 && $args['verb'] == 'GET'){
					$endPoint .= '?'.http_build_query($args['data']);
					unset($args['data']); 
				}
			break; 	
			case 'recipients':
				if($args['verb'] == 'GET' && (is_null($args['id']) || empty($args['id'])) ){
					$key = array_shift(array_keys($args['data'])); 
					$value = $args['data'][$key];
					$endPoint .= '/'.$key.'/'.$value; // Searching. 
					unset($args['data']); 
				}
			break; 
		}
		

		return $this->performAction($args['verb'],$endPoint,$args['data']);

		
	}
	

	private function parseData($data){
		// Remove NULLS. Probably add more checks? 
		foreach ($data as &$value)
		  if (is_array($value))
		  	$value = $this->parseData($value);
	   
		return array_filter($data);
	}	
	
	
	// cURL REQUEST
	// @arg (string)$httpVerb = POST/GET/PUT/DELETE
	// @arg (string)$apiEndPoint = URL Endpoint. 
	// @arg (array)$data = Data Payload for Request. 

	private function performAction($httpVerb,$apiEndPoint,$data=array()){
		$data = $this->parseData($data);
		if(count($data)<=0){
			unset($data);
		}else{
			$data = json_encode($data);
		}

		if($this->showPayloads){
			$debugString = array(
				$httpVerb,
				self::BASE_URL.$apiEndPoint,
				'HTTP/1.1'
			);
			echo '<pre><strong>Request:</strong>'.implode(" ",$debugString).$data.'</pre>';
		}
			
		switch($httpVerb){ // CRUD
			case 'POST': // Create
				$response = $this->guzzle->post($apiEndPoint,[
					'body' => $data
				]);
			break; 
			case 'GET': // Read
    			$response = $this->guzzle->get($apiEndPoint);
			break; 
			case 'PUT': // Update 
				$response = $this->guzzle->put($apiEndPoint,[
					'body' => $data
				]);
			break;
			case 'DELETE': // Delete 
				$response = $this->guzzle->delete($apiEndPoint);
			break; 
		}
		
		return $response->getBody(); // Maybe check JSON string here before returning. 	
	
	}
	
	
	

	

	
	
	
	
	
	
}


