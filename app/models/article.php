<?php
 namespace App\Models;
 use App\Crud\Crud;

class article extends crud {
  private $table = 'articles';

  public function __construct(){
      parent::__construct();
  }
  

  public function selectAllArticle(){
      return $this-> displayArticles();
  }

  public function selectArticle($id){
      return $this->selectRecords($this->table, '*', 'id = ' . $id);
  }

  public function addArticle(array $data){
      return $this->insertRecord($this->table, $data);
  }

  public function updateArticle(array $data, int $id){
      return $this->updateRecord($this->table, $data, $id);
  }

  public function deleteArticle(int $id){
      return $this->deleteRecord($this->table, $id);
  }

  public function countArticle(){
      $result = $this->selectRecords($this->table, 'COUNT(*) as total');
      return $result[0]['total'];
  }
  public static function displayArticles() {
    return parent::displayArticles();
}

public static function getTopArticles($limit = 5) {
    return parent::getTopArticles($limit);
}

public static function addTag($article_id, $tag_id) {
    $data = [
        'article_id' => $article_id,
        'tag_id' => $tag_id
    ];
    $this->insertRecord('article_tags', $data);
}
public function removeTag($articleId, $tagId) {
    try {
        $sql = "DELETE FROM article_tags WHERE article_id = ? AND tag_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$articleId, $tagId]);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

}