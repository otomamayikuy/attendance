<?php
$pdo = new PDO("mysql:dbname=test;localhost;", "root", "!08regulus04?");
$pdo -> query('SET NAMES utf8;');
if(isset($_POST["number"]) || isset($_POST["name"])){
    ;
}else{
    header("Location:./start.php");
    exit();
}
$id = $_POST["number"];
$name = $_POST["name"];
$state = "none";
$state1 = "none";
$state2 = "none";
$states = [];
$stmt5 = $pdo -> prepare(
    'SELECT name, status_id, experience_id, comment
    FROM menbers
    WHERE name="'.$name.'" AND id="'.$id.'";'
);
$stmt5 -> execute();
$profile = $stmt5 -> fetch();
for($i = 0; $i < count($profile); $i++){
    $states[$i] = "none";
}
$stmt6 = $pdo -> prepare(
    'SELECT status
    FROM status
    WHERE id="'.$profile[1].'";'
);
$stmt6 -> execute();
$status = $stmt6 -> fetch();
$stmt7 = $pdo -> prepare(
    'SELECT experience
    FROM experiences
    WHERE id="'.$profile[2].'";'
);
$stmt7 -> execute();
$experience = $stmt7 -> fetch();
$comment = $profile[3];
if($comment === ""){
    $comment = "なし";
}
if(isset($_POST["attend"])){
    $stmt2 = $pdo -> prepare(
        'UPDATE menbers
        SET attendance_id=1
        WHERE name="'.$name.'" AND id="'.$id.'";'
    );
    $stmt2 -> execute();
}
if(isset($_POST["exit"])){
    $stmt3 = $pdo -> prepare(
        'UPDATE menbers
        SET attendance_id=0
        WHERE name="'.$name.'" AND id="'.$id.'";'
    );
    $stmt3 -> execute();
}

if(isset($_POST["logout"])){
    $stmt4 = $pdo -> prepare(
        'UPDATE menbers
        SET attendance_id=0
        WHERE name="'.$name.'" AND id="'.$id.'";'
    );
    $stmt4 -> execute();
    $_POST["name"]=NULL;
    $_POST["number"]=NULL;
    include("start.php");
    exit();
}

if(isset($_POST["edit"])){
    $state = "block";
    $state1 = "block";
}

if(isset($_POST["submit"])){
    $new_name = $_POST["new_name"];
    $new_status = $_POST["status"];
    $new_experience = $_POST["experience"];
    $new_comment = $_POST["comment"];
    if(!(!$new_name || !$new_status || !$new_experience)){
        $stmt8 = $pdo -> prepare(
            'UPDATE menbers
            SET name="'.$new_name.'", status_id="'.$new_status.'", experience_id="'.$new_experience.'", comment="'.$new_comment.'"
            WHERE name="'.$name.'" AND id="'.$id.'";'
        );
        $stmt8 -> execute();
        $state1 = "none";
        $state = "block";
        $state2 = "block";
    }
}

