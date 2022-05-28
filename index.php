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
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <div class="container site">
            <h1 class="text-logo"><span class="glyphicon glyphicon-grain"></span> Bestie Burger <span class="glyphicon glyphicon-grain"></span> </h1>
            
            <?php
                require 'admin/database.php';
                echo '<nav>
                        <ul class="nav nav-pills">';
                $db = Database::connect();
                $pdostatement = $db->query('SELECT * FROM categories');
                $categories = $pdostatement->fetchAll();
                foreach($categories as $category){
                    if($category['id'] == '1')
                        echo '<li role="presentation" class="active"><a href="#' . $category['id'] . '" data-toggle="tab">' .$category['name'] . '</a></li>';
                    else    
                        echo '<li role="presentation"><a href="#' . $category['id'] . '" data-toggle="tab">' .$category['name'] . '</a></li>';
                }
                echo    '</ul>
                     </nav>';
                echo '<div class="tab-content">';
                foreach($categories as $category)
                {
                    if($category['id'] == '1')
                        echo '<div class="tab-pane active" id="' . $category['id'] . '">';
                    else
                        echo '<div class="tab-pane" id="' . $category['id'] . '">';
                    echo '<div class="row">';
                    $pdostatement = $db->prepare('SELECT * FROM items WHERE items.category = ?');
                    $pdostatement->execute(array($category['id']));
                    
                    while($item = $pdostatement->fetch()){
                        echo '<div class="col-sm-6 col-md-4">
                                <div class="thumbnail">
                                    <img src="img/' . $item['img'] . '" alt="">
                                    <div class="price">' . number_format($item['price'], 2, '.', '') . ' €</div>
                                    <div class="caption">
                                        <h4>' . $item['name'] . '</h4>
                                        <p>' . $item['description'] . '</p>
                                        <a href="#" class="btn btn-order" role="button"><span class="glyphicon glyphicon-shopping-card"></span> Prêt à déguster?</a>
                                    </div>
                                </div>
                              </div>';    
                    }
                    echo     '</div>
                         </div>';
                }
                Database::disconnect();
                echo '</div>';
            ?>
        </div>
    </body>
</html>