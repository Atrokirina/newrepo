<?php $id = $_POST['id'];
$headers1 = array(
        'X-Access-Token: 1234',
        'Accept: application/json');
$url = 'http://newrepo/api.php/kekdel';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_POSTFIELDS, array('id' => $id));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers1);
$data = curl_exec($ch);
echo "<pre>";
if (curl_errno($ch)) {
    print('Error:'.curl_error($ch));
    } else {
        var_dump($data);
        curl_close($ch);
    }

 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
     <head>
         <meta charset="utf-8">
         <title></title>
     </head>
     <body>
<a href="index.php">index</a>
     </body>
 </html>
