<?php

function getJum($conn, $sql)
{
  $rs = $conn->query($sql);
  $total = $rs->num_rows;
  $rs->free();
  return $total;
}

function getJmlKat($conn, $id_kat)
{
  $sql = "SELECT * FROM preprocessing where id_kategori='$id_kat'";
  $jum = getJum($conn, $sql);
  return $jum;
}

function getJmlALl($conn)
{
  $sql = "SELECT * from preprocessing where id_kategori is not null";
  $jum = getJum($conn, $sql);
  return $jum;
}

function getKat($conn, $id_kat)
{
  $sql = "SELECT nm_kategori from kategori where id_kategori='$id_kat'";
  $rs = $conn->query($sql);
  $d = mysqli_fetch_row($rs);
  return $d[0];
}


// Fungsi untuk menghitung skor sentimen
function calculateSentimentScore($text, $positiveWords, $negativeWords)
{
  $words = explode(' ', strtolower($text));
  $score = 0;

  foreach ($words as $word) {
    if (in_array($word, $positiveWords)) {
      $score += 1;
    } elseif (in_array($word, $negativeWords)) {
      $score -= 1;
    }
  }

  return $score;
}
