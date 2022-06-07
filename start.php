<?php
$pdo = new PDO("mysql:dbname=test;localhost;", "root", "!08regulus04?");
$pdo -> query('SET NAMES utf8;');
if($_POST["name"] && $_POST["number"]){
    $name = $_POST["name"];
    $id = $_POST["number"];
    $stmt1 = $pdo -> prepare(
        'SELECT *
        FROM menbers
        WHERE name="'.$name.'" AND id="'.$id.'";'
    );
    $stmt1 -> execute();
    $menber = $stmt1 -> fetchAll();
    if(count($menber) === 0){
        echo "<script type='text/javascript'>
        alert('IDと名前が間違っています');
        </script>";
    }else{
        $stmt2 = $pdo -> prepare(
            'UPDATE menbers
            SET attendance_id=1
            WHERE name="'.$name.'" AND id="'.$id.'";'
        );
        $stmt2 -> execute();
        include("attendance.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>出席確認</title>
    </head>
    <body>
        <h1>出席確認</h1>
        <form method="post">
            会員番号を入力してください(半角数字)<input name="number"><br>
            名前を入力してください<input name="name"><br>
            <button>ログイン</button>
        </form>
        <form action="./first.php">
            <button>初めての方はこちら</button>
        </form>
    </body>
</html>