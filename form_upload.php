<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=Form, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Form Input File</title>
</head>

<body class="form">
  <!-- https://www.google.com/alerts/feeds/12246638679881655932/15333686624258242917 -->
  <h1>Form Input File XML</h1>
  <form action="load.php" method="GET">
    <input name="link" id="file" placeholder="https://google.com/alerts/feeds/....">
    <button type="submit">Upload</button>
  </form>

  <form action="preprocessing.php" method="GET">
    <h1>Preprocessing</h1>
    <button type="submit">Preprocessing</button>
  </form>
</body>

</html>