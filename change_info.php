<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
        <title>Change info</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
        include 'connect.php';
        
        //kiem tra dang nhap.
        session_start();
        $id = $_SESSION["id"];
        if (!isset($id)) {
            header("Location: login.php");
        } else {
            //tai khoan dang nhap la cua giao vien hay sinh vien.
            $gv_or_not = "SELECT name, gv FROM acc WHERE id='{$id}'";
            if ($result = mysqli_query($conn, $gv_or_not)) {
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_assoc($result);
                    $gv = $row["gv"];
                    mysqli_free_result($result);
                } 
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
        
        //giao vien thay doi thong tin sinh vien: lay id cua sinh vien can thay doi.
        if (isset($_GET["change_id"])){
            $change_id = $_GET["change_id"];
            $st = "SELECT name FROM acc WHERE id='{$change_id}'";
            $result_st = mysqli_query($conn, $st);
            if (mysqli_num_rows($result_st) == 1) {
                $row_st = mysqli_fetch_assoc($result_st);
                $st_name = $row_st["name"];
            }
            mysqli_free_result($result_st);
        }
        
        ?>
        <form method="post">
            <?php if (!isset($change_id)) { ?>
            <h2 align="center">Change your information, <?php echo $row["name"] ?></h2>
            <?php } ?>
            <?php if (isset($change_id)) { ?>
            <h2 align="center"><?php echo $row["name"] ?> changing information of <?php echo $st_name ?></h2>
            <?php } ?>
            <?php if ($gv == 1) { ?>
            <label for="newuser" ><b>New username</b></label>
            <input type="text" placeholder="Enter new username" name="newuser"><br><br>
            <?php } ?>
            <label for="newpass" ><b>New password</b></label>
            <input type="password" placeholder="Enter new password" name="newpass"><br><br>
            <?php if ($gv == 1) { ?>
            <label for="newname" ><b>New name</b></label>
            <input type="text" placeholder="Enter new name" name="newname"><br><br>
            <?php } ?>
            <label for="newemail" ><b>New email</b></label>
            <input type="text" placeholder="Enter new email" name="newemail"><br><br>
            <label for="newphone" ><b>New phone number</b></label>
            <input type="text" placeholder="Enter new phone" name="newphone"><br><br>
            <button type="submit">Change</button>
        </form>
        <form action="info.php">
            <button type="submit">Return</button>
        </form>

        <?php
        $newpass = filter_input(INPUT_POST, "newpass");
        $newemail = filter_input(INPUT_POST, "newemail");
        $newphone = filter_input(INPUT_POST, "newphone");
        $new = array($newpass, $newemail, $newphone);
        //neu la giao vien them chuc nang thay doi ten dang nhap va mat khau.
        if ($gv == 1) {
            $newuser = filter_input(INPUT_POST, "newuser");
            $newname = filter_input(INPUT_POST, "newname");
            $new = array($newuser, $newpass, $newname, $newemail, $newphone);
        }
        
        /*--Thay thong tin cu vao nhung truong khong thay doi--*/
        $old_data = "SELECT pass, email, phone FROM acc WHERE id='{$id}'";
        if ($gv == 1) {
            if (isset($change_id)) {
                $old_data = "SELECT user, pass, name, email, phone FROM acc WHERE id='{$change_id}'";
            } else {
                $old_data = "SELECT user, pass, name, email, phone FROM acc WHERE id='{$id}'";
            }
        }
        
        $result = mysqli_query($conn, $old_data);
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        if ($gv == 0) {
            $old = array($row["pass"], $row["email"], $row["phone"]);
        } else {
            $old = array($row["user"], $row["pass"], $row["name"], $row["email"], $row["phone"]);
        }
        
        for ($i = 0; $i < count($old); $i++) {
            if ($new[$i] == '') {
                $new[$i] = $old[$i];
            }
        }
        /*--Thay thong tin cu vao nhung truong khong thay doi--*/
        
        $change = "UPDATE acc SET pass='{$new[0]}', email='{$new[1]}', phone='{$new[2]}' WHERE id='{$id}'";
        if ($gv == 1) {
            if (isset($change_id)) {
                $change = "UPDATE acc SET user='{$new[0]}', pass='{$new[1]}', name='{$new[2]}', email='{$new[3]}', phone='{$new[4]}' WHERE id='{$change_id}'";
            } else {
                $change = "UPDATE acc SET user='{$new[0]}', pass='{$new[1]}', name='{$new[2]}', email='{$new[3]}', phone='{$new[4]}' WHERE id='{$id}'";
            }
        }
        if (!mysqli_query($conn, $change)) {
            echo "Error: ". mysqli_error($conn);
        } else {
            if (isset($_POST["newpass"]) || isset($_POST["newemail"]) || isset($_POST["newphone"]) || isset($_POST["newuser"]) || isset($_POST["newname"])) {
                echo "<p style='text-align: center;'>Success</p>";
            }
        }
        mysqli_close($conn);
        ?>
    </body>
</html>
