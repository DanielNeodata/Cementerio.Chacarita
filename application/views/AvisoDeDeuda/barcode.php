<!DOCTYPE html>
<html>
    <head>
        <title>CodeIgniter Send Email</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            img.barcode-image {
                width: auto;
                /*width: 70%;*/
                height: 40%;
            }
        </style>
    </head>
    <body>
        <div>
            <p><b><?php echo "Hola"; ?></b></p>
            <p><?php echo $id; ?></p>
            <p><img class='barcode-image' 
                src='<?php echo $dataUri ?>' 
                alt='Barcode' 
            /></p>
            <div></div>
        </div>        
    </body>
</html>