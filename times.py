import mysql.connector as mariadb
import time

# step 1: define times array and the now
# step 2: every hour on the hour grab all the reminder times for the hour
# step 3: every minute on the minute, check times array for a matchy time -- what do when new med added? maybe every 15 minutes. 5 for the sake of the demo? hmm
# step 3.5: if no times this hour, sleep for an hour
# step 4: if time == a check time, check db for medicine and if its taken. if yes, just add interval to the check time. if no, remind! and add interval to check time.


times = []
now = time.localtime()

while True:
	if(now[4] == 0 && now[5] == 0):
		con = mariadb.connect(user='user',password='yes',database='MED_REMINDER')
		cursor = con.cursor()
		try:
			cursor.execute("SELECT checkTime FROM CHECKTIMES WHERE checkTime LIKE %s", (now[3],))
		except mariadb.Error as error:
			print("Error: {}".format(error))
		data = cursor.fetchall()
