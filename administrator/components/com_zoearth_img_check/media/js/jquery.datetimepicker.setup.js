jQuery(document).ready(function() {
    jQuery('.date-picker').datetimepicker({
        lang:'ch',
        timepicker:false,
        format:'Y-m-d'
    });
    jQuery('.date-picker').attr('autocomplete','off');

    jQuery('.time-picker').datetimepicker({
        datepicker:false,
        format:'H:i'
    });
    jQuery('.time-picker').attr('autocomplete','off');
    
    jQuery('.date-time-picker').datetimepicker({
        format:'Y-m-d H:i'
    });
    jQuery('.date-time-picker').attr('autocomplete','off');
});