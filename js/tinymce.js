/* Simple Download Counter - TinyMCE Button */

(function() {
	
	'use strict';
	
	tinymce.create('tinymce.plugins.sdc_download', {
		
		init : function(ed) {
			
			ed.addButton('sdc_download', {
				
				title : 'Add Download',
				image : download_counter.url,
				
				onclick : function() {
					
					var download = {
						download_id  : '',
					};
					
					ed.windowManager.open({
						
						title     : 'Add Download',
						tooltip   : 'Add Download',
						minWidth  : 300,
						
						body : [
							{
								type        : 'textbox',
								name        : 'download_id',
								placeholder : 'Download ID',
								minWidth    : 300,
								multiline   : false,
								value       : download.download_id,
								
								oninput : function() {
									download.download_id = this.value();
								}
							}
						],
						
						onsubmit : function() {
							ed.insertContent('[sdc_download id="' + download.download_id + '"]');
						}
						
					});
					
				}
				
			});
			
		},
		
		createControl : function(n, cm) {
			return null;
		},
		
	});
	
	tinymce.PluginManager.add('sdc_download', tinymce.plugins.sdc_download);
	
})();