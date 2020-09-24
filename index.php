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
    $db = new \PDO("pgsql:host=$hostname;dbname=$database;user=$username;password=$password");
} catch (\PDOException $pdoException) {
    die($pdoException->getMessage());
}

foreach ($db->query('SELECT * FROM employees') as $employee) {
    echo "<p>$employee[id]. $employee[last_name], $employee[first_name]</p>";
}
