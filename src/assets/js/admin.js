jQuery( document ).ready( function ( $ ) {
  //  init
  $( document ).on( 'change', '[name=orchestrated_corona_virus_banner_enabled]', ocvb_enabled_changed );
  $( document ).on( 'keypress', '[name=orchestrated_corona_virus_banner_message_title]', ocvb_title_changed );
  $( document ).on( 'keypress', '[name=orchestrated_corona_virus_banner_message_text]', ocvb_message_changed );
  $( document ).on( 'change', '[name=orchestrated_corona_virus_banner_message_alignment]', ocvb_internal_alignment_changed );
  $( document ).on( 'change', '[name=orchestrated_corona_virus_banner_internal_link]', ocvb_internal_link_changed );
  $( document ).on( 'keypress', '[name=orchestrated_corona_virus_banner_external_link]', ocvb_external_link_changed );
  $( document ).on( 'keypress', '[name=orchestrated_corona_virus_banner_link_text]', ocvb_link_text_changed );
  $( document ).on( 'change', '[name=orchestrated_corona_virus_banner_foreground_color]', ocvb_foreground_color_changed );
  $( document ).on( 'change', '[name=orchestrated_corona_virus_banner_background_color]', ocvb_background_color_changed );

  $( '[name=orchestrated_corona_virus_banner_external_link]' ).parents('tr').addClass('admin-row admin-row-hidden');
  $( '[name=orchestrated_corona_virus_banner_link_text]' ).parents('tr').addClass('admin-row admin-row-hidden');

  var link_type = $( '[name=orchestrated_corona_virus_banner_internal_link]' ).val();
  update_view(link_type);

});

function ocvb_enabled_changed(evt) {
  var $ = jQuery;
  var enabled_value = evt.target.checked;
  if ( enabled_value ) {
    $( '#ocvb-container' ).removeClass('preview-disabled');
  } else {
    $( '#ocvb-container' ).addClass('preview-disabled');
  }
}

function ocvb_title_changed(evt) {
  var $ = jQuery;
  $( '#ocvb-container-notice-text h4' ).html(evt.target.value);
}

function ocvb_message_changed(evt) {
  var $ = jQuery;
  $( '#ocvb-container-notice-text p' ).html(evt.target.value);
}

function ocvb_internal_alignment_changed(evt) {
  var $ = jQuery;
  $( '#ocvb-container-notice-text' ).css( 'text-align', evt.target.value );
  $( '#ocvb-container-notice-text h4' ).css( 'text-align', evt.target.value );
  $( '#ocvb-container-notice-text p' ).css( 'text-align', evt.target.value );
  $( '#ocvb-container-notice-text a' ).css( 'text-align', evt.target.value );
}

function ocvb_internal_link_changed(evt) {
  var $ = jQuery;
  var link_type = evt.target.value;
  update_view(link_type);
}

function ocvb_external_link_changed(evt) {
  var $ = jQuery;
  var $external_link_elm = $( '[name=orchestrated_corona_virus_banner_external_link]' );
  $( '#ocvb-container-notice-link a' ).attr( 'href', $external_link_elm.val() );
}

function ocvb_link_text_changed(evt) {
  var $ = jQuery;
  var $link_text_elm = $( '[name=orchestrated_corona_virus_banner_link_text]' );
  $( '#ocvb-container-notice-link a' ).text( $link_text_elm.val() );
}

function ocvb_foreground_color_changed(evt) {
  var $ = jQuery;
  var $fg_color_elm = $( '[name=orchestrated_corona_virus_banner_foreground_color]' );
  $( '#ocvb-container, #ocvb-container h4, #ocvb-container p, #ocvb-container a' ).css( 'color', $fg_color_elm.val() );
}

function ocvb_background_color_changed(evt) {
  var $ = jQuery;
  var $bg_color_elm = $( '[name=orchestrated_corona_virus_banner_background_color]' );
  $( '#ocvb-container' ).css( 'background', $bg_color_elm.val() );
}

function update_view(link_type) {
  var $ = jQuery;
  var $external_link_elm = $( '[name=orchestrated_corona_virus_banner_external_link]' );
  var $link_text_elm = $( '[name=orchestrated_corona_virus_banner_link_text]' );
  switch ( link_type ) {
    case "NONE":
      $external_link_elm.parents( 'tr' ).addClass( 'admin-row-hidden' );
      $link_text_elm.parents( 'tr' ).addClass( 'admin-row-hidden' );
      $( '#ocvb-container-notice-link' ).css( 'display', 'none' );
      break;
    case "EXT":
      $link_text_elm.parents( 'tr' ).removeClass( 'admin-row-hidden' );
      $external_link_elm.parents( 'tr' ).removeClass( 'admin-row-hidden' );
      $( '#ocvb-container-notice-link' ).css( 'display', 'block' );
      $( '#ocvb-container-notice-link a' ).attr( 'href', $external_link_elm.val() );
      break;
    default:
      if ( parseInt ( link_type ) ) {
        $external_link_elm.parents( 'tr' ).addClass( 'admin-row-hidden' );
        $link_text_elm.parents( 'tr' ).removeClass( 'admin-row-hidden' );
        $( '#ocvb-container-notice-link' ).css( 'display', 'block' );
      } else {
        $external_link_elm.parents( 'tr' ).addClass( 'admin-row-hidden' );
        $link_text_elm.parents( 'tr' ).addClass( 'admin-row-hidden' );
        $( '#ocvb-container-notice-link' ).css( 'display', 'none' );
        $( '#ocvb-container-notice-link a' ).attr( 'href', $external_link_elm.val() );
      }
      break;
  }
}