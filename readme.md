# Serverside Work
  
This is where medicine info will be stored.

Separate serverside repository for my senior thesis (Medicine Reminder) so all the android dev doesn't waste space on the pi (there's probably a way to have everything in the same repository without wasting space, but I'll look into that later).

Server is a LAMP stack. Raspberry pi is connected through an arduino to an RFID scanner, which is what Sense.py is listening for on serial. There is also a photoresistor on the arduino to detect light levels. This assumes device will be put into a dark cabinet. There is a fingerprint scanner connected directly to the pi via USB.

All python code relates to listening to changes. Sense.py is the main script listening to sensor data.

Times.py listens for the time. It queries the database to check if there is any medicine that should be taken. It then calls SimpleNotification.php to send a push notification to the android app.

fingersearch.py and addfprint.py interface with the fingerprint scanner. Search is called from sense.py. Addfprint will be called from adduser. 

PHP code is all endpoints for the android app to interact with the database.
