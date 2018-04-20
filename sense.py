import serial
import mysql.connector as mariadb

ser = serial.Serial('/dev/ttyACM0', 9600, 8, 'N', 1, timeout=5)

while True:
    if ser.inWaiting() > 0:
        #print(ser.readline())
	type, val = ser.readline().split(" ")
	print(type)
	print(val)
	if type == "Light":
             if(val < 1000):
                  print("run finger time yes")
                  
	elif type == "Tag":
	     print("Looking up medicine...")
	     con = mariadb.connect(user='user',password='yes',database='MED_REMINDER')
	     cursor = con.cursor()
	     try:
	          cursor.execute("SELECT tag_id, reminded FROM MEDICINES WHERE tag_id=%s", (val,))
	     except mariadb.Error as error:
	          print("Error: {}".format(error))
	     data = cursor.fetchone()
	     if(data):
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



