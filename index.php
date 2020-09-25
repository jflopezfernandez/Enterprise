<?php

declare(strict_types=1);

require('Util/ConfigurationSettings.php');
require('Enterprise/Autoloader.php');

$autoloader = new Enterprise\Autoloader();
$autoloader->addNamespace('Enterprise\\', 'Enterprise/src');
$autoloader->addNamespace('Enterprise\\Tests\\' ,'Enterprise/tests');
$autoloader->register();

if (!file_exists(CONFIGURATION_FILENAME)) {
    throw new Enterprise\NonexistentConfigurationFileException();
}

$settings = yaml_parse_file(CONFIGURATION_FILENAME);

$db = null;

$hostname = $settings[$settings['environment']]['database']['hostname'];
$database = $settings[$settings['environment']]['database']['database'];
$username = $settings[$settings['environment']]['database']['username'];
$password = $settings[$settings['environment']]['database']['password'];

try {
    $db = new \PDO("pgsql:host=$hostname;dbname=$database", $username, $password, [
        \PDO::ATTR_CASE                 => \PDO::CASE_NATURAL,
        \PDO::ATTR_ERRMODE              => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_ORACLE_NULLS         => \PDO::NULL_NATURAL,
        \PDO::ATTR_STRINGIFY_FETCHES    => false,
        \PDO::ATTR_DEFAULT_FETCH_MODE   => \PDO::FETCH_BOTH
    ]);
} catch (\PDOException $pdoException) {
    die($pdoException->getMessage());
}
