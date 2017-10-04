<?php

//
// Command line tool for installing opencart
// Author: Vineet Naik <vineet.naik@kodeplay.com> <naikvin@gmail.com>
//
// (Currently tested on linux only)
//
// Usage:
//
//   cd install
//   php cli_install.php install --db_hostname localhost \
//                               --db_username root \
//                               --db_password pass \
//                               --db_database opencart \
//                               --db_driver mysqli \
//								 --db_port 3306 \
//                               --username admin \
//                               --password admin \
//                               --email youremail@example.com \
//

// TODO: Mandatory check that mod_rewrite is enabled for Apache;
// TODO: Mandate that APache is the server
// Until someone creates a Nginx Config for having smart URL's

ini_set('display_errors', 1);

error_reporting(E_ALL);

// DIR
define('DIR_ROOT', (dirname(__FILE__) . '/../'));
define('DIR_APPLICATION', str_replace('\\', '/', realpath(dirname(__FILE__))) . '/');
define('DIR_SYSTEM', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')) . '/system/');
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_OPENCART', str_replace('\\', '/', realpath(DIR_APPLICATION . '../')) . '/');
define('DIR_DATABASE', DIR_SYSTEM . 'database/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_MODIFICATION', DIR_SYSTEM . 'modification/');
define('DIR_PUBLIC', DIR_OPENCART . "public_html/");

echo "Pre-Install Diagnostics:\n";
echo "DIR_APPLICATION:  " . DIR_APPLICATION . "\n";
echo "DIR_SYSTEM:       " . DIR_SYSTEM . "\n";
echo "DIR_STORAGE:      " . DIR_STORAGE . "\n";
echo "DIR_OPENCART:     " . DIR_OPENCART . "\n";
echo "DIR_DATABASE:     " . DIR_DATABASE . "\n";
echo "DIR_CONFIG:       " . DIR_CONFIG . "\n";
echo "DIR_MODIFICATION: " . DIR_MODIFICATION . "\n";
echo "DIR_PUBLIC:       " . DIR_PUBLIC . "\n";

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);


