<?php
    require_once "functions.php";
    if(isset($_POST["username"]) && isset($_POST["date"]) && isset($_POST["avatar"]) && isset($_POST["content"])){
        try{
            $username = $_POST["username"];
            $date = $_POST["date"];
            $avatar = $_POST["avatar"];
            $content = $_POST["content"];
            $conn = mysqli_connect("localhost", "root", "66543986", "web_chat_room");
            mysqli_set_charset($conn, "utf8mb4");
            mysqli_query($conn, "INSERT INTO chat (avatar, name, content, time) VALUES ('$avatar', '$username', '$content', '$date')");
        }catch(Exception $e){
            echo $e->getMessage();
            exit();
        }
        unset($_POST);
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>聊天室</title>
    <link rel="stylesheet" href="css/base.css">
    <script>
        function onload() {
            let c = document.getElementById("content")
            c.scrollTop = c.scrollHeight
        }
    </script>
</head>
<body style="background-color: pink" onload="onload()">
    <div id="nav">
        <?php
        if(isset($_COOKIE["web_chat_room_username"]) && isset($_COOKIE["web_chat_room_password"])){
            $username = $_COOKIE["web_chat_room_username"];
            $password = $_COOKIE["web_chat_room_password"];
            $avatar = read_avatar($username, $password);
            echo "
                <form action='personal.php' method='post'>
                    <h1 style='text-align: center;'>
                        简陋网络聊天室~
                        <input type='hidden' name='username' value='$username'>
                        <input type='hidden' name='password' value='$password'>
                        <input type='hidden' name='avatar' value='$avatar'>
                        <input type='image' src='$avatar' id='personal' alt='个人资料'>
                        <a href='login.php' style='float: right; font-size: 16px; padding-top: 12px; padding-right: 10px'>换账号登录</a>
                    </h1>
                </form>
            ";
        }else{
            echo "
                <form action='login.php' method='post'>
                    <h1 style='text-align: center;'>
                        简陋网络聊天室~
                        <input type='image' id='personal' src='img/nologin.png' alt='请登录'>
                    </h1>
                </form>";
        }
        ?>
    </div>
    <div id="content">
        <?php
        if(isset($_COOKIE["web_chat_room_username"]) && isset($_COOKIE["web_chat_room_password"])){
            $conn = mysqli_connect("localhost", "root", "66543986", "web_chat_room");
            if(!$conn){ die("cannot connect mysql server"); }
            mysqli_set_charset($conn, "utf8mb4");
            $result = mysqli_query($conn, "SELECT * FROM `chat`");
            $row = mysqli_num_rows($result);
            if ($row <= 20){$l = $row;}else{
                mysqli_query($conn, "DELETE FROM `chat` LIMIT 1");
                $l = 20;
            }
            @mysqli_data_seek($result, $row - $l);
            for($i=0; $i<$l; $i++){
                list($avatar, $name, $content, $time) = mysqli_fetch_row($result);
                $content = str_replace(" ", "&nbsp;", $content);
                $content = str_replace("<", "&lt;", $content);
                $content =  str_replace(">", "&gt;", $content);
                $content =  str_replace("\n", "<br>", $content);
                echo "
                    <div class='dialog-content'>
                        <img src='$avatar' alt='头像' width='50' height='50' style='border-radius: 50%;'>
                        <div style='display: block'>
                            <div style='padding-left: 10px;'>
                                $name $time
                            </div>
                            <div class='dialog-content-div'>".unicodeDecode($content)."</div>
                        </div>
                    </div>
                ";
            }
            @mysqli_close($conn);
        }
        ?>
    </div>
    <form method="post" action="index.php">
        <?php
        if (isset($_COOKIE["web_chat_room_username"]) && isset($_COOKIE["web_chat_room_password"])){
            $username = $_COOKIE["web_chat_room_username"];
            $password = $_COOKIE["web_chat_room_password"];
            $date = date("Y-m-d H:i:s");
            $avatar = read_avatar($username, $password);
            echo "
                <div id='input'>
                    <div>
                        <input type='hidden' name='username' value='".$username."'>
                        <input type='hidden' name='avatar' value='".$avatar."'>
                        <input type='hidden' name='date' value='$date'>
                        <label><textarea name='content' id='content-textarea'></textarea></label>
                    </div>
                    <div>
                        <input type='submit' value='发送' id='submit-button'>
                    </div>
                </div>
            ";
        }else{
            echo "
                <div id='input'>
                    <div style='text-align: center; font-size: 40px'>请先点击右上角登录~</div>
                </div>
            ";
        }
        ?>
    </form>
</body>
</html>