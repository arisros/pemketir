<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Document</title>
</head>

<body>
  <?php
  include_once 'koneksi.php';
  include_once 'xml_to_array.php';

  $link = $_GET['link'];
  // memuat file xml dari link
  // https://www.php.net/manual/function.simplexml-load-file.php
  $xml = simplexml_load_file($link);

  if (!$xml) {
    echo "Load XML failed!";
  } else {
    $array = xml_to_array($xml);
    $a = 0;

    // iterasi setiap node dalam parent
    foreach ($array as $key => $value) {
      $id = $array["id"];
      $title = $array["title"];
      $link = $array["link"];
      $update = $array["updated"];

      // query cari data di database berdasarkan id google alert 
      $sql = "SELECT * FROM galert_data WHERE galert_id='$id'";
      // eksekusi query pencarian
      $result = $conn->query($sql);

      // cek apakah data sudah ada di database
      if ($result->num_rows > 0) {
        // jika sudah ada, maka tidak perlu disimpan
        echo "";
      } else {
        // jika belum ada, maka simpan data
        $q = "INSERT INTO galert_data(galert_id, galert_title, galert_link, galert_update) VALUES('$id', '$title', '$link', '$update')";
        // eksekusi query
        $result = $conn->query($q);

        // jika data berhasil disimpan, iterasi setiap row dalam xml
        foreach ($xml as $record) {
          // seharusnya kontraknya sama dengan database dan xml
          // TODO: perlu dilakukan handling jika xml tidak sesuai dengan kontrak!
          $entry_id = $record->id;
          $title = $record->title;
          $link = $record->link;
          $published = $record->published;
          $update = $record->update;
          $content = $record->content;
          $author = $record->author;

          // query cari data di database berdasarkan entry_id
          $sql = "SELECT * FROM galert_entry WHERE entry_id='$entry_id'";
          // eksekusi query pencarian
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            echo "";
          } else {
            // jika belum ada, maka simpan data
            $q = "INSERT INTO galert_entry(entry_id, entry_title, entry_link, entry_published, entry_updated, entry_content, entry_author, feed_id) 
                VALUES('$entry_id', '$title', '$link', '$published', '$update', '$content', '$author', '$id')";
            // eksekusi query penyimpanan galert_entry 
            $result = $conn->query($q);
          }
        }
      }
    }

    if ($result) {
      echo "Data has been saved!";
    } else {
      echo "Gagal to save data!!";
      // why error stack trace
      // print on log file
      error_log($conn->error);
    }
  }
  ?>
  <style>
    body {
      font-family: 'Courier New', Courier, monospace;
    }
  </style>

  <table>
    <tr style="background-color: #ccc;">
      <th>no</th>
      <th>id</th>
      <th>title</th>
      <th>link</th>
      <th>publisher</th>
      <th>content</th>
      <th>author</th>
    <tr>

      <?php
      $a = 1;
      foreach ($xml as $row) {
      ?>
    <tr>
      <td><?php echo $a++; ?></td>
      <td><?php echo $row->id; ?></td>
      <td><?php echo $row->title; ?></td>
      <td><?php echo $row->link; ?></td>
      <td><?php echo $row->published; ?></td>
      <td><?php echo $row->content; ?></td>
      <td><?php echo $row->author; ?></td>
    <?php
      }
    ?>
  </table>

</body>

</html>