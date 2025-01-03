<?php
$title = 'Load Data';
ob_start();

include_once 'libs/koneksi.php';
include_once 'libs/xml_to_array.php';

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
        $title = str_replace('\'', '', $record->title);
        $link = $record->link;
        $published = $record->published;
        $update = $record->update;
        $content = str_replace('\'', '', $record->content);
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
?>
  <section class="info">
    <?php
    if ($result) {
    ?>
      <h1>Data berhasil disimpan!</h1>
    <?php
    } else {
    ?>
      <h1 class="error-text">Data gagal disimpan!</h1>
  <?php
      // why error stack trace
      // print on log file
      error_log($conn->error);
    }
  }
  ?>
  <?php include 'utils/button_back.php'; ?>
  </section>

  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="table-warning">
        <tr>
          <th style="width: 5%;">No</th>
          <th class="table-id-column">ID</th>
          <th>Title</th>
          <th>Link</th>
          <th>Publisher</th>
          <th>Content</th>
          <th>Author</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $a = 1;
        foreach ($xml as $row) {
        ?>
          <tr>
            <td style="width: 5%;"><?php echo $a++; ?></td>
            <td class="table-id-column">
              <span class="table-id-column"><?php echo htmlspecialchars($row->id); ?></span>
            </td>
            <td><?php echo htmlspecialchars($row->title); ?></td>
            <td><a href="<?php echo htmlspecialchars($row->link); ?>" target="_blank">Visit</a></td>
            <td><?php echo htmlspecialchars($row->published); ?></td>
            <td><?php echo htmlspecialchars($row->content); ?></td>
            <td><?php echo htmlspecialchars($row->author); ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>

  <?php
  $content = ob_get_clean();

  include 'layout.php';
