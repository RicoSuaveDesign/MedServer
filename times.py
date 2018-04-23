import mysql.connector as mariadb
import time
import subprocess
import os

# step 1: define times array and the now
# step 2: every hour on the hour grab all the reminder times for the hour
# step 3: every minute on the minute, check times array for a matchy time -- what do when new med added? maybe every 15 minutes. 5 for the sake of the demo? hmm
# step 3.5: if no times this hour, sleep for an hour
# step 4: if time == a check time, check db for medicine and if its taken. if yes, just add interval to the check time. if no, remind! and add interval to check time.

#However, for the sake of a demo, we can't implement it in the real way
#Instead, query db every minute for a check time that matches now.
# Then match with medicine, and match with user to get the token

now = time.localtime()

while True:
	if(now[5] == 0):
            #print("Minute surveyed. Minute is: ")
            #print(now[4])
            #p = subprocess.Popen(['php', simplenotification.php, "example med"])
            
            
            strnow = ""
            if(now[3] < 10):
                strnow = "0"
                strnow += str(now[3])
            else:
                strnow = str(now[3])
            strnow += ":"
            if(now[4] < 10):
                strnow += "0"
                strnow += str(now[4])
            else:
                strnow += str(now[4])
            strnow += ":00"
            #print(strnow)
            
	    con = mariadb.connect(user='user',password='yes',database='MED_REMINDER')
	    cursor = con.cursor()
	    try:
	    	cursor.execute("SELECT checkTime, tag_id FROM CHECKTIMES WHERE checkTime = %s", (strnow,))
	    except mariadb.Error as error:
	    	print("Error: {}".format(error))
	    data = cursor.fetchall()
	    if(data):
                for row in data:
                    medcurs = con.cursor()
                    try:
                        medcurs.execute("SELECT user_id, taken, reminded, med_name FROM MEDICINES WHERE tag_id = %s", (row[1],))
                        med_dat = medcurs.fetchone()
                        if(med_dat):
                            print(med_dat[0])
                            print(row[1])
                            if(med_dat[1] > 0):
                                try:
                                    medcurs.execute("UPDATE MEDICINES SET taken = 0 WHERE tag_id = %s", (row[1],))
                                    print(row[1])
                                    con.commit()
                                except mariadb.Error as error:
                                    print("Error: {}".format(error))
                            else:
                                os.system("php /var/www/html/med_reminder/simplenotification.php %s"%(med_dat[3]))
              
                                try:
                                    medcurs.execute("UPDATE MEDICINES SET reminded = 1 WHERE tag_id = %s", (row[1],))
                                    con.commit()
                                except mariadb.Error as error:
                                    print("Error: {}".format(error))
                        else:
                            print("A medicine was deleted without deleting the time.")
                    except mariadb.Error as error:
                        print("Error: {}".format(error))
            
                con.close() #lets not leave the connection hanging
            else:
                print("there's no time here bucko")
	time.sleep(1) #we only need to survey the time once a second
	now = time.localtime() #reset the time

