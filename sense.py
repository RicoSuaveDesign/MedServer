import serial
import mysql.connector as mariadb
import time
import sys
import subprocess
import multiprocessing as mp
import os

# todo: multithread finger process so can still listen for rfid
# No user is different from failed fingerprint return so that it doesn't just wait for input again.
# User will be able to be like "nah its good" on android side when given push notification of "med stolen"
# WHich is also useful for the use case of you forgot your med at home and your roommate is being a bro and getting it for you

#output = mp.Queue()
def fingers():
    s2_out = subprocess.check_output([sys.executable, "fingersearch.py", "34"])
    user = s2_out
    print(user)
    #output.put(user)
    return user


ser = serial.Serial('/dev/ttyACM0', 9600, 8, 'N', 1, timeout=5)
curr_user = -2
timeout = time.time()

if __name__ == '__main__':
    
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
                            curr_user = -1 #while waiting for finger, we don't constantly call fingers()
                            #p = mp.Process(target=fingers)
                            #p.start()
                            #p.join()
                            #curr_user = output.get()
                            curr_user = fingers()
                            print("Curr_user is:")
                            print(curr_user)
                            timeout = time.time() + 300.0 #assume the user will take the meds out in the next 5 minutes
                     else:
                        curr_user = -2 #cabinet closed, now user is done
                elif typein == "Tag":
                     print("Looking up medicine...")
                     con = mariadb.connect(user='user',password='yes',database='MED_REMINDER')
                     cursor = con.cursor()
                     try:
                          cursor.execute("SELECT tag_id, reminded, user_id, dosesLeft, med_name FROM MEDICINES WHERE tag_id=%s", (val,))
                     except mariadb.Error as error:
                          print("Error: {}".format(error))
                     data = cursor.fetchone()
                     if(data):
                          if(data[2] != int(curr_user)):
                               print("Db data is a:")
                               print(type(data[2]))
                               print("Curr_user is a:")
                               print(type(curr_user))
                               try:
                                    cursor.execute("UPDATE MEDICINES SET stolen = 1 WHERE tag_id = %s", (val,))
                               except mariadb.Error as error:
                                    print("Error: {}".format(error))
                               con.commit()
                               print("Med stolen!")
                               stolenote = data[4]
                               stolenote += " was taken without the correct fingerprint."
                               os.system("php /var/www/html/med_reminder/othernotifications.php %s"%(stolenote))
                               
                          if(data[1] >= 1):
                               deincrem = data[3] - 1
                               if(deincrem == -1):
                                    deincrem = 0
                               try:
                                    cursor.execute("UPDATE MEDICINES SET reminded = 0, dosesLeft = %s WHERE tag_id = %s", (deincrem, val,))
                               except mariadb.Error as error:
                                    print("Error: {}".format(error))
                               con.commit()
                               print("Medicine taken with reminder.")
                               if(deincrem < 6):
                                   note = data[4]
                                   note += " needs to be refilled soon."
                                   os.system("php /var/www/html/med_reminder/othernotifications.php %s" %(note))

                          else:
                               deincrem = data[3] - 1
                               if(deincrem == -1):
                                    deincrem = 0
                               try:
                                    cursor.execute("UPDATE MEDICINES SET taken = 1, dosesLeft = %s WHERE tag_id = %s", (deincrem, val,))
                               except mariadb.Error as error:
                                    print("Error: {}".format(error))
                               con.commit()
                               print("Medicine taken without reminder.")
                               if(deincrem < 6):
                                   note = data[4]
                                   note += " needs to be refilled soon."
                                   os.system("php /var/www/html/med_reminder/othernotifications.php %s"%(note))
                     else:
                          try:
                               cursor.execute("INSERT into MEDICINES(tag_id, med_name, medFreqPerTime, dosage, user_id, newmed) VALUES(%s, 'New Medicine', 0, 0, 1, 1)", (val,)) 
                          except mariadb.Error as error:
                               print("Error: {}".format(error))
                          con.commit()
                          print("Medicine added.")
                     con.close()



