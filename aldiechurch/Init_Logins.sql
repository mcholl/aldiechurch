DROP TABLE IF EXISTS login_users;
CREATE TABLE login_users (
	user_id 			VARCHAR(255),
	member_id	INT,
	name 			VARCHAR(255),
	email	 			VARCHAR(255),
	PRIMARY KEY(user_id)
);

DROP TABLE IF EXISTS login_logins;
CREATE TABLE login_logins (
	user_id 	VARCHAR(255),
	time		DATE
);

DROP TABLE IF EXISTS login_privs;
CREATE TABLE login_privs (
	user_id			VARCHAR(255),
	priv_name		VARCHAR(255),
	priv_level		VARCHAR(4)
);
