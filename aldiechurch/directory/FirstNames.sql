SELECT f.famlyid, f.famlyname, GROUP_CONCAT(m.firstname) AS first_names
	FROM dir_families f
	INNER JOIN dir_members m ON m.famlyid = f.famlyid
	GROUP BY m.famlyid

SELECT f.famlyid, f.famlyname, GROUP_CONCAT(m.firstname) AS first_names
	FROM dir_families f
	LEFT JOIN (SELECT * FROM dir_members WHERE typeofmbr=3) m ON m.famlyid = f.famlyid
	WHERE f.famlyid>0
	GROUP BY m.famlyid 
	ORDER BY f.famlyname;


SELECT f.famlyid, f.famlyname, parents.parent_first_names, kids.kid_first_names
	FROM dir_families f	
	LEFT JOIN (SELECT famlyid, GROUP_CONCAT(firstname) AS parent_first_names 
	FROM dir_members WHERE typeofmbr=3 AND famlyid>0
	GROUP BY famlyid) parents ON parents.famlyid = f.famlyid 
	LEFT JOIN (SELECT famlyid, GROUP_CONCAT(firstname) AS kid_first_names 
	FROM dir_members WHERE typeofmbr=4 AND famlyid>0
	GROUP BY famlyid) kids ON kids.famlyid = f.famlyid;
	
	
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

DROP VIEW IF EXISTS vw_family_addresses;
CREATE VIEW vw_family_addresses AS
SELECT n.*, a.*
	FROM vw_names n 
	LEFT JOIN dir_member_address ma ON n.famlyid=ma.mbrid
	LEFT JOIN dir_addresses a ON ma.addrid = a.addrid;


	
