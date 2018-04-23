import serial
import mysql.connector as mariadb
import time
import sys
import subprocess

# todo: multithread finger process so can still listen for rfid
# No user is different from failed fingerprint return so that it doesn't just wait for input again.
# User will be able to be like "nah its good" on android side when given push notification of "med stolen"
# WHich is also useful for the use case of you forgot your med at home and your roommate is being a bro and getting it for you


ser = serial.Serial('/dev/ttyACM0', 9600, 8, 'N', 1, timeout=5)
curr_user = -2
timeout = time.time()

while True:
    if int(time.time()) > int(timeout) and curr_user != -2:
        curr_user = -2
        print("switching to no user")
    if ser.inWaiting() > 0:
        #print(ser.readline())
        data = ser.readline()
        if(len(data.split(" ")) == 2):
            typein, val = data.split(" ")
            print(typein)
            print(val)
            if typein == "Light":
                 if(int(val) < 1000):
                    if(curr_user == -2):
                        print("fiiiinger")
                        s2_out = subprocess.check_output([sys.executable, "fingersearch.py", "34"])
                        curr_user = s2_out
                        print(curr_user)
                        timeout = time.time() + 300.0 #assume the user will take the meds out in the next 5 minutes
                 else:
                    curr_user = -2 #cabinet closed, now user is done
            elif typein == "Tag":
                 print("Looking up medicine...")
                 con = mariadb.connect(user='user',password='yes',database='MED_REMINDER')
                 cursor = con.cursor()
                 try:
                      cursor.execute("SELECT tag_id, reminded, user_id FROM MEDICINES WHERE tag_id=%s", (val,))
                 except mariadb.Error as error:
                      print("Error: {}".format(error))
                 data = cursor.fetchone()
                 if(data):
                      if(data[2] != curr_user):
                           try:
                                cursor.execute("UPDATE MEDICINES SET stolen = 1 WHERE tag_id = %s", (val,))
                           except mariadb.Error as error:
                                print("Error: {}".format(error))
                           con.commit()
                           print("Med stolen!")
                           # push notification here
                      if(data[1] >= 1):
                           try:
                                cursor.execute("UPDATE MEDICINES SET reminded = 0 WHERE tag_id = %s", (val,))
                           except mariadb.Error as error:
                                print("Error: {}".format(error))
                           con.commit()
                           print("Medicine taken with reminder.")

                      else:
                           try:
                                cursor.execute("UPDATE MEDICINES SET taken = 1 WHERE tag_id = %s", (val,))
                           except mariadb.Error as error:
                                print("Error: {}".format(error))
                           con.commit()
                           print("Medicine taken without reminder.")
                 else:
                      try:
                           cursor.execute("INSERT into MEDICINES(tag_id, med_name, medFreqPerTime, dosage, user_id, newmed) VALUES(%s, 'New Medicine', 0, 0, 1, 1)", (val,)) 
                      except mariadb.Error as error:
                           print("Error: {}".format(error))
                      con.commit()
                      print("Medicine added.")
                 con.close()



