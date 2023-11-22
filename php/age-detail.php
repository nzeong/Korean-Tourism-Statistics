<?php
session_start();
$link = mysqli_connect("localhost", "team15", "team15", "team15");

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if (isset($_SESSION['selected_country']) && isset($_GET['month'])) {
    $country = $_SESSION['selected_country'];
    $month = $_GET['month'];
} else {
    $error_message = "국가 이름 혹은 달을 받아오지 못했습니다.";
    echo $error_message;
}

$age_sql = "SELECT under_twenty, under_thirty, under_fourty, under_fifty, under_sixty, over_sixty
            FROM age_{$month}
            WHERE country_name = '{$country}';";

$age_sql_result = mysqli_query($link, $age_sql);

if ($age_sql_result) {
    $row = mysqli_fetch_assoc($age_sql_result);
    $age_under_twenty = $row['under_twenty'];
    $age_under_thirty = $row['under_thirty'];
    $age_under_fourty = $row['under_fourty'];
    $age_under_fifty = $row['under_fifty'];
    $age_under_sixty = $row['under_sixty'];
    $age_over_sixty = $row['over_sixty'];
} else {
    die("ERROR: Query failed. " . mysqli_error($link));
}

$growth_sql = "SELECT under_twenty, under_thirty, under_fourty, under_fifty, under_sixty, over_sixty
                FROM growth_{$month}
                WHERE country_name = '{$country}';";

$growth_sql_result = mysqli_query($link, $growth_sql);

if ($growth_sql_result) {
    $row = mysqli_fetch_assoc($growth_sql_result);
    $growth_under_twenty = $row['under_twenty'];
    $growth_under_thirty = $row['under_thirty'];
    $growth_under_fourty = $row['under_fourty'];
    $growth_under_fifty = $row['under_fifty'];
    $growth_under_sixty = $row['under_sixty'];
    $growth_over_sixty = $row['over_sixty'];
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
    <title>Age Detail</title>
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
        <h2><?php echo $country; ?> Visitor Age Analysis in <?php echo $month; ?></h2>
    </div>

    <div class="content">
        <p>During the 3rd quarter of 2023, the total number of visitors in <?php echo $month; ?> was 
        <?php echo ($age_under_twenty + $age_under_thirty + $age_under_fourty + $age_under_fifty + $age_under_sixty + $age_over_sixty); ?> individuals.</p>
        <p>There were <?php echo $age_under_twenty; ?> under 20, <?php echo $age_under_thirty; ?> under 30,
        <?php echo $age_under_fourty; ?> under 40, <?php echo $age_under_fifty; ?> under 50,
        <?php echo $age_under_sixty; ?> under 60, and <?php echo $age_over_sixty; ?> over 60.</p>
        <p>The growth rate compared to the previous year was 
        <?php echo $growth_under_twenty; ?>% for under 20, <?php echo $growth_under_thirty; ?>% for under 30,
        <?php echo $growth_under_fourty; ?>% for under 40, <?php echo $growth_under_fifty; ?>% for under 50,
        <?php echo $growth_under_sixty; ?>% for under 60, and <?php echo $growth_over_sixty; ?>% for over 60.</p>
    </div>
</body>
</html>

