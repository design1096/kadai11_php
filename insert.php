<?php
//session_start();
include("funcs.php");

//1.ログインチェック
//sschk();

// YouTube URLのチェック
$url  = $_POST["url"];

function isValidYouTubeUrl($url) {
    $pattern = '/^(https?:\/\/)(www\.)(youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/';
    if (!preg_match($pattern, $url)) {
        echo "<script>alert('YouTube URLが正しくありません。');</script>";
        echo "<script>window.location = 'select.php';</script>";
        exit();
    }
}
isValidYouTubeUrl($url);

//2. POSTデータ取得
$sort_order  = $_POST["sort_order"];
$title  = $_POST["title"];
$related_work_name  = $_POST["related_work_name"];
$lid  = $_POST["lid"];
$video_id = substr($url, 32);

//３．データ登録SQL作成
$pdo = db_conn_local();
$stmt = $pdo->prepare("INSERT INTO playlist_user(sort_order,title,related_work_name,url,video_id,lid,indate)VALUES(:sort_order,:title,:related_work_name,:url,:video_id,:lid,sysdate())");
$stmt->bindValue(':sort_order', $sort_order, PDO::PARAM_INT);
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$stmt->bindValue(':related_work_name', $related_work_name, PDO::PARAM_STR);
$stmt->bindValue(':url', $url, PDO::PARAM_STR);
$stmt->bindValue(':video_id', $video_id, PDO::PARAM_STR);
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$status = $stmt->execute(); //実行

//４．データ登録処理後
if ($status==false) {
    sql_error($stmt);
} else {
    echo "<script>alert('新規登録が完了しました。');</script>";
    echo "<script>window.location = 'select.php';</script>";
    exit();
}