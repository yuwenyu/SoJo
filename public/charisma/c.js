/** 
 * @dependency jQuery1.7+ 
 * @author Kyle Yu 
 * @since 2015-12-18 
 */

var c = 
{
'OpenMsg':
	function (hide,show,msg)
	{
		if (typeof(hide)=='undefined'||typeof(show)=='undefined'||typeof(msg)=='undefined') {return false;}
		$('#'+hide).modal('hide');	
		$('#'+show).find('.modal-body').text(msg);
		$('#'+show).modal('show');
		return true;
	},
'TogglePopoverbsCheck':
	function (o)
	{
		if (typeof(o) == 'undefined') {
			return false;
		}
		
		for (tp in o) {
			this.TogglePopoverbsInit(tp, function (oModal){
				c.TogglePopoverbsShow(oModal, function (e){
					if (typeof(o[tp]['show']) != 'undefined') {
						o[tp]['show'](e);
					}
					return false;
				});
			},o[tp]['fields']);
		}
	},
'TogglePopover':
	function (field, callback)
	{
		var oEvent = $('#'+field);
		var oResultCallback = {};
		oResultCallback.sMsg = '';
		oResultCallback.bToggleFieldPopover = true;

		if (typeof(callback) != 'undefined') {
			var oCallback = callback(oEvent, oResultCallback);
			if (oCallback.bToggleFieldPopover) {
				oEvent.attr('data-content',oCallback.sMsg);
				oEvent.popover('show');
				
				return false;
			}
		} else {return false;}
		
		return true;
	},
'TogglePopoverbsInit':
	function (modal, callback, fields)
	{
		var oModal = $('#'+modal);
		if (typeof(callback) != 'undefined') {
			callback(oModal);
		}
		
		if (typeof(fields) != 'undefined') {
			for (field in fields) {
				$('#'+fields[field]).focus(function () {
					$(this).popover('hide');
					$(this).attr('data-content','');
				});
			}
			
			this.TogglePopoverbsHide(oModal, function (e){
				for (field in fields) {
					$('#'+fields[field]).popover('hide');
					$('#'+fields[field]).attr('data-content','');
				}
			});
		}
		
		if (typeof(callbackbsHide) != 'undefined') {
			this.TogglePopoverbsHide(oModal, function (e){
				callbackbsHide();
			});
		}
		return null;
	},
'TogglePopoverbsShow': 
	function (oModal, callback)
	{
		if (typeof(callback) != 'undefined') {
			oModal.on('show.bs.modal', function (e){
				callback(e);
			});
		}
		return null;
	},
'TogglePopoverbsHide': 
	function (oModal, callback)
	{
		if (typeof(callback) != 'undefined') {
			oModal.on('hide.bs.modal', function (e){
				callback(e);
			});
		}
		return null;
	}
}







