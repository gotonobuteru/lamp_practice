<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
//logデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'logs.php';

//外部からページが埋め込まれるのを制限する
header('X-FRAME-OPTIONS: DENY');

session_start();

if(is_logined() === false) {
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

//購入履歴の取得
//管理者ユーザの場合
if ($user['type'] === 1) {
    $logs = get_admin_logs($db);
}
//一般ユーザの場合 
else {
    $logs = get_user_logs($db, $user['user_id']);
}

//トークンの作成
$token = get_csrf_token();

include_once VIEW_PATH . 'logs_view.php';
?>