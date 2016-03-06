<?php

require_once('config.php');
require_once('functions.php');

// 受け取ったレコードのID
$id = $_GET['id'];

// データベースへの接続
$dbh = connectDb();

// SQLの準備と実行
$sql = "select * from tasks where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();

// 結果の取得
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// タスクの編集
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 受け取ったデータ
    $title = $_POST['title'];

    // エラーチェック用の配列
    $errors = array();

    // バリデーション
    if ($title == '') {
        $errors['title'] = 'タスク名を入力してください';
    }

    if ($title == $post['title']) {
        $errors['title'] = 'タスク名が変更されていません';
    }

    // エラーが1つもなければレコードを更新
    if (empty($errors)) {
        $dbh = connectDb();

        $sql = "update tasks set title = :title, updated_at = now() where id = :id";

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        header('Location: index.php');
        exit;
    }
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>タスクの編集</title>
</head>
<body>
<h1>タスクの編集</h1>
<form action="" method="POST">
    <p><input type="text" name="title">
        <input type="submit" value="編集"></p>
        <span style="color:red;"><?php echo h($errors['title']) ?></span>
</form>

<p><?php echo $task; ?></p>

</body>
</html>