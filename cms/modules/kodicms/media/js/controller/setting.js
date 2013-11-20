cms.init.add('setting_index', function () {
   
   $('#clear-cache').on('click', function() {
      Api.get('cache.clear', {}, function() {
          
      });
      
      
      return false;
   });
});