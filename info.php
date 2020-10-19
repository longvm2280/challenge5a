<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
        <title>Info</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
        include 'connect.php';
        
        //kiem tra da dang nhap hay chua.
        session_start();
        $id = $_SESSION["id"];
        if (!isset($id)) {
            header("Location: login.php");
        }

        //id sinh vien muon xem thong tin.
        if (isset($_GET["value"])) {
            $to_id = $_GET["value"];
        }
        
	//thong tin cua nguoi dang nhap.
        $info = "SELECT name, email, phone FROM acc WHERE id='{$id}'";
        if (isset($to_id)) {
            //thong tin sinh vien muon xem thong tin.
            $info = "SELECT name, email, phone FROM acc WHERE id='{$to_id}'";
        }
        $result = mysqli_query($conn, $info);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $to_name = $row['name'];
            echo '
            	<h2 align="center">Info</h2>
                <h3>Name: '.$row['name'].'</h3> 
                <h3>Email: '.$row['email'].'</h3> 
                <h3>Phone: '.$row['phone'].'</h3>';
        }
        
        if (isset($to_id)) {
            $chat = "SELECT * FROM message WHERE (from_id='{$id}' AND to_id='{$to_id}') OR (from_id='{$to_id}' AND to_id='{$id}')";
            $result_chat = mysqli_query($conn, $chat);
            $numRows = mysqli_num_rows($result_chat);
            if ($numRows > 0) {
                for ($i = 0; $i < $numRows; $i++) {
                    $row_chat = mysqli_fetch_assoc($result_chat);
                    $change_name = "change".$row_chat["message_id"];
                    $delete_name = "delete".$row_chat["message_id"];
                    if ($row_chat["from_id"] == $id) {
                        echo "
                            <form method='post'>
                            <div class='inner'><p>You: </p></div>
                            <div class='inner'><input type='text' name='mess' value='{$row_chat["message"]}'></div>
                            <div class='inner'><p> ({$row_chat["time"]})</p></div>
                            <div class='inner'><input type='submit' name='{$change_name}' value='Change'></div> 
                            <div class='inner'><input type='submit' name='{$delete_name}' value='Delete'></div>
                            </form>";

                        if (isset($_POST["{$change_name}"])) {
                            $mess = $_POST["mess"];
                            $update = "UPDATE message SET message='{$mess}' WHERE message_id='{$row_chat["message_id"]}'";
                            if (!mysqli_query($conn, $update)) {
                                    echo "Error " . mysqli_error($conn);
                            }
                            //header("Location: info.php?value={$to_id}");
                            echo "<meta http-equiv='refresh' content='0'>";
                        } else if (isset($_POST["{$delete_name}"])) {
                            $delete = "DELETE FROM message WHERE message_id='{$row_chat["message_id"]}'";
                            if (!mysqli_query($conn, $delete)) {
                                    echo "Error " . mysqli_error($conn);
                            }
                            //header("Location: info.php?value={$to_id}");
                            echo "<meta http-equiv='refresh' content='0'>";
                        }
                    } else {
                            echo nl2br ($to_name . ": " . $row_chat["message"] . ". (" . $row_chat["time"] . ")\n");
                    }
                }
            }
        
            if ($to_id != $id) {
                echo "
                    <form method='post'>
                    <div class='inner'><p>You: </p></div>
                    <div class='inner'><input type='text' name='mess' placeholder='Enter message'></div>
                    <div class='inner'><button type='submit' name='enter'>Enter</button></div> 
                    </form>";
                if (isset($_POST["enter"])) {
                    if ($_POST["mess"] != '') {
                        $time = date("H:i:s") . " " . date("d/m/Y");
                        $new_mess = "INSERT INTO message (from_id, to_id, message, time) VALUES ('{$id}', '{$to_id}', '{$_POST["mess"]}', '{$time}')";
                        if (!mysqli_query($conn, $new_mess)) {
                            echo "Error " . mysqli_error($conn);
                        }
                        $_POST["mess"] = '';
                        //header("Location: info.php?value={$to_id}");
                    }
                    echo "<meta http-equiv='refresh' content='0'>";
                }
            }
        }
        mysqli_free_result($result);
        if (isset($result_chat)) {
            mysqli_free_result($result_chat);
        }
        mysqli_close($conn);
        ?>
        
        <form action="class_info.php">
            <button type="submit">Class info</botton>
        </form>
        
        <?php if (!isset($to_id) || $to_id == $id) { ?>
        <form action="change_info.php">
            <button type="submit" name="myinfo">Change info</button>
        </form>
        
        <form action="home_work.php">
            <button type="submit" name="homework">Home work</button>
        </form>
        
        <form action="challenge.php">
            <button type="submit" name="challenge">Challenge</button>
        </form>
        <?php } ?>
        <form method='post'>
            <button type='submit' name='logout'>Log out</button>
        </form>
        <?php
        $logout = filter_input(INPUT_POST, "logout");
        if(isset($logout)) { 
            session_destroy();
            header("Location: login.php");
        } 
        ?>
    </body>
</html>
