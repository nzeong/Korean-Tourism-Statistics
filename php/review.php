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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: black;
        }
    .create {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        gap: 10px;
        width: 60%;
        margin-bottom: 50px;
    }
    .create input{
        height: 30px;
    }
    .content {
        margin: 20px 0px;
        width: 60%;
    }
    .comment {
        width: 70%;
    }
    .comment textarea{
        width: 100%;
    }
    <style>
    /* Style for input fields */
    input[type="text"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box; /* Include padding and border in width */
    }

    /* Style for submit button */
    input[type="submit"] {
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 16px;
    }

    /* Style for submit button on hover */
    input[type="submit"]:hover {
        background-color: #2980b9;
    }

    /* Style for comment box */
    .comment textarea {
        height: 150px;
        resize: vertical; /* Allow vertical resizing */
    }

</style>
<body>
    <div>
        <h2><?php echo $country; ?> Review</h2>
    </div>

    <div class="content">
    
        <?php
            $sql = "SELECT nickname, comment
                    FROM review
                    WHERE review.country_name = '{$country}'";

            $result = mysqli_query($link, $sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<p>{$row["nickname"]}: {$row["comment"]}</p>";
                }
            } else {
                echo "<p>No reviews available</p>";
            }
        ?>
    </div>

    <form action="" method="post" class="create">
        <div>
            <input type="text" name="nickname" placeholder="Nickname">
        </div>

        <div class="comment">
            <textarea name="content" placeholder="Leave a comment"></textarea>
        </div>

        <div>
            <input type="submit" value="Submit"/>
        </div>
    </form>


    <?php
//트랜잭션 시작
mysqli_autocommit($link, false); // 수동 커밋 설정
mysqli_begin_transaction($link);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nickname = $_POST["nickname"];
    $comment = $_POST["content"];

    $insert_sql = "INSERT INTO review (nickname, comment, country_name) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $insert_sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $nickname, $comment, $country);

        if (mysqli_stmt_execute($stmt)) {
            // 트랜잭션 내에서 모든 쿼리가 성공했을 때 커밋
            mysqli_commit($link);
            echo "<p>리뷰가 성공적으로 등록되었습니다.</p>";
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "ERROR: Could not execute query: $insert_sql. " . mysqli_error($link);
            mysqli_rollback($link); // 실패 시 롤백
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "ERROR: Could not prepare query: $insert_sql. " . mysqli_error($link);
        mysqli_rollback($link); // 실패 시 롤백
    }

    mysqli_close($link);
    
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // 폼 제출 시
        $('.create').on('submit', function(event) {
            event.preventDefault(); // 기본 제출 방식 방지
            var form_data = $(this).serialize(); // 폼 데이터 직렬화
            $.ajax({
                url: "", // 현재 페이지로 요청
                method: "POST",
                data: form_data,
                success: function(data) {
                    $('.content').load(location.href + ' .content'); // 댓글창 업데이트
                    // 성공 메시지 등을 여기에 표시
                }
            });
        });
    });
</script>

</body>
</html>