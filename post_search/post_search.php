<?php
/*
todo #1 ページネーションを追加する
基本的な考え方
必要な変数
・ページごとの表示件数
->10
・最大ページ
・現在のページ位置
・全体のテーブル数
SQL経由で得る
*/
//ハコを定義
$post_data = [];
//メッセージ初期化
$message = [];
//郵便番号初期化
$post_code = '';
//都道府県を初期化
$post_pref = '';
//市区町村など初期化
$post_city = '';
//結果を初期化
$result = '';
//フラグ設定
$flag = FALSE;
//ページごとの表示件数
$par_page = 10;

// pageパラメータが無ければ、page = 1をセット
if (isset ($_GET['page']) === false) {
    $page = 1;
    } else {
    //そうでなければpageパラメータの値を変数にセット
    $page =  $_GET['page']; //(int) $_GET['page'];
    }


//DB接続設定
$host = 'localhost';
$username = 'tanuki';
$passwd = 'tanuki';
$dbname = 'tanuki';
//DB接続
$link = mysqli_connect($host, $username, $passwd, $dbname);
if ($link) {
    //文字セットを設定（文字化け回避）
    mysqli_set_charset($link, 'utf8');
    //郵便番号検索処理スタート
    if(isset($_GET['post_code']) === TRUE) {
        $post_code = $_GET['post_code'];
        //todo 空白の処理
        if (mb_strlen($post_code) === 0) {
            $message[] = '郵便番号を入力してください';
        //郵便番号入力形式チェック
        }elseif (preg_match('/^[0-9]{7}$/', $post_code) !== 1) {
            $message[] = '形式が違います。ハイフン抜きの7桁の数字で入力してください';
        }else{
            //郵便番号検索用のクエリ
            $query = 'SELECT post_code, post_pref, post_city, post_st FROM post_num_table WHERE post_code LIKE ' . "'" . $post_code . "'" . 'LIMIT '. (($page-1) * 10) . "," . $par_page;
            //クエリ実行
            $result = mysqli_query($link, $query);
            //検索結果フラグを有効化
            $flag = TRUE;
            $sql_all = 'SELECT post_code, post_pref, post_city, post_st FROM post_num_table WHERE post_code LIKE ' . "'" . $post_code . "'";
            //クエリを実行
            $result_all = mysqli_query($link, $sql_all);
            //全件数を取得
            $all_rows = mysqli_num_rows($result_all);
            //var_dump($all_rows);
            //最大ページ数取得
            $total_page = ceil($all_rows / $par_page);
            //１行ずつ結果を所得
            while ($row = mysqli_fetch_array($result)) {
                $post_data[] = $row;
            }
            //メモリ解放
            mysqli_free_result($result);
        }
    }
    //文字列検索処理スタート
    if (isset($_GET['post_pref']) === TRUE) {
        //都道府県を受け取った時の処理（神奈川と東京のみ）
        $post_pref = $_GET['post_pref'];
        if ($post_pref === 'tokyo') {
            $post_pref = '東京都';
        }elseif($post_pref === 'kanagawa') {
            $post_pref = '神奈川県';
        }elseif($post_pref === 'none') {
            $message[] = '都道府県を選んでください';
        }
        //市区町村以降を受け取った時の処理
        if(isset($_GET['post_city']) === TRUE){
            $post_city = $_GET['post_city'];
            if (mb_strlen($post_city) === 0) {
                $message[] = '文字を入力してください';
            }else{
                //文字列検索用のクエリ
                $query = 'SELECT post_code, post_pref, post_city, post_st FROM post_num_table WHERE post_pref LIKE ' . "'" . $post_pref . "'" . ' AND (post_city LIKE ' . "'" . $post_city . "'" . 'OR post_st LIKE ' . "'" . $post_city . "')" . 'LIMIT '. (($page-1) * 10) . "," . $par_page;
                //クエリ実行
                $result = mysqli_query($link, $query);
                //var_dump($query);
                //検索結果フラグを有効化
                $flag = TRUE;
                //全件数取得用クエリ
                $sql_all = 'SELECT post_code, post_pref, post_city, post_st FROM post_num_table WHERE post_pref LIKE ' . "'" . $post_pref . "'" . ' AND (post_city LIKE ' . "'" . $post_city . "'" . 'OR post_st LIKE ' . "'" . $post_city . "')";
                //クエリを実行
                $result_all = mysqli_query($link, $sql_all);
                //全件数を取得
                $all_rows = mysqli_num_rows($result_all);
                //var_dump($all_rows);
                //最大ページ数取得
                $total_page = ceil($all_rows / $par_page);
                //１行ずつ結果を所得
                while ($row = mysqli_fetch_array($result)) {
                    $post_data[] = $row;
                }
                //メモリ解放
                mysqli_free_result($result);
            }
        }
    }
//DBクローズ
mysqli_close($link);
}else{
    print 'DB接続失敗だよ！';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POST Search</title>
    <link rel="stylesheet" href="post.css">
</head>
<body>
<!--  エラー表示  -->
<?php foreach ($message as $m_read) {?>
<h3><?php print $m_read; ?></h3>
<?php } ?>
<!--  検索入力メインページ  -->
<h1>郵便番号検索</h1>
<h2>郵便番号から検索</h2>
<!--  郵便番号から検索  -->
<form method="get">
    <label for="post_code">郵便番号:</label>
    <input id="post_code" type="text" name="post_code">
    <input type="submit" value="検索"><br>
</form>
<!--  住所文字列から検索  -->
<form method="get">
    <!--  現状東京神奈川のみ対応  -->
    <label for="post_pref">都道府県:</label>
    <select name="post_pref">
        <option value="none">都道府県を選んでください</option>
        <option value="tokyo">東京都</option>
        <option value="kanagawa">神奈川県</option>
    </select>
    <!--  市区町村から検索  -->
    <label for="post_city">それ以降:</label>
    <input id="post_city" type="text" name="post_city">
    <input type="submit" value="検索">
</form>
<hr>
<!--  検索結果表示  -->
<?php
//flagが有効の時のみ結果を表示
if($flag !== TRUE) {
?>
<!-- 検索前の表示 -->
<p>ここに結果が表示されます!</p>
<?php }else { ?>
<table>
    <tr>
        <td>郵便番号</td>
        <td>都道府県</td>
        <td>市区町村</td>
        <td>それ以降</td>
    </tr>

<?php foreach ($post_data as $value) { ?>
    <tr>
        <td><?php print htmlspecialchars($value['post_code'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php print htmlspecialchars($value['post_pref'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php print htmlspecialchars($value['post_city'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php print htmlspecialchars($value['post_st'], ENT_QUOTES, 'UTF-8'); ?></td>
    </tr>
<?php
}
?>
</table>
<?php
//https://tenderfeel.xsrv.jp/php/639/ を参照
function paging($total_page, $page = 1){

    $page = (int) htmlspecialchars($page);

    $prev = max($page - 1, 1); // 前のページ番号
    $next = min($page + 1, $total_page); // 次のページ番号

    //検索条件を変数$sarch_textに格納
    if (!empty($_GET['post_city'])){
            $sarch_text = '?post_pref=' . $_GET['post_pref'] . '&' . 'post_city=' . $_GET['post_city'];
    }
    if (!empty($_GET['post_st'])) {
        $sarch_text = '?post_pref=' . $_GET['post_pref'] . '&' . 'post_st=' . $_GET['post_st'];
    }

    if ($page !== 1) { // 最初のページ以外で「前へ」を表示
        print '<a href=' . $_SERVER['SCRIPT_NAME'] . $sarch_text . '&page=' . $prev . '>&laquo; 前へ</a>';
    }
    if ($page < $total_page){ // 最後のページ以外で「次へ」を表示
        print '<a href=' . $_SERVER['SCRIPT_NAME'] . $sarch_text . '&page=' . $next . '>次へ &raquo;</a>';
    }
}
if (!empty($_GET['page'])){ // $_GET['page']が空でないかどうかチェック
    paging($total_page, $_GET['page']); //空でない場合は当初の処理を実行
} else {
    paging($total_page, 1); //空の場合は1（すなわち1ページ目）を設定
}

?>

<?php } ?>
</body>
</html>