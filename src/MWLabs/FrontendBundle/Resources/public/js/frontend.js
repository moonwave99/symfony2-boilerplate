$(function(){

	var router = new Router(basePath);
	router.setControllers({

		home : Controller.extend({

			startup : function(element){
				
				this._super(element);				
				
			}
			
		})		
	});
	
});