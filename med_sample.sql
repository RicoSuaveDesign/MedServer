USE MED_REMINDER;

INSERT INTO USERS(name, allergies) VALUES('John Egbert', 1);
INSERT INTO MEDICINES(user_id, med_name, medFreqPerTime, medFreqInterval, dosage, unit, expiration, dosesLeft,taken) VALUES(1, 'Ghost Repellent', 1, 96800, 3.5, 'mg', '2018-04-13', 20, 0);

INSERT INTO USERS(user_id, allergies) VALUES('Rose Lalonde', 0);
INSERT INTO MEDICINES(user_id, med_name, medFreqPerTime, medFreqInterval, dosage, unit, expiration, dosesLeft, taken) VALUES(2, 'Grimdark Cure', 1, 96800, 2, 'g', '2018-04-13', 12, 0);
