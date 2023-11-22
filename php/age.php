<?php
session_start();
$link = mysqli_connect("localhost", "team15", "team15", "team15");

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// 세션에서 선택한 국가 이름 가져오기
if (isset($_SESSION['selected_country'])) {
    $country = $_SESSION['selected_country'];

    // 이하 쿼리와 HTML 부분은 이전 코드와 동일합니다.
} else {
    // 세션에서 국가 정보가 없는 경우에 대한 처리
    echo "국가 정보를 받아오지 못했습니다.";
}

$sql = "SELECT SUM(under_twenty) AS totalUnderTwenty, SUM(under_thirty) AS totalUnderThirty, SUM(under_fourty) AS totalUnderFourty, SUM(under_fifty) AS totalUnderFifty, SUM(under_sixty) AS totalUnderSixty, SUM(over_sixty) AS totalOverSixty
        FROM (SELECT * FROM age_august
        UNION ALL
        SELECT * FROM age_july
        UNION ALL
        SELECT * FROM age_september) AS TOTAL
        GROUP BY TOTAL.country_name
        HAVING TOTAL.country_name = '{$country}'";

$result = mysqli_query($link, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalUnderTwenty = $row['totalUnderTwenty'];
    $totalUnderThirty = $row['totalUnderThirty'];
    $totalUnderFourty = $row['totalUnderFourty'];
    $totalUnderFifty = $row['totalUnderFifty'];
    $totalUnderSixty = $row['totalUnderSixty'];
    $totalOverSixty = $row['totalOverSixty'];
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
    <title>Country Age Data</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: black;
        }

        .content{
            width: 70%;
        }
    </style>
</head>
<body>
    <div>
        <h2><?php echo $country; ?> Visitor Age Analysis</h2>
    </div>

    <div class="content">
        <p>During the 3rd quarter of 2023, the total number of visitors in <?php echo $country; ?> was 
        <?php echo $totalUnderTwenty; ?> under 20, <?php echo $totalUnderThirty; ?> under 30,
        <?php echo $totalUnderFourty; ?> under 40, <?php echo $totalUnderFifty; ?> under 50,
        <?php echo $totalUnderSixty; ?> under 60, and <?php echo $totalOverSixty; ?> over 60,
        totaling <?php echo ($totalUnderTwenty + $totalUnderThirty + $totalUnderFourty + $totalUnderFifty + $totalUnderSixty + $totalOverSixty); ?> visitors.</p>
    </div>
    <br>
    <div>
        <button>
            <a href="age-detail.php?country=<?php echo $country; ?>&month=july">July</a>
        </button>

        <button>
            <a href="age-detail.php?country=<?php echo $country; ?>&month=august">August</a>
        </button>

        <button>
            <a href="age-detail.php?country=<?php echo $country; ?>&month=september">September</a>
        </button>
    </div>
</body>
</html>
