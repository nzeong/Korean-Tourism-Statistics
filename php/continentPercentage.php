<?php

$conn = mysqli_connect("localhost", "team15", "team15", "team15");

    // 대륙 이름 가져오기
    if (isset($_GET['continent'])) {
        $continent = $_GET['continent'];
       
    } else {
        // continent 값이 없는 경우 오류 메시지를 화면에 출력
        $error_message = "대륙 이름을 받아오지 못했습니다.";
        echo $error_message;
   }

    // 대륙별 7월, 8월, 9월 방한 여행객 합계 쿼리
   $sql = "SELECT SUM(July + August + September - July_attendant - August_attendant - September_attendant) AS total_visitors
   FROM total_passenger
   WHERE country_name IN (SELECT country_name FROM country WHERE continent_name = '$continent')";


    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);
    $total = $row['total_visitors'];

    // 전체 방한 관광객 수 가져오기
    $sqlTotal = "SELECT SUM(July + August + September - July_attendant - August_attendant - September_attendant) AS total
                 FROM total_passenger";
    $resultTotal = mysqli_query($conn, $sqlTotal);
    $rowTotal = mysqli_fetch_assoc($resultTotal);
    $totalAll = $rowTotal['total'];

    // 대륙별 백분율 계산
    $percentage = ($total / $totalAll) * 100;
    

    // 소수점 한 자리까지 출력
    $percentageFormatted = number_format($percentage, 1);

    // 결과 출력
    echo "$percentageFormatted";


    mysqli_close($conn);
?>