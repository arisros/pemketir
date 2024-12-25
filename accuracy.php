<?php
$title = 'Form Data Uji';
ob_start();

require_once 'utils/button_back.php';
require_once "libs/koneksi.php";

// Step 1: Calculate Accuracy
$sql_accuracy = "SELECT COUNT(*) as total, 
                        SUM(CASE WHEN id_actual = id_predicted THEN 1 ELSE 0 END) as correct 
                 FROM classify";
$result_accuracy = mysqli_query($conn, $sql_accuracy);
$row = mysqli_fetch_assoc($result_accuracy);
$total = $row['total'];
$correct = $row['correct'];

$accuracy = ($correct / $total) * 100;

echo "<h3>Accuracy: " . round($accuracy, 2) . "%</h3>";

// Step 2: Generate Confusion Matrix
$sql_confusion_matrix = "
SELECT 
    id_actual, 
    id_predicted, 
    COUNT(*) as count
FROM classify
GROUP BY id_actual, id_predicted";

$result_confusion_matrix = mysqli_query($conn, $sql_confusion_matrix);

// Initialize an array for the confusion matrix
$confusion_matrix = [
  'Politik' => ['Politik' => 0, 'Teknologi' => 0, 'Ekonomi' => 0],
  'Teknologi' => ['Politik' => 0, 'Teknologi' => 0, 'Ekonomi' => 0],
  'Ekonomi' => ['Politik' => 0, 'Teknologi' => 0, 'Ekonomi' => 0]
];

// Populate the confusion matrix array with the query results
while ($row = mysqli_fetch_assoc($result_confusion_matrix)) {
  $actual = $row['id_actual'];
  $predicted = $row['id_predicted'];
  $count = $row['count'];

  // Ensure the count is inserted into the correct position
  // Adjust the names of 'Politik', 'Teknologi', 'Ekonomi' for matching actual and predicted values
  $actual_label = '';
  $predicted_label = '';

  switch ($actual) {
    case 3:
      $actual_label = 'Politik';
      break;
    case 2:
      $actual_label = 'Ekonomi';
      break;
    case 1:
      $actual_label = 'Teknologi';
      break;
  }

  switch ($predicted) {
    case 3:
      $predicted_label = 'Politik';
      break;
    case 2:
      $predicted_label = 'Ekonomi';
      break;
    case 1:
      $predicted_label = 'Teknologi';
      break;
  }

  // Update the confusion matrix with the correct count
  if ($actual_label && $predicted_label) {
    $confusion_matrix[$actual_label][$predicted_label] = $count;
  }
}

// Display the confusion matrix in a table format
echo "<h3>Confusion Matrix</h3>";
echo "<table class='table table-bordered table-striped'>";
echo "<thead class='table-warning'>
        <tr>
            <th>Actual/Predicted</th>
            <th>Politik</th>
            <th>Teknologi</th>
            <th>Ekonomi</th>
        </tr>
      </thead><tbody>";

foreach ($confusion_matrix as $actual => $predictions) {
  echo "<tr><td class='table-warning'><strong>$actual</strong></td>";
  foreach ($predictions as $predicted => $count) {
    echo "<td>$count</td>";
  }
  echo "</tr>";
}

echo "</tbody></table>";

$content = ob_get_clean();
include 'layout.php';
