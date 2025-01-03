<?php


namespace App\crud;

use Database\config\conection;


class crud extends conection {


     public function __construct(){
        parent::connect();
        
     }

    public static function selectRecords(string $table, string $columns = "*", string $where = null)
    {

        $sql = "SELECT $columns FROM $table";

        if ($where !== null) {
            $sql .= " WHERE $where";
        }
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error in prepared statement: " . $conn->errorInfo()[2]);
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

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error in prepared statement: " . $conn->errorInfo()[2]);
        }

        $i = 1;
        foreach ($data as $key => &$value) {
            $stmt->bindParam($i, $value);
            $i++;
        }

        if ($stmt->execute()) {
            $lastInsertId = $pdo->lastInsertId();
            return $lastInsertId;
        } else {
            return false;
        }
    }

    public static function updateRecord( $table, $data, $id)
    {
        $args = array();

        foreach ($data as $key => $value) {
            $args[] = "$key = ?";
        }

        $sql = "UPDATE $table SET " . implode(',', $args) . " WHERE id = ?";

        $stmt = $pdo->prepare($sql);

        if (!$stmt) {
            die("Error in prepared statement: " . $conn->errorInfo()[2]);
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
    public static function deleteRecord($conn, string $table, int $id)
    {

        $sql = "DELETE FROM $table WHERE id = ?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error in prepared statement: " . $pdo->errorInfo()[2]);
        }

        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

}


