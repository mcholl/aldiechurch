SET foreign_key_checks = 0;

DROP TABLE IF EXISTS cel_dates;
CREATE TABLE cel_dates (
	date_id 			int 	AUTO_INCREMENT NOT NULL,
	service_date	date,
	service_offer	varchar(5),
	description	varchar(32),
	PRIMARY KEY(date_id)
) ENGINE=INNODB;
-- service_offer allows us to have, say two services on a given sunday
ALTER TABLE cel_dates ADD UNIQUE INDEX(service_date, service_offer);

DROP TABLE IF EXISTS cel_roles;
CREATE TABLE cel_roles (
	role_id 			int		AUTO_INCREMENT NOT NULL,
	description	varchar(32),
	default_order		int,
	default_pid	int,
	PRIMARY KEY(role_id),
	FOREIGN KEY(default_pid) REFERENCES cel_participants(p_id) 	
) ENGINE=INNODB;

DROP TABLE IF EXISTS cel_particpants;
CREATE TABLE cel_particpants (
	p_id		int AUTO_INCREMENT NOT NULL,
	name	varchar(255),
	email		varchar(255),
	PRIMARY KEY(p_id)
) ENGINE=INNODB;

DROP TABLE IF EXISTS cel_participant_roles;
CREATE TABLE cel_participant_roles (
	p_id		int,
	role_id	int,
	FOREIGN KEY(p_id) REFERENCES cel_participants(p_id) 
		ON UPDATE CASCADE 
		ON DELETE CASCADE,
	FOREIGN KEY(role_id) REFERENCES cel_roles(role_id) 
		ON UPDATE CASCADE 
		ON DELETE CASCADE
) ENGINE=INNODB;

DROP TABLE IF EXISTS cel_signups;
CREATE TABLE cel_signups (
	date_id	int,
	p_id 		int,
	role_id 	int,
	FOREIGN KEY(p_id) REFERENCES cel_participants(p_id) 
		ON UPDATE CASCADE 
		ON DELETE CASCADE,
	FOREIGN KEY(role_id) REFERENCES cel_roles(role_id) 
		ON UPDATE CASCADE 
		ON DELETE CASCADE,
	FOREIGN KEY(date_id) REFERENCES cel_dates(date_id) 
		ON UPDATE CASCADE 
		ON DELETE CASCADE
) ENGINE=INNODB;

SET foreign_key_checks = 1;

-- Populate the roles
INSERT INTO cel_roles  (role_id, description, default_order) VALUES
	(1, 'Preacher', 10),
	(2, 'Celebrant', 20),
	(3, 'LEM', 30),
	(4, 'Lector', 40),
	(5, 'Acolyte', 50),
	(6, 'Usher', 60),
	(7, 'Coffee Hour Host', 70);
	
-- Populate the Sunday Services (e.g. all Sundays from today for the next 5 years)
DELIMITER //

-- Add all regularly scheduled Sunday services, through the specified end date
DROP PROCEDURE IF EXISTS proc_add_sundays_through //
CREATE PROCEDURE proc_add_sundays_through(end_date date, service varchar(5), descrip varchar(32))
BEGIN

	DECLARE	nextsunday date;
	
	-- Delete all future sundays...
	-- DELETE FROM cel_dates WHERE service_date>end_date AND WEEKDAY(service_date) = 6; 
	
	-- Figure out what next sunday is, and keep adding 7
	SELECT ADDDATE( CURDATE(), 
				INTERVAL (6-WEEKDAY( CURDATE() )) DAY) INTO nextsunday;
	WHILE (nextsunday < end_date ) DO
		-- INSERT INTO cel_dates (service_date, service_offer, description) VALUES ( nextsunday, service, descrip);
		
		CALL proc_add_service_date( nextsunday, service, descrip );
		SELECT ADDDATE( nextsunday, INTERVAL 7 DAY) INTO nextsunday;
	END WHILE;
END //

-- Add a named service
DROP PROCEDURE IF EXISTS proc_add_service_date //
CREATE PROCEDURE proc_add_service_date(add_date DATE, service varchar(5), descrip varchar(32)) 
BEGIN
	INSERT INTO cel_dates 
		SET service_date = add_date, service_offer = service, description = descrip 
		ON DUPLICATE KEY UPDATE service_date = add_date, service_offer = service, description = descrip;
END //

-- Helper / Testing function to choose a random willing particpant
DROP PROCEDURE IF EXISTS randomly_select_participant_from_role //
CREATE PROCEDURE randomly_select_participant_from_role (roleid INT, OUT pid INT)
BEGIN
	SELECT p_id FROM cel_participant_roles WHERE role_id=roleid ORDER BY RAND() LIMIT 1 INTO pid;
END //

-- Helper / testing function to fill a particular service with staff
DROP PROCEDURE IF EXISTS randomly_select_participants //
CREATE PROCEDURE randomly_select_participants (dateid INT)
BEGIN
	DECLARE roleid INT;
	DECLARE done INT DEFAULT 0;
	DECLARE roles CURSOR FOR SELECT role_id FROM cel_roles;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @pid  = 1 ;
	
	open roles;
	readloop: LOOP
		FETCH roles INTO roleid;
		IF done THEN 
			LEAVE readloop;
		END IF;
		
		CALL randomly_select_participant_from_role(roleid, @pid);
		INSERT INTO cel_signups (date_id, p_id, role_id ) VALUES (dateid, @pid, roleid);
	END LOOP;
END //

DELIMITER ;

DROP VIEW IF EXISTS vw_volunteers;
CREATE VIEW vw_volunteers AS 
	SELECT r.role_id, r. description, p.p_id, p.name, p.email
	     FROM cel_roles r, cel_participants p, cel_participant_roles cpr
	     WHERE cpr.role_id=r.role_id AND cpr.p_id = p.p_id;