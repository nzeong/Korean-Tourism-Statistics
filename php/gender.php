<?php
session_start();
$link = mysqli_connect("localhost", "team15", "team15", "team15");

// 세션에서 선택한 국가 이름 가져오기
if (isset($_SESSION['selected_country'])) {
    $country = $_SESSION['selected_country'];

    // 이하 쿼리와 HTML 부분은 이전 코드와 동일합니다.
} else {
    // 세션에서 국가 정보가 없는 경우에 대한 처리
    echo "국가 정보를 받아오지 못했습니다.";
}


$sql = "SELECT SUM(male) AS totalMale, SUM(female) AS totalFemale
        FROM (SELECT * FROM gender_august
        UNION ALL
        SELECT * FROM gender_july
        UNION ALL
        SELECT * FROM gender_september) AS TOTAL
        GROUP BY TOTAL.country_name
        HAVING TOTAL.country_name = '{$country}'";

$result = mysqli_query($link, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalMale = $row['totalMale'];
    $totalFemale = $row['totalFemale'];
} else {
    die("ERROR: Query failed. " . mysqli_error($link));
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gender</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: black;
        }
    </style>
</head>
<body>
    <div>
        <h2><?php echo $country; ?> Visitor Gender Analysis</h2>
    </div>

    <div>
    <p>In the 3rd quarter of 2023, the total number of visitors in <?php echo $country; ?> was <?php echo $totalFemale; ?> females and <?php echo $totalMale; ?> males, totaling <?php echo ($totalFemale + $totalMale); ?> visitors.</p>
    </div>
    <br>
    <div>
        <button>
            <a href="gender-detail.php?country=<?php echo $country; ?>&month=july">July</a>
        </button>

        <button>
            <a href="gender-detail.php?country=<?php echo $country; ?>&month=august">August</a>
        </button>

        <button>
            <a href="gender-detail.php?country=<?php echo $country; ?>&month=september">September</a>
        </button>
    </div>
</body>
</html>