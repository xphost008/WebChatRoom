<?php
    require_once "functions.php";
    session_start();
    $reg = isset($_POST['reg']);
    if(!$reg) { exit(); }
    if($_POST['reg'] == "registry") {
        $is_username = isset($_POST['username']);
        $is_password = isset($_POST['password']);
        $is_confirm_password = isset($_POST['confirm-password']);
        $is_captcha_code = isset($_POST['captcha_code']);
        $is_avatar = isset($_FILES['avatar']);
        $is_session = isset($_SESSION["captcha_code"]);
        if($is_username && $is_password && $is_confirm_password && $is_captcha_code && $is_avatar && $is_session){
            $username_raw = $_POST['username'];
            $password_raw = $_POST['password'];
            $confirm_password_raw = $_POST['confirm-password'];
            $captcha_code_raw = $_POST['captcha_code'];
            $session_raw = $_SESSION["captcha_code"];
            unset($_POST);
            $fileTmpPath = $_FILES['avatar']['tmp_name'];
            $fileName = $_FILES['avatar']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            if($session_raw != $captcha_code_raw){
                $_SESSION['captcha_code'] = null;
                $_SESSION['is_success_code'] = "验证码错误~";
                header("Location: /registry.php");
                exit();
            }
            $_SESSION['captcha_code'] = null;
            if(strlen($username_raw) < 3 || strlen($username_raw) > 16){
                $_SESSION['is_success_username'] = "用户名不正确，请输入3~16位数字或字母或中文~";
                header("Location: /registry.php");
                exit();
            }
            if(strlen($password_raw) < 3 || strlen($password_raw) > 16 || !check_str($password_raw)){
                $_SESSION['is_success_password'] = "密码不正确，请输入3~16位数字或字母~";
                header("Location: /registry.php");
                exit();
            }
            $conn = mysqli_connect("localhost", "root", "66543986", "web_chat_room");
            if(!$conn){die("Cannot connect to MySQL: " . mysqli_connect_error());}
            $result = mysqli_query($conn, "SELECT `username` FROM users");
            $row = mysqli_num_rows($result);
            @mysqli_data_seek($result, 0);
            for($i = 0; $i < $row; $i++){
                list($username) = mysqli_fetch_row($result);
                if ($username == $username_raw){
                    $_SESSION['is_success_username'] = "用户名已存在~";
                    mysqli_close($conn);
                    unset($_POST);
                    header("Location: /registry.php");
                    exit();
                }
            }
            $imgbase = imagetobase64($fileTmpPath, $fileExtension);
            if($imgbase == "error_ext"){
                $_SESSION['is_success_avatar'] = "图片后缀名不正确~";
                mysqli_close($conn);
                unset($_POST);
                header("Location: /registry.php");
                exit();
            }else if($imgbase == "error_create"){
                $_SESSION['is_success_avatar'] = "头像无法图像化，请重新上传~";
                mysqli_close($conn);
                unset($_POST);
                header("Location: /registry.php");
                exit();
            }
            $avatarbase64 = 'data:image/' . $fileExtension . ';base64,' . $imgbase;
            setcookie("web_chat_room_username", $username_raw, time() + 3600);
            setcookie("web_chat_room_password", $password_raw, time() + 3600);
            $_COOKIE['web_chat_room_username'] = $username_raw;
            $_COOKIE['web_chat_room_password'] = $password_raw;
            mysqli_query($conn, "INSERT INTO users (`username`, `password`, `avatar`) VALUES ('".$username_raw."', '".$password_raw."', '".$avatarbase64."')");
            mysqli_close($conn);
            unset($_POST);
            header("Location: /index.php");
        }else{
            unset($_POST);
            header("Location: /registry.php");
        }
        exit();
    }else if ($_POST['reg'] == "login") {
        $is_username = isset($_POST['username']);
        $is_password = isset($_POST['password']);
        $is_captcha_code = isset($_POST['captcha_code']);
        $is_session = isset($_SESSION["captcha_code"]);
        if($is_username && $is_password && $is_captcha_code && $is_session){
            $username_raw = $_POST["username"];
            $password_raw = $_POST["password"];
            $captcha_code_raw = $_POST["captcha_code"];
            $session_raw = $_SESSION["captcha_code"];
            unset($_POST);
            if($session_raw != $captcha_code_raw){
                $_SESSION['captcha_code'] = null;
                $_SESSION['is_code'] = "验证码错误~";
                header("Location: /login.php");
                exit();
            }
            $_SESSION['captcha_code'] = null;
            $conn = mysqli_connect("localhost", "root", "66543986", "web_chat_room");
            if(!$conn) { die("Cannot connect to MySQL: " . mysqli_connect_error()); }
            $result = mysqli_query($conn, "SELECT `username`, `password`, `avatar` FROM users");
            $row = mysqli_num_rows($result);
            @mysqli_data_seek($result, 0);
            for($i = 0; $i < $row; $i++){
                list($username, $password, $avatar) = mysqli_fetch_row($result);
                if ($username == $username_raw && $password == $password_raw){
                    setcookie("web_chat_room_username", $username, time() + 3600);
                    setcookie("web_chat_room_password", $password, time() + 3600);
                    $_COOKIE['web_chat_room_username'] = $username;
                    $_COOKIE['web_chat_room_password'] = $password;
                    mysqli_close($conn);
                    header("Location: /index.php");
                    exit();
                }
            }
            $_SESSION['is_success'] = "用户名或密码错误~";
            mysqli_close($conn);
        }
        unset($_POST);
        header("Location: /login.php");
        exit();
    }else if($_POST['reg'] == "personal"){
        if(isset($_POST["cancel"])) {
            $is_session = isset($_SESSION["captcha_code"]);
            if(!$is_session) {
                unset($_POST);
                header("Location: /personal.php");
                exit();
            }
            if($_SESSION["captcha_code"] != $_POST["captcha_code"]){
                $_SESSION['captcha_code'] = null;
                $_SESSION['is_change_success_code'] = "验证码错误~";
                header("Location: /personal.php");
                exit();
            }
            $conn = mysqli_connect("localhost", "root", "66543986", "web_chat_room");
            if(!$conn) { die("Cannot connect to MySQL: " . mysqli_connect_error()); }
            $result = mysqli_query($conn, "SELECT `id`, `username`, `password` FROM users");
            $row = mysqli_num_rows($result);
            @mysqli_data_seek($result, 0);
            $id_raw = "";
            for($i = 0; $i < $row; $i++){
                list($id, $username, $password) = mysqli_fetch_row($result);
                if($username == $_COOKIE["web_chat_room_username"] && $password == $_COOKIE["web_chat_room_password"]){
                    $id_raw = $id;
                    break;
                }
            }
            mysqli_query($conn, "DELETE FROM users WHERE id = ".$id_raw);
            setcookie("web_chat_room_username", "", time() - 3600);
            setcookie("web_chat_room_username", "", time() - 3600);
            $_COOKIE['web_chat_room_username'] = null;
            $_COOKIE['web_chat_room_password'] = null;
            unset($_COOKIE);
            unset($_POST);
            mysqli_close($conn);
            header("Location: /index.php");
            echo "cancel";
            exit();
        }
        if(isset($_COOKIE["web_chat_room_username"]) && isset($_COOKIE["web_chat_room_password"])){
            $is_username = isset($_POST['username']) && $_POST['username'] != "";
            $is_old_password = isset($_POST['old_password']) && $_POST['old_password'] != "";
            $is_new_password = isset($_POST['new_password']) && $_POST['new_password'] != "";
            $is_confirm_password = isset($_POST['new_confirm_password']) && $_POST['new_confirm_password'] != "";
            $is_avatar = isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name']) && $_FILES['avatar']['error'] === 0 && !empty($_FILES['avatar']['name']);
            $is_captcha_code = isset($_POST['captcha_code']) && $_POST['captcha_code'] != "";
            $is_session = isset($_SESSION["captcha_code"]);
            if(!$is_session) {
                unset($_POST);
                header("Location: /personal.php");
                exit();
            }
            if($is_captcha_code && $_SESSION["captcha_code"] != $_POST["captcha_code"]){
                $_SESSION['captcha_code'] = null;
                $_SESSION['is_change_success_code'] = "验证码错误~";
                header("Location: /personal.php");
                exit();
            }
            $conn = mysqli_connect("localhost", "root", "66543986", "web_chat_room");
            if(!$conn) { die("Cannot connect to MySQL: " . mysqli_connect_error()); }
            $result = mysqli_query($conn, "SELECT `id`, `username`, `password` FROM users");
            $row = mysqli_num_rows($result);
            @mysqli_data_seek($result, 0);
            $id_raw = "";
            for($i = 0; $i < $row; $i++){
                list($id, $username, $password) = mysqli_fetch_row($result);
                if($username == $_COOKIE["web_chat_room_username"] && $password == $_COOKIE["web_chat_room_password"]){
                    $id_raw = $id;
                    break;
                }
            }
            if($is_username){
                if(strlen($_POST["username"]) < 3 || strlen($_POST["username"]) > 16){
                    $_SESSION['is_change_success_username'] = "用户名不正确，请输入3~16位数字或字母或中文~";
                    unset($_POST);
                    mysqli_close($conn);
                    header("Location: /personal.php");
                    exit();
                }
                mysqli_query($conn, "UPDATE users SET username = '".$_POST["username"]."' WHERE `id` = ".$id_raw.";");
                setcookie("web_chat_room_username", $_POST["username"], time() + 3600);
                $_COOKIE['web_chat_room_username'] = $_POST["username"];
            }
            if($is_new_password){
                if($is_old_password) {
                    if($_POST["old_password"] != $_COOKIE["web_chat_room_password"]){
                        $_SESSION['is_change_success_old_password'] = "旧密码不正确，请输入正确的旧密码~";
                        unset($_POST);
                        mysqli_close($conn);
                        header("Location: /personal.php");
                        exit();
                    }
                    if($_POST["new_password"] != $_POST["new_confirm_password"]){
                        $_SESSION['is_change_success_new_confirm_password'] = "新确认密码不一致，请重新输入~";
                        unset($_POST);
                        mysqli_close($conn);
                        header("Location: /personal.php");
                        exit();
                    }
                    mysqli_query($conn, "UPDATE users SET password = '".$_POST["new_password"]."' WHERE id = ".$id_raw.";");
                    setcookie("web_chat_room_password", $_POST["new_password"], time() + 3600);
                    $_COOKIE['web_chat_room_password'] = $_POST["new_password"];
                }else{
                    $_SESSION['is_change_success_old_password'] = "请输入旧密码~";
                    unset($_POST);
                    mysqli_close($conn);
                    header("Location: /personal.php");
                    exit();
                }
            }
            if($is_avatar){
                $fileTmpPath = $_FILES['avatar']['tmp_name'];
                $fileName = $_FILES['avatar']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                $imgbase = imagetobase64($fileTmpPath, $fileExtension);
                if($imgbase == "error_ext"){
                    $_SESSION['is_change_success_avatar'] = "图片后缀名不正确~";
                    unset($_POST);
                    mysqli_close($conn);
                    header("Location: /personal.php");
                    exit();
                }else if($imgbase == "error_create"){
                    $_SESSION['is_change_success_avatar'] = "头像无法图像化，请重新上传~";
                    unset($_POST);
                    mysqli_close($conn);
                    header("Location: /personal.php");
                    exit();
                }
                $avatarbase64 = 'data:image/' . $fileExtension . ';base64,' . $imgbase;
                mysqli_query($conn, "UPDATE users SET avatar = '".$avatarbase64."' WHERE id = ".$id_raw.";");
            }
            mysqli_close($conn);
        }
        unset($_POST);
        header("Location: /index.php");
        exit();
    }else{
        unset($_POST);
        header("Location: /index.php");
        exit();
    }
?>