<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'logs.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細一覧</h1>
 
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    
    <caption>購入履歴</caption>
    <table class="table table-bordered">
    <!-- <caption>購入履歴</caption> -->
      <thead class="thead-light">
        <tr>
          <th>注文番号</th>
          <th>購入日時</th>
          <th>合計金額</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php print $order_id; ?></td>
          <td><?php print $order_date; ?></td>
          <td><?php print $total; ?></td>
        </tr>
      </tbody>
    </table>
    
    <caption>購入明細</caption>
    <table class="table table-bordered">
    <!-- <caption>購入履歴</caption> -->
      <thead class="thead-light">
        <tr>
          <th>商品名</th>
          <th>商品価格</th>
          <th>購入数</th>
          <th>小計</th>
        </tr>
      </thead>
      <tbody>
      <?php
        //選択した履歴の明細を表示
        foreach ($logs_info as $log_info) {?>
        <tr>
          <td><?php print $log_info['name']; ?></td>
          <td><?php print $log_info['price']; ?></td>
          <td><?php print $log_info['amount']; ?></td>
          <td><?php print $log_info['小計']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

  </div>
</body>
</html>