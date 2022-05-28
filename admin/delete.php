<?php require 'database.php';

    if(!empty($_GET['id'])){
        $id = checkInput($_GET['id']);
    }
    if(!empty($_POST['id'])){
        $id = checkInput($_POST['id']);
        $db = Database::connect();
        $pdostatement = $db->prepare("DELETE FROM items WHERE id = ?");
        $pdostatement->execute(array($id));
        Database::disconnect();
        header("Location:index.php");
    }

    function checkInput($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Bestie Burger</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width-device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" ></script>
        <link href="http://fonts.googleapis.com/css?family=Bebas Neue" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../css/styles.css">
    </head>
    <body>
        <h1 class="text-logo"><span class="glyphicon glyphicon-grain"></span> Bestie Burger <span class="glyphicon glyphicon-grain"></span></h1>
        <div class="container admin">
            <div class="row">
                <h1><strong>Supprimer un item</strong></h1>
                <br>
                <form class="form" role="form" action="delete.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id;?>"/>
                    <p class='alert alert-warning'>Êtes-vous sûrs de vouloir supprimer ?</p>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"> Oui</button>
                        <a class="btn btn-default" href="index.php"> Non</a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>