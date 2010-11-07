/* So, there's no reason to do duplicate entry for the celebrants.
Here, I simply recreate the cel_paricipants table as a view of the directory */
SET foreign_key_checks = 0;

DROP TABLE IF EXISTS cel_participants;

DROP VIEW IF EXISTS vw_HomePhones;  
CREATE VIEW vw_HomePhones AS SELECT mbrid, contactinf as 'Home_Phone' FROM dir_contacts WHERE contactloc='Home' AND contacttyp='Voice';

DROP VIEW IF EXISTS vw_email;  
CREATE VIEW vw_email AS 
SELECT mbrid, contactinf as 'Email' FROM dir_contacts WHERE contacttyp='Email';

DROP VIEW IF EXISTS cel_participants;	
CREATE VIEW cel_paricipants AS
SELECT m.mbrid AS p_id, CONCAT(m.firstname, " ", m.lastname) AS name, p.Home_Phone as phone, e.Email as email 
	FROM dir_members m
	LEFT JOIN vw_HomePhones p ON m.mbrid = p.mbrid
	LEFT JOIN vw_email e ON m.mbrid = e.mbrid;
	
SET foreign_key_checks = 1;
