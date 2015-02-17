<script type="text/javascript">
$(function() {
	$('#ace-select').on('change', function() {
		change_ace_theme($(this).val());
	});
	
	cms.filters.switchOn('highlight_content', 'ace', $('#textarea_content').data());
});
function change_ace_theme(theme) {
	var editor = ace.edit('highlight_contentDiv');
	editor.setTheme("ace/theme/" + theme);
}
</script>
<div class="panel-heading" data-icon="code-o">
	<span class="panel-title"><?php echo __('Ace settings'); ?></span>
</div>
<div class="panel-body no-padding-b">
	<div class="well no-margin-b">
		<?php echo Form::label('ace-select', __('Select theme')); ?>
		<?php echo Form::select('setting[ace][theme]', array(
			'ambiance' => 'ambiance',
			'chaos' => 'chaos',
			'chrome' => 'chrome',
			'clouds' => 'clouds',
			'clouds_midnight' => 'clouds_midnight',
			'cobalt' => 'cobalt',
			'crimson_editor' => 'crimson_editor',
			'dawn' => 'dawn',
			'dreamweaver' => 'dreamweaver',
			'eclipse' => 'eclipse',
			'github' => 'github',
			'idle_fingers' => 'idle_fingers',
			'katzenmilch' => 'katzenmilch',
			'kr_theme' => 'kr_theme',
			'kuroir' => 'kuroir',
			'merbivore' => 'merbivore',
			'merbivore_soft' => 'merbivore_soft',
			'mono_industrial' => 'mono_industrial',
			'monokai' => 'monokai',
			'pastel_on_dark' => 'pastel_on_dark',
			'solarized_dark' => 'solarized_dark',
			'solarized_light' => 'solarized_light',
			'terminal' => 'terminal',
			'textmate' => 'textmate',
			'tomorrow' => 'tomorrow',
			'tomorrow_night' => 'tomorrow_night',
			'tomorrow_night_blue' => 'tomorrow_night_blue',
			'tomorrow_night_bright' => 'tomorrow_night_bright',
			'tomorrow_night_eighties' => 'tomorrow_night_eighties',
			'twilight' => 'twilight',
			'vibrant_ink' => 'vibrant_ink',
			'xcode' => 'xcode'
		), Config::get('ace', 'theme', 'textmate'), array(
			'id' => 'ace-select', 'class' => 'form-control'
		)); ?>
	</div>

	<textarea id="highlight_content" name="content" data-height="470" data-mode="html" data-readonly="on">
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <h1>Hello, world!</h1>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html></textarea>
</div>