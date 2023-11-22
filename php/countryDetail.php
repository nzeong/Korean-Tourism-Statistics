<?php
$conn = mysqli_connect("localhost", "team15", "team15", "team15");

// URL 매개변수에서 나라 이름 가져오기
session_start();
if(isset($_GET['country'])) {
    $_SESSION['selected_country'] = $_GET['country'];
    // 선택한 국가를 세션에 저장
}else {
    // country_name 값이 없는 경우 오류 메시지를 화면에 출력
    $error_message = "국가 이름을 받아오지 못했습니다.";
    echo $error_message;
}
$country = $_SESSION['selected_country'];
// 나라의 7월, 8월, 9월 총 방문객 수 및 3분기 총 방문객 수 가져오는 쿼리
$sql = "SELECT July - July_attendant AS July,
               August - August_attendant AS August,
               September - September_attendant AS September,
               (July - July_attendant + August - August_attendant + September - September_attendant) AS total_quarter_visitors
        FROM total_passenger
        WHERE country_name = '$country'";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);
$visitors = array(
    'July' => $row['July'],
    'August' => $row['August'],
    'September' => $row['September'],
    'Total' => $row['total_quarter_visitors']
);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $country; ?> Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
        /* 버튼 스타일 */
        .btn-container {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center; 
        }

        .btn {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        /* 표 스타일 */
        table {
            border-collapse: collapse;
            
            margin: 20px auto;
        }

        th, td {
            border: 1px solid;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #27292C;
        }

    </style>
<body>
    <h1><?php echo $country; ?> Details</h1>

    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Total Visitors</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($visitors as $key => $value) : ?>
                <tr>
                    <td><?php echo ucfirst($key); ?></td>
                    <td><?php echo $value; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="btn-container">
        <!-- 추가된 버튼 -->
        <button class="btn" id="genderBtn">Show Gender Stats</button>
        <button class="btn" id="ageBtn">Show Age Stats</button>
        <button class="btn" id="reviewBtn">Show Review Stats</button>
    </div>

    <script>

    document.getElementById('genderBtn').addEventListener('click', function() {
        // 성별에 대한 정보를 가져오는 함수 호출
        // gender.php로 이동
        redirectToPage('gender.php', '<?php echo $country; ?>');
    });

    document.getElementById('ageBtn').addEventListener('click', function() {
        // 연령에 대한 정보를 가져오는 함수 호출
        // age.php로 이동
        redirectToPage('age.php', '<?php echo $country; ?>');
    });

    document.getElementById('reviewBtn').addEventListener('click', function() {
        // 리뷰 카테고리에 대한 정보를 가져오는 함수 호출
        // review.php로 이동
        redirectToPage('review.php', '<?php echo $country; ?>');
    });

    // 페이지로 이동하는 함수
    function redirectToPage(page, country) {
    
        // URL 생성
        var url = page + '?country=' + encodeURIComponent(country);
        // 페이지로 이동
        window.location.href = url;
    }
</script>

</body>
</html>
