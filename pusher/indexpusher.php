<!-- this was to learn hot to user pusher but we are not using it on our project yet  -->

<?php
  require __DIR__ . '/vendor/autoload.php';

  $options = array(
    'cluster' => 'eu',
    'useTLS' => true
  );
  $pusher = new Pusher\Pusher(
    '9cd7310c266592cf4d58',
    '917394d73a8b4444d852',
    '1029710',
    $options
  );

  $data['message'] = 'hello world';
  $pusher->trigger('my-channel', 'my-event', $data);
?>