<?php

namespace App\Crud;

use Database\Config\conection;
use PDO;

class Crud extends conection {

    private static $conn;

    public function __construct(){
        self::$conn = conection::getPDO();
    }

    public static function selectRecords(string $table, string $columns = "*", string $where = null, array $params=[])
    {
        $sql = "SELECT $columns FROM $table ";

        if ($where !== null) {
            $sql .= " WHERE $where";
        }
        $stmt = self::$conn->prepare($sql);

       
        if(!$stmt){
            die("Error in prepared statement: " . self::$conn->errorInfo()[2]);
        }
        if (!empty($params)) {
            foreach ($params as $key => &$value) {
                $stmt->bindParam($key + 1, $value);
            }
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function insertRecord(string $table, array $data)
    {
        
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        $stmt = self::$conn->prepare($sql);

        if (!$stmt) {
            die("Error in prepared statement: " . self::$conn->errorInfo()[2]);
        }

        $i = 1;
        foreach ($data as $key => &$value) {
            $stmt->bindParam($i, $value);
            $i++;
        }

        if ($stmt->execute()) {
            $lastInsertId = self::$conn->lastInsertId();
            return $lastInsertId;
        } else {
            return false;
        }
    }

    public static function updateRecord(string $table, array $data, int $id)
    {
        $args = array();

        foreach ($data as $key => $value) {
            $args[] = "$key = ?";
        }

        $sql = "UPDATE $table SET " . implode(',', $args) . " WHERE id = ?";

        $stmt = self::$conn->prepare($sql);

        if (!$stmt) {
            die("Error in prepared statement: " . self::$conn->errorInfo()[2]);
        }

        $i = 1;
        foreach ($data as $key => &$value) {
            $stmt->bindParam($i, $value);
            $i++;
        }
        $stmt->bindParam($i, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function deleteRecord(string $table, int $id)
    {
        $sql = "DELETE FROM $table WHERE id = ?";

        $stmt = self::$conn->prepare($sql);

        if (!$stmt) {
            die("Error in prepared statement: " . self::$conn->errorInfo()[2]);
        }

        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
