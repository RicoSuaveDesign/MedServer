import mysql.connector as mariadb
import time
import subprocess
import os

#query db every minute for a check time that matches now.
# Then match with medicine, and match with user to get the token

now = time.localtime()

while True:
	if(now[5] == 0):
            #print("Minute surveyed. Minute is: ")
            #print(now[4])
            #p = subprocess.Popen(['php', simplenotification.php, "example med"])
            
            
            strnow = ""
            strdate = ""
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
            
            strdate += str(now[0])
            strdate += "-"
            if(now[1] < 10):
                strdate += "0"
            strdate += str(now[1])
            strdate += "-"
            if(now[2] < 10):
                strdate += "0"
            strdate += str(now[2])
            #print(strnow)
            
	    con = mariadb.connect(user='user',password='yes',database='MED_REMINDER')
	    cursor = con.cursor()
	    try:
	    	cursor.execute("SELECT checkTime, tag_id, time_id FROM CHECKTIMES WHERE checkTime = %s AND checkDate = %s", (strnow, strdate,))
	    except mariadb.Error as error:
	    	print("Error: {}".format(error))
	    data = cursor.fetchall()
	    if(data):
                for row in data:
                    medcurs = con.cursor()
                    try:
                        medcurs.execute("SELECT user_id, taken, reminded, med_name, medFreqInterval FROM MEDICINES WHERE tag_id = %s", (row[1],))
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
                            interval = med_dat[4]/1000
                            nexttim = interval + time.time()
                            stnexttim = time.localtime(nexttim)
                            nextrem = ""
                            nextrem += str(stnexttim[0])
                            nextrem += "-"
                            if(stnexttim[1] < 10):
                                nextrem += "0"
                            nextrem += str(stnexttim[1])
                            nextrem += "-"
                            if(stnexttim[2] < 10):
                                nextrem += "0"
                            nextrem += str(stnexttim[2])
                            #print(nextrem)
                            
                            
                            try:
                                medcurs.execute("UPDATE CHECKTIMES SET checkDate = %s WHERE time_id = %s", (nextrem, row[2],))
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

