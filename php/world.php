<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$conn = mysqli_connect("localhost", "team15", "team15", "team15");

if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>

    <h1>Monthly entry data for foreign visitors in 2023</h1>
    <div class="button-container">
    <a href="ranking.php" class="ranking-button">
        <img src="trophy.png" alt="Ranking Icon"> 
        <span>Ranking</span> 
    </a>
    </div>

    <!-- 세계지도 이미지 맵 -->
    <div id="world-map-container">
        <img src="/worldMap.png" alt="World Map" id="world-map">
        <button class="continent-label" style="top: 40%; left: 70%;" data-continent="Asia">Asia</button>
        <button class="continent-label" style="top: 30%; left: 55%;" data-continent="Europe">Europe</button>
        <button class="continent-label" style="top: 50%; left: 20%;" data-continent="America">America</button>
        <button class="continent-label" style="top: 50%; left: 45%;" data-continent="Africa">Africa</button>
        <button class="continent-label" style="top: 75%; left: 80%;" data-continent="Oceania">Oceania</button>
        <button class="continent-label" style="top: 80%; left: 10%;" data-continent="Etc.">Etc.</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 대륙 버튼에 대한 초기 로딩 처리
            var continents = ['Asia', 'Europe', 'America', 'Africa', 'Oceania', 'Etc.'];
            continents.forEach(function (continent) {
                showContinentInfo(continent);
            });

            // 대륙 버튼에 클릭 이벤트 리스너 추가
            var continentButtons = document.querySelectorAll('.continent-label');
            continentButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var continent = button.getAttribute('data-continent');
                    goToCountryPage(continent);
                });
            });

            // 대륙 버튼 클릭 시 새 페이지로 이동하는 함수
            function goToCountryPage(continent) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        window.location.href = 'continent.php'; // 대륙 정보를 PHP 페이지로 전송하고 페이지 이동
                    }
                };
                xhr.open('GET', 'continent.php?continent=' + encodeURIComponent(continent), true);
                xhr.send();
            }


            // 대륙 정보 표시 함수
            function showContinentInfo(continent) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var percentage = parseFloat(this.responseText);
                        displayContinentInfo(continent, percentage);
                    }
                };
                xhr.open('GET', 'continentPercentage.php?continent=' + continent, true);
                xhr.send();
            }

            // 퍼센트 값 대륙 옆 위치 표시 함수
            function displayContinentInfo(continent, percentage) {
                var display = document.createElement('div');
                display.className = 'result-display';
                display.textContent = percentage.toFixed(2) + '%';

                var button = document.querySelector('.continent-label[data-continent="' + continent + '"]');
                var buttonRect = button.getBoundingClientRect();
                display.style.top = (buttonRect.top + window.scrollY) + 'px';
                display.style.left = (buttonRect.right + window.scrollX) + 'px';

                document.body.appendChild(display);
            }

        });
    </script>
</body>
</html>


