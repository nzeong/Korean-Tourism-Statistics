<?php
session_start();
if(isset($_GET['continent'])) {
    $_SESSION['selected_continent'] = $_GET['continent'];
}

$continent = $_SESSION['selected_continent']; 

$conn = mysqli_connect("localhost", "team15", "team15", "team15");

if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// 각 나라의 7월, 8월, 9월 총 방문객 수를 가져오는 쿼리
$sql = "SELECT country_name, (July + August + September - July_attendant - August_attendant - September_attendant) AS total_visitors
        FROM total_passenger
        WHERE country_name IN (SELECT country_name FROM country WHERE continent_name = '$continent')
        ORDER BY total_visitors DESC";
$result = mysqli_query($conn, $sql);

$countries = array();
while ($row = mysqli_fetch_assoc($result)) {
    $countries[] = array('country' => $row['country_name'], 'total_visitors' => $row['total_visitors']);
}

// 대륙별 7월, 8월, 9월 방한 여행객 합계 쿼리
$sqlContinentTotal = "SELECT SUM(July + August + September - July_attendant - August_attendant - September_attendant) AS total_visitors
                    FROM total_passenger
                    WHERE country_name IN (SELECT country_name FROM country WHERE continent_name = '$continent')";
$resultContinentTotal = mysqli_query($conn, $sqlContinentTotal);
$rowContinentTotal = mysqli_fetch_assoc($resultContinentTotal);
$totalContinent = $rowContinentTotal['total_visitors'];

// 전체 방한 관광객 수 가져오기
$sqlTotal = "SELECT SUM(July + August + September - July_attendant - August_attendant - September_attendant) AS total
            FROM total_passenger";
$resultTotal = mysqli_query($conn, $sqlTotal);
$rowTotal = mysqli_fetch_assoc($resultTotal);
$totalAll = $rowTotal['total'];

// 대륙별 백분율 계산
$percentage = ($totalContinent / $totalAll) * 100;

// 소수점 한 자리까지 출력
$percentageFormatted = number_format($percentage, 1);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $continent; ?> Countries</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .info-box {
            border: 1px solid black;
            padding: 10px;
            width: 300px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: black;
            margin-left: auto;
            margin-right: auto;
        }
</style>
<body>
    <h1><?php echo $continent; ?> Countries</h1>

    <div class="info-box">
    <p>Total Visitors for <?php echo $continent; ?>: <?php echo $totalContinent; ?></p>
    <p>Percentage of Total Visitors: <?php echo $percentageFormatted; ?>%</p>
    </div>

    <table id="countryTable">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Country</th>
                <th>Total Visitors</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($countries as $index => $country) : ?>
            <tr class="country-Row" data-country="<?php echo $country['country']; ?>">
                <td><?php echo $index + 1; ?></td>
                <td><?php echo $country['country']; ?></td>
                <td><?php echo $country['total_visitors']; ?></td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var countryRows = document.querySelectorAll('.country-Row');
            countryRows.forEach(function(row) {
                row.addEventListener('click', function() {
                    const countryName = row.dataset.country;
                    window.location.href = 'countryDetail.php?country=' + encodeURIComponent(countryName);
                });
            });
        });
    </script>
</body>
</html>


