<?php if(empty($page->id)): ?>
<div class="widget-content widget-no-border-radius">
	<h4><?php echo __('Copy widgets from'); ?></h4>
	<select name="widgets[from_page_id]" class="span12">
		<?php foreach ($pages as $p): ?>
		<option value="<?php echo($p['id']); ?>" <?php echo($p['id'] == $page->parent_id ? ' selected="selected"': ''); ?> ><?php echo str_repeat('- ', $p['level'] * 2).$p['title']; ?></option>
		<?php endforeach; ?>
	</select>
</div>
<?php else: ?>
<div class="widget-header widget-no-border-radius spoiler-toggle" data-spoiler=".spoiler-widgets">
	<h4><?php echo __('Widgets'); ?> <?php echo UI::icon( 'chevron-down spoiler-toggle-icon' ); ?></h4>
</div>

<div class="widget-content widget-no-border-radius spoiler spoiler-widgets">
	
	<?php echo UI::button( __( 'Add widget to page' ), array(
		'id' => 'addWidgetToPage', 'icon' => UI::icon( 'plus' )
	) ); ?>
	<br /><br />
	<table class="table table-hover">
		<colgroup>
			<col />
			<col width="250px" />
		</colgroup>
		<tbody>
		<?php foreach($widgets as $widget): ?>
		<tr>
			<th>
				<?php echo HTML::anchor('widgets/edit/' . $widget->id, $widget->name, array('target' => 'blank')); ?>
				<?php if(!empty($widget->description)): ?>
				<p class="muted"><?php echo $widget->description; ?></p>
				<?php endif; ?>
			</th>
			<td>
				<?php 
				$_blocks = array(
					'----', 'PRE' => __('Before page render')
				);
				$_blocks += $blocks;
				echo Form::select('widget['.$widget->id.'][block]', $_blocks, $widget->block, array('disabled')); 
				?>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endif; ?>
