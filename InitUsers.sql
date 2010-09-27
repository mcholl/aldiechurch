USE doverkn1_wpac;
CREATE USER 'doverkn1_aldie'@'%';
CREATE USER 'doverkn1_aldie'@'localhost';
SET PASSWORD FOR 'doverkn1_aldie' = PASSWORD('JCIMSAISTI');
GRANT ALL PRIVILEGES ON *.* TO 'doverkn1_aldie' @'%' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'doverkn1_aldie' @'localhost' WITH GRANT OPTION;
CREATE USER 'admin'@'localhost';
GRANT RELOAD,PROCESS ON *.* TO 'admin'@'localhost';
