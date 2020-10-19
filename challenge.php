<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
        <title>Challenge</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h2 style="text-align: center">Challenge</h2>
        <?php
        include 'connect.php';
        
        //kiem tra dang nhap.
        session_start();
        $id = $_SESSION["id"];
        if (!isset($id)) {
            header("Location: login.php");
        } else {
            //tai khoan cua giao vien hay sinh vien.
            $gv = "SELECT name, gv FROM acc WHERE id='{$id}'";
            $result_gv = mysqli_query($conn, $gv);
            if (mysqli_num_rows($result_gv) == 1) {
                $row = mysqli_fetch_assoc($result_gv);
                $gv = $row["gv"];
            }
        }
        
        $col_1st = '<table id="table_t"> 
                    <tr> 
                        <th>Challenge</th> 
                        <th>Suggestion</th>
                        <th>Answer</th>
                    </tr>';
        if ($gv == 1) {
            $col_1st = '<table id="table_t"> 
                    <tr> 
                        <th>Challenge</th> 
                        <th>Suggestion</th>
                    </tr>';
        }
        echo $col_1st;
        
        $list_challenge = "SELECT * FROM challenge";
        $result_list = mysqli_query($conn, $list_challenge);
        
        $numRows = mysqli_num_rows($result_list);
        for ($i = 0; $i < $numRows; $i++) {
            $row = mysqli_fetch_assoc($result_list);
            $c_id = $row["id"];
            $challenge = $row["challenge"];
            $suggestion = $row["suggestion"];
            $answer_name = "answer".$c_id;
            $col_next = '
                <tr> 
                    <td>'.$challenge.'</td> 
                    <td>'.$suggestion.'</td> 
                    <td>
                    <form action="challenge_answer.php?c_id='.$c_id.'" method="post">
                        <input type="submit" name="'.$answer_name.'" value="Answer"><br><br>
                    </form>
                    </td>
                </tr>';
            if ($gv == 1) {
                $col_next = '
                    <tr> 
                        <td>'.$challenge.'</td> 
                        <td>'.$suggestion.'</td> 
                    </tr>';
            }
            echo $col_next;
        }
        
        ?>
        <?php if ($gv == 1) { ?>
        <form action="challenge.php" method="post" enctype="multipart/form-data">
            <h3>Add new challenge:</h3>
            <input type="file" name="file" id="file"><br><br>
            <input type="text" name="suggest" placeholder="Enter suggestion" required>
            <input type="submit" name="add" value="Add"><br><br>
        </form>
        <?php
        if (isset($_POST["add"]) && isset($_POST["suggest"])) {
            $new_c_num = $numRows + 1;
            $new_dir = "challenge" . $new_c_num . "/";
            $challenge_name = "challenge" . $new_c_num;
            mkdir($new_dir);

            $target_dir = $new_dir;
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            $file_name = basename($_FILES["file"]["name"]);
            $suggest = $_POST["suggest"];
            
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
            $add_file = "INSERT INTO challenge (challenge, suggestion) VALUES ('{$challenge_name}', '{$suggest}')";
            if (!mysqli_query($conn, $add_file)) {
                echo "Error ". mysqli_error($conn);
            }
            echo "<meta http-equiv='refresh' content='0'>";
        }
        ?>
        <?php } ?>
        <?php
        mysqli_free_result($result_list);
        mysqli_free_result($result_gv);
        mysqli_close($conn);
        ?>
        <form action="info.php" method="post">
            <button type="submit" name="return">Return</button><br><br>
        </form>
    </body>
</html>
