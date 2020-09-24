<?php

declare(strict_types=1);

require('Enterprise/Autoloader.php');

$autoloader = new Enterprise\Autoloader();
$autoloader->addNamespace('Enterprise\\', 'Enterprise/src');
$autoloader->addNamespace('Enterprise\\Tests\\' ,'Enterprise/tests');
$autoloader->register();

$db = null;

try {
    $db = new \PDO('pgsql:host=localhost;port=5432;dbname=enterprise;user=enterprisedb;password=enterprisedb');
} catch (\PDOException $pdoException) {
    die($pdoException->getMessage());
}

// class Employee
// {
//     private int $id;

//     private string $firstName;

//     private string $lastName;

//     public function __construct(int $id, string $firstName, string $lastName)
//     {
//         $this->id = $id;
//         $this->firstName = $firstName;
//         $this->lastName = $lastName;
//     }

//     public function getId() : int
//     {
//         return $this->id;
//     }

//     public function getFirstName() : string
//     {
//         return $this->firstName;
//     }

//     public function getLastName() : string
//     {
//         return $this->lastName;
//     }
// }

// class NonexistentEmployeeIdException extends Exception
// {
//     public function __construct(string $message = 'The specified employee identification number does not exist.', int $code = 0, \Exception $previousException = null)
//     {
//         parent::__construct($message, $code, $previousException);
//     }
// }

// class EmployeeMapper
// {
// 	/**
// 	 * @param int $id
// 	 * @return Enterprise\Employee
// 	 * @throws Enterprise\NonexistentEmployeeIdException
// 	 */
//     public static function findById(int $id) : Employee
//     {
//         $db = null;

//         try {
//             $db = new \PDO('pgsql:host=localhost;port=5432;dbname=enterprise;user=enterprisedb;password=enterprisedb');
//         } catch (\PDOException $pdoException) {
//             die($pdoException->getMessage());
//         }

//         $statement = $db->prepare('SELECT id, first_name, last_name FROM employees WHERE id=:id');
//         $statement->bindParam(':id', $id, \PDO::PARAM_INT);
//         $statement->execute();
//         $employee = $statement->fetch();
//         $statement->closeCursor();

//         if ($employee === FALSE) {
//             throw new Enterprise\NonexistentEmployeeIdException();
//         }

//         return new Enterprise\Employee(intval($employee['id']), $employee['first_name'], $employee['last_name']);
//     }
// }

echo '<h1>Enterprise</h1>';
echo '<section>';
echo '<h2>Employees</h2>';

try {
    $employee = Enterprise\EmployeeMapper::findById(4);
    echo "<p>{$employee->getId()}.&nbsp;{$employee->getLastName()},&nbsp;{$employee->getFirstName()}</p>";
} catch (Enterprise\NonexistentEmployeeIdException $exception) {
    echo "<p>{$exception->getMessage()}</p>";
}

// foreach ($db->query('SELECT id, first_name, last_name FROM employees') as $employee) {
//     echo "<p>{$employee['id']}.&nbsp;{$employee['last_name']},&nbsp;{$employee['first_name']}</p>";
// }

echo '</section>';
