<?php
$json_data = json_decode($json_data);
if (!empty($json_data)) {
  echo nl2br($json_data);
}
else {
  echo $json_data->error->msg;
}
?>