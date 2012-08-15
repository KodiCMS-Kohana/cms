<li rel="<?php echo $item->id; ?>">            
    <label for="PIList-<?php echo $item->id; ?>"><a href="<?php echo $item->url(); ?>" target="_blank"><img class="pi-image" src="<?php echo $item->url(56, 56); ?>" alt="<?php echo $item->file_name; ?>" title="<?php echo $item->file_name; ?>" /></a></label>
    <span><textarea id="PIList-<?php echo $item->id; ?>" class="pi-field-textarea" name="pi_description[<?php echo $item->id; ?>]"><?php echo htmlentities($item->description, ENT_COMPAT, 'UTF-8'); ?></textarea></span>
    <a class="pi-remove-link" href="<?php echo get_url('plugin/page_images/delete/' . $item->id); ?>" title="<?php echo __('Remove'); ?>"><img src="images/remove.png" /></a>
</li>