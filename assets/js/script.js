jQuery(document).ready(function($){
  $('#wp_contact_form_aura').on('submit', function(e){
    e.preventDefault();
    $('#wp_contact_form_aura-response').text('Submitting...');
  });
});