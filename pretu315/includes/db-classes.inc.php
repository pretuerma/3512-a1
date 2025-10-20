<?php
class DatabaseHelper {
  public static function createConnection($values = array()) {
    $connString = $values[0];
    $user = $values[1];
    $password = $values[2];
    $pdo = new PDO($connString, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
  }

  public static function runQuery($connection, $sql, $parameters = null) {
    if ($parameters !== null) {
      if (!is_array($parameters)) $parameters = array($parameters);
      $stmt = $connection->prepare($sql);
      $ok = $stmt->execute($parameters);
      if (!$ok) throw new PDOException;
      return $stmt;
    } else {
      $stmt = $connection->query($sql);
      if (!$stmt) throw new PDOException;
      return $stmt;
    }
  }
}

class UsersDB {
  private PDO $pdo;
  public function __construct(PDO $pdo) { 
    $this->pdo = $pdo; 
  }

  public function getAll(): array {
    $sql = "SELECT id, firstname, lastname FROM users ORDER BY lastname, firstname";
    return DatabaseHelper::runQuery($this->pdo, $sql)->fetchAll();
  }

  public function getById(int $id): ?array {
    $sql = "SELECT firstName, lastName FROM users WHERE id = ?";
    return DatabaseHelper::runQuery($this->pdo, $sql,[$id])->fetch();
  }
}

class CompaniesDB {
  private PDO $pdo;
  private string $table = 'companies';

  public function __construct(PDO $pdo) {
     $this->pdo = $pdo;
  }

  public function table(): string { 
    return $this->table; 
}

  public function getAll(): array {
    $sql = "SELECT * FROM {$this->table} ORDER BY symbol";
    return DatabaseHelper::runQuery($this->pdo, $sql)->fetchAll();
  }

  public function getBySymbol(string $sym): ?array {
    $sql = "SELECT * FROM {$this->table} WHERE symbol = ?";
    return DatabaseHelper::runQuery($this->pdo, $sql, [$sym])->fetch();
  }

  public function getKPI(string $symbol): ?array {
    $sql = "SELECT 
              MAX(high) AS hi, 
              MIN(low) AS lo, 
              SUM(volume) AS vol_sum, 
              AVG(volume) AS vol_avg
            FROM history 
            WHERE symbol = ?";
    $kpi = DatabaseHelper::runQuery($this->pdo, $sql, [$symbol])->fetch();
    return $kpi ?: null;
  }
}



class PortfolioDB {
  private PDO $pdo;
  private string $companyTable;

  public function __construct(PDO $pdo, CompaniesDB $companies){
    $this->pdo = $pdo;
    $this->companyTable = $companies->table();
  }

  public function getRowsForUser(int $userId): array {
    $sql = "
      SELECT s.symbol, s.name, s.sector, p.amount,
             (SELECT h.close FROM history h
               WHERE h.symbol = s.symbol
               ORDER BY h.date DESC
               LIMIT 1) AS last_close
      FROM portfolio p
      JOIN {$this->companyTable} s ON p.symbol = s.symbol
      WHERE p.userId = ?
      ORDER BY s.symbol
    ";
    return DatabaseHelper::runQuery($this->pdo, $sql, [$userId])->fetchAll();
  }
}

class HistoryDB {
  private PDO $pdo;

  public function __construct(PDO $pdo){
    $this->pdo = $pdo;
  }

  public function getHistory(string $symbol): array {
    $sql = "SELECT date, open, high, low, close, volume
            FROM history
            WHERE symbol = ?
            ORDER BY date DESC";
    return DatabaseHelper::runQuery($this->pdo, $sql, [$symbol])->fetchAll();
  }

  public function getHistoryAsc(string $symbol): array {
    $sql = "SELECT date, open, high, low, close, volume
            FROM history
            WHERE symbol = ?
            ORDER BY date ASC";
    return DatabaseHelper::runQuery($this->pdo, $sql, [$symbol])->fetchAll();
  }
}
