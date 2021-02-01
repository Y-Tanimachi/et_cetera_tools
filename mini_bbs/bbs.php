<?php
//ファイルを設定
$filename = './bbs_data.txt';
//名前初期化
$name = '';
//コメント初期化
$comment = '';
//エラーコメント初期化
$error = [];

//POSTを受け取ったら処理を開始
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //お名前チェック&文字数チェック
    if(mb_strlen($_POST['name']) === 0) {
        $error[] = '名前を入れてください';
    }
    if(mb_strlen($_POST['name']) > 20) {
        $error[] = '名前は２０文字以内にしてください';
    }
    if((mb_strlen($_POST['comment']) === 0)) {
        $error[] = 'コメントを記入してください';
    }
    if (mb_strlen($_POST['comment']) > 100){
        $error[] = 'コメントは100文字以内にしてください';
    }
    if(isset($_POST['name']) === TRUE && mb_strlen($_POST['name']) !== 0 && mb_strlen($_POST['name']) <= 20) {
        if (isset($_POST['comment']) === TRUE && mb_strlen($_POST['comment']) !== 0 && mb_strlen($_POST['comment']) <= 100) {
            $comment ='・' .  $_POST['name'] . "\t" . $_POST['comment'] . "\t" . date('Y-m-d H:i:s') . "\n";
        }
    }
        if (($fp = fopen($filename, 'a')) !== FALSE) {
            if (fwrite($fp, $comment) === FALSE) {
                print 'ファイル書き込み失敗: ' . $filename;
            }
            fclose($fp);
        }
    }

$data = [];

if (is_readable($filename) === TRUE) {
    if (($fp =fopen($filename, 'r')) !== FALSE){
        while (($tmp = fgets($fp)) !== FALSE) {
            $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
        }
        fclose($fp);
    }
} else {
    $data[] = 'ファイルがありません';
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBS</title>
    <link rel="stylesheet" href="bbs.css">
</head>
<body>
<h1>ひとこと掲示板</h1>

<?php foreach ($error as $e_read) {?>
<h3><?php print $e_read; ?></h3>
<?php } ?>

<form method="post">
    名前:<input type="text" name="name">
    ひとこと:<input type="text" name="comment">
    <input type="submit" name="submit" value="送信">
</form>

<p>発言一覧</p>
<?php foreach ($data as $read) {?>
<p><?php print $read; ?></p>
<?php } ?>
</body>
</html>