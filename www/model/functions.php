<?php

function dd($var){
  var_dump($var);
  exit();
}

function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}

function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}

function set_session($name, $value){
  $_SESSION[$name] = $value;
}

function set_error($error){
  $_SESSION['__errors'][] = $error;
}

function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}

function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

function set_message($message){
  $_SESSION['__messages'][] = $message;
}

function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}

function is_logined(){
  return get_session('user_id') !== '';
}

function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}



function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}


function is_valid_upload_image($image){
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  $mimetype = exif_imagetype($image['tmp_name']);
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

//トークンの作成
function get_csrf_token() {
  $token = get_random_string();
  //セッション変数に$tokenを代入
  set_session('csrf_token', $token);
  return $token;
}

//トークンのチェック
function is_valid_csrf_token($token) {
  //トークンが設定されていない場合
  if ($token === '') {
    return false;
  }
  //トークンとセッション変数に代入されているトークンの真偽値を返す
  return $token === get_session('csrf_token');
}

//購入履歴の保存
function insert_logs($db, $user_id){
  $sql = "
    INSERT INTO
      logs(
        user_id
      )
    VALUES(?);
  ";

  return execute_query($db, $sql, array($user_id));
}

//最後に追加したデータのIDを取得
function get_lastID($db) {
  return $db->lastInsertID();
}

//購入履歴明細を保存する
function insert_logs_info($db, $item_id, $price, $amount, $last_id){
  $sql = "
    INSERT INTO
      logs_info(
        order_id,
        item_id,
        price,
        amount
      )
    VALUES(?, ?, ?, ?);
  ";

  return execute_query($db, $sql, array($last_id, $item_id, $price, $amount));
}

//購入履歴と購入履歴明細をトランザクション処理する
function insert_log_transaction($db, $user_id, $carts) {
  $err_check = array();
  
  // $db->beginTransaction();
  //購入履歴の保存
  if (insert_logs($db, $user_id) === false) {
    $err_check[] = false;
  }
  //最後に追加したデータのID取得
  if (($last_id = get_lastID($db)) === '') {
    $err_check[] = false;
  }
  //↑ここまで正常動作----------------------

  // カートに入っている商品毎SQL文を実行する
  foreach ($carts as $cart) {
    if (insert_logs_info($db, $cart['item_id'], $cart['price'], $cart['amount'], $last_id) === false) {
      $err_check[] = false;
    }
  }
  //SQL文が全てtrueならコミットする
  // if (count($err_check) === 0) {
  //   $db->commit();
  //   return true;
  // } else {
  //   $db->rollback();
  //   return false;
  // } 



  // // カートに入っている商品毎SQL文を実行する
  // foreach ($carts as $cart) {
  //   $item_id[] = $cart['item_id'];
  //   $price[] = $cart['price'];
  //   $amount[] = $cart['amount'];
  // }
  // for ($i = 0; $i < count($item_id); $i++) {
  //   if (insert_logs_info($db, $item_id[$i], $price[$i], $amount[$i], $last_id) === false) {
  //     $err_check[] = false;
  //   }
  // }
  // //SQL文が全てtrueならコミットする
  // if (count($err_check) === 0) {
  //   $db->commit();
  //   return true;
  // } else {
  //   $db->rollback();
  //   return $item_id;
  // } 


  // //カートに入っている商品毎SQL文を実行する
  // for ($i = 0; $i < count($carts); $i++) {
  //   if (insert_logs_info($db, $cart[$i]['item_id'], $cart[$i]['price'], $cart[$i]['amount'], $last_id) === false) {
  //     $err_check[] = false;
  //   }
  // }
  // // SQL文が全てtrueならコミットする
  // if (count($err_check) === 0) {
  //   $db->commit();
  //   return true;
  // } else {
  //   $db->rollback();
  //   return $err_check;
  // } 

}