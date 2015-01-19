<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title><?php bloginfo('name'); ?></title>
        <meta name="description" content="<?php bloginfo('description'); ?>">

        <meta property="og:title" content="<?php bloginfo('name'); ?>" />
        <meta property="og:image" content="<?php bloginfo('stylesheet_directory'); ?>/images/compartilhar.png" />
        <meta property="og:description" content="<?php bloginfo('description'); ?>" />

        <meta name="viewport" content="width=device-width">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <script type="text/javascript">
            window.blogUrl ='<?php bloginfo('url'); ?>';
            window.templateUrl = '<?php bloginfo('stylesheet_directory'); ?>';
        </script>
        <!-- build:css(.tmp) styles/main.css -->
        <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/styles/main.css">
        <!-- endbuild -->
        <!-- build:js scripts/vendor/modernizr.js -->
        <script src="<?php bloginfo('stylesheet_directory'); ?>/scripts/vendor/modernizr.js"></script>
        <!-- endbuild -->
        <?php wp_head(); ?>
    </head>
    <body class="home page">
