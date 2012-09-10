<!-- Yandex.Metrika counter -->
<div style="display:none;"><script type="text/javascript">
(function(w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter<?php echo Plugins::getSetting('counter_id', 'yandex_metrika'); ?> = new Ya.Metrika({id:<?php echo Plugins::getSetting('counter_id', 'yandex_metrika'); ?>, enableAll: true});
        }
        catch(e) { }
    });
})(window, "yandex_metrika_callbacks");
</script></div>
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script>
<noscript><div><img src="//mc.yandex.ru/watch/<?php echo Plugins::getSetting('counter_id', 'yandex_metrika'); ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->