<?php
require __DIR__ . '/../includes/config.inc.php';
require __DIR__ . '/../includes/db-classes.inc.php';

$pdo   = DatabaseHelper::createConnection([DBCONNSTRING, DBUSER, DBPASS]);
$comps = new CompaniesDB($pdo);

$data = $comps->getAll();

if (isset($_GET['ref'])) {
  $sym = trim($_GET['ref']);
  $found = $comps->getBySymbol($sym);
  if ($found) {
    $data = $found;                
  } else {
    header("Location: api-error.php"); 
    exit;
  }
}

header('Content-Type: application/json');
echo json_encode($data, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
