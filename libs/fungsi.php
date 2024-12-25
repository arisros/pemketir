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

function getN($conn, $id_kat)
{
  $sql = "SELECT COUNT(*) FROM P_Data WHERE id_kategori = '$id_kat'";
  $result = $conn->query($sql);
  $row = $result->fetch_row();
  return $row[0];  // Mengembalikan hasil hitung jumlah baris
}

function getKosakata($conn)
{
  $sql = "SELECT COUNT(DISTINCT kata) FROM P_Data";
  $result = $conn->query($sql);
  $row = $result->fetch_row();
  return $row[0];  // Mengembalikan jumlah kata unik
}
function getJmlKata($conn, $kata, $id_kategori)
{
  $sql = "SELECT COUNT(*) AS jumlah FROM preprocessing WHERE id_kategori = '$id_kategori' AND data_bersih LIKE '% $kata %'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    $data = mysqli_fetch_assoc($result);
    return $data['jumlah'];
  } else {
    return 0; // Jika query gagal
  }
}

function getNilaiKategori($conn, $id_kategori)
{
  $sql = "SELECT nilai FROM P_Kategori WHERE id_kategori = '$id_kategori'";
  $result = mysqli_query($conn, $sql);
  $data = mysqli_fetch_array($result);
  return $data['nilai'];
}
