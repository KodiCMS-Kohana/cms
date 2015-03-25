<?php echo Form::open(Request::current()->uri(), array(
	'class' => array(Form::HORIZONTAL, 'panel')
)); ?>
	<?php echo Form::hidden('id', $widget->id); ?>

	<div class="panel-heading panel-toggler" data-target-spoiler=".general-spoiler" data-hash="description">
		<span class="panel-title" data-icon="info-circle"><?php echo __('Widget Information'); ?></span>
	</div>
	<div class="panel-body panel-spoiler general-spoiler">
		<div class="form-group form-group-lg">
			<label class="control-label col-md-3"><?php echo __('Widget Header'); ?></label>
			<div class="col-md-9">
				<?php echo Form::input('name', $widget->name, array(
					'class' => 'form-control'
				)); ?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Widget Description'); ?></label>
			<div class="col-md-9">
				<?php echo Form::textarea('description', $widget->description, array(
					'class' => 'form-control', 'rows' => 4
				)); ?>
			</div>
		</div>
		
		<div class="form-group form-inline">
			<label class="control-label col-md-3"><?php echo __('Type'); ?></label>
			<div class="col-md-9">
				<?php echo Form::input(NULL, $widget->type(FALSE), array(
					'class' => 'form-control', 'disabled', 'size' => 50
				)); ?>
			</div>
		</div>
	</div>

	<?php if ($widget->use_template()): ?>
	<div class="panel-heading">
		<span class="panel-title" data-icon="hdd-o"><?php echo __('Widget template'); ?></span>
	</div>
	<div class="note note-info no-margin-b">
		<div class="row">
			<div class="col-sm-offset-2 col-sm-10">
				<strong><?php echo __('Widget variables'); ?>: </strong>
				<?php foreach ($params as $param): ?>
				<?php echo UI::label($param); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php echo View::factory('helper/snippet_select', array(
		'template' => $widget->template,
		'default' => $widget->default_template() 
			? UI::button(UI::hidden(__('Default template'), array('sm', 'xs')), array(
				'href' => Route::get('backend')->uri(array(
					'controller' => 'widgets', 
					'action' => 'template',
					'id' => $widget->id
				)), 'icon' => UI::icon('desktop'),
				'id' => 'defaultTemplateButton',
				'class' => 'popup fancybox.iframe btn-default'
			)) 
			: NULL
	)); ?>
	
	
	<?php endif; ?>

	<?php if ($widget->use_caching() AND ACL::check('widgets.cache')): ?>
	<div class="panel-heading">
		<span class="panel-title" data-icon="hdd-o"><?php echo __('Caching'); ?></span>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<div class="checkbox col-xs-offset-3">
				<label>
					<?php echo Form::checkbox('caching', 1, (bool) $widget->caching, array(
						'class' => 'px', 'id' => 'caching'
					)); ?>
					<span class="lbl"><?php echo __('Cache enabled'); ?></span>
				</label>
			</div>
		</div>
		
		<div id="cache_settings_container">
			<div id="cache_lifetime_group" class="form-group">
				<label class="control-label col-xs-3" for="cache_lifetime"><?php echo __('Cache lifetime'); ?></label>
				<div class="col-xs-3">
					<?php echo Form::input('cache_lifetime', $widget->cache_lifetime, array(
						'class' => 'form-control', 'id' => 'cache_lifetime'
					)); ?>
				</div>
				
				<div class="col-md-6">
					<span class="flags" id="cache_lifetime_labels" data-target="#cache_lifetime">
						<span class="label" data-value="<?php echo Date::MINUTE; ?>"><?php echo __('Minute'); ?></span> 
						<span class="label" data-value="<?php echo Date::HOUR; ?>"><?php echo __('Hour'); ?></span>
						<span class="label" data-value="<?php echo Date::DAY; ?>"><?php echo __('Day'); ?></span>
						<span class="label" data-value="<?php echo Date::WEEK; ?>"><?php echo __('Week'); ?></span>
						<span class="label" data-value="<?php echo Date::MONTH; ?>"><?php echo __('Month'); ?></span>
						<span class="label" data-value="<?php echo Date::YEAR; ?>"><?php echo __('Year'); ?></span>
					</span>
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-xs-3"><?php echo __('Cache tags'); ?></label>
				<div class="col-xs-9">
					<?php echo Form::textarea('cache_tags',$widget->cache_tags(), array(
						'class' => 'tags'
					)); ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if (ACL::check('widgets.roles') AND ! $widget->is_handler()): ?>
	<div class="panel-heading panel-toggler" data-target-spoiler=".roles-spoiler" data-hash="roles">
		<span class="panel-title" data-icon="users"><?php echo __('Widget permissions'); ?></span>
	</div>
	<div class="panel-body panel-spoiler roles-spoiler">
		<?php echo Form::select('roles[]', $roles, $widget->roles, array(
			'class' => 'col-md-12'
		)); ?>
	</div>
	<?php endif; ?>
	
	<?php if ($widget->use_template()): ?>
	<div class="panel-heading panel-toggler" data-target-spoiler=".media-spoiler" data-hash="media">
		<span class="panel-title" data-icon="file-o"><?php echo __('Widget media'); ?></h4>
	</div>
	<div class="panel-body panel-spoiler media-spoiler">		
		<?php echo View::factory('helper/rows_only_value', array(
			'field' => 'media',
			'data' => $widget->media
		)); ?>
		<hr />
		<div class="form-group">
			<div class="col-xs-12">
				<label class="control-label"><?php echo __('Media packages'); ?></label>
				<?php echo Form::select('media_packages[]', Assets_Package::select_choises(), (array)$widget->media_packages, array(
					'class' => 'form-control'
				)); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
	
	<div class="panel-heading">
		<span class="panel-title" data-icon="cogs"><?php echo __('Widget parameters'); ?></span>
	</div>
	<?php if ($widget->use_template()): ?>
	<div class="panel-body">
		<div class="form-group">
			<label class="control-label col-xs-3"><?php echo __('Header'); ?></label>
			<div class="col-xs-9">
				<?php echo Form::input('header', $widget->header, array(
					'class' => 'form-control'
				)); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php echo $content; ?>
	<?php if ($widget->is_handler()): ?>
	<div class="alert alert-danger note-dark no-margin-b">
		<?php echo __('To use handler send your data to URL :href or use route :route', array(
			':href' => '<code>' . URL::site($widget->link(), TRUE) . '</code>',
			':route' => '<code>Route::get(\'handler\')->uri(array(\'id\' => ' .$widget->id. '));</code>'
		)); ?>
	</div>
	<?php endif; ?>
	<?php if (ACL::check('widgets.location')): ?>
	<hr class="no-margin-vr" />
	<div class="panel-body">
		<?php echo HTML::anchor(Route::get('backend')->uri(array(
		'controller' => 'widgets', 
		'action' => 'location',
		'id' => $widget->id)), __('Widget location'), array(
			'class' => 'btn btn-primary popup fancybox.iframe',
			'data-icon' => 'sitemap'
		)); ?>
	</div>
	<?php endif; ?>
	<div class="panel-footer form-actions">
		<?php echo UI::actions($page_name); ?>
	</div>
<?php echo Form::close(); ?>