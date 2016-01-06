/* ========================================================================
 * IDIES: drilldown2.js v0.2
 * Based on Bootstrap's COLLAPSE
 * ========================================================================
 * What it does:
 * 
 * Shows and hides elements based on constraints selected from one or more 
 * groups of constraints, e.g. schools, departments, research interests.
 * 
 * All constraints should be in a wrapper with the class dd2-controls.
 * All targets should be in a wrapper with the class dd2-targets.
 * 
 * Each group of constraints starts with a Show All element with data-toggle="drilldown2" 
 * and data-target="[group]-all", e.g. "sch-all".
 * 
 * Each individual constraint has a data-toggle set to "drilldown2" and a data-target 
 * set to a unique selector "[group]-[selector]", e.g. "sch-1099".
 * Only elements satisfying all constraints will be shown.
 * 
 * Next to each control the plugin displays the number of shown targets 
 * satisfying that constraint.
 * 
 * Initially, only the Show All option is checked.
 * 
 * Selecting an individual constraint will (1) uncheck the Show All option and 
 * (2) hide all elements not satisfying ALL constraints, (3) update the number 
 * of shown elements next to each constraint.
 * 
 * Unselecting an individual constraint will (1) uncheck the option and (2) if no 
 * constraints are selected, check Show All, (3) hide all elements not satisfying 
 * ALL constraints, (4) update the number of shown elements next to each 
 * constraint.
 * 
 * Selecting the Show All option will (1) do nothing if already selected, 
 * (2) select all options in that group is Show All not selected, (3) hide 
 * all elements not satisfying ALL constraints, (4) update the number of shown 
 * elements next to each constraint.
 * 
 * 
 * ======================================================================== 
 * Copyright 2011-2015 IDIES
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

 //self invoking function
+function ($) {
 
	'use strict';

	// DRILLDOWN2 PUBLIC CLASS DEFINITION
	// ================================
	var Drilldown2 = {
		
		// initialize the plugin
		init: function(tog){
			Drilldown2.tog = tog;
			
			//update controller counts
			this.resetCount();
			
			// set onclick functions for group-controllers and individual-controllers
			var dd2=this;
			$(".dd2-controls [data-toggle='"+Drilldown2.tog+"'][data-target$='-all']").each( function() {
				
				//get the prefix of the group
				var this_group = this.dataset.target.replace( "-all" , "" )
				
				// set the click event for group constraint
				$( this ).on( "click" , { group: this_group } , dd2.toggleGroup );
				
				//set the click events for individual constraints
				$(".dd2-controls [data-toggle='"+Drilldown2.tog+"'][data-target^='"+this_group+"']:not([data-target$='-all'])").each( function() {
					$( this ).on( "click" , { group : this_group , target: this.dataset.target } , dd2.toggleIndiv );
				});
			});
			
		},
		
		// Show targets that have controls Checked.
		showChecked: function(){

			// Show the checked controls
			$(".dd2-controls input[data-toggle='"+Drilldown2.tog+"']:checked").each( function() {
				$(this.dataset.target).removeClass('hidden');
			})

			// Hide the checked controls
			$(".dd2-controls input[data-toggle='"+Drilldown2.tog+"']:checked").each( function() {
				$(this.dataset.target).removeClass('hidden');
			})
		},
		
		// reset the count of not-hidden elements in menu
		resetCount: function(){

			$(".dd2-controls [data-toggle='"+Drilldown2.tog+"']:not([data-target$='-all']").each( function() {
				if ($(this.dataset.target + ":not(.hidden)").length) {
					$("span", $(".dd2-controls label[for='dd2-"+this.dataset.target+"']") ).html(' ('+$(this.dataset.target + ":not(.hidden)").length+')');
				} else {
					$("span", this).html('');
				}
			})
		},
		
		// toggle an 'all'  controller
		toggleGroup: function( event ) {
			
			// if checked
			if ( $( this ).prop('checked') ) {
				
				//uncheck other boxes in group
				$( ".dd2-controls [data-toggle='"+Drilldown2.tog+"'][data-target^='"+event.data.group+"']:not([data-target$='-all'])").each( function(  ) {
					$( this ).prop( 'checked' , false );
				});

			}
			
			Drilldown2.resetCount();
		},
		
		// toggle an 'individual' controller
		toggleIndiv: function( event ) {
			
			// if checked
			if ( $( this ).prop('checked') ) {
				
				$( ".dd2-controls [data-toggle='"+Drilldown2.tog+"'][data-target='"+event.data.group+"-all']" ).prop('checked',false);
				
			}
			
			//hide what needs to be hidden and reset the counts
			
			Drilldown2.resetCount();
		}

	}

	$(document).ready(function() {

		//Drilldown2.resetCount();

		Drilldown2.init("drilldown2");

		return;
		
	});
  
}(jQuery);
