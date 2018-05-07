   function saveprofile() 
    {
        err_message = "";
        
        if ($('#tx_username').val()=="")
        {
          err_message = "Missing User Name";
        }
        
        if ($('#tx_email').val()=="")
        {
          if (err_message!='')
          {
            err_message = err_message + " and Email";
          }
          else
          {
            err_message = "Missing Email Address";
          }
        }
        
        
        if (err_message != "")
        {
          $("#btn_update").notify(err_message, {className: "error",arrowShow: false,position:"right",showDuration: 200}); 
        }
        else
        {
         
          $.ajax({
              url: "https://www.coinschedule.com/NEW/includes/profile_updateprofile.php",
              type: "POST",
              data: $(".container :input").serialize(),                   
              success: function(data)
                          {
                            if (data=='OK')
                            {
                              $("#btn_update").notify("Your profile has been updated", {className: "success",arrowShow: false,position:"right",showDuration: 200}); 
                              $('.username').html($('#tx_username').val());
                              $('.userinitial').html($('#tx_username').val().charAt(0));
                              $('#tx_pass').val("");
                              $('#tx_newpass').val("");
                            }
                            else if (data!='')
                            {
                               $("#btn_update").notify(data, {className: "error",arrowShow: false,position:"right",showDuration: 200});  
                            }                    
                          }
          });
        }
    }