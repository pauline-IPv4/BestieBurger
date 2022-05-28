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
                <h1><strong>Liste des items</strong><a href="insert.php" class="btn btn-dark btn-lg"><span class="glyphicon glyphicon-plus"></span></a></h1>
                <table class="table table-stripped table-bordered">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Catégorie</th>
                            <th>Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        require 'database.php';
                        $db=Database::connect();
                        $pdostatement = $db->query('SELECT items.id, items.name, items.description, items.price, categories.name AS category 
                                                    FROM items LEFT JOIN categories ON items.category=categories.id
                                                    ORDER BY items.id DESC');
                        while($item = $pdostatement->fetch()){

                            echo "<tr>";
                            echo "<td>" . $item['name'] . "</td>";
                            echo "<td>" . $item['description'] . "</td>";
                            echo "<td>" . number_format((float)$item['price'],2,'.','') . "</td>";
                            echo "<td>" . $item['category'] . "</td>";
                            echo "<td width=350>";
                            echo "<a class='btn btn-default' href='view.php?id=" . $item['id'] ."'><span class='glyphicon glyphicon-eye-open'></span>&nbsp Voir</a>";
                            echo " ";
                            echo "<a class='btn' href='update.php?id=" . $item['id'] ."'><span class='glyphicon glyphicon-pencil'></span>&nbsp Modifier</a>";
                            echo " ";
                            echo  "<a class='btn' href='delete.php?id=" . $item['id'] ."'><span class='glyphicon glyphicon-remove'></span>&nbsp Supprimer</a>";
                            echo "</td>";
                            echo "</tr>";

                        }
                        Database::disconnect();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>