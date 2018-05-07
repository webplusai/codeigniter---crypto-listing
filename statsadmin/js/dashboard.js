var baseUrl = 'https://statsadmin.coinschedule.com/';

$('document').ready(function(){

    $( document ).ready(function() {
       $('.datepicker').pickadate({
          format: 'yyyy/mm/dd',
          selectMonths: true, // Creates a dropdown to control month
          selectYears: 15 // Creates a dropdown of 15 years to control year
        });
    });
    $(document).on('submit','#signin_form',function(e){
        e.preventDefault();
        var url=$('#signin_form').attr('action');
        $.ajax({
            type: 'POST',
            url: url,
            data: $('#signin_form').serializeArray(),
            success: function (data)
            {
                if (data=='Success')
                {
                    Materialize.toast('Sign In Successfully!', 5000,'green');
                    window.location.replace("dashboard.php")
                }
                else
                {
                    Materialize.toast('Invalid Email or Password!',5000,'red');
                }
            }
        });
        return false;
    });
     $(document).on('click','.jn_detail',function(e)
    {
            _this=$(this);
            var id= $(this).attr('name');
            $.ajax({
                url: _this.data('url'), 
                data:{id: id},
                success: function (data)
                {
                    $('.modal_content').html(data);
                    $('.modal').modal();
                    $('.modal').modal('open')
                    $('select').material_select();
                }
            });
    });
    $(document).on('click','.remove',function(e)
    {
        if (confirm("Are you sure?"))
        {
            _this=$(this);
            var id= $(this).attr('name');
            $.ajax({
                url: _this.data('url'), 
                data:{id: id},
                success: function (data)
                {
                    Materialize.toast('Delete Successfully!', 5000,'green');
                    $.ajax({
                        type: 'GET',
                        url: 'results.php',
                        success: function (data)
                        {
                            $('.result_content').html(data);
                            $('.datatable_report').DataTable( {
                                "paging": true,
                                aaSorting: [[2, 'desc']],
                                stateSave: true
                            });
                            $('head').append('<link rel="stylesheet" href="'+baseUrl+'datatable_customize.css" type="text/css"/>');
                            $('head').append('<script src="'+baseUrl+'js/datatable_customize.js" type="text/javascript"></script>');
                        }
                    });
                }
            });
        }
    });
    $.ajax({
        type: 'GET',
        url: 'results.php',
        success: function (data)
        {
            $('.result_content').html(data);
            $('.datatable_report').DataTable( {
                "paging": true,
                aaSorting: [[2, 'desc']],
                stateSave: true
            });
            $('head').append('<link rel="stylesheet" href="'+baseUrl+'css/datatable_customize.css" type="text/css"/>');
            $('head').append('<script src="'+baseUrl+'js/datatable_customize.js" type="text/javascript"></script>');
        }
    });
    $(document).on('click','.years_result',function(e)
    {
        e.preventDefault();
        var value=$(this).attr('id');
        $.ajax({
            type: 'GET',
            url: 'year_results.php',
            data:{year: value},
            success: function (data)
            {
                $('.result_content').html(data);
                $('.datatable_report').DataTable( {
                    "paging": true,
                    aaSorting: [[2, 'desc']],
                    stateSave: true
                });
                $('head').append('<link rel="stylesheet" href="'+baseUrl+'css/datatable_customize.css" type="text/css"/>');
                $('head').append('<script src="'+baseUrl+'js/datatable_customize.js" type="text/javascript"></script>');
            }
        });
    });
    $(document).on('click','.all_results',function(e)
    {
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: 'results.php',
            success: function (data)
            {
                $('.result_content').html(data);
                $('.datatable_report').DataTable( {
                    "paging": true,
                    aaSorting: [[2, 'desc']],
                    stateSave: true
                });
                $('head').append('<link rel="stylesheet" href="'+baseUrl+'css/datatable_customize.css" type="text/css"/>');
                $('head').append('<script src="'+baseUrl+'js/datatable_customize.js" type="text/javascript"></script>');
            }
        });
    });
    $(document).on('click','.single_row',function (e) {
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: 'add_single.php',
            success: function (data)
            {
                $('.result_content').html(data);
                $('select').material_select();
            }
        });
    });
    $(document).on('click','.multiple_row',function (e) {
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: 'add_multiple.php',
            success: function (data)
            {
                $('.result_content').html(data);
            }
        });
    });
    $(document).on('submit','#add_multiple_form',function(e){
        e.preventDefault(); //form will not submitted
        $('#loader').show();
        var form = $('form').get(0);
        $.ajax({
            url:'add_multiple_processing.php',
            method:"POST",
            data: new FormData(form),
            contentType:false,          // The content type used when sending data to the server.
            cache:false,                // To unable request pages to be cached
            processData:false,
            dataType: 'text',          // To send DOMDocument or non processed data file it is set to false
            success: function(data)
            {
                if (data=='Success')
                {
                    $('#loader').hide();
                    Materialize.toast('Process Completed!', 6500,'green');
                }
                else
                {
                    $('#loader').hide();
                    Materialize.toast('CSV pattren not correct', 6500,'red');
                }
            }
        });
        return false;
    });
    $(document).on('click','#form_button',function(e)
    {
        $('#modal1').modal('close');
        e.preventDefault();
        var id=$('.categorey_select option:selected').val();
        if(id)
        {
            $('#categorey_input').val(id);
        }
        var url=$('#edit_form').attr('action');
        $.ajax({
            type: 'POST',
            url: 'update.php',
            data: $('#edit_form').serializeArray(),
            success: function (data)
            {
                if (data=='Success')
                {
                    $.ajax({
                        type: 'GET',
                        url: 'results.php',
                        success: function (data)
                        {
                            $('.result_content').html(data);
                            $('.datatable_report').DataTable( {
                                "paging": true,
                                aaSorting: [[2, 'desc']],
                                stateSave: true
                            });
                            $('head').append('<link rel="stylesheet" href="'+baseUrl+'css/datatable_customize.css" type="text/css"/>');
                            $('head').append('<script src="'+baseUrl+'js/datatable_customize.js" type="text/javascript"></script>');
                        }
                    });
                    Materialize.toast('Updated Successfully!', 5000,'green');
                }
                else
                {
                    Materialize.toast('Could not Updated!',5000,'red');
                }
            }
        });
        return false;
    });
    $(document).on('click','#add_single_form_button',function(e)
    {
        e.preventDefault();
        var id=$('.categorey_select option:selected').val();
        $('#categorey_input').val(id);
        var url=$('#add_data_form').attr('action');
        $.ajax({
            type: 'POST',
            url: 'add_single_processing.php',
            data: $('#add_data_form').serializeArray(),
            success: function (data)
            {
                if (data=='Success')
                {
                    Materialize.toast('Added Successfully!', 5000,'green');
                    $.ajax({
                        type: 'GET',
                        url: 'results.php',
                        success: function (data)
                        {
                            $('.result_content').html(data);
                            $('.datatable_report').DataTable( {
                                "paging": true,
                                aaSorting: [[2, 'desc']],
                                stateSave: true
                            });
                            $('head').append('<link rel="stylesheet" href="'+baseUrl+'css/datatable_customize.css" type="text/css"/>');
                            $('head').append('<script src="'+baseUrl+'js/datatable_customize.js" type="text/javascript"></script>');
                        }
                    });
                }
                else
                {
                    Materialize.toast('Could not Added!',5000,'red');
                }
            }
        });
        return false;
    });
     $(document).on('click','.dropdown-button',function(e)
    {
        $('.dropdown-button').dropdown();
    });
});