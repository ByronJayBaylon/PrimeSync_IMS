<?php
include 'db_connector.php';
date_default_timezone_set('Asia/Manila');

$type = $_GET['type'];
$sales = [];
$total = 0;

switch ($type) {
    case 'today':
        $date = date('Y-m-d');
        $sql = "SELECT * FROM sales WHERE DATE(date) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $date);
        break;
    case 'week':
        $startDate = date('Y-m-d', strtotime('last Sunday'));
        $endDate = date('Y-m-d', strtotime('next Saturday'));
        $sql = "SELECT * FROM sales WHERE DATE(date) BETWEEN ? AND ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $startDate, $endDate);
        break;
    case 'month':
        $month = date('m');
        $year = date('Y');
        $sql = "SELECT * FROM sales WHERE MONTH(date) = ? AND YEAR(date) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $month, $year);
        break;
    case 'year':
        $year = date('Y');
        $sql = "SELECT * FROM sales WHERE YEAR(date) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $year);
        break;
    case 'all':
        $sql = "SELECT * FROM sales";
        $stmt = $conn->prepare($sql);
        break;
}

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $sales[] = $row;
    $total += $row['sub_total'];
}
$stmt->close();
$conn->close();

echo json_encode(['sales' => $sales, 'total' => $total]);
?>
