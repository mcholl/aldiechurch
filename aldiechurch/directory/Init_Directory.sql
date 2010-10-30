DROP TABLE IF EXISTS dir_families;
CREATE TABLE dir_families (
	famlyid			int,
	famlyname	varchar(32),
	anniversary	date,
	PRIMARY KEY(famlyid)
);

DROP TABLE IF EXISTS dir_members;
CREATE TABLE dir_members (
	mbrid			int,
	famlyid			int,
	firstname		varchar(32),
	lastname		varchar(32),
	birthday		date,
	typeofmbr		int,
	PRIMARY KEY(mbrid)
);

DROP TABLE IF EXISTS dir_addresses;
CREATE TABLE dir_addresses (
	addrid			int,
	addrln1			varchar(64),
	city				varchar(32),
	state				varchar(2),
	zipcode			varchar(10),
	PRIMARY KEY(addrid)
);

DROP TABLE IF EXISTS dir_member_address;
CREATE TABLE  dir_member_address (
	mbrid			int,
	addrid			int
);

DROP TABLE IF EXISTS dir_contacts;
CREATE TABLE dir_contacts (
	mbrid			int,
	contactloc		varchar(4),
	contacttyp		varchar(10),
	contactinf		varchar(32)
);
	

DROP TABLE IF EXISTS dir_dataviews;
CREATE TABLE dir_dataviews (
	menu			varchar(32),
	url_action		varchar(15),
	pre_include	varchar(255),
	post_include	varchar(255),
	data_view		varchar(32),
	PRIMARY KEY(url_action)
);

DROP VIEW IF EXISTS vw_fam_members;
CREATE VIEW vw_fam_members AS 
SELECT mbrid, famlyid, firstname, lastname FROM dir_members;

DROP VIEW IF EXISTS vw_fam_contacts;
CREATE VIEW vw_fam_contacts AS 
SELECT fam.famlyid, fam.famlyname, c.* FROM dir_contacts c INNER JOIN dir_families fam ON fam.famlyid = c.mbrid;


/*Dynamically Defined Member Lists */
INSERT INTO dir_dataviews (url_action, menu, data_view) VALUES 
('famaddress', 'Home Addresses', 'vwhtml_famaddress'),
('anniversary', 'Anniversary Calendar', 'vwhtml_famanniversaries'),
('birthday', 'Birthday Calendar', 'vwhtml_birthdays'),
('phonelist', 'Home Phone List', 'vwhtml_famphones'),
('email_list', 'Email List', 'vwhtml_famemails');


/* Common Views - Get the first names of the various family members */
DROP VIEW IF EXISTS vw_parents;
DROP VIEW IF EXISTS vw_kids;
DROP VIEW IF EXISTS vw_names;
CREATE VIEW vw_parents AS 	
SELECT f.famlyid, f.famlyname, GROUP_CONCAT(m.firstname) AS first_names
	FROM dir_families f
	LEFT JOIN dir_members m ON m.famlyid = f.famlyid
	WHERE f.famlyid>0 AND m.typeofmbr=3 
	GROUP BY m.famlyid 
	ORDER BY f.famlyname;

CREATE VIEW vw_kids AS 	
SELECT f.famlyid, f.famlyname, GROUP_CONCAT(m.firstname) AS first_names
	FROM dir_families f
	LEFT JOIN dir_members m ON m.famlyid = f.famlyid
	WHERE f.famlyid>0 AND m.typeofmbr=4 
	GROUP BY m.famlyid 
	ORDER BY f.famlyname;

CREATE VIEW vw_names AS 
	SELECT p.famlyid, p.famlyname, p.first_names AS 'parent_first_names', k.first_names AS 'kid_first_names'
	FROM vw_parents p
	LEFT JOIN vw_kids k ON k.famlyid = p.famlyid
	ORDER BY famlyname;

/* For ?list=phonelist */
DROP VIEW IF EXISTS vwhtml_famphones;
CREATE VIEW vwhtml_famphones AS
SELECT CONCAT("<SPAN class=\'names\'>","<a href=\'ShowFamily.php?fam=", n.famlyid, "\'>",n.famlyname, "   " ,n.parent_first_names, " ", IF(isnull(n.kid_first_names), "", n.kid_first_names),"</a>","</SPAN>","<SPAN class=\'address\'>",c.contactinf, "</SPAN>")
	FROM vw_fam_contacts c
	LEFT JOIN vw_names n ON c.mbrid = n.famlyid
	WHERE c.contacttyp='Voice' AND c.contactloc='Home';

/* For ?list=email_list */
DROP VIEW IF EXISTS vwhtml_famemails;
CREATE VIEW vwhtml_famemails AS
SELECT CONCAT("<SPAN class=\'names\'>","<a href=\'ShowFamily.php?fam=", n.famlyid, "\'>",n.famlyname, "   " ,n.parent_first_names, " ", IF(isnull(n.kid_first_names), "", n.kid_first_names),"</a>","</SPAN>","<SPAN class=\'address\'>",c.contactinf, "</SPAN>")
	FROM vw_fam_contacts c
	LEFT JOIN vw_names n ON c.mbrid = n.famlyid
	WHERE c.contacttyp='Email';

/* For ?list=famaddress */
DROP VIEW IF EXISTS vwhtml_famaddress;
CREATE VIEW vwhtml_famaddress AS
SELECT CONCAT("<SPAN class=\'names\'>","<a href=\'ShowFamily.php?fam=", n.famlyid, "\'>",n.famlyname, "   " ,n.parent_first_names, " ", IF(isnull(n.kid_first_names), "", n.kid_first_names),"</a>","</SPAN>","<SPAN class=\'address\'>",a.addrln1, "; " ,a.city, ", ", a.state, " ", a.zipcode, "</SPAN>")
	FROM vw_names n
	LEFT JOIN dir_member_address ma ON n.famlyid=ma.mbrid
	LEFT JOIN dir_addresses a ON ma.addrid = a.addrid;

/* For ?list=anniversary */
DROP VIEW IF EXISTS vwhtml_famanniversaries;
CREATE VIEW vwhtml_famanniversaries AS
SELECT CONCAT("<SPAN class=\'names\'>","<a href=\'ShowFamily.php?fam=", n.famlyid, "\'>",n.famlyname, "   " ,n.parent_first_names,  "</a></SPAN>","<SPAN class=\'anniversary\'>",  MONTH(a.anniversary), "/", DAY(a.anniversary), "</SPAN>")
	FROM vw_names n
	LEFT JOIN dir_families a ON a.famlyid = n.famlyid
	WHERE a.anniversary > '1901-01-01'
	ORDER BY MONTH(a.anniversary), DAY(a.anniversary);
	
/* For ?list=birthday */
DROP VIEW IF EXISTS vwhtml_birthdays;
CREATE VIEW vwhtml_birthdays AS 
SELECT CONCAT("<span class='name'><a href=\'ShowFamily.php?fam=",famlyid,"&mbrid=", mbrid, "'>", firstname, " ", lastname, "</a></span>", "<span class='birthday'>", CONCAT(LPAD(MONTH(birthday),2,'0'),'/',LPAD(DAY(birthday),2,'0'), IF(YEAR(birthday)>(YEAR(CURDATE())-19),CONCAT('/',YEAR(birthday)),'')  ),"</SPAN>") FROM dir_members WHERE birthday >  '1900-12-31' ORDER BY MONTH(birthday), DAY(birthday);