function handleError($errno, $errstr, $errfile, $errline, array $errcontext) {
	// error was suppressed with the @-operator
	if (0 === error_reporting()) {
		return false;
	}
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

set_error_handler('handleError');

// TODO: Installer chokes if database password contains $ sign

function usage() {
	echo "Usage:\n";
	echo "======\n";
	echo "\n";
	$options = implode(" ", array(
		'--db_hostname', 'localhost',
		'--db_username', 'root',
		'--db_password', 'pass',
		'--db_database', 'opencart',
		'--db_driver', 'mpdo',
		'--db_port', '3306',
		'--username', 'admin',
		'--password', 'admin',
        '--url', 'www.yoursite.com',
		'--email', 'youremail@example.com'
	));
	echo 'php install.php install ' . $options . "\n\n";
}


function get_options($argv) {
	$defaults = array(
		'db_hostname' => 'localhost',
		'db_database' => 'opencart',
		'db_prefix' => 'oc_',
		'db_driver' => 'mpdo',
		'db_port' => '3306',
		'username' => 'admin',
	);

	$options = array();
	$total = count($argv);
	for ($i=0; $i < $total; $i=$i+2) {
		$is_flag = preg_match('/^--(.*)$/', $argv[$i], $match);
		if (!$is_flag) {
			throw new Exception($argv[$i] . ' found in command line args instead of a valid option name starting with \'--\'');
		}
		$options[$match[1]] = $argv[$i+1];
	}
	return array_merge($defaults, $options);
}


function valid($options) {
	$required = array(
		'db_hostname',
		'db_username',
		'db_password',
		'db_database',
		'db_prefix',
		'db_port',
		'username',
		'password',
		'email',
        'url',
	);
	$missing = array();
	foreach ($required as $r) {
		if (!array_key_exists($r, $options)) {
			$missing[] = $r;
		}
	}
	$valid = count($missing) === 0;
	return array($valid, $missing);
}


function install($options) {
	$check = check_requirements();
	if ($check[0]) {
		setup_db($options);
		write_config_files($options);
		dir_permissions();
	} else {
		echo 'FAILED! Pre-installation check failed: ' . $check[1] . "\n\n";
		exit(1);
	}
}


function check_requirements() {
	$error = null;

// TODO: Verify this recognizes PHP version
    if (phpversion() < '7.0') {
        $error = 'Warning: You need to use PHP7.0+ or above for OpenCommerce to work!';
    }

	if (!ini_get('file_uploads')) {
		$error = 'Warning: file_uploads needs to be enabled!';
	}

	if (ini_get('session.auto_start')) {
		$error = 'Warning: OpenCart will not work with session.auto_start enabled!';
	}

// TODO: LJK check that this actually gets triggered if PDO isn't installed
    if (!defined('PDO::ATTR_DRIVER_NAME')) {
        echo 'PDO unavailable';
    }

// TODO: Check for PDO/MySQL driver
// TODO: Check for Apache mod_rewrite

	if (!extension_loaded('gd')) {
		$error = 'Warning: GD extension needs to be loaded for OpenCart to work!';
	}

	if (!extension_loaded('curl')) {
		$error = 'Warning: CURL extension needs to be loaded for OpenCart to work!';
	}

	if (!function_exists('openssl_encrypt')) {
		$error = 'Warning: OpenSSL extension needs to be loaded for OpenCart to work!';
	}

	if (!extension_loaded('zlib')) {
		$error = 'Warning: ZLIB extension needs to be loaded for OpenCart to work!';
	}

	return array($error === null, $error);
}


function setup_db($data)
{
    $db = new DB($data['db_driver'], htmlspecialchars_decode($data['db_hostname']), htmlspecialchars_decode($data['db_username']), htmlspecialchars_decode($data['db_password']), htmlspecialchars_decode($data['db_database']), $data['db_port']);

    $file = DIR_APPLICATION . 'opencommerce.sql';

    if (!file_exists($file)) {
        exit('Could not load sql file: ' . $file);
    }

    $lines = file($file);

    if ($lines) {
        $sql = '';

        foreach ($lines as $line) {
            if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
                $sql .= $line;

                if (preg_match('/;\s*$/', $line)) {
                    $sql = str_replace("DROP TABLE IF EXISTS `oc_", "DROP TABLE IF EXISTS `" . $data['db_prefix'], $sql);
                    $sql = str_replace("CREATE TABLE `oc_", "CREATE TABLE `" . $data['db_prefix'], $sql);
                    $sql = str_replace("INSERT INTO `oc_", "INSERT INTO `" . $data['db_prefix'], $sql);

                    $db->query($sql);

                    $sql = '';
                }
            }
        }

        $db->query("DELETE FROM `oc_user` WHERE user_id = '1'");

        $db->query("INSERT INTO `oc_user` SET user_id = '1', user_group_id = '1', username = '" . $db->escape($data['username']) . "', password = '" . password_hash($data['password'], PASSWORD_DEFAULT) . "', firstname = 'John', lastname = 'Doe', email = '" . $db->escape($data['email']) . "', status = '1'");

        $db->query("DELETE FROM `oc_setting` WHERE `key` = 'config_email'");
        $db->query("INSERT INTO `oc_setting` SET `code` = 'config', `key` = 'config_email', value = '" . $db->escape($data['email']) . "'");

        $db->query("DELETE FROM `oc_setting` WHERE `key` = 'config_encryption'");
        $db->query("INSERT INTO `oc_setting` SET `code` = 'config', `key` = 'config_encryption', value = '" . $db->escape(token(1024)) . "'");

        $db->query("UPDATE `oc_product` SET `viewed` = '0'");

        $db->query("INSERT INTO `oc_api` SET username = 'Default', `key` = '" . $db->escape(token(256)) . "', status = 1");

        $api_id = $db->getLastId();

        $db->query("DELETE FROM `oc_setting` WHERE `key` = 'config_api_id'");
        $db->query("INSERT INTO `oc_setting` SET `code` = 'config', `key` = 'config_api_id', value = '" . (int)$api_id . "'");
    }
}

