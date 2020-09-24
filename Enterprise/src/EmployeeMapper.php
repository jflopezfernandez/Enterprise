<?php

declare(strict_types=1);

namespace Enterprise;

class EmployeeMapper
{
	/**
	 * @param int $id
	 * @return \Enterprise\Employee
	 * @throws \Enterprise\NonexistentEmployeeIdException
	 */
    public static function findById(int $id) : Employee
    {
        $db = null;

        try {
            $db = new \PDO('pgsql:host=localhost;port=5432;dbname=enterprise;user=enterprisedb;password=enterprisedb');
        } catch (\PDOException $pdoException) {
            die($pdoException->getMessage());
        }

        $statement = $db->prepare('SELECT id, first_name, last_name FROM employees WHERE id=:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $employee = $statement->fetch();
        $statement->closeCursor();

        if ($employee === FALSE) {
            throw new \Enterprise\NonexistentEmployeeIdException();
        }

        return new \Enterprise\Employee(intval($employee['id']), $employee['first_name'], $employee['last_name']);
    }
}
