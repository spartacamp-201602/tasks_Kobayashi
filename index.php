<?php

require_once('config.php');
require_once('functions.php');

$dbh = connectDb();

$sql = 'select * from tasks';
$stmt = $dbh->prepare($sql);
$stmt->execute();

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $title = $_POST['title'];

    $errors = array();
    if ($title == "")
    {
        $errors['title']= 'タスク名を入力してください。';
    }

    if (empty($errors))
    {
        $sql = 'insert into tasks (title, created_at, updated_at) ';
        $sql.= 'values (:title, now(), now())';

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":title", $title);
        $stmt->execute();
        //

        var_dump($stmt); exit;
        header('Location: index.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>タスク管理</title>
</head>
<body>
    <h1>タスク管理アプリ</h1>

    <form action="" method="post">
        <p>
            <input type="text" name="title">
            <input type="submit" value="追加">
        <span style="color:red;"><?php echo h($errors['title']) ?></span>
        <p>
            <span style="color:red;">
                <?php echo h($errors['title'])?>
                </span>
        </p>
    </form>

        </p>


    <h2>未完了タスク</h2>
    <ul>
        <?php foreach($tasks as $task): ?>
        <?php if ($task['status'] == 'notyet') : ?>
        <li>
        <a href="done.php?id=<?php echo h($task['id']); ?>">[完了]</a>
        <?php echo $task['title']?>
        <a href="edit.php?id=<?php echo h($task['id']); ?>">[編集]</a>
        <a href="delete.php?id=<?php echo h($task['id']); ?>">[削除]</a>

        </li>
    <?php endif ?>
    <?php endforeach ?>

    </ul>


    <hr>

    <h2>完了したタスク</h2>
    <ul>
        <?php foreach($tasks as $task):?>

            <?php if ($task['status']=='done'):?>
                <li><?php echo h($task['title']) ?></li>
            <?php endif ?>
        <?php endforeach?>

    </ul>


</body>