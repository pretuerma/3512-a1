<?php
require __DIR__ . '/includes/config.inc.php';
require __DIR__ . '/includes/db-classes.inc.php';

try {
  $pdo   = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS)); 
  $users = (new UsersDB($pdo))->getAll();
}
 catch (Exception $e) 
{ 
  die( $e->getMessage() ); 
} 

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Portfolio Project</title>
  <link rel="stylesheet" href="/3512-a1/pretu315/css/style.css?v=<?= time() ?>">

</head>

<body>
<header>
  <h1>Portfolio Project</h1>
  <nav>
    <a href="index.php">Home</a> 
    <a href="about.php">About</a> 
    <a href="api.php">APIs</a>
  </nav>
</header>
<hr>

<main class="main-layout">
  <section class="left-panel">
      <h2>Customers</h2>
      <table>
         <tr><th>Name</th><th>Portfolio</th></tr>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><?= $u['lastname'] . ', ' . $u['firstname'] ?></td> 
            <td><a href="portfolio.php?userId=<?= (int)$u['id'] ?>" target="rightFrame">View Portfolio</a></td>
          </tr>
        <?php endforeach; ?>
      </table>
  </section>

    <section class="right-panel">
    <iframe
      name="rightFrame"
      srcdoc="<p style='text-align:center; padding-top:2em;'>Select a customer to view their portfolio.</p>">
    </iframe>
  </section>

</main>
</body>
</html>
