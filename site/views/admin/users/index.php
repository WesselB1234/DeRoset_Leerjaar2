<?php 
    require "../../../database.php";
    require "../../../permissions.php";

    adminPermission("../../login.php");

    function getUsers($conn){

        $users = $conn->prepare("SELECT * FROM users");
        $users->execute();
        $users = $users->fetchAll();

        return $users;
    }

    $users = getUsers($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table>
        <thead>
            <th>
                ID
            </th>
            <th>
                Naam
            </th>
            <th>
                Email
            </th>
        </thead>
        <tbody>
            <?php foreach($users as $user){?>
                <tr>
                    <td>
                        <?php echo $user["id"];?>
                    </td>
                    <td>
                        <?php echo $user["username"];?>
                    </td>
                    <td>
                        <?php echo $user["email"];?>
                    </td>
                    <td>
                        <a href="details.php?id=<?php echo $user["id"];?>">Bekijk details</a>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</body>
</html>