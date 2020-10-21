<?php

function get_user_logs($db, $user_id) {
  $sql = "
    SELECT
      logs.order_id,
      logs.order_date,
      SUM(logs_info.price * logs_info.amount) AS '合計'
    FROM
      logs
    INNER JOIN 
      logs_info
    ON 
      logs.order_id = logs_info.order_id
    WHERE
      logs.user_id = ?
    GROUP BY
      logs.order_id
    ORDER BY
      logs.order_id DESC
  ";

    return fetch_all_query($db, $sql, array($user_id));
}

function get_admin_logs($db) {
  $sql = "
    SELECT 
      logs.order_id,
      logs.order_date,
      SUM(logs_info.price * logs_info.amount) AS '合計'
    FROM
      logs
    INNER JOIN
      logs_info
    ON
      logs.order_id = logs_info.order_id
    GROUP BY
      logs.order_id
    ORDER BY
      logs.order_id DESC
  ";

  return fetch_all_query($db, $sql);
}

function get_logs_info($db, $order_id) {
  $sql = "
    SELECT
      items.name,
      logs_info.price,
      logs_info.amount,
      (logs_info.price * logs_info.amount) AS '小計'
    FROM
      items
    INNER JOIN
      logs_info
    ON
      items.item_id = logs_info.item_id
    WHERE
      logs_info.order_id = ?
  ";

  return fetch_all_query($db, $sql, array($order_id));
}
?>