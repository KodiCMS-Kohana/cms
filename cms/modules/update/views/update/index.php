<div class="widget">
	<div class="widget-header">
		<h3><?php echo __('Updates'); ?></h3>
	</div>
	<div class="widget-content">
		<?php if(Update::check() === Update::VERSION_OLD): ?>
		<h3>Имеется новая версия системы (<?php echo Update::remote_version(); ?>)</h3>
		
		<p>Для обновления системы вы можете скачать <?php echo Update::link('архив'); ?> с последними обновлениями и обновить папку <strong><?php echo CMSPATH; ?></strong></p>
		<p>Если репозиторий был клонирован с сервера Github, то воспользоваться командой <strong>git pull</strong></p>
		
		<div class="alert alert-warning">
			<i class="icon icon-lightbulb"></i> При замене файлов в папке CMS не забудьте настроить права доступа к папкам `cms\cache` и `cms\logs`, 
			а также сохранить изменения внесенные в ядро системы
		</div>
		<?php else: ?>
		<h3>У вас установлена последняя версия</h3>
		<?php endif; ?>
	</div>
</div>