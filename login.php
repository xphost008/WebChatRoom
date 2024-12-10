<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
</head>
<body>
<div>
    <script>
        function change_code(obj) {
            obj.src = "captcha_code.php"
        }
    </script>
    <form action="is_success.php" method="post">
        <input type="hidden" value="login" name="reg">
        <table style="margin: auto; top: 0; left: 0; right: 0; position:relative;">
            <tr>
                <td><label for="username">用户名</label></td>
                <td colspan="3"><input type="text" id="username" name="username"></td>
            </tr>
            <tr>
                <td><label for="password">密码</label></td>
                <td><input type="password" id="password" name="password"></td>
                <td colspan="2"><?php
                    if (isset($_SESSION['is_success'])) {
                        $li = $_SESSION['is_success'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_success'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td><label for="captcha_code">验证码</label></td>
                <td><input type="text" id="captcha_code" name="captcha_code"></td>
                <td><img onclick="change_code(this)" src="captcha_code.php" alt="验证码" style="border: 1px solid black"></td>
                <td><?php
                    if (isset($_SESSION['is_code'])) {
                        $li = $_SESSION['is_code'];
                        echo "<span style='color: red'>'$li'</span>";
                        $_SESSION['is_code'] = null;
                    }
                    ?></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="登录"></td>
                <td colspan="2"><a href="registry.php">没有账号？点我注册</a></td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
