/* Sample Data for the prayer list */

DELETE FROM prayer_submitter;
DELETE FROM pray_main;
DELETE FROM pray_sub;
DELETE FROM pray_lifespan;

INSERT INTO prayer_submitter (submitter_id, name, identifier, email) VALUES
	(1, 'Church Admin', '', 'admin@aldiechurch.com'),
	(2, 'Michael Hollinger', 'michaelhollinger', 'mcholl@yahoo.com'),
	(3, 'Jillian Roebuck', 'jroebuck', 'jillian@kkb.com'),
	(4, 'Worthy Caulk', 'wc', 'worthy@aldiechurch.com');

INSERT INTO pray_main (pray_id, display, pray_cat, is_member, is_public, details) VALUES
	(1, 'Lisa Livermore', 1, true, true, 'Lisa has undergone chemo for stomach cancer.'),
	(2, 'Eileen Hough', 1, false, true, 'Eileen also has a cancer of some kind.'),
	(3, 'Susan Hollinger', 5, true, false, 'Susan has an undisclosed issue that I dont want anyone to know.'),
	(4, 'Barack', 5, false, true, 'Our president needs all the prayer he can get!'),
	(5, 'Joe', 4, false, true, 'VP needs a foot or mouth transpant'),
	(6, 'Elizabeth Catron', 1, true, true, ''),
	(7, 'Candi Caulk', 1, true, true, ''),
	(8, 'Lucy Owusu-Gwayu', 1, true, true, ''),
	(9, 'Dorothea Cunnigham', 1, true, true, ''),
	(10, 'Caroline Memery', 1, true, true, ''),
	(11, 'Sam McCoullough', 1, false, true, ''),
	(12, 'Barbara O\'Leary', 1, false, true, ''),
	(13, 'Forrest Phillips', 1, false, true, ''),
	(14, 'Tess', 1, false, true, ''),
	(15, 'David Kloecker', 1, false, true, ''),
	(16, 'Larry Nail', 1, false, true, ''),
	(17, 'Tom and Sue Baber', 1, false, true, ''),
	(18, 'Natasica Alessi', 1, false, true, '');
	
INSERT INTO pray_sub (pray_id, submitter_id, relationship) VALUES
	(1, 4, 'friend'),
	(2, 2, 'wifes coworker'),
	(3, 2, 'wife'),
	(4, 1, 'pray for the leaders'),
	(5, 1, 'pray for the leaders');
	
INSERT INTO pray_lifespan (pray_id, valid_from, valid_until, is_active) VALUES
	(1, '2010-10-15', '2010-12-31', 1),
	(2, '2010-10-15', '2010-10-18', 1),
	(3, '2010-10-15', null, 1),
	(4, '2010-10-15', '2010-12-31', 1),
	(5, '2010-10-15', null, 0);
