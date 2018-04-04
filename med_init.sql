CREATE DATABASE IF NOT EXISTS MED_REMINDER;
USE MED_REMINDER;

CREATE TABLE IF NOT EXISTS USERS
(user_id int unsigned not null auto_increment, name varchar(100) not null, pw varchar(200) not null, salt varchar(200) not null, fprint int, allergies tinyint not null, PRIMARY KEY (user_id));

CREATE TABLE IF NOT EXISTS MEDICINES
(tag_id int unsigned not null, med_name varchar(100) not null, med_desc varchar(200), medFreqPerTime int not null, medFreqInterval bigint unsigned, dosage double not null, unit varchar(30), expiration date, dosesLeft int, taken tinyint, reminded tinyint, newmed tinyint, stolen tinyint, inOrOut tinyint, lastout time, user_id int unsigned not null, PRIMARY KEY(tag_id), FOREIGN KEY (user_id) REFERENCES USERS(user_id));

CREATE TABLE IF NOT EXISTS CHECKTIMES
(time_id int unsigned not null auto_increment, checkTime time not null, checkDate date, tag_id int unsigned not null, PRIMARY KEY (time_id), FOREIGN KEY (tag_id) REFERENCES MEDICINES(tag_id));
