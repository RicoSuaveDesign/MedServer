USE MED_REMINDER;

INSERT INTO USERS(name, pw, salt, allergies) VALUES('John Egbert', 'fake', 'serversidejunk', 1);
INSERT INTO MEDICINES(user_id, tag_id, med_name, med_desc, medFreqPerTime, medFreqInterval, dosage, unit, expiration, dosesLeft,taken, reminded, newmed, inOrOut) VALUES(1, 1234, 'Ghost Repellent', 'Repels ghosts', 1, 96800, 3.5, 'mg', '2018-04-13', 20, 0, 0, 1, 0);

INSERT INTO USERS(user_id, pw, salt, allergies) VALUES('Rose Lalonde', 'stillfake', 'serversidejunk', 0);
INSERT INTO MEDICINES(user_id, tag_id, med_name, med_desc, medFreqPerTime, medFreqInterval, dosage, unit, expiration, dosesLeft, taken, reminded, newmed, inOrOut) VALUES(2, 612345, 'Grimdark Cure', 'Dying is one hell of a hangover cure', 1, 96800, 2, 'g', '2018-04-13', 12, 0, 0, 0, 0);
