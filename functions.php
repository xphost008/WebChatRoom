<?php
function imagetobase64($filename, $fileext): string {
    $allowedfileExtensions = array('jpg', 'jpeg', 'png');
    if (in_array($fileext, $allowedfileExtensions)) {
        $fileData = file_get_contents($filename);
        $image = imagecreatefromstring($fileData);
        if ($image !== false) {
            ob_start();
            switch ($fileext) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($image);
                    break;
                case 'gif':
                    imagegif($image);
                    break;
                case 'png':
                    imagepng($image);
                    break;
            }
            $imageData = ob_get_contents();
            ob_end_clean();
            imagedestroy($image);
            return base64_encode($imageData);
        } else {
            return "error_create";
        }
    }else{
        return "error_ext";
    }
}
function read_avatar($username, $password): string {
    $conn = mysqli_connect("localhost", "root", "66543986", "web_chat_room");
    if(!$conn) { die("Cannot connect to MySQL: " . mysqli_connect_error()); }
    mysqli_set_charset($conn, "utf8");
    $result = mysqli_query($conn, "SELECT `username`, `password`, `avatar` FROM users");
    $row = mysqli_num_rows($result);
    @mysqli_data_seek($result, 0);
    for($i = 0; $i < $row; $i++) {
        list($username_raw, $password_raw, $avatar) = mysqli_fetch_row($result);
        if($username == $username_raw && $password == $password_raw) {
            return $avatar;
        }
    }
    return "img/nologin.png";
}
function unicodeDecode($str): string{
    $text = json_encode($str);
    $text = preg_replace_callback('/\\\\\\\\/', function() {return "\\";}, $text);
    return json_decode($text);
}
function check_str($str): bool {
    $raw = "abcdefghijklmnopqrstuvwxyz0123456789_";
    $ind = 0;
    for ($i = 0; $i < strlen($str); $i++) {
        for ($j = 0; $j < strlen($raw); $j++) {
            if ($str[$i] == $raw[$j]) {
                $ind++;
                break;
            }
        }
    }
    return ($ind == strlen($str));
}