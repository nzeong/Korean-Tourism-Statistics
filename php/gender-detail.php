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

$gender_sql = "SELECT male, female
                FROM gender_{$month}
                WHERE country_name = '{$country}';";

$gender_sql_result = mysqli_query($link, $gender_sql);

if ($gender_sql_result) {
    $row = mysqli_fetch_assoc($gender_sql_result);
    $gender_male = $row['male'];
    $gender_female = $row['female'];
} else {
    die("ERROR: Query failed. " . mysqli_error($link));
}

$growth_sql = "SELECT male, female
                FROM growth_{$month}
                WHERE country_name = '{$country}';";

$growth_sql_result = mysqli_query($link, $growth_sql);

if ($growth_sql_result) {
    $row = mysqli_fetch_assoc($growth_sql_result);
    $growth_male = $row['male'];
    $growth_female = $row['female'];
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
    <title>Gender Detail</title>
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
    <h2><?php echo $country; ?> <?php echo $month; ?> Visitor Gender Analysis</h2>
    </div>

    <div>
        <p>During the 3rd quarter of 2023, the total number of <?php echo $month; ?> visitors was <?php echo $gender_male + $gender_female; ?>.</p>
        <p>There were <?php echo $gender_female; ?> females and <?php echo $gender_male; ?> males.</p>
        <p>The growth rate compared to the previous year was <?php echo $growth_female; ?>% for females and <?php echo $growth_male; ?>% for males.</p>
    </div>
</body>
</html>











