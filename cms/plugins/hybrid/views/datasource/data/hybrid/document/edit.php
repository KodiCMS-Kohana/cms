<?php if(!Acl::check('hybrid'.$ds->id().'.document.edit')): ?>
<script>
$(function() {
	$('input,textarea,select').attr('disabled', 'disabled');
})
</script>
<?php endif; ?>

<div class="outline">
	<div class="widget outline_inner">
	
	<?php if(Acl::check('hybrid'.$ds->id().'.document.edit')): ?>
	<?php echo Form::open(Request::current()->url() . URL::query(array('id' => $doc->id)), array(
		'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'
	)); ?>
	<?php echo Form::hidden('ds_id', $ds->id()); ?>
	<?php echo Form::hidden('id', $doc->id); ?>
	<?php else: ?>
	<div class="form-horizontal">
	<?php endif; ?>
	<div class="widget-header">
		<div class="control-group">
			<label class="control-label title"><?php echo __('Header'); ?></label>
			<div class="controls">
				<?php echo Form::input('header', $doc->header, array(
					'class' => 'input-title input-block-level slug-generator', 'data-slug' => '.from-header'
				)); ?>
			</div>
			
			<div class="controls">
				<?php echo View::factory('datasource/data/hybrid/document/fields/published', array(
					'doc' => $doc
				)); ?>
			</div>	
		</div>		
	</div>
		
	<?php if(!empty($record->struct[DataSource_Hybrid_Field::TYPE_FILE] )): ?>
	<div class="widget-header ">
		<h4>
			<small><?php echo __('Section'); ?> <?php echo DataSource_Hybrid_Field::TYPE_FILE; ?></small>
		</h4>
	</div>
	<div id="file_fields" class="widget-content">
		<?php foreach($record->struct[DataSource_Hybrid_Field::TYPE_FILE] as $type => $fields): ?>
			<?php foreach($fields as $key): ?>
			<?php echo View::factory('datasource/data/hybrid/document/fields/file', array(
				'value' => $doc->fields[$key], 
				'field' => $record->fields[$key]
			)); ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
	<?php endif ;?>

	<?php if(!empty($record->struct[DataSource_Hybrid_Field::TYPE_PRIMITIVE] )): ?>
	<div class="widget-header ">
		<h4>
			<small><?php echo __('Section'); ?> <?php echo DataSource_Hybrid_Field::TYPE_PRIMITIVE; ?></small>
		</h4>
	</div>
	<div id="primitive_fields" class="widget-content">
		<?php foreach($record->struct[DataSource_Hybrid_Field::TYPE_PRIMITIVE] as $type => $fields): ?>
			<?php if($type !== DataSource_Hybrid_Field_Primitive::PRIMITIVE_TYPE_HTML): ?>
			<?php foreach($fields as $key): ?>
		
			<?php
			$value = (empty($doc->fields[$key]) AND empty($doc->id) AND !empty($record->fields[$key]->default )) ? $record->fields[$key]->default : $doc->fields[$key];
			?>
			<?php echo View::factory('datasource/data/hybrid/document/fields/' . $type, array(
				'value' => $value, 'field' => $record->fields[$key]
			)); ?>
			<?php endforeach; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<?php endif ;?>

	<?php $html_fields = Arr::path($record->struct, 'primitive.html');
	if(!empty($html_fields)): ?>
	<div class="widget-tabs">
		<ul class="nav nav-tabs">
			<?php foreach($html_fields as $i => $key): ?>
				<li class="<?php echo $i == 0 ? 'active' : ''; ?>">
					<a href="#tab-<?php echo $key; ?>"><?php echo $record->fields[$key]->header; ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="clearfix"></div>
	</div>
	<div id="html_fields" class="widget-content widget-nopad" style="height: 244px">
		<div class="tabs-content">
			<?php foreach($html_fields as $i => $key): ?>
			<div class="tab-pane <?php echo $i == 0 ? 'active' : ''; ?>" id="tab-<?php echo $key; ?>">
				<?php echo View::factory('datasource/data/hybrid/document/fields/html', array(
					'value' => $doc->fields[$key], 'field' => $record->fields[$key]
				)); ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif ;?>

	<?php if(!empty($record->struct[DataSource_Hybrid_Field::TYPE_DOCUMENT])): ?>
	<div class="widget-header ">
		<h4>
			<small><?php echo __('Section'); ?> <?php echo __('Documents') ?></small>
		</h4>
	</div>
	<div id="document_fields" class="widget-content">
		<?php foreach($record->struct[DataSource_Hybrid_Field::TYPE_DOCUMENT] as $type => $fields): ?>
			<?php foreach($fields as $key): ?>
			<?php echo View::factory('datasource/data/hybrid/document/fields/document', array(
				'value' => $doc->fields[$key], 'field' => $record->fields[$key], 'doc' => $doc
			)); ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
	<?php endif ;?>
	<?php if(!empty($record->struct[DataSource_Hybrid_Field::TYPE_ARRAY])): ?>
	<div class="widget-header ">
		<h4>
			<small><?php echo __('Section'); ?> <?php echo __('Arrays of documents') ?></small>
		</h4>
	</div>
	<div id="array_fields" class="widget-content">
		<?php foreach($record->struct[DataSource_Hybrid_Field::TYPE_ARRAY] as $type => $fields): ?>
			<ul class="nav nav-tabs">
				<?php foreach($fields as $i => $key): ?>
					<li class="<?php echo $i == 0 ? 'active' : ''; ?>">
						<a href="#tab-<?php echo $key; ?>"><?php echo $record->fields[$key]->header; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		
			<div class="tabs-content">
			<?php foreach($fields as $i => $key): ?>
				<div class="tab-pane <?php echo $i == 0 ? 'active' : ''; ?>" id="tab-<?php echo $key; ?>">
					<?php echo View::factory('datasource/data/hybrid/document/fields/array', array(
						'value' => $doc->fields[$key], 'field' => $record->fields[$key], 'doc' => $doc
					)); ?>
				</div>
			</div>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
	<?php endif ;?>
		
	<?php if(!empty($record->struct[DataSource_Hybrid_Field::TYPE_USER] )): ?>
	<div class="widget-header ">
		<h4>
			<small><?php echo __('Section'); ?> <?php echo DataSource_Hybrid_Field::TYPE_USER; ?></small>
		</h4>
	</div>
	<div id="user_fields" class="widget-content">
		<?php foreach($record->struct[DataSource_Hybrid_Field::TYPE_USER] as $type => $fields): ?>
			<?php foreach($fields as $key): ?>
			<?php echo View::factory('datasource/data/hybrid/document/fields/user', array(
				'value' => $doc->fields[$key], 
				'field' => $record->fields[$key],
				'doc' => $doc
			)); ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
	<?php endif ;?>
		
	<?php if(!empty($record->struct[DataSource_Hybrid_Field::TYPE_TAGS] )): ?>
	<div class="widget-header ">
		<h4>
			<small><?php echo __('Section'); ?> <?php echo DataSource_Hybrid_Field::TYPE_TAGS; ?></small>
		</h4>
	</div>
	<div id="tags_fields" class="widget-content">
		<?php foreach($record->struct[DataSource_Hybrid_Field::TYPE_TAGS] as $type => $fields): ?>
			<?php foreach($fields as $key): ?>
			<?php echo View::factory('datasource/data/hybrid/document/fields/tags', array(
				'value' => $doc->fields[$key], 
				'field' => $record->fields[$key],
				'doc' => $doc
			)); ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
	<?php endif ;?>
		
	<?php if(Acl::check('hybrid'.$ds->id().'.document.edit')): ?>
	<div class="form-actions widget-footer">
		<?php echo UI::actions(TRUE, Route::url('datasources', array(
			'controller' => 'data',
			'directory' => 'datasources'
		)) . URL::query(array('ds_id' => $ds->id()), FALSE)); ?>
	</div>
	<?php echo Form::close(); ?>
	<?php else: ?>
	</div>
	<?php endif; ?>
</div></div>