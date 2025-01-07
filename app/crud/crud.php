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

    public static function displayArticles() {
        try {
            $stmt = self::$conn->query("
                SELECT
                    a.*,
                    c.name as category_name,
                    u.username as author_name,
                    GROUP_CONCAT(t.name ORDER BY t.name SEPARATOR ', ') as tag_name
                FROM articles a
                LEFT JOIN categories c ON a.category_id = c.id
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN article_tags at ON a.id = at.article_id
                LEFT JOIN tags t ON at.tag_id = t.id
                GROUP BY a.id
            ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public static function getTopArticles($limit = 5)
    {
        try {
            $sql = "SELECT a.*, u.username as author_name
                    FROM articles a
                    LEFT JOIN users u ON a.author_id = u.id
                    ORDER BY a.views DESC, a.created_at DESC
                    LIMIT " . (int)$limit;

            $stmt = self::$conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    public static function getTopUsers($limit = 5) {
        try {
            $sql = "SELECT u.*, COUNT(a.id) as article_count, SUM(a.views) as total_views
                    FROM users u
                    LEFT JOIN articles a ON u.id = a.author_id
                    GROUP BY u.id
                    ORDER BY total_views DESC, article_count DESC
                    LIMIT " . (int)$limit;

            $stmt = self::$conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
   
      public static function addTag($articleId,$tagId){
        $data = [
            'article_id'=>$articleId,
            'tag_id' => $tagId
        ];

        self::insertRecord('article_tags',$data);
      }
    public static function getCategoryStats(){
        $sql = "SELECT COUNT(*) as article_count, categories.name as category_name FROM articles JOIN categories ON articles.category_id = categories.id GROUP BY category_name;";
        $stmt = self::$conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    }
    
}
