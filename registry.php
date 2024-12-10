<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>注册</title>
</head>
<body>
    <script>
        function change_code(obj) {
            obj.src = "captcha_code.php"
        }
    </script>
    <form action="is_success.php" method="post" enctype="multipart/form-data">
        <input type="hidden" value="registry" name="reg">
        <table style="margin: auto; top: 0; left: 0; right: 0; position:relative;">
            <tr>
                <td><label for="username">用户名</label></td>
                <td><input type="text" id="username" name="username"></td>
                <td colspan="2"><?php
                    if (isset($_SESSION['is_success_username'])) {
                        $li = $_SESSION['is_success_username'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_success_username'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td><label for="password">密码</label></td>
                <td><input type="password" id="password" name="password"></td>
                <td colspan="2"><?php
                    if (isset($_SESSION['is_success_password'])) {
                        $li = $_SESSION['is_success_password'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_success_password'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td><label for="confirm-password">确认密码</label></td>
                <td><input type="password" id="confirm-password" name="confirm-password"></td>
                <td colspan="2"><?php
                    if (isset($_SESSION['is_success_confirm_password'])) {
                        $li = $_SESSION['is_success_confirm_password'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_success_confirm_password'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td><label for="avatar">头像</label></td>
                <td><input type="file" id="avatar" name="avatar" accept="image/jpeg, image/png"></td>
                <td colspan="2"><?php
                    if (isset($_SESSION['is_success_avatar'])) {
                        $li = $_SESSION['is_success_avatar'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_success_avatar'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td><label for="captcha_code">验证码</label></td>
                <td><input type="text" id="captcha_code" name="captcha_code"></td>
                <td><img onclick="change_code(this)" src="captcha_code.php" alt="验证码" style="border: 1px solid black"></td>
                <td><?php
                    if (isset($_SESSION['is_success_code'])) {
                        $li = $_SESSION['is_success_code'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_success_code'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td><input type="submit" value="注册"></td>
                <td></td>
                <td><a href="login.php">已有账号？点我登录</a></td>
                <td></td>
            </tr>
        </table>
    </form>
</body>
</html>