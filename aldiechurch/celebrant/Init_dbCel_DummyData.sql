-- This file generates several fake signups in the database

DELETE FROM cel_dates;
CALL proc_add_sundays_through('2013-11-01', '10a', 'Regular Service');

-- Add Christmas Services
CALL proc_add_service_date('2010-12-24', '5p', 'Early Christmas Service');
CALL proc_add_service_date('2010-12-24', '11p', 'Late Christmas Service');
CALL proc_add_service_date('2011-12-24', '5p', 'Early Christmas Service');
CALL proc_add_service_date('2011-12-24', '11p', 'Late Christmas Service');
CALL proc_add_service_date('2012-12-24', '5p', 'Early Christmas Service');
CALL proc_add_service_date('2012-12-24', '11p', 'Late Christmas Service');

-- Add Easter Vigil Services
CALL proc_add_service_date('2011-04-23', '5p', 'Easter Vigil');
CALL proc_add_service_date('2012-04-08', '5p', 'Easter Vigil');
CALL proc_add_service_date('2013-03-31', '5p', 'Easter Vigil');


DELETE FROM cel_participants;
INSERT INTO cel_participants (p_id, name, email) VALUES
	(1, 'John Sheehan', 'jts@aldiechurch.com'),
	(2, 'Michael Hollinger', 'mch'),
	(3, 'Denise Sheehan', 'dts'),
	(4, 'Ted Pritekel', 'ted'),
	(5, 'Wanda Pritekel', 'wanda'),
	(6, 'Bonnie Bleakley', 'bbleak'),
	(7, 'Nikki Ellis', 'nikki'),
	(8, 'Bill Baxter', 'bbax'),
	(9, 'Matt Sheehan', 'matt'),
	(10, 'Catie Hutchison', 'catie'),
	(11, 'Lucia Benedetto', 'lucia'),
	(12, 'Scott Mason', 'scot');

DELETE FROM cel_particpant_roles;
INSERT INTO cel_participant_roles (p_id, role_id) VALUES
	(1, 1),
	(1, 2),
	(2, 1),
	(2, 3),
	(2, 4),
	(2, 5),
	(3, 4),
	(4, 4),
	(5, 3),
	(6, 3),
	(6, 4),
	(7, 4),
	(8, 3),
	(8, 5),
	(9, 5),
	(10, 5),
	(11, 6),
	(12, 6);

-- Randomly Set up the next n Sundays

DROP PROCEDURE IF EXISTS assign_all_services;

DELIMITER //
CREATE PROCEDURE assign_all_services()
BEGIN
	DECLARE dateid INT;
	DECLARE done INT DEFAULT 0;
	DECLARE dates CURSOR FOR SELECT date_id FROM cel_dates;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

	OPEN dates;
	readloop: LOOP
		FETCH dates INTO dateid;
		IF done THEN 
			LEAVE readloop;
		END IF;
		
		CALL randomly_select_participants (dateid);
	END LOOP;		
END //
DELIMITER ;

CALL assign_all_services();
DROP PROCEDURE assign_all_services;

