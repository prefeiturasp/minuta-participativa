
        <script type="text/javascript">
        var $buoop = {vs:{i:8,f:15,o:10.6,s:5,n:9}}
        $buoop.ol = window.onload;
        window.onload=function(){
         try {if ($buoop.ol) $buoop.ol();}catch (e) {}
         var e = document.createElement("script");
         e.setAttribute("type", "text/javascript");
         e.setAttribute("src", "http://browser-update.org/update.js");
         document.body.appendChild(e);
        }
        </script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create', 'UA-43470077-1', 'sp.gov.br');ga('send', 'pageview');
        </script>

        <script data-main="<?php bloginfo('stylesheet_directory'); ?>/scripts/main" src="<?php bloginfo('stylesheet_directory'); ?>/scripts/require.js?v=1.1"></script>
        <?php wp_footer(); ?>
</body>
</html>