<script type="text/javascript">
function htmlspecialchars_decode(string, quote_style) {
		var optTemp = 0,
				i = 0,
				noquotes = false;
		if (typeof quote_style === 'undefined') {
			quote_style = 2;
		}
		string = string.toString().replace(/&lt;/g, '<').replace(/&gt;/g, '>');
		var OPTS = {
			'ENT_NOQUOTES': 0,
			'ENT_HTML_QUOTE_SINGLE': 1,
			'ENT_HTML_QUOTE_DOUBLE': 2,
			'ENT_COMPAT': 2,
			'ENT_QUOTES': 3,
			'ENT_IGNORE': 4
		};
		if (quote_style === 0) {
			noquotes = true;
		}
		if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
			quote_style = [].concat(quote_style);
			for (i = 0; i < quote_style.length; i++) {
				// Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
				if (OPTS[quote_style[i]] === 0) {
					noquotes = true;
				} else if (OPTS[quote_style[i]]) {
					optTemp = optTemp | OPTS[quote_style[i]];
				}
			}
			quote_style = optTemp;
		}
		if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
			string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
			// string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
		}
		if (!noquotes) {
			string = string.replace(/&quot;/g, '"');
		}
		// Put this in last place to avoid escape being double-decoded
		string = string.replace(/&amp;/g, '&');

		return string;
	}

$(function() {
	var text1 = $('#text').html();
	
	$('.btn-clear').on('click', function() {
		$('#text').html(text1);
	});

	$('.btn-diff').on('click', function() {
		var id = $(this).data('id');
		
		var dmp = new diff_match_patch();
		
		var text2 = $('.text'+id).html();

		var d = dmp.diff_main(text1, text2);
		dmp.diff_cleanupSemantic(d);
		var ds = dmp.diff_prettyHtml(d);
		$('#text').html(htmlspecialchars_decode(ds));
	});
});

</script>

<?php if(!empty($part->id)): ?>
<div class="panel">
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Current text'); ?></span>
	</div>
	<div class="panel-body">
		<pre id="text"><?php echo htmlspecialchars($part->content); ?></pre>
		
		<button class="btn btn-default btn-xs btn-clear"><?php echo __('Clear diff'); ?></button>
	</div>

	<?php foreach($parts as $id => $p):?>
	<div class="panel-heading">
		<span class="panel-title"><?php echo Date::format($p->created_on, 'd F Y H:i'); ?></span>
	</div>

	<div class="panel-body">
		<pre class="text<?php echo $p->id; ?>"><?php echo htmlspecialchars($p->content); ?></pre>
		
		<br />
		<span class="btn-group">
			<?php if(!empty($part->id)): ?>
			<button class="btn btn-default btn-xs btn-diff" data-id="<?php echo $p->id; ?>">
				<?php echo __('Show diff'); ?>
			</button>
			<?php endif ;?>			
			<?php echo HTML::anchor(Route::get('backend')->uri(array(
				'controller' => 'part',
				'action' => 'revert',
				'id' => $p->id
			)), __('Use this revision'), array('class' => 'btn btn-xs btn-confirm btn-success')); ?>
		</span>
	</div>
	<?php endforeach; ?>
</div>
<?php endif ;?>