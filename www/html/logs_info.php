<?php

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
//logデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'logs.php';

session_start();

//トークンの照合
if(is_valid_csrf_token($_POST['token'])) {
  //トークンの削除
  $token = '';
} else {
  redirect_to(LOGOUT_URL);
}

if(is_logined() === false) {
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

//選択された注文番号の購入履歴を取得
$order_id = get_post('selected_order_id');
$order_date = get_post('order_date');
$total = get_post('total');
//購入明細を取得
$logs_info = get_logs_info($db, $order_id);

include_once VIEW_PATH . 'logs_info_view.php';
?>