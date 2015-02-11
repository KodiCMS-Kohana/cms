<script type="text/javascript">
$(function() {
	cms.filters.switchOn('highlight_content', DEFAULT_CODE_EDITOR, $('#textarea_content').data());
});
</script>
<textarea id="highlight_content" data-readonly="on"><?php echo htmlentities($data); ?></textarea>