<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'logs.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴一覧</h1>
 
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <table class="table table-bordered">
      <thead class="thead-light">
        <tr>
          <th>注文番号</th>
          <th>購入日時</th>
          <th>合計金額</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      <?php
        //ログインユーザの購入履歴を注文番号毎に表示 
        foreach ($logs as $log) {?>
        <tr>
          <td><?php print $log['order_id']; ?></td>
          <td><?php print $log['order_date']; ?></td>
          <td><?php print $log['合計']; ?></td>
          <td>
            <form action="logs_info.php" method="POST">
              <input type="submit" value="明細">
              <input type="hidden" name="selected_order_id" value="<?php print $log['order_id']; ?>">
              <input type="hidden" name="order_date" value="<?php print $log['order_date']; ?>">
              <input type="hidden" name="total" value="<?php print $log['合計']; ?>"> 
              <input type="hidden" name="token" value="<?php print $token; ?>">  
            </form>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

  </div>
</body>
</html>