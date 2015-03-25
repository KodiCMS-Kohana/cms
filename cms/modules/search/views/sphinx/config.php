<?php
$connection_string = '';

$connection_string .= "\ttype\t\t\t= mysql\n";
$connection_string .= "\tsql_host\t\t= " . DB_SERVER . "\n";
$connection_string .= "\tsql_user\t\t= " . DB_USER . "\n";
$connection_string .= "\tsql_pass\t\t= @DB_PASS@\n";
$connection_string .= "\tsql_db\t\t\t= " . DB_NAME . "\n";
$connection_string .= "\tsql_port\t\t= " . DB_PORT . "\n";
$connection_string .= "\tsql_query_pre\t= SET NAMES utf8\n\n";
?>

<div class="panel">
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Sphinx config'); ?></span>
	</div>

	<textarea id="highlight_content" data-readonly="on" data-mode="ini">
# @CONFDIR@ = <?php echo DOCROOT; ?>

<?php foreach (Datasource_Data_Manager::get_all_indexed() as $ds_array): ?>
<?php 
$ds = Datasource_Section::load($ds_array['id']);
if (!$ds->loaded())
{
	continue;
}

$fields = $ds->get_indexable_fields();

if (!empty($fields))
{
	$fields = implode(', ', $fields) . ',';
}
?>
source src_ds_<?php echo $ds->id(); ?><?php echo PHP_EOL; ?>
{
<?php echo $connection_string; ?>

	sql_query		= \
		SELECT dsh.id, ds_id, published, header, created_on, <?php echo $fields; ?> \
		'ds_<?php echo $ds->id(); ?>' as module \
		FROM dshybrid_<?php echo $ds->id(); ?> as dsh \
		LEFT JOIN dshybrid ON dshybrid.id = dsh.id

	sql_field_string	= header
	sql_field_string	= module
	sql_field_string	= created_on
	sql_attr_bool		= published
}

index ds_<?php echo $ds->id(); ?><?php echo PHP_EOL; ?>
{
	source			= src_ds_<?php echo $ds->id(); ?><?php echo PHP_EOL; ?>
	path			= @CONFDIR@/data/ds_<?php echo $ds->id(); ?><?php echo PHP_EOL; ?>
	morphology		= stem_en, stem_ru
}
<?php endforeach; ?>

source src_page_parts
{
<?php echo $connection_string; ?>
	sql_query		= \
		SELECT id, page_id, content_html, 'pages' as module \
		FROM <?php echo TABLE_PREFIX; ?>page_parts \
		WHERE is_indexable = 1

	sql_field_string	= content_html
}

index page_parts
{
	source			= src_page_parts
	path			= @CONFDIR@/data/page_parts
	morphology		= stem_en, stem_ru
}

indexer
{
	mem_limit		= 128M
}

searchd
{
	listen			= <?php echo Arr::get($config, 'port', 9312); ?><?php echo PHP_EOL; ?>
	listen			= 9306:mysql41
	log				= @CONFDIR@/log/searchd.log
	query_log		= @CONFDIR@/log/query.log
	read_timeout	= 5
	max_children	= 30
	pid_file		= @CONFDIR@/log/searchd.pid
	seamless_rotate	= 1
	preopen_indexes	= 1
	unlink_old		= 1
	workers			= threads # for RT to work
	binlog_path		= @CONFDIR@/data
}</textarea>
</div>