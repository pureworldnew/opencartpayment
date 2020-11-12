"use strict";
/**
 * @class elFinder command "Multi Image upload".
 * Put files in product image tab.
 *
 * @type  elFinder.command
 * @author  Kamen Sharlandjiev
 */
elFinder.prototype.commands.multiupload = function() {
	this.title = 'Add selected to product';
	this.alwaysEnabled  = true;
	this.updateOnSelect = true;
    
	this.getstate = function(sel) {

	  if (typeof(parent.addPowerImage) == 'function') {
  		var sel = this.files(sel),
		  	cnt = sel.length;
		} 	
		return cnt && $.map(sel, function(f) { return f.phash && f.read && !f.locked ? f : null  }).length == cnt ? 0 : -1;
	}
	
	this.exec = function(hashes) {
		var fm     = this.fm,
			dfrd   = $.Deferred()
				.fail(function(error) {
					fm.error(error);
				});

		$.each(this.files(hashes), function(i, file) {
			var image = fm.path(file.hash);
			image = image.replace(/\\/g, "/");
			if(typeof(parent.addPowerImage) == 'function') {
			   parent.addPowerImage(image);
			} else {
  			dfrd   = $.Deferred()
				.fail(function(error) {
					fm.error('The function is available only on Product layout.');
				});
			
  			

			}
		});
		
		return dfrd.isRejected() ? dfrd : dfrd.resolve(fm.clipboard(this.hashes(hashes), true));
	}
}