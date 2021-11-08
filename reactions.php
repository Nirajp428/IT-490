<?php
class Reactions {
  // (A) CONSTRUCTOR - CONNECT TO DATABASE
  private $pdo;
  private $stmt;
  public $error;
  function __construct () {
    $mydb = new mysqli('127.0.0.1', 'niraj', 'password', 'IT490db');
 
  }

 

  // (C) GET REACTIONS FOR ID
  function get ($id, $uid=null) {
    // (C1) GET TOTAL REACTIONS
    $results = ["react" => [0, 0]]; // [LIKES, DISLIKES]
    $query =(
      "SELECT `reaction`, COUNT(`reaction`) `total`
      FROM `reactions` WHERE `id`=?
      GROUP BY `reaction`"
    );
    $result = $mydb->query($query);
    while ($row = mysqli_fetch_assoc($result); {
      if ($row["reaction"]==1) { $results["react"][0] = $row["total"]; }
      else { $results["react"][1] = $row["total"]; }
    }

    // (C2) GET REACTION BY USER (IF SPECIFIED)
    if ($uid !== null) {
      $this->stmt = $this->pdo->prepare(
        "SELECT `reaction` FROM `reactions` WHERE `id`=? AND `user_id`=?"
      );
      $this->stmt->execute([$id, $uid]);
      $results["user"] = $this->stmt->fetchColumn();
    }
    return $results;
  }
 
  // (D) SAVE REACTION
  function save ($id, $uid, $react) {
    // (D1) FORMULATE SQL
    if ($react == 0) {
      $sql = "DELETE FROM `reactions` WHERE `id`=? AND `user_id`=?";
      $data = [$id, $uid];
    } else {
      $sql = "REPLACE INTO `reactions` (`id`, `user_id`, `reaction`) VALUES (?,?,?)";
      $data = [$id, $uid, $react];
    }
 
    // (D2) EXECUTE SQL
    try {
      $this->stmt = $this->pdo->prepare($sql);
      $this->stmt->execute($data);
      return true;
    } catch (Exception $ex) {
      $this->error = $ex->getMessage();
      return false;
    $mydb->close();
    }
  }
}

// (F) CREATE NEW CONTENT OBJECT
$_REACT = new Reactions();
