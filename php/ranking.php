<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Rankings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Ranking of Tourist Arrivals in the 3rd Quarter of 2023</h1>
    <?php
        // MySQL 연결
        $conn = mysqli_connect("localhost", "team15", "team15", "team15");

        // 방문자 수가 가장 많은 나라 순으로 조회하는 SQL 쿼리
        $sql = "SELECT c.country_name, c.continent_name,
        (tp.July + tp.August + tp.September - tp.July_attendant - tp.August_attendant - tp.September_attendant) AS total_visitors,
        ((tp.July + tp.August + tp.September - tp.July_attendant - tp.August_attendant - tp.September_attendant) /
        (SELECT SUM(tp2.July + tp2.August + tp2.September - tp2.July_attendant - tp2.August_attendant - tp2.September_attendant) FROM total_passenger tp2)) * 100 AS visitor_percentage
        FROM total_passenger tp
        JOIN country c ON tp.country_name = c.country_name
        ORDER BY total_visitors DESC";

        $result = mysqli_query($conn, $sql);

        // 랭킹 테이블 생성
        echo '<table>';
        echo '<tr><th>Rank</th><th>Country</th><th>Continent</th><th>Total Visitors</th><th>Visitor Percentage (%)</th></tr>';

        $resultArray = []; // 수정된 부분: $resultArray를 초기화

        $rank = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr class="country-row" data-country="' . $row['country_name'] . '">';
            echo '<td>' . $rank . '</td>';
            echo '<td>' . $row['country_name'] . '</td>';
            echo '<td>' . $row['continent_name'] . '</td>';
            echo '<td>' . $row['total_visitors'] . '</td>';
            echo '<td>' . number_format($row['visitor_percentage'], 2) . '</td>';
            echo '</tr>';

            // 수정된 부분: $resultArray에 데이터 추가
            $resultArray[] = [
                'country_name' => $row['country_name'],
                'total_visitors' => $row['total_visitors'],
            ];

            $rank++;
        }
        echo '</table>';
    ?>
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countryRows = document.querySelectorAll('.country-row');
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
