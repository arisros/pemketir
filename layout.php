<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <?php include 'bootstrap.php'; ?>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet">

  <title><?= $title ?? 'Pemketir' ?></title>
</head>

<body>
  <main class="main-container">
    <?= $content ?>
  </main>
</body>

</html>