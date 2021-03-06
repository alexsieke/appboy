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
	deals.csv	people.csv	schedule.php
	
	
You are now ready to run the push notification script

	php -f schedule.php > log.txt

In the log file you will find the result returned from the rest api from your push requests
	
**important** - when you run the script it will send out a lot of push notifications.  Be careful and make sure you fully understand the code before executing the above command

After completion of the code you will generate a file that contains the name of the campaign and the date in which the campaign was run.

	hotdeals-2014-07-03.txt
	
its contents should look something like this.

	174	1120991	2014-07-03 23:22:19
	580	1120991	2014-07-03 23:22:19
	12122	1120991	2014-07-03 23:22:19
	20590	1120991	2014-07-03 23:22:19
	22330	1120991	2014-07-03 23:22:19

###You are now a push master