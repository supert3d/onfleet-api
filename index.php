<?php
// Move require(s) to some kind of bootstrap file. 
require 'vendor/autoload.php'; // Guzzle
require 'libs/OnFleet.php'; // onfleet.com API stuff. 



$onFleet = new OnFleet('fc3e8b909b6dd29882679cde7df2cc5b'); // Single @arg = API KEY. 
$onFleet->showPayloads = true; // Purely for resting, will verbose out JSON data payload. 
$onFleet->debug = true;

echo '<h2>All Workers</h2>';
echo $onFleet->doAction([
	'verb' => 'GET',
	'endpoint' => 'workers'
]);
echo '<hr />';
echo '<h2>Single Worker (id:kuPdtyC9S8PT5gI*YekE2gRx w/Filter Applied)</h2>';
echo $onFleet->doAction([
	'verb' => 'GET',
	'endpoint' => 'workers',
	'id' => 'kuPdtyC9S8PT5gI*YekE2gRx',
	'data' => array('filter'=>'tasks,onDuty')
]);


$destination = array(
	'address' => array(
		//'name' => 'Buckingham Palace',  
		'number' => '87-135',
		'street' => 'Brompton Road',
		//'apartment' => 'Suite 9',
		'city' => 'London',
		//'state' => 'London',
		'postalCode' => 'SW1X 7XL',
		'country' => 'United Kingdom'
		//'unparsed' => 'Buckingham Palace, The Mall, London, SW1A 1AA'
	),
	'location' => array(
		'longitude' => NULL,
		'latitude ' => NULL
	),
	'notes' => 'Harrods!'
); 





echo '<hr />';
echo '<h2>Add Destination</h2>';
echo $onFleet->doAction([
	'verb' => 'POST',
	'endpoint' => 'destinations',
	'data' => $destination
]);

echo '<hr />';
echo '<h2>Get Destination (NrXyLLRdHCJe6w8jjm34eBzd)</h2>';
echo $onFleet->doAction([
	'verb' => 'GET',
	'endpoint' => 'destinations',
	'id' => 'NrXyLLRdHCJe6w8jjm34eBzd'
]);

/*

*/ 

echo '<hr />';
echo '<h2>Get Recipient</h2>';
echo $onFleet->doAction([
	'verb' => 'GET',
	'id' => 'EyZBMh8oXJFSTo5EzJ~kdqsx',
	'endpoint' => 'recipients'
]);
echo '<hr />';
echo '<h2>Add Recipient</h2>';
$recipient = array(
	'name' => 'Boris Johnson Rules!',
	'phone' => '650-320-1134'
);
echo $onFleet->doAction([
	'verb' => 'POST',
	'endpoint' => 'recipients',
	'data' => $recipient
]);
echo '<h2>Search Recipient (Tony Collings)</h2>';
echo $onFleet->doAction([
	'verb' => 'GET',
	'data' => array('name' => rawurlencode('Tony Collings')),
	'endpoint' => 'recipients'
]);

$task = array(
	'autoAssign' => array('mode' => 'distance'),
	'merchant' => 'ubDq8uGuwU~KqzqHz7kUgZiJ',
	'executor' => 'ubDq8uGuwU~KqzqHz7kUgZiJ',
	'destination' => '91sHDxcjEbkhYVESOQYYH3Wn',
	'notes' => 'Pop into Harrods for a spot of Tea!',
	'recipients' => array('EyZBMh8oXJFSTo5EzJ~kdqsx')
); 


/*
echo '<hr />';
echo '<h2>Add Task</h2>';
echo $onFleet->doAction([
	'verb' => 'POST',
	'endpoint' => 'tasks',
	'data'=>$task
]);
*/

$worker = array(
	'name' => 'A New Worker5 via API',
	'phone' => '2037887784',
	'teams' => array('G610r9TC7AF77bvp0mCVNI5x')
); 

echo '<hr />';
echo '<h2>Add Worker</h2>';
echo $onFleet->doAction([
	'verb' => 'POST',
	'endpoint' => 'workers',
	'data'=>$worker
]);



echo '<hr />';
echo '<h2>Get Teams</h2>';
echo $onFleet->doAction([
	'verb' => 'GET',
	'endpoint' => 'teams'
]);


echo '<hr />';
echo '<h2>Get Org</h2>';
echo $onFleet->doAction([
	'verb' => 'GET',
	'endpoint' => 'organization'
]);