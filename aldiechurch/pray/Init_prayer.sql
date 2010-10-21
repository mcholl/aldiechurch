/* Store the main details of the prayer */
DROP TABLE IF EXISTS pray_main;
CREATE TABLE pray_main (
	pray_id  		int AUTO_INCREMENT NOT NULL,
	display			varchar(64),
	pray_cat		int,
	is_public		bool,
	is_member	bool,
	details			text,
	PRIMARY KEY(pra_id)
);

DROP TABLE IF EXISTS pray_sub;
CREATE TABLE pray_sub (
	pray_id			int,
	submitter_id	int,
	relationship	varchar(32)
)

/* When a prayer request is to be active / needs to be renewed */
DROP TABLE IF EXISTS pray_lifespan;
CREATE TABLE pray_lifespan (
	pray_id			int,
	valid_from		date,
	valid_until		date
	is_active		int
);

/* Who submitted it */
DROP TABLE IF EXISTS pray_submitters;
CREATE TABLE prayer_submitter (
	submitter_id int AUTO_INCREMENT NOT NULL,
	name			varchar(255),
	identifier		varchar(255),
	email				varchar(255)
);

/* Categories e.g. In Hospital, Sick at Home, Unemployed, Deployed, Travel, other */ 
DROP TABLE IF EXISTS pray_cats;
CREATE TABLE pray_cats (
	pray_cat		int AUTO_INCREMENT NOT NULL,
	description	varchar(32),
	PRIMARY KEY (pray_cat)
);

INSERT INTO pray_cats (description) VALUES 
	('In Hospital'),
	('At Home'),
	('Deployed - Military'),
	('Employment concerns'),
	('Other');




