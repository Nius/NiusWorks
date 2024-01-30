/*
 * jQuery UI Autocomplete Alias Extension
 *
 * Copyright 2023, Nicholas Harrell
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 */
(function($){
/*
var proto = $.ui.autocomplete.prototype;

$.extend(proto,{
	proto._on(this.menu.element,{
		menuselect: function( event, ui ) {
			var item = ui.item.data( "ui-autocomplete-item" ),
				previous = this.previous;

			// Only trigger when focus was lost (click on menu)
			if ( this.element[ 0 ] !== $.ui.safeActiveElement( this.document[ 0 ] ) ) {
				this.element.trigger( "focus" );
				this.previous = previous;

				// #6109 - IE triggers two focus events and the second
				// is asynchronous, so we need to reset the previous
				// term synchronously and asynchronously :-(
				this._delay( function() {
					this.previous = previous;
					this.selectedItem = item;
				} );
			}

			if ( false !== this._trigger( "select", event, { item: item } ) ) {
				this._value( item.label );
				this.element.attr('Data-Selected',item.value);
			}

			// reset the term after the select event
			// this allows custom select handling to work properly
			this.term = this._value();

			this.close( event );
			this.selectedItem = item;
		}
	})
});
*/
})(jQuery);
