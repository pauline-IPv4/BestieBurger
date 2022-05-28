<?php require 'database.php';

    if(!empty($_GET['id'])){
        $id = checkInput($_GET['id']);
    }
    $nameErr = $descriptionErr = $priceErr = $categoryErr = $imgErr = $name = $description = $price = $category = $img = "";

    if(!empty($_POST)){
        $name = checkInput($_POST['name']);
        $description = checkInput($_POST['description']);
        $price = checkInput($_POST['price']);
        $category = checkInput($_POST['category']);
        $img = checkInput($_FILES['img']['name']);
        $imgPath = '../img/' . basename($img);
        $imgExt = pathinfo($imgPath, PATHINFO_EXTENSION);
        $success = true;

        if(empty($name)){
            $nameErr = "Ce champ ne doit pas être vide";
            $success = false;
        }
        
        if(empty($description)){
            $descErr = "Ce champ ne doit pas être vide";
            $success = false;
        }
        
        if(empty($price)){
            $nameErr = "Ce champ ne doit pas être vide";
            $success = false;
        }
        
        if(empty($img)){
            $isImgUpdated = false;
        } 
        
        else{
            $isImgUpdated = true;
            $uploadSuccess = true;
            if($imgExt != "jpg" && $imgExt != "png" && $imgExt != "jpeg" && $imgExt != "gif"){
                $imgErr = "Les fichiers autorisés sont : .jpg .jpeg .png .gif";
                $uploadSuccess = false;
            }
            if(file_exists($imgPath)){
                $imgErr = "Le fichier existe déjà";
                $uploadSuccess = false;
            }
            if($_FILES["img"]["size"] > 500000){
                $imgErr = "Le fichier ne soit pas dépasser les 500KB";
                $uploadSuccess = false;
            }
            if($uploadSuccess){
                if(!move_uploaded_file($_FILES["img"]["tmp_name"], $imgPath)){
                    $imgErr = "Erreur upload";
                    $uploadSuccess = false;
                }
            }
        }
        if(($success && $isImgUpdated && $isUploadSuccess) || ($success && !$isImgUpdated)){
            $db = Database::connect();
            if($isImgUpdated){
                $pdostatement = $db->prepare("UPDATE items SET name = ?, description = ?, price = ?, category = ?, img = ? WHERE id = ? ");
                $pdostatement->execute(array($name, $description, $price, $category, $image, $id));
            }
            else{
                $pdostatement = $db->prepare("UPDATE items SET name = ?, description = ?, price = ?, category = ?, img = ? WHERE id = ? ");
                $pdostatement->execute(array($name, $description, $price, $category, $id));
            }
            Database::disconnect();
            header("Location: index.php");
        } 
        else if($isImgUpdated && !$isUploadSuccess){ 
            $db = Database::connect();
            $pdostatement = $db->prepare("SELECT image FROM items WHERE id = ?");
            $pdostatement->execute(array($id));
            $item = $pdostatement->fetch();
            $img = $item['img'];
            Database::disconnect();
        }
    
    }
    else{
        $db = Database::connect();
        $pdostatement = $db->prepare("SELECT * FROM items WHERE id = ?");
        $pdostatement->execute(array($id));
        $item = $pdostatement->fetch();
        $name = $item['name'];
        $description = $item['description'];
        $price = $item['price'];
        $category = $item['category'];
        $img = $item['img'];
        Database::disconnect();
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
                <div class="col-sm-6">
                    <h1><strong>Modifer un item</strong></h1>
                    <br>
                    <form class="form" role="form" action="<?php echo 'update.php?id=' . $id;?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Nom:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nom" value="<?php echo $name;?>">
                            <span class="help-inline"><?php echo $nameErr; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php echo $description;?>">
                            <span class="help-inline"><?php echo $descriptionErr; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="price">Prix: (en €)</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Prix" value="<?php echo $price;?>">
                            <span class="help-inline"><?php echo $priceErr; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="category">Catégorie:</label>
                            <select class="form-control" id="category" name="category">
                                <?php
                                
                                    $db = Database::connect();
                                    foreach($db->query('SELECT * FROM categories') as $row){
                                        if($row['id'] == $category)
                                            echo '<option selected="selected" value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                        else
                                            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                    }
                                    //$Database::disconnect();
                                ?>
                            </select>
                            <span class="help-inline"><?php echo $categoryErr; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Image:</label>
                            <p><?php echo $img;?></p>
                            <label for="img">Selectionner une image:</label>
                            <input type="file" id="img" name="img">
                            <span class="help-inline"><?php echo $imgErr;?></span>
                        </div>
                        <br>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>
                            <a class="btn btn-primary" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
                        </div>
                    </form>

                </div>
                <div class="col-sm-6 site">
                    <div class="thumbnail"> 
                        <img src="<?php echo '../img/'. $img;?>" alt="">
                        <div class="price"><?php echo number_format((float)$price, 2, '.', '');?></div>
                        <div class="caption">
                            <h4><?php echo $name;?></h4>
                            <p><?php echo $description;?></p>
                            <a href="#" class="btn btn-order" role="button"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp Prêt à déguster ?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>