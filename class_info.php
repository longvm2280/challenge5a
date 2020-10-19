<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
        <title>Class information</title>
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

        //thong tin name, email, phone cua sinh vien.
        $student_info = "SELECT id, name, email, phone FROM acc";
        $result = mysqli_query($conn, $student_info);
        if (mysqli_num_rows($result) > 0) {
            $numRows = mysqli_num_rows($result);
        }
        
        //ten cac cot thong tin sinh vien va cot xem thong tin sinh vien khac.
        $col_1st = '<table id="table_t"> 
                    <tr> 
                        <th>Name</th> 
                        <th>Email</th> 
                        <th>Phone</th>
                        <th>Info</th>
                    </tr>';
        if ($gv == 1) {
            //neu la giao vien them cot thay doi thong tin sinh vien.
            $col_1st = '<table id="table_t"> 
                        <tr> 
                            <th>Name</th> 
                            <th>Email</th> 
                            <th>Phone</th>
                            <th>Info</th>
                            <th>Change info</th>
                        </tr>';
        }
        
        echo $col_1st;
        
        for ($i = 0; $i < $numRows; $i++) {
            $row = mysqli_fetch_assoc($result);
            $id_2 = $row["id"];
            $name = $row["name"];
            $email = $row["email"];
            $phone = $row["phone"];
			
            //noi dung thong tin sinh vien.
            $col_next = '
                    <tr> 
                        <td>'.$name.'</td> 
                        <td>'.$email.'</td> 
                        <td>'.$phone.'</td>
                        <td>
                        <form action="info.php?value='.$id_2.'" method="post">
                            <button type="submit">Info</button>
                        </form>
                        </td>
                    </tr>';
            if ($gv == 1) {
            	//neu la giao vien them cot thay doi thong tin sinh vien.
                $col_next = '
                        <tr> 
                            <td>'.$name.'</td> 
                            <td>'.$email.'</td> 
                            <td>'.$phone.'</td>
                            <td>
                            <form action="info.php?value='.$id_2.'" method="post">
                                <button type="submit">Info</button>
                            </form>
                            </td>
                            <td>
                            <form action="change_info.php?change_id='.$id_2.'" method="post">
                                <button type="submit">Change info</button>
                            </form>
                            </td>
                        </tr>';
            }
            echo $col_next;
        }
        
        echo '
            <form action="info.php">
                <button type="submit">Return</button>
            </form>';
        
        mysqli_free_result($result);
        mysqli_close($conn);
        ?>
        
    </body>
</html>
