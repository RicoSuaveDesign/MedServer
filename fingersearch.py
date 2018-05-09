import time
from pyfingerprint.pyfingerprint import PyFingerprint
import mysql.connector as mariadb

try:
    f = PyFingerprint('/dev/ttyUSB0', 57600, 0xFFFFFFFF, 0x00000000)

    if ( f.verifyPassword() == False ):
        raise ValueError('The given fingerprint sensor password is wrong!')

except Exception as e:
    print('The fingerprint sensor could not be initialized!')
    print('Exception message: ' + str(e))
    exit(1)
    
try:
    #print('Waiting for finger...')
    timeout = time.time() + 180.0 # user has 3 minutes to scan their finger
    

    ## Wait that finger is read
    while ( f.readImage() == False ):
        if(time.time() > timeout):
            #print("No finger given")
            retVal = -1
            print(retVal)
            exit(0)
    
    retVal = -1

    ## Converts read image to characteristics and stores it in charbuffer 1
    f.convertImage(0x01)

    ## Checks if finger is already enrolled
    result = f.searchTemplate()
    positionNumber = result[0]

    if ( positionNumber >= 0 ):
        con = mariadb.connect(user='user',password='yes',database='MED_REMINDER')
	cursor = con.cursor()
	try:
	    cursor.execute("SELECT user_id FROM USERS WHERE fprint=%s", (positionNumber,))
	except mariadb.Error as error:
	    print("Error: {}".format(error))
	data = cursor.fetchone()
	if(data):
            retVal = data[0]
            #print("finger found, and in db too!")
            con.close()
        else:
            retVal = -1
            print("Finger found, but not in database")
            con.close()
    else:
        print("Finger not found")
                
                    
    print(retVal)




except Exception as e:
    print('Operation failed!')
    print('Exception message: ' + str(e))
    exit(1)