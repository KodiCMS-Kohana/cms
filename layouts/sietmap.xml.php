<?php

if(!function_exists('snippet_sitemap'))
{

    function snippet_xml_sitemap($parent)
    {
        $out = '';
        $childs = $parent->children();
        if (count($childs) > 0)
        {
            foreach ($childs as $child)
            {
                $out .= "  <url>\n";
                $out .= "   <loc>".$child->url()."</loc>\n";
                $out .= "   <lastmod>".$child->date('%Y-%m-%d', 'updated')."</lastmod>\n";
                $out .= "   <changefreq>".($child->hasContent('changefreq') ? $child->content('changefreq'): 'weekly')."</changefreq>\n";
                $out .= "  </url>\n";
                $out .= snippet_xml_sitemap($child);
            }
        }
        return $out;
    }

}

?>
<?php echo '<?'; ?>xml version="1.0" encoding="UTF-8" <?php echo '?>'; ?> 
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php echo snippet_xml_sitemap($this->find('/')); ?>
</urlset>