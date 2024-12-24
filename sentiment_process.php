<?php
$title = 'Labelisasi Data';
ob_start();
?>

<?php

include 'libs/koneksi.php';
include 'libs/fungsi.php';

// incluse button back
include 'utils/button_back.php';
// get data from database preprocessing table and update with sentiment analysis score negatie or possitive or neutral
$sql = "SELECT * FROM preprocessing";

$result = $conn->query($sql);
// then need update
$positiveWords = file('sentiment-dict/positive.txt', FILE_IGNORE_NEW_LINES);
$negativeWords = file('sentiment-dict/negative.txt', FILE_IGNORE_NEW_LINES);
while ($d = mysqli_fetch_array($result)) {

  $clean_data = $d['data_bersih'];
  $score = calculateSentimentScore($clean_data, $positiveWords, $negativeWords);

  if ($score > 0) {
    $sentiment = 'positive';
  } elseif ($score < 0) {
    $sentiment = 'negative';
  } else {
    $sentiment = 'neutral';
  }

  $id = $d['entry_id'];
  $sql = "UPDATE preprocessing SET sentiment_label='$sentiment' WHERE entry_id='$id'";
  $conn->query($sql);
}
?>

<h2>Hasil Sentiment Analysis</h2>

<!-- then i need show result after scoring -->
<table class="table table-bordered table-striped table-hover">
  <thead>
    <tr bgcolor="#CCC">
      <th style="width: 10%;">
        No.
      </th>
      <th>
        Data
      </th>
      <th style="width: 10%;">
        Sentiment
      </th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sql = "SELECT * FROM preprocessing";
    $result = $conn->query($sql);
    $i = 1;
    while ($d = mysqli_fetch_array($result)) {
    ?>
      <tr>
        <td>
          <?php echo $i; ?>
        </td>
        <td>
          <?php echo $d['data_bersih']; ?>
        </td>
        <td>
          <?php echo $d['sentiment_label']; ?>
        </td>
      </tr>
    <?php
      $i++;
    }
    ?>
  </tbody>

  <?php

  $content = ob_get_clean();
  include 'layout.php';
  ?>