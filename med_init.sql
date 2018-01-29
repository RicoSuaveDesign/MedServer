CREATE DATABASE IF NOT EXISTS med_reminder;
USE med_reminder;

CREATE TABLE IF NOT EXISTS Users
(user_id int unsigned not null auto_increment, name varchar(100) not null, fprint int, allergies tinyint not null, chronic tinyint not null, PRIMARY KEY (user_id));

CREATE TABLE IF NOT EXISTS Medicines
(tag_id int unsigned not null auto_increment, med_name varchar(100) not null, medFreqPerTime int not null, medFreqInterval long, dosage double not null, unit varchar(30), expiration date, dosesLeft int, taken tinyint, user_id int unsigned not null, PRIMARY KEY(tag_id), FOREIGN KEY (user_id) REFERENCES Users(user_id));

CREATE TABLE IF NOT EXISTS CheckTimes
(time_id int unsigned not null auto_increment, checkTime time not null, tag_id int unsigned not null, PRIMARY KEY (time_id), FOREIGN KEY (tag_id) REFERENCES Medicines(tag_id));

CREATE TABLE IF NOT EXISTS Chronics
(c_id int unsigned not null auto_increment, name varchar(100) not null, user_id int unsigned not null, PRIMARY KEY(c_id), FOREIGN KEY (user_id) REFERENCES Users(user_id));
