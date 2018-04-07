#Serverside Work
  
This is where medicine info will be stored.

Separate serverside repository for my senior thesis (Medicine Reminder) so all the android dev doesn't waste space on the pi (there's probably a way to have everything in the same repository without wasting space, but I'll look into that later).

Server is a LAMP stack. Raspberry pi is connected through an arduino to an RFID scanner, which is what Sense.py is listening for on serial. There will be a light sensor on the arduino as well, but a fingerprint scanner will be directly connected to the pi.
