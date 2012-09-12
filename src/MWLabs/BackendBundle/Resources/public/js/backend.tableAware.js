(function(){

	$.extend( $.fn.dataTableExt.oStdClasses, {
	    "sWrapper": "dataTables_wrapper form-inline"
	} );	
	
	TableAware = Controller.extend({
	
		startup : function(element, options){

			var self = this;
			
			this._super(element);	

			this._table = $('#table', element);
			this._modal = $('#modal')
				.modal({ 'keyboard' : false, 'show' : false})
				.on('show', function(){ $(this).removeAttr('style') })
				.on('shown', function(){ $('input', this).first().focus() });
				
			this._entity = this._table.attr('data-entity');	
			
			this._messages = {
				
				500 : "We had a problem, please contact webmaster."
				
			};
	
			this._templates = {};
			
			var tmpl = {
				newForm : 'form-new',
				removeForm : 'form-remove',
				rowActions : 'row-actions'
			};
			
			var $t;
			
			for( key in tmpl)
			{

				if( ($t = $('#template-' + this._entity + '-' + tmpl[key])).length == 0 )
					continue;
					
				this._templates[key] = _.template($t.html());
				
			}
			
			this._tableOptions = {
			    'fnInitComplete': function(oSettings, json) {
					$('.loader', element).hide();
					$('.hidelet', element).fadeIn();
			    },				
				'sDom': "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
				'sPaginationType': 'bootstrap',
				'oLanguage': {
					sInfo: 'Showing: _START_-_END_ of _TOTAL_.',
					sLengthMenu : 'Show _MENU_ elements per page.',
					sSearch : 'Filter results: '
				},
				'bProcessing' : true,
				'sAjaxSource' : $(this._table).attr('data-source'),
				'sAjaxDataProp' : '',
			    'aoColumnDefs': _.reduce($('thead th[data-m-data]', this.table), function(memo, element){ 
					
					var props = $(element).data();
					props.aTargets = [memo.length];
					
					if( props.mData == 'id'){
						$.extend( props, { mRender : function ( data, type, full ) { return self._templates.rowActions({ id : full.id }) } } );
					}
					
					if( props.type == 'boolean'){
						$.extend( props, { mRender : function ( data, type, full ) { return full[props.mData] ? '<i class="icon-ok"></i>' : '-' } } );						
					}
					
					memo.push(props);
					return memo;
				}, [])
		    };
			
			$.extend(true, this, options);

			this._table.dataTable( this._tableOptions );
		
			/*===========================================
			 * Private functions
			 *===========================================*/
			this._injectErrors = function(form, errors){
				
				$('.error', form).html('');
				
				for(key in errors){ $('[name$="[' + key + ']"]', form).parent().find('.error').html(errors[key]) }
				
			}
			
			this._restCall = function(form, type, callback){
			
				var self = this;
				var $form = $(form);
				var $button = $('.btn-primary', $form);

				if($button.hasClass('btn-success'))
					return;				
				else
					$button.button('loading');

				var url = $form.attr('action') + ( (type == 'PUT' || type == 'DELETE') ? '/' + $("input[name='" + this._entity + "[id]']", $form).val() : '' );

				$.ajax({
					url : url,
					type : type,
					data : $form.serialize(),
					dataType : 'json',
					statusCode : {
						
						200 : function(data, textStatus, jqXHR){

							$('.error', form).html('');
							$button.button('complete').addClass('btn-success').attr('disabled', true);							
							$form.find('.alert-danger').hide();	
							self._table.dataTable().fnReloadAjax();										

						},						

						201 : function(data, textStatus, jqXHR){

							$('.error', form).html('');
							$button.button('complete').addClass('btn-success').attr('disabled', true);							
							$form.find('.alert-danger').hide();	
							self._table.dataTable().fnReloadAjax();										

						},
						
						204 : function(data, textStatus, jqXHR){

							$('.error', form).html('');
							$button.button('complete').addClass('btn-success').attr('disabled', true);							
							$form.find('.alert-danger').hide();	
							self._table.dataTable().fnReloadAjax();										

						},						

						400 : function(jqXHR, textStatus, errorThrown){

							var data = $.parseJSON(jqXHR.responseText);
							self._injectErrors(form, data.errors);
							$button.button('complete').addClass('btn-danger').val('Please retry!');						
							if(data.message)
								$form.find('.alert-danger .message').html(data.message).parent().show();

						},
						
						403 : function(jqXHR, textStatus, errorThrown){

							var data = $.parseJSON(jqXHR.responseText);
							$button.button('complete').addClass('btn-danger').val('Please retry!');
							$form.find('.alert-danger .message').html(data.message).parent().show();

						},						

						500 : function(jqXHR, textStatus, errorThrown){

							$button.button('complete').addClass('btn-danger').val('Please retry!');
							$form.find('.alert-danger .message').html(self._messages[500]).parent().show();

						}

					},
					
					complete : function(jqXHR, textStatus)
					{
						
						if(callback)
							callback.call(self);

					}

				});				
				
			};
		
		},
		
		showNewForm : function(element){
			
			$(this._modal)
				.html(this._templates.newForm({ action : 'post'}))
				.modal('show');
			
		},
		
		showEditForm : function(element){
		
			var entry = this._table.dataTable().fnGetData( $(element).closest('tr')[0] );
			var $modal = $(this._modal).html(this._templates.newForm({ action : 'put', entry : entry }));
			var $form = $modal.find('form');
			
			for(key in entry){

				$div = $form.find('div#' + this._entity + '_' + key);
				if($div.length > 0){
					
					for(var i = 0; i < entry[key].length; i++){
						
						$('input[name="' + this._entity + '['+ key +']['+ entry[key][i] +']"]', $div).attr('checked', true);
						
					}
					
				}else{
				
					$form.find('[name$="[' + key + ']"]').val( typeof(entry[key]) == 'boolean' ? entry[key]+0 : entry[key]);				
					
				}
				
			}
			
			$modal.modal('show');
			
		},
		
		showRemoveForm : function(element){
		
			$(this._modal)
				.html(this._templates.removeForm( { entry : this._table.dataTable().fnGetData( $(element).closest('tr')[0] ) } ))
				.modal('show');
			
		},		
		
		post : function(form, callback){
			
			this._restCall(form, 'POST', callback);

		},
		
		put : function(form, callback){

			this._restCall(form, 'PUT', callback);
			
		},
		
		remove : function(form, callback){
			
			this._restCall(form, 'DELETE', callback);
			
		}
		
	});
	
})();