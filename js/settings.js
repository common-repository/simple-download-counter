/* Plugin Settings */

jQuery(document).ready(function($) {
	
	// popup dialog
	
	$('.download-counter-reset-options').on('click', function(e) {
		e.preventDefault();
		$('.download-counter-modal-dialog').dialog('destroy');
		var link = this;
		var button_names = {}
		button_names[download_counter_reset_true]  = function() { window.location = link.href; }
		button_names[download_counter_reset_false] = function() { $(this).dialog('close'); }
		$('<div class="download-counter-modal-dialog">'+ download_counter_reset_message +'</div>').dialog({
			title: download_counter_reset_title,
			buttons: button_names,
			modal: true,
			width: 350,
			closeText: ''
		});
	});
	
	
	// image upload
	
	var download_counter_uploader;
	$('#sdc_download_upload').on('click', function(e) {
		e.preventDefault();
		if (download_counter_uploader) {
			download_counter_uploader.open();
			return;
		}
		download_counter_uploader = wp.media.frames.file_frame = wp.media({ multiple: false });
		download_counter_uploader.on('select', function() {
			console.log(download_counter_uploader.state().get('selection').toJSON());
			attachment = download_counter_uploader.state().get('selection').first().toJSON();
			$('#sdc_download_url').val(attachment.url);
		});
		download_counter_uploader.open();
	});
	
	
	// fancy input
	
	function sdcFancyInput(el) {
		if ($(el).val().length > 0) {
			$(el).addClass('sdc-input-fancy');
		} else {
			$(el).removeClass('sdc-input-fancy');
		}
	}
	
	$('input#sdc_download_url').each(function() { sdcFancyInput(this); });
	$('input#sdc_download_version').each(function() { sdcFancyInput(this); });
	$('input#sdc_download_count').each(function() { sdcFancyInput(this); });
	$('textarea#sdc_download_notes').each(function() { sdcFancyInput(this); });
	
	$('input#sdc_download_url').on('change keyup', function() { sdcFancyInput(this); });
	$('input#sdc_download_version').on('change keyup', function() { sdcFancyInput(this); });
	$('input#sdc_download_count').on('change keyup', function() { sdcFancyInput(this); });
	$('textarea#sdc_download_notes').on('change keyup', function() { sdcFancyInput(this); });
	
	
	// based on jquery.copy-to-clipboard @ https://github.com/mmkyncl/jquery-copy-to-clipboard
	
	function CopyToClipboard(val) {
		var hiddenClipboard = $('#_hiddenClipboard_');
		if (!hiddenClipboard.length) {
			$('body').append('<textarea readonly style="position:absolute;top:-99999em;" id="_hiddenClipboard_"></textarea>');
			hiddenClipboard = $('#_hiddenClipboard_');
		}
		hiddenClipboard.html(val);
		hiddenClipboard.select();
		document.execCommand('copy');
		document.getSelection().removeAllRanges();
		hiddenClipboard.remove();
	}
	
	$(function() {
		$('[data-clipboard-target]').each(function() {
			$(this).click(function(e) {
				e.preventDefault();
				$($(this).data('clipboard-target')).CopyToClipboard();
				$('.download-clipboard-target').remove();
				$($('<span class="download-clipboard-target">'+ download_counter.clipboard +'</span>')).hide().appendTo($(this).parent()).fadeIn(300);
				setTimeout(function() { $('.download-clipboard-target').fadeOut(); }, 3000);
			});
		});
	});
	
	$.fn.CopyToClipboard = function() { CopyToClipboard(this.text()); };
	$('.download-counter-shortcode').CopyToClipboard();
	
});
