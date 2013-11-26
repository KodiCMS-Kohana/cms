<script type="text/javascript">
cms.ui.add('check-new-messages', function() {
	count_new();
});

function count_new() {
	Api.get('user-messages.count_new', {uid: USER_ID}, function(response){
		if(response.response)
			cms.navigation.counter.add('messages', parseInt(response.response));
	});
	
	setTimeout(count_new, 10000);
}
</script>