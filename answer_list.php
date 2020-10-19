<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
        <title>Answer list</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
        function get_name_by_id($id, $conn) {
            $name = "SELECT name FROM acc WHERE id='$id'";
            $result = mysqli_query($conn, $name);
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                return $row["name"];
            }
        }
        
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
        
        $question_id = $_GET["question_id"];
        $id_to_name = "SELECT file_name FROM file WHERE id='$question_id'";
        $result = mysqli_query($conn, $id_to_name);
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            echo '<h2 style="text-align: center">Home work: ' . $row["file_name"] . '</h2>';
        }
        
        $col_1st = '<table id="table_t"> 
                    <tr> 
                        <th>File name</th> 
                        <th>Uploader</th>
                        <th>Download</th>
                    </tr>';
        echo $col_1st;
        
        $all_answer = "SELECT file_name, uploader_id FROM file WHERE question_id='{$question_id}'";
        $result_all = mysqli_query($conn, $all_answer);
        if (mysqli_num_rows($result_all) > 0) {
            $numRows = mysqli_num_rows($result_all);
            for ($i = 0; $i < $numRows; $i++) {
                $row = mysqli_fetch_assoc($result_all);
                $name = $row["file_name"];
                $uploader = get_name_by_id($row["uploader_id"], $conn);
                $col_next = '
                        <tr> 
                            <td>'.$name.'</td> 
                            <td>'.$uploader.'</td> 
                            <td>
                            <a download href="upload/'.$name.'">Download</a>
                            </td>
                        </tr>';
                echo $col_next;
            }
        }
        ?>
        <form action="home_work.php" method="post">
            <button type="submit" name="return">Return</button><br><br>
        </form>
    </body>
</html>
