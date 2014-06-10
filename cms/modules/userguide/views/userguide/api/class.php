<h3>
	<?php foreach ($doc->parents as $parent): ?>
	<small><?php echo __('extends'); ?> <?php echo HTML::anchor($route->uri(array('class' => $parent->name)), $parent->name, NULL, NULL, TRUE) ?></small><br/>
	<?php endforeach; ?>
</h3>

<?php echo $doc->description ?>

<?php if ($doc->tags): ?>
<dl class="class-tags dl-horizontal">
	<?php foreach ($doc->tags as $name => $set): ?>
		<dt><?php echo $name ?></dt>
		<?php foreach ($set as $tag): ?>
		<dd><?php echo $tag ?></dd>
		<?php endforeach ?>
	<?php endforeach ?>
</dl>
<?php endif; ?>

<p class="note">
	<?php if ($path = $doc->class->getFilename()): ?>
	<?php echo __('Class declared in :path on line :line.', array(
		':path' => Debug::path($path), ':line' => $doc->class->getStartLine()
	) ); ?>
	<?php else: ?>
	<?php echo __('Class is not declared in a file, it is probably an internal :link.', array(
		':link' => HTML::anchor('http://php.net/manual/class.'.strtolower($doc->class->name).'.php', 'PHP class')
	) ); ?>
	<?php endif ?>
</p>

<div class="row-fluid">
	<div class="span4">
		<h3><?php echo __('Constants'); ?></h3>
		<ul class="unstyled">
		<?php if ($doc->constants): ?>
		<?php foreach ($doc->constants as $name => $value): ?>
			<li><?php echo HTML::anchor(Request::current()->uri() . '#constant:' . $name, $name); ?></li>
		<?php endforeach ?>
		<?php else: ?>
			<li><em><?php echo __('None'); ?></em></li>
		<?php endif ?>
		</ul>
	</div>
	<div class="span4">
		<h3><?php echo __('Properties'); ?></h3>
		<ul class="unstyled">
		<?php if ($properties = $doc->properties()): ?>
		<?php foreach ($properties as $prop): ?>
			<li><?php echo HTML::anchor(Request::current()->uri() . '#property:' . $prop->property->name, $prop->property->name); ?></li>
		<?php endforeach ?>
		<?php else: ?>
			<li><em><?php echo __('None'); ?></em></li>
		<?php endif ?>
		</ul>
	</div>
	<div class="span4">
		<h3><?php echo __('Methods'); ?></h3>
		<ul class="unstyled">
		<?php if ($methods = $doc->methods()): ?>
		<?php foreach ($methods as $method): ?>
			<li><?php echo HTML::anchor(Request::current()->uri() . '#' . $method->method->name, $method->method->name. '()'); ?></li>
		<?php endforeach ?>
		<?php else: ?>
			<li><em><?php echo __('None'); ?></em></li>
		<?php endif ?>
		</ul>
	</div>
</div>

<div class="clearfix"></div>

<?php if ($doc->constants): ?>
<div class="constants">
	<h3 id="constants"><?php echo __('Constants'); ?></h3>

	<dl>
		<?php foreach ($doc->constants as $name => $value): ?>
		<dt id="constant:<?php echo $name ?>"><h4 ><?php echo $name ?></h4></dt>
		<dd><?php echo $value ?></dd>
		<?php endforeach; ?>
	</dl>
</div>

<hr />
<?php endif ?>

<?php if ($properties = $doc->properties()): ?>
<h3 id="properties"><?php echo __('Properties'); ?></h3>
<div class="properties">
	<dl>
		<?php foreach ($properties as $prop): ?>
		<dt id="property:<?php echo $prop->property->name ?>"><h4><?php echo $prop->modifiers ?> <code><?php echo $prop->type ?></code> $<?php echo $prop->property->name ?></h4></dt>
		<dd><?php echo $prop->description ?></dd>
		<dd><?php echo $prop->value ?></dd>
		<?php endforeach ?>
	</dl>
</div>

<hr />
<?php endif ?>

<?php if ($methods = $doc->methods()): ?>
<h3 id="methods"><?php echo __('Methods'); ?></h3>
<div class="methods">
	<?php foreach ($methods as $method): ?>
	<?php echo View::factory('userguide/api/method')->set('doc', $method)->set('route', $route) ?>
	<br />
	<?php endforeach ?>
</div>
<?php endif ?>
