$(function(){

	var router = new Router(basePath);
	
	router.setControllers({

		users : TableAware.extend({

			startup : function(element){
				
				var self = this;

				this._super(element);
				
			}
			
		})
				
	});	
	
});