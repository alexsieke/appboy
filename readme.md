AppBoy Push Notification Script
-------------------------------

The following script interacts with LivingSocial's watson data store and AppBoy's rest API to send push notifications to users with the mobile application (iOS & Android)

###Data Overview

An overview of the tables in Watson.  All are tagged **[AppBoy](https://watson.livingsocial.com/tags/appboy)**

	watson_mobile_people
	this table contains an exhaustive list of all people that have downloaded the livingsocial Android or iPhone app.  In addition it includes location information and engagement and activity bucket information
	
	appboy_hotdeals_deals
	this is an example of a table for a campaign that identifies deals in first 24 hours that are performing in the 90th percentile and sends them to people in the respective locations
	
	appboy_hotdeals_people
	this table supports the hotdeals campaign and pulls the actual person_ids that map to which deal each person should get
	
Each campaign should get 2 tables - 1 with deal information and 1 with a person-deal mapping.

###Installation of Script

Ensure that PHP is intalled- this comes standard in the mac os x environment.  The following command shoud yield information about the php version you have installed

	php -v
	
Clone this github repo.  Using [Jumbo](https://jumob.livingsocial.com) download the campaign files.  For this example we will be using [Hotdeals_deals](https://watson.livingsocial.com/summary_tables/29122) and [Hotdeals_people](https://watson.livingsocial.com/summary_tables/29124) the push notif script depends on your campaign files being in the same format.

Rename the downloaded jumbo files to

	watson_appboy_hotdeals_people -> people.csv
	watson_appboy_hotdeals_deals -> deals.csv
	
Your directory should now look like this:

	ls
	deals.csv	people.csv	push_log.txt	schedule.php
	
