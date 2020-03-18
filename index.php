<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>


 <button onclick="req1()">Загрузить test!</button>
 <!-- <button onclick="req2()">Загрузить test2!</button> -->

  <script>
    function req1() {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'api.php/kek', false);
      xhr.setRequestHeader('X-Access-Token', '1234');
      xhr.send();
      if (xhr.status !== 200) {
        alert('Ошибка ' + xhr.status + ': ' + xhr.statusText);
      } else {
          alert(xhr.responseText);
        // console.log(JSON.parse(xhr.responseText).response[0]);
      }
    }
  </script>

<a href="action.php">action</a>
<?php

// $headers1 = array(
//         'X-Access-Token: 1234',
//         'Accept: application/json');
// $url = 'http://newrepo/api.php/kek?name=113&surname=345';
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL,$url);
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// // curl_setopt($ch, CURLOPT_POST, TRUE);
// curl_setopt($ch, CURLOPT_TIMEOUT, 60);
// // curl_setopt($ch, CURLOPT_POSTFIELDS, array('name' => 1234,'surname' => 'aerewr'));
// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers1);
// $data = curl_exec($ch);
// echo "<pre>";
// if (curl_errno($ch)) {
//     print('Error:'.curl_error($ch));
//     } else {
//         var_dump($data);
//         curl_close($ch);
//     }

// var_dump($_SERVER['PATH_INFO']);
// var_dump($_SERVER['REQUEST_METHOD']);
// var_dump($_REQUEST);

 ?>
 <form action="add.php" method="get">
     <button type="submit" name="">addtestrecord</button>
 </form>
 <form action="delete.php" method="post">
     <input type="text" name="id" placeholder="id">
     <button type="submit" name="">delete</button>
 </form>
 <form action="update.php" method="get">
     <input type="text" name="id" placeholder="id">
     <button type="submit" name="">update</button>
 </form>

<?php
require 'main.php';
$que = $articles->allrecords();
foreach ($que as $news) {
   echo '<div class="">'.$news['id'].'_'. $news['name'].'_'.$news['surname'].'</div>';
}
 ?>
</body>
</html>
