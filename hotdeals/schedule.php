<?php

date_default_timezone_set("GMT");

/********************************
SET UP CAMPAIGN NAME VARIABLES
*********************************/

$campaign_name = "hotdeals";

$ts = date("Y-m-d");
$handle = fopen("$campaign_name"."-".$ts.".txt","a+");

/*Create Deals Array*/
/*
$deals[deal_id] = array(6)

0 = deal_id
1 = DMA
2 = Merchant Name
3 = Image URL
4 = Short Title

*/

/*read the file into $file object and parse the rows into deals array*/
$file = file("deals.csv");
foreach($file as $line){
	$arr = explode("\",\"",substr(trim($line), 1, -1));
	$deals[$arr[0]] = $arr;
}

/*set global variables*/
$total_pushes = 0;
foreach($deals as $deal_id => $deal_arr){
	$iphone_count[$deal_id] = 0;
	$android_count[$deal_id] = 0;
}


/*Read in people / deals
0 = person_id
1 = app type
2 = deal_id
*/


ini_set('memory_limit', '1024M');
$file = file("people.csv");
for($i=1; $i < count($file); $i++){
	$arr = explode("\",\"",substr(trim($file[$i]), 1, -1));
	$deal_id = $arr[2];
	$person_id = $arr[0];
	$app = $arr[1];
	
	if($app == "iPhone"){
		$iphone_people[$deal_id][] = $person_id;
		$iphone_count[$deal_id] += 1;

		//if there are 50 people then execute push notif
		if($iphone_count[$deal_id] == 50){
			send_iphone($deal_id,$iphone_people[$deal_id]);
			//reset the counter
			$iphone_count[$deal_id] = 0;
			$iphone_people[$deal_id] = array();
		}
	}

	if($app == "Android"){
		$android_people[$deal_id][] = $person_id;
		$android_count[$deal_id] += 1;
		//if there are 50 people then execute push notif
		if($android_count[$deal_id] == 50){
			send_android($deal_id,$android_people[$deal_id]);
			//reset the counter
			$android_count[$deal_id] = 0;
			$android_people[$deal_id] = array();
		}		
	}
}

/*Send the leftover push notifs*/
foreach($iphone_people as $deal_id => $people_array){
	send_iphone($deal_id, $people_array);
}

foreach($android_people as $deal_id => $people_array){
	send_android($deal_id, $people_array);
}

function send_iphone($deal_id, $people_array){
	//sends an iphone push notif for $deal_id to people in $people_array
	
	global $total_pushes, $deals, $handle;

	foreach($people_array as $person){
		fwrite($handle,"$person\t$deal_id\t".date("Y-m-d G:i:s")."\n");	
	}

	$total_pushes += count($people_array);

	$data = array(
		"company_secret" => "x5hhwgGylFYruy9aR_C-lQ", 
		"app_group_id" => "eb3c7a73-3628-4807-80bb-7e833dae05da", 
		"external_user_ids" => $people_array, 
		"messages" => array(
			"apple_push" => array(
				"alert" => $deals[$deal_id][2] /*[2] = merchant name, change to [4] for short_title*/,
				"extra" => array(
					"url" => "ls://daily/$deal_id"
					)
				)
			)
		);

	$data_string = json_encode($data, JSON_UNESCAPED_SLASHES);

	$ch = curl_init('https://api.appboy.com/messages/send');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json',                                                                                
	    'Content-Length: ' . strlen($data_string))                                                                       
	);

	$result = curl_exec($ch);

}

function send_android($deal_id, $people_array){
	//sends an android push notif for $deal_id to people in $people_array
	
	global $total_pushes, $deals, $handle;

	foreach($people_array as $person){
		fwrite($handle,"$person\t$deal_id\t".date("Y-m-d G:i:s")."\n");	
	}

	$total_pushes += count($people_array);

	$data = array(
		"company_secret" => "x5hhwgGylFYruy9aR_C-lQ", 
		"app_group_id" => "eb3c7a73-3628-4807-80bb-7e833dae05da", 
		"external_user_ids" => $people_array, 
		"messages" => array(
			"android_push" => array(
				"alert" => $deals[$deal_id][2] /*[2] = merchant name, change to [4] for short_title*/,
				"extra" => array(
					"url" => "ls://daily/$deal_id"
					)
				)
			)
		);

	$data_string = json_encode($data, JSON_UNESCAPED_SLASHES);

	$ch = curl_init('https://api.appboy.com/messages/send');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json',                                                                                
	    'Content-Length: ' . strlen($data_string))                                                                       
	);

	$result = curl_exec($ch);

}



?>