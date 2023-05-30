/*!
 * jQuery initialize - v1.0.0 - 12/14/2016
 * https://github.com/adampietrasiak/jquery.initialize
 *
 * Copyright (c) 2015-2016 Adam Pietrasiak
 * Released under the MIT license
 * https://github.com/timpler/jquery.initialize/blob/master/LICENSE
 */
!function(i){"use strict";var t=function(i,t){this.selector=i,this.callback=t},e=[];e.initialize=function(e,n){var c=[],a=function(){-1==c.indexOf(this)&&(c.push(this),i(this).each(n))};i(e).each(a),this.push(new t(e,a))};var n=new MutationObserver(function(){for(var t=0;t<e.length;t++)i(e[t].selector).each(e[t].callback)});n.observe(document.documentElement,{childList:!0,subtree:!0,attributes:!0}),i.fn.initialize=function(i){e.initialize(this.selector,i)},i.initialize=function(i,t){e.initialize(i,t)}}(jQuery);


(function($) {
  merimag_options_admin_UI = {
    orginal_animation_select_classes: false,
    init: function() {
      console.log('test');
      this.init_elements();
      this.init_post_format_boxes();
    },
    init_post_format_boxes: function() {
        $.initialize('#post-format-selector-0', function() {
           var current_post_formt = $(this).val();
          show_post_format_box( current_post_formt );
          $(this).on('change', function() {
            var val = $(this).val();
            console.log(val);
            show_post_format_box(val);
          })
        });
        
      
      function show_post_format_box( format ) {
        console.log(format);
        var post_format_settings = $('#fw-options-box-gallery_settings, #fw-options-box-audio_settings, #fw-options-box-video_settings');
        $(post_format_settings).hide();
        if( format === 'gallery' ) {
          $('#fw-options-box-gallery_settings').slideDown();
        }
        if( format === 'video' ) {
          $('#fw-options-box-video_settings').slideDown();
        }
        if( format === 'audio' ) {
          $('#fw-options-box-audio_settings').slideDown();
        }
      }
    },
    init_elements: function() {
      $(document).on('change', '.fw-animation-select', function() {
        if( merimag_options_admin_UI.orginal_animation_select_classes === false ) {
          merimag_options_admin_UI.orginal_animation_select_classes = $(this).attr('class');
        }
        var animation = $(this).val();
        $(this).removeAttr('class');
        $(this).attr('class', merimag_options_admin_UI.orginal_animation_select_classes );
        $(this).addClass('animated ' + animation);
      });
      
      $.initialize('.image_picker_image', function() {
        $(this).css('height', $(this).attr('height')).css('max-height', 'none').show();
      });
      $(document).ready(function() {
       
      });
      $(document).on('click', '.merimag-activation-button', function() {
        var license = $('.merimag-activation-license').val();
        var btn = $(this);
        if(!license) {
          alert('Please enter a valid license!');
          return
        }
         $.ajax({
          type:'post',
          url: merimag_theme.ajax,
          data:{nonce: merimag_theme.nonce, license: license, action: 'merimag_license_verify'},
          beforeSend: function() {
            $(btn).addClass('loading');
          },
          success:function(data) {
            if( data == true ) {
              $('.merimag-theme-activation-form').removeClass('merimag-error expired').addClass('activated');
            } else {
              $('.merimag-theme-activation-form').removeClass('activated').addClass('merimag-error expired');
            }
          },
          complete: function(data) {
            $(btn).removeClass('loading');
          }
        })
      });
      $.initialize('.merimag-check-update', function() {
        var elm = $(this);
        $(elm).on('click', function() {
          $.ajax({
            type: 'POST',
            url: merimag_theme.ajax,
            data:{ nonce: merimag_theme.nonce, action: 'merimag_update_check' },
            success: function(data) {
              $(elm).parents('.notice').hide();
              $(elm).parents('.notice').after(data);
              return false;
            },
            beforeSend: function(data) {
             
            },
            complete: function(data) {
              return false;
            }
          });
          return false;
        })
        
      })
    }
  };
  $(document).ready( function() {

    merimag_options_admin_UI.init();
     
  });
})(jQuery);