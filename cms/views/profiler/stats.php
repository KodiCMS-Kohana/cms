<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<style type="text/css">
.kohana table.profiler { width: 99%; margin: 0 auto 1em; border-collapse: collapse; }
.kohana table.profiler th,
.kohana table.profiler td { padding: 0.2em 0.4em; background: #fff; border: solid 1px #999; border-width: 1px 0; text-align: left; font-weight: normal; font-size: 1em; color: #111; vertical-align: top; text-align: right; }
.kohana table.profiler th.name { text-align: left; }
.kohana table.profiler tr.group th { font-size: 1.4em; background: #222; color: #eee; border-color: #222; }
.kohana table.profiler tr.group td { background: #222; color: #777; border-color: #222; }
.kohana table.profiler tr.group td.time { padding-bottom: 0; }
.kohana table.profiler tr.headers th { text-transform: lowercase; font-variant: small-caps; background: #ddd; color: #777; }
.kohana table.profiler tr.mark th.name { width: 40%; font-size: 1.2em; background: #fff; vertical-align: middle; }
.kohana table.profiler tr.mark td { padding: 0; }
.kohana table.profiler tr.mark.final td { padding: 0.2em 0.4em; }
.kohana table.profiler tr.mark td > div { position: relative; padding: 0.2em 0.4em; }
.kohana table.profiler tr.mark td div.value { position: relative; z-index: 2; }
.kohana table.profiler tr.mark td div.graph { position: absolute; top: 0; bottom: 0; right: 0; left: 100%; background: #71bdf0; z-index: 1; }
.kohana table.profiler tr.mark.memory td div.graph { background: #acd4f0; }
.kohana table.profiler tr.mark td.current { background: #eddecc; }
.kohana table.profiler tr.mark td.min { background: #d2f1cb; }
.kohana table.profiler tr.mark td.max { background: #ead3cb; }
.kohana table.profiler tr.mark td.average { background: #ddd; }
.kohana table.profiler tr.mark td.total { background: #d0e3f0; }
.kohana table.profiler tr.time td { border-bottom: 0; font-weight: bold; }
.kohana table.profiler tr.memory td { border-top: 0; }
.kohana table.profiler tr.final th.name { background: #222; color: #fff; }
.kohana table.profiler abbr { border: 0; color: #777; font-weight: normal; }
.kohana table.profiler:hover tr.group td { color: #ccc; }
.kohana table.profiler:hover tr.mark td div.graph { background: #1197f0; }
.kohana table.profiler:hover tr.mark.memory td div.graph { background: #7cc1f0; }
</style>

<?php
$group_stats      = Profiler::group_stats();
$group_cols       = array('min', 'max', 'average', 'total');
$application_cols = array('min', 'max', 'average', 'current');
?>

<div class="kohana">
	<?php foreach (Profiler::groups() as $group => $benchmarks): ?>
	<table class="profiler">
		<tr class="group">
			<th class="name" rowspan="2"><?php echo __(ucfirst($group)) ?></th>
			<td class="time" colspan="4"><?php echo number_format($group_stats[$group]['total']['time'], 6) ?> <abbr title="seconds">s</abbr></td>
		</tr>
		<tr class="group">
			<td class="memory" colspan="4"><?php echo number_format($group_stats[$group]['total']['memory'] / 1024, 4) ?> <abbr title="kilobyte">kB</abbr></td>
		</tr>
		<tr class="headers">
			<th class="name"><?php echo __('Benchmark') ?></th>
			<?php foreach ($group_cols as $key): ?>
			<th class="<?php echo $key ?>"><?php echo __(ucfirst($key)) ?></th>
			<?php endforeach ?>
		</tr>
		<?php foreach ($benchmarks as $name => $tokens): ?>
		<tr class="mark time">
			<?php $stats = Profiler::stats($tokens) ?>
			<th class="name" rowspan="2" scope="rowgroup"><?php echo HTML::chars($name), ' (', count($tokens), ')' ?></th>
			<?php foreach ($group_cols as $key): ?>
			<td class="<?php echo $key ?>">
				<div>
					<div class="value"><?php echo number_format($stats[$key]['time'], 6) ?> <abbr title="seconds">s</abbr></div>
					<?php if ($key === 'total'): ?>
						<div class="graph" style="left: <?php echo max(0, 100 - $stats[$key]['time'] / $group_stats[$group]['max']['time'] * 100) ?>%"></div>
					<?php endif ?>
				</div>
			</td>
			<?php endforeach ?>
		</tr>
		<tr class="mark memory">
			<?php foreach ($group_cols as $key): ?>
			<td class="<?php echo $key ?>">
				<div>
					<div class="value"><?php echo number_format($stats[$key]['memory'] / 1024, 4) ?> <abbr title="kilobyte">kB</abbr></div>
					<?php if ($key === 'total'): ?>
						<div class="graph" style="left: <?php echo max(0, 100 - $stats[$key]['memory'] / $group_stats[$group]['max']['memory'] * 100) ?>%"></div>
					<?php endif ?>
				</div>
			</td>
			<?php endforeach ?>
		</tr>
		<?php endforeach ?>
	</table>
	<?php endforeach ?>

	<table class="profiler">
		<?php $stats = Profiler::application() ?>
		<tr class="final mark time">
			<th class="name" rowspan="2" scope="rowgroup"><?php echo __('Application Execution').' ('.$stats['count'].')' ?></th>
			<?php foreach ($application_cols as $key): ?>
			<td class="<?php echo $key ?>"><?php echo number_format($stats[$key]['time'], 6) ?> <abbr title="seconds">s</abbr></td>
			<?php endforeach ?>
		</tr>
		<tr class="final mark memory">
			<?php foreach ($application_cols as $key): ?>
			<td class="<?php echo $key ?>"><?php echo number_format($stats[$key]['memory'] / 1024, 4) ?> <abbr title="kilobyte">kB</abbr></td>
			<?php endforeach ?>
		</tr>
	</table>
</div>