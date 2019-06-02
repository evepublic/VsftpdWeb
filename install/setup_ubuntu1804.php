<?php

function run($command, &$return_var = null)
{
	echo "\nRunning command: $command\n";
	return system($command, $return_var);
}

function setup_var($message, &$var)
{
	echo $message . " (default=$var):\n";
	if (($readline_output = readline()) !== '') {
		$var = $readline_output;
	};
}

function echo_title($message)
{
	echo $color_start = "\e[92m";
	echo $color_reset = "\e[0m";
	echo "\n$color_start$message$color_reset\n";
}

if (php_sapi_name() !== 'cli') {
	die(basename(__FILE__) . " should run in cli\n");
}

if (posix_geteuid() !== 0) {
	die(basename(__FILE__) . " should run as root\n");
}

chdir(__DIR__);

$webserver_group = 'www-data';
$database = 'vsftpdweb_db';
$database_user = 'vsftpdweb_user';
$database_password = 'MYSQLPASSWORD';


echo_title('WELCOME TO VSFTPDWEB SETUP');
setup_var("What is the group of your webserver?", $webserver_group);
setup_var("Set the name of the database", $database);
setup_var("Set the name of the database user", $database_user);
setup_var("Set the password of the database user", $database_password);


echo_title('CONFIGURING DATABASE');
$result = run("echo \"SHOW DATABASES LIKE '$database';\" | mysql");
if ($result == '') {
	run("echo \"CREATE DATABASE $database COLLATE 'utf8_general_ci';\" | mysql");
	run("mysql $database < database/vsftpd.sql");
} else {
	echo "Database $database already exists, skipping database creation.\n";
}

run("echo \"CREATE USER '$database_user'@'localhost' IDENTIFIED BY '$database_password';\" | mysql");
run("echo \"GRANT ALL PRIVILEGES ON $database.* TO '$database_user'@'localhost';\" | mysql");


echo_title('CONFIGURING PAM');
if (!file_exists('/etc/pam.d/vsftpd.backup')) run("cp /etc/pam.d/vsftpd /etc/pam.d/vsftpd.backup");
echo "Writing /etc/pam.d/vsftpd\n";
$pam_vsftpd_contents = file_get_contents('pam/vsftpd');
$search = [
	"user=vsftpdweb_user",
	"passwd=MYSQLPASSWORD",
	"db=vsftpdweb_db"];
$replace = [
	"user=$database_user",
	"passwd=$database_password",
	"db=$database"];
file_put_contents('/etc/pam.d/vsftpd', str_replace($search, $replace, $pam_vsftpd_contents));


echo_title('CONFIGURING SUDOERS');
$sudoers_vsftpd_contents = file_get_contents('sudoers/01_vsftpd');
$search = [
	'www-data',
	'/path/to/'];
$replace = [
	$webserver_group,
	realpath(__DIR__ . '/..') . '/'];
file_put_contents('/etc/sudoers.d/01_vsftpd', str_replace($search, $replace, $sudoers_vsftpd_contents));
run("chmod 440 /etc/sudoers.d/01_vsftpd");

$create_user_config_script = realpath(__DIR__ . '/../application/scripts/create_user_config_file.php');
run("chown root:root '$create_user_config_script'");
run("chmod 444 '$create_user_config_script'");


echo_title('CONFIGURING VSFTPD HELPER USERS');
run("adduser ftpsecure --shell=/usr/sbin/nologin --disabled-login --gecos '' --no-create-home");
run("adduser ftpuser --shell=/usr/sbin/nologin --disabled-login --gecos '' --no-create-home");
run("mkdir /home/ftpuser/");
run("chown ftpuser:ftpuser /home/ftpuser/");
run("chmod 100 /home/ftpuser/");

run("mkdir -p /mnt/ftpusers/");
run("chown ftpuser:$webserver_group /mnt/ftpusers/");
run("chmod 770 /mnt/ftpusers/");


echo_title('GENERATING VSFTPD CERTIFICATE');
echo $color_start = "\e[7;93m";
echo $color_reset = "\e[0m";
echo "${color_start}Use your domain name for 'Common Name (e.g. server FQDN or YOUR name)'.$color_reset\n";
run("openssl req -x509 -nodes -days 1825 -newkey rsa:2048 -keyout /etc/ssl/private/vsftpd.pem -out /etc/ssl/private/vsftpd.pem");


echo_title('CONFIGURING VSFTPD');
run("mkdir -p /etc/vsftpd_users/");
run("chown root:$webserver_group /etc/vsftpd_users/");
run("chmod 770 /etc/vsftpd_users/");

if (!file_exists('/etc/vsftpd.conf.backup')) run("cp /etc/vsftpd.conf /etc/vsftpd.conf.backup");
run("cp conf/vsftpd.conf /etc/vsftpd.conf");

run("touch /var/log/xferlog");
run("chown root:$webserver_group /var/log/xferlog");
run("chmod 640 /var/log/xferlog");

run("/etc/init.d/vsftpd restart");


echo_title('CONFIGURING VSFTPDWEB APPLICATION');
$config_config_contents = file_get_contents('../application/config/config_default.php');
$encryption_key = bin2hex(random_bytes(16));
$search = [
	"\$config['base_url'] = '';",
	"\$config['index_page'] = 'index.php';",
	"\$config['encryption_key'] = '';"];
$replace = [
	"\$config['base_url'] = '/';",
	"\$config['index_page'] = '';",
	"\$config['encryption_key'] = hex2bin('$encryption_key');"];
file_put_contents('../application/config/config.php', str_replace($search, $replace, $config_config_contents));

$config_database_contents = file_get_contents('../application/config/database_default.php');
$search = [
	"	'username' => '',",
	"	'password' => '',",
	"	'database' => '',"];
$replace = [
	"	'username' => '$database_user',",
	"	'password' => '$database_password',",
	"	'database' => '$database',"];
file_put_contents('../application/config/database.php', str_replace($search, $replace, $config_database_contents));


echo_title('VSFTPDWEB SETUP FINISHED');