if(isset($_POST["look"])){
    $look_id = $_POST["look"];
    $state_number = $_POST["state_number"];
    $stmt11 = $pdo -> prepare(
        'SELECT name, status_id, experience_id, comment
        FROM menbers
        WHERE id="'.$look_id.'";'
    );
    $stmt11 -> execute();
    $look_profile = $stmt11 -> fetch();
    $stmt6 = $pdo -> prepare(
        'SELECT status
        FROM status
        WHERE id="'.$look_profile[1].'";'
    );
    $stmt6 -> execute();
    $look_status = $stmt6 -> fetch();
    $stmt7 = $pdo -> prepare(
        'SELECT experience
        FROM experiences
        WHERE id="'.$look_profile[2].'";'
    );
    $stmt7 -> execute();
    $look_experience = $stmt7 -> fetch();
    $states[$look_id] = $_POST["state"];
    if($states[$look_id] === "none"){
        $states[$look_id] = "block";
    }else{
        $states[$look_id] = "none";
    }
    if($look_profile[3] === ""){
        $look_profile[3] = "なし";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>出席確認</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div id="main">
            <h1>出席確認</h1>
            <div id="text">
                <h3>あなたの情報</h3>
                <p>会員番号：<?= $id ?></p>
                <p>名前：<?= $name ?></p>
                <p>所属：<?= $status[0] ?></p>
                <p>経験年数：<?= $experience[0] ?></p>
                <p>コメント：<?= $comment ?></p>
            </div>
            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                <button name="attend">出席する</button>
                <button name="exit">退出する</button>
                <button name="logout">ログアウト</button>
                <button name="edit">プロフィールの編集</button>
                <input type="hidden" name="name" value=<?= $name ?>>
                <input type="hidden" name="number" value=<?= $id ?>>
            </form>
            <h2>出席者</h2>
            <ul id="attend_list">
                <?php
                $stmt1 = $pdo -> prepare(
                    "SELECT id, name
                    FROM menbers
                    WHERE attendance_id=1"
                );
                $stmt1 -> execute();
                $names = $stmt1 -> fetchAll();
                for($i = 0; $i < count($names); $i++){
                    $info = $names[$i][0];
                    echo "<li><form method='post' action='./attendance.php' name='form".$i."'>";
                    echo "<a href=javascript:form".$i.".submit()>";
                    echo $names[$i][1];
                    echo '<input type="hidden" name="look" value="'.$info.'">';
                    echo '<input type="hidden" name="name" value="'.$name.'">';
                    echo '<input type="hidden" name="number" value="'.$id.'">';
                    echo '<input type="hidden" name="state" value="'.$states[$info].'">';
                    echo "</a></form>";
                    echo "<div style='display:".$states[$info]."'>";
                    echo '<p>所属：'.$look_status[0].'</p>';
                    echo '<p>経験年数：'.$look_experience[0].'</p>';
                    echo '<p>コメント：'.$look_profile[3].'</p>';
                    echo "</div>";
                }
                ?>
            </ul>
        </div>
        <div id="popup" style="display:<?= $state1 ?>">
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                名前を入力してください<input name="new_name" value=<?= $name ?>><br>
                所属を選んでください<br>
                <input type="radio" id="status1" name="status" value="1" <?= $profile[1]===1 ? "checked" : "";?>><label for="status1">東大生</label>
                <input type="radio" id="status2" name="status" value="2" <?= $profile[1]===2 ? "checked" : "";?>><label for="status2">大学生(東大以外)</label>
                <input type="radio" id="status3" name="status" value="3" <?= $profile[1]===3 ? "checked" : "";?>><label for="status3">社会人</label>
                <input type="radio" id="status4" name="status" value="4" <?= $profile[1]===4 ? "checked" : "";?>><label for="status4">高校生以下</label>
                <input type="radio" id="status5" name="status" value="5" <?= $profile[1]===5 ? "checked" : "";?>><label for="status5">その他</label><br>
                プログラミング経験年数を選んでください<br>
                <input type="radio" id="experience1" name="experience" value="1" <?= $profile[2]===1 ? "checked" : "";?>><label for="experience1">1年未満</label>
                <input type="radio" id="experience2" name="experience" value="2" <?= $profile[2]===2 ? "checked" : "";?>><label for="experience2">1年</label>
                <input type="radio" id="experience3" name="experience" value="3" <?= $profile[2]===3 ? "checked" : "";?>><label for="experience3">2〜3年</label>
                <input type="radio" id="experience4" name="experience" value="4" <?= $profile[2]===4 ? "checked" : "";?>><label for="experience4">4〜5年</label>
                <input type="radio" id="experience5" name="experience" value="5" <?= $profile[2]===5 ? "checked" : "";?>><label for="experience5">6〜9年</label>
                <input type="radio" id="experience6" name="experience" value="6" <?= $profile[2]===6 ? "checked" : "";?>><label for="experience6">10年以上</label><br>
                一言コメント(任意)<input name="comment" value=<?= $profile[3] ?>><br>
                <input type="hidden" name="name" value=<?= $name ?>>
                <input type="hidden" name="number" value=<?= $id ?>>
                <input type="submit" name="submit">
            </form>
        </div>
        <div id="check" style="display:<?= $state2 ?>">
            <form action="./attendance.php" method="post">
                プロフィールを変更します。
                <input type="hidden" name="name" value=<?= $new_name ?>>
                <input type="hidden" name="number" value=<?= $id ?>>
                <input type="submit" value="はい">
            </form>
        </div>
        <div id="block" style="display:<?= $state ?>"></div>
    </body>
</html>