<?php

/*Create Deals Array*/
/*
$deals[deal_id] = array(6)

0 = deal_id
1 = DMA
2 = Merchant Name
3 = Image URL
4 = Short Title

*/

$file = file("deals.csv");
foreach($file as $line){
	$arr = explode("\",\"",substr(trim($line), 1, -1));

	$deals[$arr[0]] = $arr;
}

/*set global variables*/
$total_pushes = 0;

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
	
	global $total_pushes, $deals;

	$total_pushes += count($people_array);

	echo "iPhone Push Sent\t$total_pushes\n";
}

function send_android($deal_id, $people_array){
	//sends an iphone push notif for $deal_id to people in $people_array
	
	global $total_pushes, $deals;

	$total_pushes += count($people_array);

	echo "Android Push Sent\t$total_pushes\n";
}


/*Android Push Notifs*/
/*curl -X POST -H "Content-Type: application/json" -d "{\"company_secret\":\"x5hhwgGylFYruy9aR_C-lQ\",\"app_group_id\":\"eb3c7a73-3628-4807-80bb-7e833dae05da\",\"external_user_ids\":[\"8151533\"],\"messages\":{\"android_push\":{\"alert\":\"50% of 18 holes of golf\",\"extra\":{\"deal_id\":\"1098135\",\"title\":\"Raspberry Falls\"}}}}" https://api.appboy.com/messages/send*/

/*curl -X POST -H "Content-Type: application/json" -d "{\"company_secret\":\"x5hhwgGylFYruy9aR_C-lQ\",\"app_group_id\":\"eb3c7a73-3628-4807-80bb-7e833dae05da\",\"external_user_ids\":[\"8151533\",\"174534995\"],\"messages\":{\"android_push\":{\"alert\":\"Vapiano\",\"extra\":{\"url\":\"ls://daily/1145213\"}}}}" https://api.appboy.com/messages/send*/

// $data = array(
// 	"company_secret" => "x5hhwgGylFYruy9aR_C-lQ", 
// 	"app_group_id" => "eb3c7a73-3628-4807-80bb-7e833dae05da", 
// 	"external_user_ids" => array("8151533"), 
// 	"messages" => array(
// 		"android_push" => array(
// 			"alert" => "50% of Hot Brunch",
// 			"TITLE_KEY" => "How you like my main",
// 			"extra" => array(
// 				"url" => "ls://daily/1186902",
// 				"TITLE_KEY" => "How you like me now"
// 				)
// 			)
// 		)
// 	);

// $data_string_old = "{\"company_secret\":\"x5hhwgGylFYruy9aR_C-lQ\",\"app_group_id\":\"eb3c7a73-3628-4807-80bb-7e833dae05da\",\"external_user_ids\":[\"8151533\"],\"messages\":{\"android_push\":{\"alert\":\"50% of Hot Brunch\",\"extra\":{\"url\":\"ls://daily/1186902\"}}}}";
// $data_string = json_encode($data, JSON_UNESCAPED_SLASHES);

// echo "$data_string_old\n\n$data_string\n\n";

// $ch = curl_init('https://api.appboy.com/messages/send');
// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
// curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
//     'Content-Type: application/json',                                                                                
//     'Content-Length: ' . strlen($data_string))                                                                       
// );

// $result = curl_exec($ch);

// echo "$result\n";

?>