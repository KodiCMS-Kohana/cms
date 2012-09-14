<!-- Reformal -->
<script type="text/javascript">
    var reformalOptions = {
        project_id: <?php echo $plugin->project_id; ?>,
        project_host: "paytech.reformal.ru",
        force_new_window: false,
        tab_alignment: "<?php echo $plugin->tab_alignment; ?>",
        tab_top: "300",
        tab_bg_color: "<?php echo $plugin->tab_bg_color; ?>",
        tab_image_url: "http://tab.reformal.ru/0JLQsNGI0Lgg0L7RgtC30YvQstGLINC4INC%252F0YDQtdC00LvQvtC20LXQvdC40Y8=/FFFFFF/c931f419d308ca654c15aa9f4d2fa692"
    };
    
    (function() {
        if ('https:' == document.location.protocol) return;
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'http://media.reformal.ru/widgets/v1/reformal.js';
        document.getElementsByTagName('head')[0].appendChild(script);
    })();
</script>
<!-- /Reformal -->