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

class Section
{
    protected string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function setTitle(string $title) : void
    {
        $this->title = $title;
    }

    public function getTitle() : string
    {
        return $this->title;
    }
}

class View
{
    protected string $title;

    protected array $sections = [ ];

    public function __construct(string $title = '')
    {
        $this->title = $title;
    }

    public function setTitle(string $title) : void
    {
        $this->title = $title;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function prependSection(Section $section) : void
    {
        array_unshift($this->sections, $section);
    }

    public function appendSection(Section $section) : void
    {
        array_push($this->sections, $section);
    }

    public function getSections() : array
    {
        return $this->sections;
    }
}

function renderSection(Section $section)
{
    echo "\n";
    echo "        <section>\n";
    echo "            <h2>" . $section->getTitle() . "</h2>\n";
    echo "\n";
    echo "            <!-- TODO: Add section content -->\n";
    echo "        </section>\n";
}

function renderView(View $view)
{
    echo "<!DOCTYPE html>\n";
    echo "<html lang=\"en-US\">\n";
    echo "<head>\n";
    echo "    <meta charset=\"UTF-8\">\n";
    echo "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, shrink-to-fit=no\">\n";
    echo "\n";
    echo "    <title>" . SITE_NAME . (($view->getTitle !== '') ? ' - ' : '') . "{$view->getTitle()}</title>\n";
    echo "\n";
    echo "    <!-- Stylesheets -->\n";
    echo "    <link rel=\"stylesheet\" href=\"res/css/stylesheet.css\">\n";
    echo "\n";
    echo "    <!-- Javascript -->\n";
    echo "    <script src=\"node_modules/jquery/dist/jquery.min.js\"></script>\n";
    echo "    <script src=\"res/js/enterprise.js\"></script>\n";
    echo "</head>\n";
    echo "<body>\n";
    echo "    <header>\n";
    echo "        <h1 class=\"shadow\">" . SITE_NAME . "</h1>\n";
    echo "    </header>\n";
    echo "\n";
    echo "    <main role=\"main\">";
    //echo "        <!-- TODO: Add view content -->\n";

    foreach ($view->getSections() as $section) {
        renderSection($section);
    }

    echo "    </main>\n";
    echo "\n";
    echo "    <footer>\n";
    echo "        <p>Copyright &copy; Enterprise, Inc., 1957-" . (new DateTime())->format('Y') . ".</p>\n";
    echo "    </footer>\n";
    echo "</body>\n";
    echo "</html>\n";
}

$employeesSection = new Section('Employees');
$organizationalUnitsSection = new Section('Organizational Units');

$home = new View('Home');
$home->appendSection($employeesSection);
$home->appendSection($organizationalUnitsSection);

renderView($home);

// echo "<!DOCTYPE html>\n";
// echo "<html lang=\"en-US\">\n";
// echo "<head>\n";
// echo "    <meta charset=\"UTF-8\">\n";
// echo "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, shrink-to-fit=no\">\n";
// echo "\n";
// echo "    <title>Enterprise, Inc.</title>\n";
// echo "</head>\n";
// echo "<body>\n";
// echo "    <header>\n";
// echo "        <h1>Enterprise, Inc.</h1>\n";
// echo "    </header>\n";
// echo "\n";
// echo "    <main role=\"main\">\n";
// echo "        <section>\n";
// echo "            <h2>Employees</h2>\n";
// foreach ($db->query('SELECT * FROM employees') as $employee) {
//     echo "            <p>$employee[id]. $employee[last_name], $employee[first_name]</p>\n";
// }
// echo "        </section>\n";
// echo "\n";
// echo "        <section>\n";
// echo "            <h2>Organizational Units</h2>\n";
// foreach ($db->query('SELECT * FROM organizational_units') as $organizationalUnit) {
//     echo "            <p>$organizationalUnit[name]</p>\n";
// }
// echo "        </section>\n";
// echo "    </main>\n";
// echo "</body>\n";
// echo "</html>\n";
