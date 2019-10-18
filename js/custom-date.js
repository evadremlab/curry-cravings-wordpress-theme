jQuery('.pp_date').datepicker({
    dateFormat:'M dd, yy',
    numberOfMonths: 3,
    onSelect: function(){
    	var myDate = new Date(this.value);
    	var myDateRaw = myDate.setDate(myDate.getDate());
    	jQuery('#'+jQuery(this).attr('id')+'_raw').attr('value', myDateRaw);
    }
});