<?php
    if(!isset($_COOKIE["web_chat_room_username"]) || !isset($_COOKIE["web_chat_room_password"])){ exit(); }
    session_start();
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>修改个人资料</title>
</head>
<body>
    <script>
        function change_code(obj) {
            obj.src = "captcha_code.php"
        }
        function right_button_click() {
            if(!window.confirm("是否确认注销？")) { return; }
            let form = document.getElementById("mform")
            let hidden = document.createElement("input")
            hidden.setAttribute("type", "hidden")
            hidden.setAttribute("name", "cancel")
            hidden.setAttribute("value", "cancel")
            form.appendChild(hidden)
            form.submit()
        }
    </script>
    <form id="mform" action="is_success.php" method="post" enctype="multipart/form-data">
        <input type="hidden" value="personal" name="reg">
        <table style="margin: auto; top: 0; left: 0; right: 0; position:relative;">
            <tr>
                <td><label for="username">用户名</label></td>
                <td><input type="text" id="username" name="username" value="<?php echo $_COOKIE['web_chat_room_username']?>"></td>
                <td colspan="2"><?php
                    if (isset($_SESSION['is_change_success_username'])) {
                        $li = $_SESSION['is_change_success_username'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_change_success_username'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td><label for="old_password">旧密码</label></td>
                <td><input type="password" id="old_password" name="old_password" value="<?php echo $_COOKIE['web_chat_room_password']?>"></td>
                <td colspan="2"><?php
                    if (isset($_SESSION['is_change_success_old_password'])) {
                        $li = $_SESSION['is_change_success_old_password'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_change_success_old_password'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td><label for="new_password">新密码</label></td>
                <td><input type="password" id="new_password" name="new_password"></td>
                <td colspan="2"><?php
                    if (isset($_SESSION['is_change_success_new_password'])) {
                        $li = $_SESSION['is_change_success_new_password'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_change_success_new_password'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td><label for="new_confirm_password">确认新密码</label></td>
                <td><input type="password" id="new_confirm_password" name="new_confirm_password"></td>
                <td colspan="2"><?php
                    if (isset($_SESSION['is_change_success_new_confirm_password'])) {
                        $li = $_SESSION['is_change_success_new_confirm_password'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_change_success_new_confirm_password'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td><label for="avatar">头像</label></td>
                <td><input type="file" id="avatar" name="avatar" accept="image/jpeg, image/png"></td>
                <td colspan="2"><?php
                    if (isset($_SESSION['is_change_success_avatar'])) {
                        $li = $_SESSION['is_change_success_avatar'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_change_success_avatar'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td><label for="captcha_code">验证码</label></td>
                <td><input type="text" id="captcha_code" name="captcha_code"></td>
                <td><img onclick="change_code(this)" src="captcha_code.php" alt="验证码" style="border: 1px solid black"></td>
                <td><?php
                    if (isset($_SESSION['is_change_success_code'])) {
                        $li = $_SESSION['is_change_success_code'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_change_success_code'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td><input type="submit" value="修改"></td>
                <td></td>
                <td><input type="button" value="注销" style="background-color: red; color: white; border: 1px solid black" onclick="right_button_click()"></td>
                <td></td>
            </tr>
        </table>
    </form>
</body>
</html>