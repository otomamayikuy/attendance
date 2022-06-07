<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>出席確認</title>
    </head>
    <body>
        <?php
        $pdo = new PDO("mysql:dbname=test;localhost;", "root", "!08regulus04?");
        $pdo -> query('SET NAMES utf8;');
        $stmt = $pdo -> prepare(
            "SELECT name
            FROM menbers"
        );
        $stmt -> execute();
        $name = $stmt -> fetchAll();
        $id = count($name)+1;
        echo "あなたの会員番号は".$id."です。";
        ?>
        <form action="./register.php" method="post">
            名前を入力してください<input name="name"><br>
            所属を選んでください<br>
            <input type="radio" id="status1" name="status" value="1"><label for="status1">東大生</label>
            <input type="radio" id="status2" name="status" value="2"><label for="status2">大学生(東大以外)</label>
            <input type="radio" id="status3" name="status" value="3"><label for="status3">社会人</label>
            <input type="radio" id="status4" name="status" value="4"><label for="status4">高校生以下</label>
            <input type="radio" id="status5" name="status" value="5"><label for="status5">その他</label><br>
            プログラミング経験年数を選んでください<br>
            <input type="radio" id="experience1" name="experience" value="1"><label for="experience1">1年未満</label>
            <input type="radio" id="experience2" name="experience" value="2"><label for="experience2">1年</label>
            <input type="radio" id="experience3" name="experience" value="3"><label for="experience3">2〜3年</label>
            <input type="radio" id="experience4" name="experience" value="4"><label for="experience4">4〜5年</label>
            <input type="radio" id="experience5" name="experience" value="5"><label for="experience5">6〜9年</label>
            <input type="radio" id="experience6" name="experience" value="6"><label for="experience6">10年以上</label><br>
            一言コメント(任意)<input name="comment"><br>
            <input type="submit">
        </form>
    </body>
</html>