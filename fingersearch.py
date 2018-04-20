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
    print('Waiting for finger...')
    now = time.time()
    interv = 300.0

    ## Wait that finger is read
    while ( f.readImage() == False ):
        pass
        if(now - time.time() > 300.0):
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
                
                    
	    
	con.close()

    print('Remove finger...')
    time.sleep(2)

    print('Waiting for same finger again...')

    ## Wait that finger is read again
    while ( f.readImage() == False ):
        pass

    ## Converts read image to characteristics and stores it in charbuffer 2
    f.convertImage(0x02)

    ## Compares the charbuffers
    if ( f.compareCharacteristics() == 0 ):
        raise Exception('Fingers do not match')

    ## Creates a template
    f.createTemplate()

    ## Saves template at new position number
    positionNumber = f.storeTemplate()
    print('Finger enrolled successfully!')
    print('New template position #' + str(positionNumber))

except Exception as e:
    print('Operation failed!')
    print('Exception message: ' + str(e))
    exit(1)