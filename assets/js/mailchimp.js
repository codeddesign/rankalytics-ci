$(document).ready(function() {

  function loading() {
    $('.result').show().html('Loading...');
  }

  function formResult(data) {
    $('.result').html(data);
    $('#mailchimp-signup input').val('');
  }

  function onSubmit() {
    $('#mailchimp-signup').submit(function() {
      var action = $(this).attr('action');
      loading();
      $.ajax({
        url: action,
        type: 'POST',
        data: {
          email: $('#mailchimp-email').val(),
          fname: $('#mailchimp-fname').val(),
          lname: $('#mailchimp-lname').val()
        },
        success: function(data){
          formResult(data);
        },
        //error: function(data) {
          //formResult(data);
        //}
      });
    return false;
    });
  }onSubmit();

});