function clean_form(area) {
  $('#website').val('');
  $('#mail').val('');
  if (area == 2) {
    $("#resultat_tab tr").remove();
  }
  if (area == 1) {
    grecaptcha.reset();
  }
  $('input[name=newsletter]:checked').prop("checked", false);
  $('input[name=service]:checked').prop("checked", false);
}
// A $( document ).ready() block.
$(document).ready(function() {
  // I do a clean form
  clean_form(2);
  // When form is submit I do ...
  $('#form_snoop').submit(function(event) {
    // Take all information from form
    $("#resultat_tab tr").remove();
    site_url = $('#website').val();
    mail = $('#mail').val();
    newsletter = $('input[name=newsletter]:checked').val();
    service = $('input[name=service]:checked').val();
    captcha_response = grecaptcha.getResponse();

    if (captcha_response != '' && site_url != '' && mail != '' && newsletter != '' && service != '') {
      $.post('count_link.php', {
        site_url: site_url,
        mail: mail,
        newsletter: newsletter,
        service: service,
        captcha_response: captcha_response
      }, function(data) {
        //Write the result
        $('#add').show();
        $('#resultat_area').val(data);
        $('#resultat').show();
        clean_form(1);
        $(window).scrollTop($('#resultat').offset().top);
      });
    } else {
      alert("Empty input");
    }
    // I give a request to process 
    return false;
  });
  $('#close').click(function(event) {
    $('#add').hide();
  });
  $('#form_add').submit(function(event) {
    q1 = $('input[name=add1]:checked').val();
    q2 = $('input[name=add2]:checked').val();
    $.post('add_form.php', {
      q1: q1,
      q2: q2
    }, function(data) {
      jQuery.parseJSON(data);
      console.log(data);
      $('#add').hide(); // hide the add after send
    });
    return false;
  });
});