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

			/*===========================================
			 * Templates
			 *===========================================*/
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

			/*===========================================
			 * Data Formatters
			 *===========================================*/			
			this._formatters = {

				"id" : function(value){

					return self._templates.rowActions({ id : value });

				},

				"boolean" : function(value){
					return value ? '<i class="icon-ok"></i>' : '-';
				},

				"date" : function(value){
					return value;
				}

			};			

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

					if( props.type in self._formatters){

						$.extend( props, { mRender : function ( data, type, full ) { return self._formatters[props.type](full[props.mData]) } } );

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

				$('.error', form).remove();

				for(key in errors){ $('[name$="[' + key + ']"]', form).after($('<div></div>').html( errors[key] ).addClass('error')) }

			}

			this._getRoute = function($form, type){

				var params = {}, routeParams = $form.data('route-params'), route = type.toLowerCase() + '_';

				for(var i = 0; i < routeParams.length; i++){

					params[ routeParams[i] ] = $("input[name='" + this._entity + "[" + routeParams[i] + "]']", $form).val();

					route += ( i != routeParams.length -1 ) ? routeParams[i] + 's_' : this._entity + 's';

				}

				return Routing.generate( route, params );

			};

			this._restCall = function(form, type, callback){

				var self = this;
				var $form = $(form);
				var $button = $('.btn-primary', $form);

				if($button.hasClass('btn-success'))
					return;				
				else
					$button.button('loading');

				$.ajax({
					url : this._getRoute($form, type),
					type : type,
					data : $form.serialize(),
					dataType : 'json',
					statusCode : {

						200 : function(data, textStatus, jqXHR){

							$('.error', form).html('');
							$button.button('complete').addClass('btn-success').attr('disabled', true);							
							$form.find('.alert-danger').hide();	
							self._table.length > 0 && self._table.dataTable().fnReloadAjax();										

						},						

						201 : function(data, textStatus, jqXHR){

							$('.error', form).html('');
							$button.button('complete').addClass('btn-success').attr('disabled', true);							
							$form.find('.alert-danger').hide();	
							self._table.length > 0 && self._table.dataTable().fnReloadAjax();								

						},

						204 : function(data, textStatus, jqXHR){

							$('.error', form).html('');
							$button.button('complete').addClass('btn-success').attr('disabled', true);							
							$form.find('.alert-danger').hide();	
							self._table.length > 0 && self._table.dataTable().fnReloadAjax();							

						},						

						400 : function(jqXHR, textStatus, errorThrown){

							var data = $.parseJSON(jqXHR.responseText);
							self._injectErrors(form, data.errors);
							$button.button('complete').addClass('btn-danger').val('Ooops! Riprova');						
							if(data.message)
								$form.find('.alert-danger .message').html(data.message).parent().show();

						},

						403 : function(jqXHR, textStatus, errorThrown){

							var data = $.parseJSON(jqXHR.responseText);
							$button.button('complete').addClass('btn-danger').val('Ooops! Riprova');
							$form.find('.alert-danger .message').html(data.message).parent().show();

						},						

						500 : function(jqXHR, textStatus, errorThrown){

							$button.button('complete').addClass('btn-danger').val('Ooops! Riprova');
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

						$('input[value="' + entry[key][i] +'"]', $div).prop('checked', true);

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