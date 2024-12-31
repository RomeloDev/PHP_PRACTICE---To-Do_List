<?php
    include 'connect.php';

    function alert($message){
        echo "<script>alert('$message')</script>";
    }

    if(isset($_POST['submit'])){
        $task = trim($_POST['task']);
        $status = 'Pending';

        if(empty($task)){
            echo alert("Task field is empty");
        }else{
            $query = "INSERT INTO tasks(id, taskName, status) VALUES(null, ?, ?)";
            $stmt = $conn->prepare($query);

            if(!$stmt){
                die("Error in preparing a statement: {$conn->error}");
            }

            $stmt->bind_param('ss',$task, $status);
            if($stmt->execute()){
                echo alert("Task added successfully");
            }else{
                echo alert("Error in adding task: {$conn->error}");
            }
        }
    }

    if(isset($_GET['done_id'])){
        $done_id = intval($_GET['done_id']); 
        $query = "UPDATE tasks SET status = 'Done' WHERE id = ?";
        $stmt = $conn->prepare($query);

        if(!$stmt){
            die("Error in preparing a statement: {$conn->error}");
        }

        $stmt->bind_param('i', $done_id);
        if($stmt->execute()){
            echo alert("Task marked as done");
        }else{
            echo alert("Error in updating task: {$conn->error}");
        }

        header("Location: index.php");
        exit;
    }
   

    $query = "SELECT * FROM tasks";
    $result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <h1>To-Do List</h1>
    <form action="index.php" method="post">
        <input type="text" name="task" id="task" placeholder="Enter your task" required>
        <input type="submit" value="Add Task" name="submit">
    </form>
    <br>
    <table>
        <tr>
            <th>ID</th>
            <th>Task Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['taskName']}</td>";
                    echo "<td>{$row['Status']}</td>";
                    echo "<td>";
                    if($row['Status'] === 'Pending'){
                        echo "<a href='index.php?done_id={$row['id']}'>
                                <button>Done</button>
                              </a>";
                    }else{
                        echo "Completed";
                    }
                    
                    echo "</td>";
                    echo "</tr>";
                }
            }else{
                echo "<tr><td colspan='4'>No task found</td></tr>";
            }
        ?>
    </table>
    </div>
</body>
</html>