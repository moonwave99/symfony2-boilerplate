$(function(){

	var router = new Router(basePath);
	
	router.setControllers({

		users : TableAware.extend({

			startup : function(element){
				
				var self = this;

				this._super(element, {
					
					_roles : {
					
						ROLE_SUPER_ADMIN : 'Admin',
						ROLE_WRITER : 'Redattore',
						ROLE_ORGANIZER : 'Organizzatore',
						ROLE_USER : 'Utente'
						
					},
					
					_tableOptions : {
					
					    fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

							var value = _.reduce(aData.roles, function(memo, element){ return memo + self._roles[element] + ' / ' }, '');

							$('td:eq(3)', nRow).html( value.substring(0, value.length -3) );

					    }				
						
					}
					
				});
				
			}
			
		}),
		
		articles : TableAware.extend({

			startup : function(element){
				
				var self = this;

				this._super(element, {
					
					_entity : 'article'
					
				});
				
				this._articleBody = $('#article_body', this._element);
				
			},
			
			single : function(element){
				
				var self = this;

				if(!self._editor){
				
					self._editor = ace.edit('editor');
					self._editor.setTheme("ace/theme/twilight");
					self._editor.setShowPrintMargin(false);
					self._editor.renderer.setShowGutter(false);
					self._editor.getSession().setUseWrapMode(true);
					self._editor.getSession().setWrapLimitRange();					
					
				}

				self._editor.getSession().getDocument().setValue(self._articleBody.val());				
				
			},
			
			post : function(form){
				
				this._articleBody.val( this._editor.getSession().getDocument().getValue() );

				this._super(form, function(){

					$('[data-action="showRules"]').attr('data-disabled', true).addClass('disabled');
					
				});

			},

			put : function(form, callback){
				
				this._articleBody.val( this._editor.getSession().getDocument().getValue() );				

				this._super(form, function(){
					
					$('[data-action="showRules"]').attr('data-disabled', true).addClass('disabled');
					
				});

			}			
			
		}),		
		
		leagues : TableAware.extend({

			startup : function(element){
				
				var self = this;

				this._super(element, {
					
					_templates : {
					
						torneiButton : _.template( $('#template-league-tornei-button').html() )
						
					},
					
					_tableOptions : {
					
					    fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

							$('td:eq(4)', nRow).html( self._templates.torneiButton({ slug : aData.slug }) );

					    }				
						
					}					
					
				});
				
				this._modal.on('hidden', function(){
					
					self._editor = null;
					
				});
				
			},
			
			showRules : function(element){
				
				var self = this;

				$formWrapper = $('.form-wrapper', self._modal);				
				$aceWrapper = $('.form-ace', self._modal);

				$.when(
					
					$formWrapper.animate(
						{
							'opacity' : '-=1'
						}
					),
					$('.modal-body', self._modal).animate(
						{
							'height' : '+=150px'					
						}
					),				
					self._modal.animate({
						'width' : '+=200px',
						'left' : '-=100px',
						'top' : '-=50px'					
					})		
								
				).then(function(){
						
					if(!self._editor){
					
						self._editor = ace.edit("editor");
						self._editor.setTheme("ace/theme/twilight");
						self._editor.setShowPrintMargin(false);
						self._editor.renderer.setShowGutter(false);						
						self._editor.getSession().setUseWrapMode(true);
						self._editor.getSession().setWrapLimitRange();					
						
					}

					self._editor.getSession().getDocument().setValue($('#league_rules', $formWrapper).val());					

					$('input[type=submit]', self._modal).attr('disabled', true);
					$formWrapper.hide();
					$aceWrapper.show();
					$('.btn-mini span', $aceWrapper).removeAttr('style');
					$('#editor', $aceWrapper).show();
					
				});

			},
			
			aceToggle : function(element){
				
				var self = this;
				
				$('span', element).toggle();
				
			},
			
			aceDone : function(element){
				
				var self = this;
				
				$formWrapper = $('.form-wrapper', self._modal);
				$aceWrapper = $('.form-ace', self._modal);
				
				var value = self._editor.getSession().getDocument().getValue();
				self._editor.destroy();
				
				$('#editor', $aceWrapper).hide();
				
				$.when(
					
					$('.modal-body', self._modal).animate(
						{
							'height' : '-=150px'					
						}
					),				
					self._modal.animate({
						'width' : '-=200px',
						'left' : '+=100px',
						'top' : '+=50px'					
					})			
							
				).then(function(){

					$('input[type=submit]', self._modal).removeAttr('disabled');
					$aceWrapper.hide();					
					$formWrapper.css('opacity', 1).show();

					$('#league_rules', $formWrapper).val( value	);

				});				
				
			},
			
			post : function(form){

				this._super(form, function(){

					$('[data-action="showRules"]').attr('data-disabled', true).addClass('disabled');
					
				});

			},

			put : function(form, callback){

				this._super(form, function(){
					
					$('[data-action="showRules"]').attr('data-disabled', true).addClass('disabled');
					
				});

			}			
			
		})		
		
	});	
	
});