function write_config_files($options) {
    // Config file (database, email, etc)
    $output  = "<?php\n";
    $output .= "\n";
    $output .= "define('STORE_URL',         '" . $options['url'] . "');\n";
    $output .= "define('DIR_ROOT',          '" . DIR_OPENCART . "');\n";
    $output .= "\n";
    $output .= "// Stored Credentials\n";
    $output .= "\n";
    $output .= "define('DB_HOSTNAME',       '" . $options['db_hostname'] . "');\n";
    $output .= "define('DB_USERNAME',       '" . $options['db_username'] . "');\n";
    $output .= "define('DB_PASSWORD',       '" . $options['db_password'] . "');\n";
    $output .= "define('DB_DATABASE',       '" . $options['db_database'] . "');\n";
    $output .= "define('DB_DRIVER',         'mpdo');\n";
    $output .= "define('DB_PREFIX',         'oc_');\n";
    $output .= "define('DB_PORT',           '" . $options['db_port'] ."');\n";

    $file = fopen(DIR_OPENCART . 'config/config.php', 'w');
    fwrite($file, $output);
    fclose($file);

    // Paths file (universal)
//    $output  = "<?php\n";;
//    $output .= "// Any modifications to this file are at your own risk!\n";
//    $output .= "define('DIR_SYSTEM',        DIR_ROOT    . 'system/');\n";
//    $output .= "define('DIR_STORAGE',       DIR_SYSTEM  . 'storage/');\n";
//    $output .= "define('DIR_CONFIG',        DIR_SYSTEM  . 'config/');\n";
//    $output .= "define('DIR_CACHE',         DIR_STORAGE . 'cache/');\n";
//    $output .= "define('DIR_DOWNLOAD',      DIR_STORAGE . 'download/');\n";
//    $output .= "define('DIR_LOGS',          DIR_STORAGE . 'logs/');\n";
//    $output .= "define('DIR_MODIFICATION',  DIR_STORAGE . 'modification/');\n";
//    $output .= "define('DIR_SESSION',       DIR_STORAGE . 'session/');\n";
//    $output .= "define('DIR_UPLOAD',        DIR_STORAGE . 'upload/');\n";
//    $output .= "\n";
//    $output .= "// these are used by catalog\n";
//    $output .= "define('HTTP_ROOT',         '/');\n";
//    $output .= "define('HTTP_SERVER',       '/');\n";
//    $output .= "\n";
//    $output .= "// these are used by admin\n";
//    $output .= "define('HTTP_ADMIN',       '/admin/');\n";
//    $output .= "define('HTTP_CATALOG',     '/');\n";
//
//    $file = fopen(DIR_OPENCART . 'config/paths.php', 'w');
//    fwrite($file, $output);
//    fclose($file);

}


function dir_permissions() {
	$dirs = array(
		DIR_PUBLIC . 'image/',
		DIR_SYSTEM . '/storage/download/',
        DIR_SYSTEM . '/storage/upload/',
        DIR_SYSTEM . '/storage/cache/',
        DIR_SYSTEM . '/storage/logs/',
        DIR_SYSTEM . '/storage/modification/',
	);
	exec('chmod o+w -R ' . implode(' ', $dirs));
}


$argv = $_SERVER['argv'];
$script = array_shift($argv);
$subcommand = array_shift($argv);


switch ($subcommand) {

case "install":
	try {
		$options = get_options($argv);
		$valid = valid($options);
		if (!$valid[0]) {
			echo "FAILED! Following inputs were missing or invalid: ";
			echo implode(', ', $valid[1]) . "\n\n";
			exit(1);
		}
		install($options);
		echo "SUCCESS! OpenCommerce successfully installed on your server\n";
	} catch (ErrorException $e) {
		echo 'FAILED!: ' . $e->getMessage() . "\n";
		exit(1);
	}
	break;
case "usage":
default:
	echo usage();
}