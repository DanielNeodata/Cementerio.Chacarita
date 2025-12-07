<?php
   $language_file=str_replace("-","_",$language);
?>
<head>
	<meta charset="utf-8">
	<title><?php echo $title_page;?></title>
    <meta http-equiv="Content-Language" content="<?php echo $language;?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <link rel="icon" type="image/png" href="favicon.ico"/>

    <link rel="stylesheet" href="./assets/css/britanico.css" />
    <link rel="stylesheet" href="./assets/css/neotransac.css" />
    <link rel="stylesheet" href="./assets/css/material-icons.css" />
    <link rel="stylesheet" href="./assets/css/croppie.css" />
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap-select.css" />
    <link rel="stylesheet" href="./assets/bootstrap-material-design/css/bootstrap-material-design.min.css" />
    <link rel="stylesheet" href="./assets/js/trumbo/ui/trumbowyg.min.css" />
    <link rel="stylesheet" href="./assets/css/website.css" />

    <script type="text/javascript" src="./assets/js/_third/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/js/_third/popper.min.js"></script>
    <script type="text/javascript" src="./assets/js/_third/jszip.min.js"></script>
    <script type="text/javascript" src="./assets/js/_third/moment.min.js"></script>
    <script type="text/javascript" src="./assets/js/_third/moment-timezone.min.js"></script>
    <script type="text/javascript" src="./assets/js/_third/shorten.js"></script>
    <script type="text/javascript" src="./assets/js/_third/blockui.js"></script>
    <script type="text/javascript" src="./assets/js/_third/exif.js"></script>
    <script type="text/javascript" src="./assets/js/_third/croppie.min.js"></script>
    <script type="text/javascript" src="./assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./assets/bootstrap/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="./assets/bootstrap-material-design/js/bootstrap-material-design.min.js"></script>
    <script type="text/javascript" src="./assets/js/trumbo/trumbowyg.js"></script>
    <script type="text/javascript" src="./assets/js/trumbo/langs/<?php echo $language_file;?>.min.js"></script>
    <script type="text/javascript" src="./assets/js/_third/nicEdit.js"></script>
</head>
