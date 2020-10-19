<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
        <title>Login page</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        
        <form action="login.php" method="post">
            <div class="container">
                <h2 align="center">Log in</h2>
                <label for="user" ><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="user" required>

                <label for="pass"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="pass" required>

                <button type="submit">Login</button>
            </div>
        </form>

        <?php
        include 'connect.php';
        
        //kiem tra da dang nhap.
        session_start();
        if (isset($_SESSION["id"])) {
            header("Location: info.php");
        }

        $user = filter_input(INPUT_POST, "user");
        $pass = filter_input(INPUT_POST, "pass");

        $login = "SELECT id, gv FROM acc WHERE user='{$user}' AND pass='{$pass}'";
        $result = mysqli_query($conn, $login);
        
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            session_start();
            $_SESSION["id"] = $row["id"];
            header("Location: info.php");
        }
        
        mysqli_free_result($result);
        mysqli_close($conn);
        ?>
    </body>
</html>
