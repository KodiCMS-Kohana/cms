<?php if(count($messages) > 0): ?>
<ul class="mail-list padding-sm-vr no-margin-t">
	<?php foreach($messages as $message): ?>
	<li class="mail-item <?php if( $message->is_read == Model_API_Message::STATUS_NEW): ?>unread<?php endif; ?> <?php if( $message->is_starred == Model_API_Message::STARRED): ?>starred<?php endif; ?>" data-id="<?php echo $message->id; ?>">
		<div class="m-chck"><label class="px-single"><input type="checkbox" name="" value="" class="select-checkbox"></label></div>
		<div class="m-star"><a href="#"></a></div>
		<div class="m-from"><?php echo HTML::anchor(Route::get('backend')->uri(array(
			'controller' => 'users',
			'action' => 'profile',
			'id' => $message->from_user_id
		)), $message->author); ?></div>
		<div class="m-subject">
			<?php if( $message->is_read == Model_API_Message::STATUS_NEW): ?><?php echo UI::label(__('New'), 'info'); ?><?php endif; ?>
			<?php echo HTML::anchor(Route::get('backend')->uri(array('controller' => 'messages', 'action' => 'view', 'id' => (int) $message->id)), $message->title); ?>
		</div>
		<div class="m-date"><?php echo Date::format($message->created_on); ?></div>
	</li>
	<?php endforeach; ?>

	<div class="clearfix"></div>
</ul>
<?php else: ?>
<div class="panel-body">
	<h2 class="no-margin-t"><?php echo __('You dont have messages'); ?></h2>
</div>
<?php endif; ?>