<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>出席確認</title>
    </head>
    <body>
        <?php
        $name = $_POST["name"];
        $status = $_POST["status"];
        $experience = $_POST["experience"];
        $comment = $_POST["comment"];
        if(!$name||!$status||!$experience){
            header("Location:./first.php");
            exit();
        }
        $pdo = new PDO("mysql:dbname=test;localhost;", "root", "!08regulus04?");
        $pdo -> query('SET NAMES utf8;');
        $stmt1 = $pdo -> prepare(
            "SELECT name
            FROM menbers"
        );
        $stmt1 -> execute();
        $names = $stmt1 -> fetchAll();
        $id = count($names)+1;

        if(isset($_POST["button"])){
            $stmt2 = $pdo -> prepare(
                'INSERT INTO menbers
                VALUES ("'.$id.'","'.$name.'",0,"'.$status.'","'.$experience.'","'.$comment.'");'
            );
            $stmt2 -> execute();
            header("Location:./start.php");
            exit();
        }
        $stmt3 = $pdo -> prepare(
            'SELECT experience
            FROM experiences
            WHERE id="'.$experience.'";'
        );
        $stmt3 -> execute();
        $exp = $stmt3 -> fetch();

        $stmt4 = $pdo -> prepare(
            'SELECT status
            FROM status
            WHERE id="'.$status.'";'
        );
        $stmt4 -> execute();
        $sta = $stmt4 -> fetch();
        ?>
        <p>こちらの内容でよろしいですか。</p>
        <form method="post">
            名前：<?= $name ?><input type="hidden" name="name" value=<?= $name ?>><br>
            所属：<?= $sta[0] ?><input type="hidden" name="status" value=<?= $status ?>><br>
            経験年数：<?= $exp[0] ?><input type="hidden" name="experience" value=<?= $experience ?>><br>
            コメント：<?= $comment ?><input type="hidden" name="comment" value=<?= $comment ?>><br>
            <button name="button">はい</button>
        </form>
        <form action="./first.php" method="post">
            <button>いいえ</button>
        </form>
    </body>
</html>