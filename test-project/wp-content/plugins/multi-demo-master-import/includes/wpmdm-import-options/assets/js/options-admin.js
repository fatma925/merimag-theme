/**
 * WPmdpm Options UI
 * 
 * Dependencies: jQuery, jQuery UI, ColorPicker
 *
 * @author Merrasse Mouhcine ( Based on work of Derek Herman )
 */
;(function($) {
  wpmdm_import_options_UI = {
    processing: false,
    init: function() {
      this.init_sortable();
      this.init_add();
      this.init_edit();
      this.init_remove();
      this.init_edit_title();
      this.init_conditions();
      this.init_upload();
      this.init_upload_remove();
      this.init_numeric_slider();
      this.init_tabs();
      this.init_radio_image_select();
      this.bind_select_wrapper();
      this.fix_upload_parent();
      this.fix_textarea();
      this.replicate_ajax();
      this.reset_settings();
      this.select_element();
      this.init_table_source_filter();
      this.init_message();
      this.init_select_icon();
      this.init_ajax_action();
      this.init_clear_folder();
      this.init_refresh_folder();
    },
    init_clear_folder: function() {

      $(document).on('click', '.wpmdm-import-options-clear-folder', function() {

        $this = $(this);

        $.ajax({
            type: 'POST',
            url: wpmdm_import_options.ajax,
            data: {
              nonce : wpmdm_import_options.nonce,
              action: 'wpmdm_import_options_check_folder',
              folder: $this.data('folder'),
            },
            beforeSend: function(data) {
               wpmdm_import_options_UI.add_message('progress', $this.data( 'message-check-progress') );
            },
            success: function( data ) {
              if( data == 1 ) {

                $.ajax({
                    type: 'POST',
                    url: wpmdm_import_options.ajax,
                    data: {
                      nonce : wpmdm_import_options.nonce,
                      action: 'wpmdm_import_options_clear_folder',
                      folder: $this.data('folder'),
                    },
                    beforeSend: function(data) {
                       wpmdm_import_options_UI.add_message('progress', $this.data( 'message-progress'));
                    },
                    success: function( data ) {
                      wpmdm_import_options_UI.add_message('success', $this.data( 'message-success'));
                      console.log(data);
                    },
                    error: function(  jqXHR, textStatus, errorThrown) {
                        wpmdm_import_options_UI.add_message('error', $this.data( 'message-error'));

                    }
                });

              } else {
                  wpmdm_import_options_UI.add_message('error', $this.data( 'message-check-error'));
              }
            },
            error: function(  jqXHR, textStatus, errorThrown) {
                wpmdm_import_options_UI.add_message('error', errorThrown);

            }
            

        });
        

      });

    },
    init_refresh_folder: function( elm ) {

      $(document).on('click', '.wpmdm-import-options-refresh-folder', function() {

        button = $(this);
        elm = $(this).parents('.wpmdm-import-options-format-setting-wrap').find('.wpmdm-import-options-directory-files-view');
        wpmdm_import_options_UI.refresh_folder( elm, button );

      });

    },
    refresh_folder: function( elm, button = null) {

      $.ajax({

            type: 'POST',
            url: wpmdm_import_options.ajax,
            data: {
              nonce : wpmdm_import_options.nonce,
              action: 'wpmdm_import_options_refresh_folder',
              path: $(elm).data('path'),
              url: $(elm).data('url'),
              type: $(elm).data('files-type'),
            },
            beforeSend: function(data) {
               wpmdm_import_options_UI.add_message('progress', $(button).data('message-progress') );
            },
            success: function( data ) {
              wpmdm_import_options_UI.add_message('success', $(button).data('message-success'));
              $(elm).html(data);
            },
            error: function(  jqXHR, textStatus, errorThrown) {
                wpmdm_import_options_UI.add_message('error', $(button).data('message-error') );

            }
            

      });

    },
    init_ajax_action: function() {

      $(document).on('click', '.wpmdm-import-options-ajax-action-button', function() {
          button = $(this);
          action = $(this).data( 'func' );
          result_container = '.' + $(this).data( 'id' ) + '-result';
          data = $(this).parents('.wpmdm-import-options-format-setting').find( '.wpmdm-import-options-format-setting-inner :input' ).serialize();
          ajaxurl = wpmdm_import_options.ajax;
          nonce = wpmdm_import_options.nonce;
          $.ajax({
            type : 'POST',
            url : ajaxurl,
            data : {
              action : action,
              nonce: nonce,
              data : data,
            },
            beforeSend: function(data) {
               wpmdm_import_options_UI.add_message('progress', $(button).data('message-progress'));
               $(result_container).fadeIn('fast');
               $(result_container).html('<div class="wpmdm-import-options-loader"><i class="fa fa-cog fa-spin"></i></div>');

            },
            success: function( data ) {
              wpmdm_import_options_UI.add_message('success', $(button).data('message-success'));
              $(result_container).html( data );
              
            },
            error: function(  jqXHR, textStatus, errorThrown) {
                wpmdm_import_options_UI.add_message('error', $(button).data('message-error'));

            }

          });

      });

    },
    init_select_icon: function()  {

        $(document).on('click', '.wpmdm-import-options-select-icon-button', function() {

          $('.wpmdm-import-options-select-icon-container').fadeOut();

          $(this).parents('.wpmdm-import-options-select-icon-setting').find('.wpmdm-import-options-select-icon-container').fadeIn().css('display','flex');

        });

        $(document).on('click', '.wpmdm-import-options-select-icon-close', function(e) {

           $('.wpmdm-import-options-select-icon-container').fadeOut();

        });

        $(document).on('click', '.wpmdm-import-options-select-icon-container', function(e) {

          if( e.target == this ) { // only if the target itself has been clicked

             $(this).fadeOut();
          }

        });

        $(document).on('click', '.wpmdm-import-options-select-icon-item span', function() {

          value = $(this).attr('class');

          result_element = $(this).parents('.wpmdm-import-options-select-icon-setting').find('.wpmdm-import-options-select-icon-holder');

          value_element = $(this).parents('.wpmdm-import-options-select-icon-setting').find('.wpmdm-import-options-select-icon-value');

          $(result_element).children('.wpmdm-import-options-select-icon-result').removeClass().addClass('wpmdm-import-options-select-icon-result').addClass( value );

          $(value_element).val( value );

          $(result_element).children( '.wpmdm-import-options-select-icon-button' ).text( value );

          $('.wpmdm-import-options-select-icon-container').fadeOut();

          $(this).parents('.wpmdm-import-options-select-icon-container').find('.wpmdm-import-options-select-icon-item span').removeClass('active');

          $(this).addClass('active');

        });

    },
    init_message: function() {

      $(document).on( 'click', '.wpmdm-import-options-header-messag-close-message', function() {

        $(this).parents('.wpmdm-import-options-header-message').slideUp();

        $(this).parents('.wpmdm-import-options-header-message').unstick();

      });

    },
    add_message: function( type, message, delay = 7000 ) {
      var remove_message = function() {
        wpmdm_import_options_UI.remove_message();
      }
      $('.wpmdm-import-options-header-message').sticky({topSpacing:32, zIndex: 9999});
      var message_class = 'wpmdm-import-options-' + type + '-message';
      $('.wpmdm-import-options-header-message').removeClass().addClass('wpmdm-import-options-header-message');
      $('.wpmdm-import-options-header-message').addClass(message_class).find('.wpmdm-import-options-header-messag-text-message').text(message);
      $('.wpmdm-import-options-header-message').show();
      $('.wpmdm-import-options-header-message').sticky('update');
      
    },
    remove_message: function() {
      $('.wpmdm-import-options-header-message').hide();
      $('.wpmdm-import-options-header-message').unstick();
    },
    init_table_source_filter : function() {

      $(document).on('input', '.wpmdm-import-options-table-source-filter', function() {

        $(this).parents('.wpmdm-import-options-table-source-container').find('tbody tr').hide();

        $(this).parents('.wpmdm-import-options-table-source-container').find('.wpmdm-import-options-table-source-val:contains("'+$(this).val()+'")').parents('tr').show();

      });

    },
    init_sortable: function(scope) {
      scope = scope || document;
      $('.wpmdm-import-options-sortable', scope).each( function() {
        if ( $(this).children('li').length ) {
          var elm = $(this);
          elm.show();
          elm.sortable({
            items: 'li:not(.ui-state-disabled)',
            handle: 'div.open',
            placeholder: 'ui-state-highlight',
            start: function (event, ui) {
              ui.placeholder.height(ui.item.height()-2);
            },
            stop: function(evt, ui) {
              setTimeout(
                function(){
                  wpmdm_import_options_UI.update_ids(elm);
                },
                200
              )
            }
          });
        }
      });
    },
    init_add: function() {
      $(document).on('click', '.wpmdm-import-options-list-item-add', function(e) {
        e.preventDefault();
        wpmdm_import_options_UI.add(this,'list_item');
      });
      $(document).on('click', '.wpmdm-import-options-list-item-add-to', function(e) {
        e.preventDefault();
        var add_to = $(this).data('add-to');
        var id = $(this).data('id');
        var value = $(this).data('value');
        console.log(value);
        var elm = $('.wpmdm-import-options-list-item-add[data-id='+add_to+']');
        wpmdm_import_options_UI.add(elm,'list_item', value);
      });

      $(document).on('click', '.wpmdm-import-options-list-item-inner-add', function(e) {
        e.preventDefault();
        if ( $(this).parents('ul').parents('ul').hasClass('ui-sortable') ) {
          alert(wpmdm_import_options.setting_limit);
          return false;
        }
        wpmdm_import_options_UI.add(this,'list_item_inner');
      });
    },
    init_edit: function() {
      $(document).on('click', '.wpmdm-import-options-setting-edit', function(e) {
        e.preventDefault();
        wpmdm_import_options_UI.toggle_list($(this));
        $(this).parents('.wpmdm-import-options-setting').find('textarea').each(function () {
              var textAreaID = $(this).attr('id');
              if($('#'+textAreaID).hasClass('wpmdm-import-options-wp-editor')) {
                $('#'+textAreaID).wp_editor();
              }
            });
      });
    },
    toggle_list: function( elm ) {
      key = $(elm).data('key');
      list_id = $(elm).data('list-id');
      item = $('.wpmdm-import-options-setting-body[data-key=' + key + ']');
      $('.wpmdm-import-options-setting-body[data-list-id=' + list_id + ']').slideUp();
      if( $(item).is(":visible") )  {
         $(item).slideUp();
      } else {
        $(item).slideDown();
      }
    },
    init_remove: function() {
      $(document).on('click', '.wpmdm-import-options-setting-remove', function(event) {
        event.preventDefault();
        if ( $(this).parents('li').hasClass('ui-state-disabled') ) {
          alert(wpmdm_import_options.remove_no);
          return false;
        }
        var agree = confirm(wpmdm_import_options.remove_agree);
        if (agree) {
          var list    = $(this).parents('li[data-key='+$(this).data('key')+']').parent('ul');
          var list_li = $(this).parents('li[data-key='+$(this).data('key')+']');
          wpmdm_import_options_UI.remove(list_li);
          setTimeout( function() { 
            wpmdm_import_options_UI.update_ids(list); 
          }, 200 );
          wpmdm_import_options_UI.add_message('delete', 'Item was deleted');
        }
        return false;
      });
    },
    init_edit_title: function() {
      $(document).on('keyup', '.wpmdm-import-options-setting-title', function() {
        wpmdm_import_options_UI.edit_title(this);
      });
      
    },
    add: function(elm, type, value ) {
      var self = this, 
      list = '', 
      list_class = '',
      name = '', 
      post_id = 0, 
      get_option = '', 
      settings = '';
      list = $(elm).parent('.wpmdm-import-options-format-setting-inner').children('ul');
      list_class = 'wpmdm-import-options-list-item';
      name = list.data('name');
      post_id = list.data('id');
      get_option = list.data('getOption');
      settings = $('#'+name+'_settings_array').val();
      field_id = $(elm).data('id');
      if ( this.processing === false ) {
        this.processing = true;
        var count = parseInt(list.children('li').length);
        if ( type == 'list_item' ) {
          list.find('li input.wpmdm-import-options-setting-title', self).each(function(){
            var setting = $(this).attr('name'),
                regex = /\[([0-9]+)\]/,
                matches = setting.match(regex),
                id = null != matches ? parseInt(matches[1]) : 0;
            id++;
            if ( id > count) {
              count = id;
            }
          });
        }
        $.ajax({
          url: wpmdm_import_options.ajax,
          type: 'post',
          data: {
            action: 'wpmdm_import_add_' + type,
            count: count,
            name: name,
            post_id: post_id,
            get_option: get_option,
            settings: settings,
            type: type,
            nonce: wpmdm_import_options.nonce,
            value: value
          },
          beforeSend: function(data) {
            wpmdm_import_options_UI.add_message('progress', 'In progress');
          },
          success: function( data ) {
            wpmdm_import_options_UI.add_message('success', 'Item Added Successfully');
            
          },
          complete: function( data ) {

            var listItem = $('<li class="ui-state-active ' + list_class + '" data-list="' + field_id + '" data-key="' + field_id + '-' + count + '">' + data.responseText + '</li>');

            list.append(listItem);
            toogle_elm = listItem.find('.wpmdm-import-options-setting-edit');
            wpmdm_import_options_UI.toggle_list(toogle_elm);
            listItem.find('.wpmdm-import-options-setting-title').focus();
            
            wpmdm_import_options_UI.init_sortable(listItem);
            wpmdm_import_options_UI.init_numeric_slider(listItem);
            wpmdm_import_options_UI.parse_condition();
            self.processing = false;
            
            $('.'+list_class).find('textarea').each(function () {
              var textAreaID = $(this).attr('id');
              if($('#'+textAreaID).hasClass('wpmdm-import-options-wp-editor')) {
                $('#'+textAreaID).wp_editor();
              }
            });

            title_elm = $(data.responseText).find('.wpmdm-import-options-setting-title');
            wpmdm_import_options_UI.edit_title( title_elm );
            $('.list-item-empty[data-id=' + field_id + ']').remove();
          }
        });
      }
    },
    remove: function(e) {
      $(e).fadeOut('slow', function() {
        $(e).remove();
      });
    },
    edit_title: function(e) {
      if ( this.timer ) {
        clearTimeout(e.timer);
      }
      this.timer = setTimeout( function() {
        id = $(e).attr('id');
        $('.wpmdm-import-options-setting .open span[data-id='+id+']').text( $(e).val() );
      }, 100);
      return true;
    },
    update_id: function(e) {
      if ( this.timer ) {
        clearTimeout(e.timer);
      }
      this.timer = setTimeout( function() {
        wpmdm_import_options_UI.update_ids($(e).parents('ul'));
      }, 100);
      return true;
    },
    update_ids: function(list) {
      var last_section, section, list_items = list.children('li');
      list_items.each(function(index) {
        if ( $(this).hasClass('list-section') ) {
          section = $(this).find('.section-id').val().trim().toLowerCase().replace(/[^a-z0-9]/gi,'_');
          if (!section) {
            section = $(this).find('.section-title').val().trim().toLowerCase().replace(/[^a-z0-9]/gi,'_');
          }
          if (!section) {
            section = last_section;
          }
        }
        if ($(this).hasClass('list-setting') ) {
          $(this).find('.hidden-section').attr({'value':section});
        }
        last_section = section;
      });
    },
    condition_objects: function() {
      return 'select, input[type="radio"]:checked, input[type="text"], input[type="hidden"], input.wpmdm-import-options-numeric-slider-hidden-input';
    },
    match_conditions: function(condition) {
      var match;
      var regex = /(.+?):(is|not|contains|less_than|less_than_or_equal_to|greater_than|greater_than_or_equal_to)\((.*?)\),?/g;
      var conditions = [];

      while( match = regex.exec( condition ) ) {
        conditions.push({
          'check': match[1], 
          'rule':  match[2], 
          'value': match[3] || ''
        });
      }

      return conditions;
    },
    parse_condition: function() {
      $( '.wpmdm-import-options-format-settings[id^="setting_"][data-condition]' ).each(function() {

        var passed;
        var conditions = wpmdm_import_options_UI.match_conditions( $( this ).data( 'condition' ) );
        var operator = ( $( this ).data( 'operator' ) || 'and' ).toLowerCase();

        $.each( conditions, function( index, condition ) {

          var target   = $( '#setting_' + condition.check );
          var targetEl = !! target.length && target.find( wpmdm_import_options_UI.condition_objects() ).first();

          if ( ! target.length || ( ! targetEl.length && condition.value.toString() != '' ) ) {
            return;
          }

          var v1 = targetEl.length ? targetEl.val().toString() : '';
          var v2 = condition.value.toString();
          var result;

          switch ( condition.rule ) {
            case 'less_than':
              result = ( parseInt( v1 ) < parseInt( v2 ) );
              break;
            case 'less_than_or_equal_to':
              result = ( parseInt( v1 ) <= parseInt( v2 ) );
              break;
            case 'greater_than':
              result = ( parseInt( v1 ) > parseInt( v2 ) );
              break;
            case 'greater_than_or_equal_to':
              result = ( parseInt( v1 ) >= parseInt( v2 ) );
              break;
            case 'contains':
              result = ( v1.indexOf(v2) !== -1 ? true : false );
              break; 
            case 'is':
              result = ( v1 == v2 );
              break;
            case 'not':
              result = ( v1 != v2 );
              break;
          }

          if ( 'undefined' == typeof passed ) {
            passed = result;
          }

          switch ( operator ) {
            case 'or':
              passed = ( passed || result );
              break;
            case 'and':
            default:
              passed = ( passed && result );
              break;
          }
          
        });

        if ( passed ) {
          $(this).animate({opacity: 'show' , height: 'show'}, 200);
        } else {
          $(this).animate({opacity: 'hide' , height: 'hide'}, 200);
        }
        
        delete passed;

      });
    },
    init_conditions: function() {
      var delay = (function() {
        var timer = 0;
        return function(callback, ms) {
          clearTimeout(timer);
          timer = setTimeout(callback, ms);
        };
      })();

      $('.wpmdm-import-options-format-settings[id^="setting_"]').on( 'change.conditionals, keyup.conditionals', wpmdm_import_options_UI.condition_objects(), function(e) {
        if (e.type === 'keyup') {
          // handle keyup event only once every 500ms
          delay(function() {
            wpmdm_import_options_UI.parse_condition();
          }, 500);
        } else {
          wpmdm_import_options_UI.parse_condition();
        }
      });
      wpmdm_import_options_UI.parse_condition();
    },
    init_upload: function() {
      $(document).on('click', '.wpmdm_import_options_upload_media', function() {
        var field_id            = $(this).parent('.wpmdm-import-options-ui-upload-parent').find('input').attr('id'),
            post_id             = $(this).attr('rel'),
            save_attachment_id  = $('#'+field_id).hasClass('wpmdm-import-options-upload-attachment-id'),
            btnContent          = '';
        if ( window.wp && wp.media ) {
          window.wpmdm_import_options_media_frame = window.wpmdm_import_options_media_frame || new wp.media.view.MediaFrame.Select({
            title: $(this).attr('title'),
            button: {
              text: wpmdm_import_options.upload_text
            }, 
            multiple: false
          });
          window.wpmdm_import_options_media_frame.on('select', function() {
            var attachment = window.wpmdm_import_options_media_frame.state().get('selection').first(), 
                href = attachment.attributes.url,
                attachment_id = attachment.attributes.id,
                mime = attachment.attributes.mime,
                regex = /^image\/(?:jpe?g|png|gif|x-icon)$/i;
            if ( mime.match(regex) ) {
              btnContent += '<div class="wpmdm-import-options-ui-image-wrap"><img src="'+href+'" alt="" /></div>';
            }
            btnContent += '<a href="javascript:(void);" class="wpmdm-import-options-ui-remove-media wpmdm-import-options-ui-button button button-secondary light" title="'+wpmdm_import_options.remove_media_text+'"><span class="fa fa-trash"></span>'+wpmdm_import_options.remove_media_text+'</a>';
            $('#'+field_id).val( ( save_attachment_id ? attachment_id : href ) );
            $('#'+field_id+'_media').remove();
            $('#'+field_id).parent().parent('div').append('<div class="wpmdm-import-options-ui-media-wrap" id="'+field_id+'_media" />');
            $('#'+field_id+'_media').append(btnContent).slideDown();
            window.wpmdm_import_options_media_frame.off('select');
          }).open();
        } else {
          var backup = window.send_to_editor,
              intval = window.setInterval( 
                function() {
                  if ( $('#TB_iframeContent').length > 0 && $('#TB_iframeContent').attr('src').indexOf( "&field_id=" ) !== -1 ) {
                    $('#TB_iframeContent').contents().find('#tab-type_url').hide();
                  }
                  $('#TB_iframeContent').contents().find('.savesend .button').val(wpmdm_import_options.upload_text); 
                }, 50);
          tb_show('', 'media-upload.php?post_id='+post_id+'&field_id='+field_id+'&type=image&TB_iframe=1');
          window.send_to_editor = function(html) {
            var href = $(html).find('img').attr('src');
            if ( typeof href == 'undefined') {
              href = $(html).attr('src');
            } 
            if ( typeof href == 'undefined') {
              href = $(html).attr('href');
            }
            var image = /\.(?:jpe?g|png|gif|ico)$/i;
            if (href.match(image) && wpmdm_import_options_UI.url_exists(href)) {
              btnContent += '<div class="wpmdm-import-options-ui-image-wrap"><img src="'+href+'" alt="" /></div>';
            }
            btnContent += '<a href="javascript:(void);" class="wpmdm-import-options-ui-remove-media wpmdm-import-options-ui-button button button-secondary light" title="'+wpmdm_import_options.remove_media_text+'"><span class="icon wpmdm-import-options-icon-minus-circle"></span>'+wpmdm_import_options.remove_media_text+'</a>';
            $('#'+field_id).val(href);
            $('#'+field_id+'_media').remove();
            $('#'+field_id).parent().parent('div').append('<div class="wpmdm-import-options-ui-media-wrap" id="'+field_id+'_media" />');
            $('#'+field_id+'_media').append(btnContent).slideDown();
            wpmdm_import_options_UI.fix_upload_parent();
            tb_remove();
            window.clearInterval(intval);
            window.send_to_editor = backup;
          };
        }
        return false;
      });
    },
    init_upload_remove: function() {
      $(document).on('click', '.wpmdm-import-options-ui-remove-media', function(event) {
        event.preventDefault();
        var agree = confirm(wpmdm_import_options.remove_agree);
        if (agree) {
          wpmdm_import_options_UI.remove_image(this);
          return false;
        }
        return false;
      });
    },
    init_upload_fix: function(elm) {
      var id  = $(elm).attr('id'),
          val = $(elm).val(),
          img = $(elm).parent().next('.wpmdm-import-options-ui-media-wrap').find('img'),
          src = img.attr('src'),
          btnContent = '';
      if ( val == src ) {
        return;
      }
      if ( val != src ) {
        img.attr('src', val);
      }
      if ( val !== '' && ( typeof src == 'undefined' || src == false ) && wpmdm_import_options_UI.url_exists(val) ) {
        var image = /\.(?:jpe?g|png|gif|ico)$/i;
        if (val.match(image)) {
          btnContent += '<div class="wpmdm-import-options-ui-image-wrap"><img src="'+val+'" alt="" /></div>';
        }
        btnContent += '<a href="javascript:(void);" class="wpmdm-import-options-ui-remove-media wpmdm-import-options-ui-button button button-secondary light" title="'+wpmdm_import_options.remove_media_text+'"><span class="icon wpmdm-import-options-icon-minus-circle">'+wpmdm_import_options.remove_media_text+'</span></a>';
        $('#'+id).val(val);
        $('#'+id+'_media').remove();
        $('#'+id).parent().parent('div').append('<div class="wpmdm-import-options-ui-media-wrap" id="'+id+'_media" />');
        $('#'+id+'_media').append(btnContent).slideDown();
      } else if ( val == '' || ! wpmdm_import_options_UI.url_exists(val) ) {
        $(elm).parent().next('.wpmdm-import-options-ui-media-wrap').remove();
      }
    },
    init_numeric_slider: function(scope) {
      scope = scope || document;
      $(".wpmdm-import-options-numeric-slider-wrap", scope).each(function() {
        var hidden = $(".wpmdm-import-options-numeric-slider-hidden-input", this),
            value  = hidden.val(),
            helper = $(".wpmdm-import-options-numeric-slider-helper-input", this);
        if ( ! value ) {
          value = hidden.data("min");
          helper.val(value)
        }
        $(".wpmdm-import-options-numeric-slider", this).slider({
          min: hidden.data("min"),
          max: hidden.data("max"),
          step: hidden.data("step"),
          value: value, 
          slide: function(event, ui) {
            hidden.add(helper).val(ui.value).trigger('change');
          },
          create: function() {
            hidden.val($(this).slider('value'));
          },
          change: function() {
            wpmdm_import_options_UI.parse_condition();
          }
        });
      });
    },
    init_tabs: function() {
      $(".wrap.settings-wrap .ui-tabs, .wpmdm-import-options-inside-section-tabs-container").tabs({ 
        fx: { 
          opacity: "toggle", 
          duration: "fast"
        }
      });
      $(".wrap.settings-wrap .ui-tabs a.ui-tabs-anchor").on("click", function(event, ui) {
        var obj = "input[name='_wp_http_referer']";
        if ( $(obj).length > 0 ) {
          var url = $(obj).val(),
              hash = $(this).attr('href');
          if ( url.indexOf("#") != -1 ) {
            var o = url.split("#")[1],
                n = hash.split("#")[1];
            url = url.replace(o, n);
          } else {
            url = url + hash;
          }
          $(obj).val(url);
        }
      });
    },
    init_radio_image_select: function() {
      $(document).on('click', '.wpmdm-import-options-ui-radio-image', function() {
        $(this).closest('.wpmdm-import-options-type-radio-image').find('.wpmdm-import-options-ui-radio-image').removeClass('wpmdm-import-options-ui-radio-image-selected');
        $(this).toggleClass('wpmdm-import-options-ui-radio-image-selected');
        $(this).parent().find('.wpmdm-import-options-ui-radio').prop('checked', true).trigger('change');
      });
    },
    bind_select_wrapper: function() {
      $(document).on('change', '.wpmdm-import-options-ui-select', function () {
        $(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
      });
    },
    bind_colorpicker: function(field_id) {
      $('#'+field_id).wpColorPicker({
        change: function() {
          wpmdm_import_options_UI.parse_condition();
        }, 
        clear: function() {
          wpmdm_import_options_UI.parse_condition();
        }
      });
    },
    bind_date_picker: function(field_id, date_format) {
      $('#'+field_id).datepicker({
        showwpmdm_import_optionsherMonths: true,
        showButtonPanel: true,
        currentText: wpmdm_import_options.date_current,
        closeText: wpmdm_import_options.date_close,
        dateFormat: date_format
      });
    },
    bind_date_time_picker: function(field_id, date_format) {
      $('#'+field_id).datetimepicker({
        showwpmdm_import_optionsherMonths: true,
        closeText: wpmdm_import_options.date_close,
        dateFormat: date_format
      });
    },
    fix_upload_parent: function() {
      $('.wpmdm-import-options-ui-upload-input').not('.wpmdm-import-options-upload-attachment-id').on('focus blur', function(){
        $(this).parent('.wpmdm-import-options-ui-upload-parent').toggleClass('focus');
        wpmdm_import_options_UI.init_upload_fix(this);
      });
    },
    remove_image: function(e) {
      $(e).parent().parent().find('.wpmdm-import-options-ui-upload-input').attr('value','');
      $(e).parent('.wpmdm-import-options-ui-media-wrap').remove();
    },
    fix_textarea: function() {
      $('.wp-editor-area').focus( function(){
        $(this).parent('div').css({borderColor:'#bbb'});
      }).blur( function(){
        $(this).parent('div').css({borderColor:'#ccc'});
      });
    },
    replicate_ajax: function() {
      if (location.href.indexOf("#") != -1) {
        var url = $("input[name=\'_wp_http_referer\']").val(),
            hash = location.href.substr(location.href.indexOf("#"));
        $("input[name=\'_wp_http_referer\']").val( url + hash );
        this.scroll_to_top();
      }
      setTimeout( function() {
        $(".wrap.settings-wrap .fade").fadeOut("fast");
      }, 3000 );
    },
    reset_settings: function() {
      $(document).on("click", ".reset-settings", function(event){
        var $this=$(this);
        var agree = confirm(wpmdm_import_options.reset_agree);
        if (agree) {
          $.ajax({
            type: "POST",
            url: wpmdm_import_options.ajax,
            data: {
                action: 'wpmdm_import_options_reset_options',
                nonce: $this.data('nonce'),
                page: $this.data('page'),
            },
            success: function(output) {
                location.reload();
            }
          });
          return false;
        } else {
          return false;
        }
        event.preventDefault();
      });
    },

    
    url_exists: function(url) {
      var link = document.createElement('a')
      link.href = url
      if ( link.hostname != window.location.hostname ) {
        return true; // Stop the code from checking across domains.
      }
      var http = new XMLHttpRequest();
      http.open('HEAD', url, false);
      http.send();
      return http.status!=404;
    },
    scroll_to_top: function() {
      setTimeout( function() {
        $(this).scrollTop(0);
      }, 50 );
    },
    select_element: function() {

      var select_element =  $('#wpmdm-import-options-select-field').data('select');
    
      if( $('#'+select_element).length ) {

          var section_id = $('#'+select_element).parents('.ui-tabs-panel').attr('id');

          $('.ui-tabs').tabs("option", "active", $("#" + section_id).index());


          var top = $('#'+select_element).parents('.wpmdm-import-options-format-setting-wrap').find('.wpmdm-import-options-format-setting-label').offset().top;

          var admin_bar_height = $('#wpadminbar').height();

          var scrollTop = top - admin_bar_height;

          $('html, body').animate({
            scrollTop: scrollTop
          }, 500);


          $('#'+select_element).select();

      }
    }
  };
  $(document).ready( function() {

    wpmdm_import_options_UI.init();


  });

})(jQuery);

/* Gallery */
!function ($) {
  
  wpmdm_import_options_gallery = {
      
    frame: function (elm, state) {
      
      var selection = this.select(elm)
      
      this._frame = wp.media({
        id:         'wpmdm-import-options-gallery-frame'
      , frame:      'post'
      , state:      state
      , title:      wp.media.view.l10n.editGalleryTitle
      , editing:    true
      , multiple:   true
      , selection:  selection
      })
      
      this._frame.on('update', function () {
        var controller = wpmdm_import_options_gallery._frame.states.get('gallery-edit')
          , library = controller.get('library')
          , ids = library.pluck('id')
          , parent = $(elm).parents('.wpmdm-import-options-format-setting-inner')
          , input = parent.children('.wpmdm-import-options-gallery-value')
          , shortcode = wp.media.gallery.shortcode( selection ).string().replace(/\"/g,"'")
        
        input.attr('value', ids)
                        
        if ( parent.children('.wpmdm-import-options-gallery-list').length <= 0 )
          input.after('<ul class="wpmdm-import-options-gallery-list" />')
        
        $.ajax({
          type: 'POST',
          url: ajaxurl,
          dataType: 'html',
          data: {
            action: 'gallery_update'
          , ids: ids
          },
          success: function(res) {
            parent.children('.wpmdm-import-options-gallery-list').html(res);
            if ( input.hasClass('wpmdm-import-options-gallery-shortcode') ) {
              input.val(shortcode);
            }
            if ( $(elm).parent().children('.wpmdm-import-options-gallery-delete').length <= 0 ) {
              $(elm).parent().append('<a href="#" class="wpmdm-import-options-ui-button button button-secondary hug-left wpmdm-import-options-gallery-delete">' + wpmdm_import_options.delete + '</a>');
            }
            $(elm).text(wpmdm_import_options.edit);
            $(elm).addClass('wpmdm-import-options-gallery-edit');
            $(elm).removeClass('wpmdm-import-options-gallery-add');
            wpmdm_import_options_UI.parse_condition();
          }
        })
      })
        
      return this._frame
      
    }
      
  , select: function (elm) {
      var input = $(elm).parents('.wpmdm-import-options-format-setting-inner').children('.wpmdm-import-options-gallery-value')
        , ids = input.attr('value')
        , _shortcode = input.hasClass('wpmdm-import-options-gallery-shortcode') ? ids : '[gallery ids=\'' + ids + '\]'
        , shortcode = wp.shortcode.next('gallery', ( ids ? _shortcode : wp.media.view.settings.wpmdm_import_options_gallery.shortcode ) )
        , defaultPostId = wp.media.gallery.defaults.id
        , attachments
        , selection
        
      // Bail if we didn't match the shortcode or all of the content.
      if ( ! shortcode )
        return
      
      // Ignore the rest of the match object.
      shortcode = shortcode.shortcode
      
      if ( _.isUndefined( shortcode.get('id') ) && ! _.isUndefined( defaultPostId ) )
        shortcode.set( 'id', defaultPostId )
      
      if ( _.isUndefined( shortcode.get('ids') ) && ! input.hasClass('wpmdm-import-options-gallery-shortcode') && ids )
        shortcode.set( 'ids', ids )
      
      if ( _.isUndefined( shortcode.get('ids') ) )
        shortcode.set( 'ids', '0' )
      
      attachments = wp.media.gallery.attachments( shortcode )

      selection = new wp.media.model.Selection( attachments.models, {
        props:    attachments.props.toJSON()
      , multiple: true
      })
      
      selection.gallery = attachments.gallery
    
      // Fetch the query's attachments, and then break ties from the query to allow for sorting.
      selection.more().done( function () {
        selection.props.set({ query: false })
        selection.unmirror()
        selection.props.unset('orderby')
      })
      
      return selection
      
    }
    
  , open: function (elm, state) {
      
      wpmdm_import_options_gallery.frame(elm, state).open()
      
    }
  
  , remove: function (elm) {
      
      if ( confirm( wpmdm_import_options.confirm ) ) {
        
        $(elm).parents('.wpmdm-import-options-format-setting-inner').children('.wpmdm-import-options-gallery-value').attr('value', '');
        $(elm).parents('.wpmdm-import-options-format-setting-inner').children('.wpmdm-import-options-gallery-list').remove();
        $(elm).next('.wpmdm-import-options-gallery-edit').text( wpmdm_import_options.create );
        $(elm).remove();
        wpmdm_import_options_UI.parse_condition();
        
      }

    }
  
  }

  // Gallery delete
  $(document).on('click.wpmdm_import_options_gallery.data-api', '.wpmdm-import-options-gallery-delete', function (e) {
    e.preventDefault()
    wpmdm_import_options_gallery.remove($(this))
  })
  
  // Gallery edit
  $(document).on('click.wpmdm_import_options_gallery.data-api', '.wpmdm-import-options-gallery-edit', function (e) {
    e.preventDefault()
    wpmdm_import_options_gallery.open($(this), 'gallery-edit')
  })

  // Gallery edit
  $(document).on('click.wpmdm_import_options_gallery.data-api', '.wpmdm-import-options-gallery-add', function (e) {
    e.preventDefault()
    wpmdm_import_options_gallery.open($(this), 'gallery-library')
  })
  
}(window.jQuery);

/*!
 * Adds metabox tabs
 */
!function ($) {

  $(document).on('ready', function () {
    
    // Loop over the metaboxes
    $('.wpmdm-import-options-metabox-wrapper').each( function() {
    
      // Only if there is a tab option
      if ( $(this).find('.wpmdm-import-options-type-tab').length ) {
        
        // Add .wpmdm-import-options-metabox-panels
        $(this).find('.wpmdm-import-options-type-tab').parents('.wpmdm-import-options-metabox-wrapper').wrapInner('<div class="wpmdm-import-options-metabox-panels" />')
        
        // Wrapp with .wpmdm-import-options-metabox-tabs & add .wpmdm-import-options-metabox-nav before .wpmdm-import-options-metabox-panels
        $(this).find('.wpmdm-import-options-metabox-panels').wrap('<div class="wpmdm-import-options-metabox-tabs" />').before('<ul class="wpmdm-import-options-metabox-nav" />')
        
        // Loop over settings and build the tabs nav
        $(this).find('.wpmdm-import-options-format-settings').each( function() {
      
          if ( $(this).find('.wpmdm-import-options-type-tab').length > 0 ) {
            var title = $(this).find('.wpmdm-import-options-type-tab').prev().find('label').text()
              , id = $(this).attr('id')
  
            // Add a class, hide & append nav item 
            $(this).addClass('is-panel').hide()
            $(this).parents('.wpmdm-import-options-metabox-panels').prev('.wpmdm-import-options-metabox-nav').append('<li><a href="#' + id + '">' + title + '</a></li>')
            
          }
          
        })
        
        // Loop over the panels and wrap and ID them.
        $(this).find('.is-panel').each( function() {
          var id = $(this).attr('id')
          
          $(this).add( $(this).nextUntil('.is-panel') ).wrapAll('<div id="' + id + '" class="tab-content" />')
          
        })
        
        // Create the tabs
        $(this).find('.wpmdm-import-options-metabox-tabs').tabs({
          activate: function( event, ui ) {
            var parent = $(this).outerHeight(),
                child = $(this).find('.wpmdm-import-options-metabox-panels').outerHeight() + 8,
                minHeight = parent - 34
            if ( $(this).find('.wpmdm-import-options-metabox-panels').css('padding') == '12px' && child < parent ) {
              $(this).find('.wpmdm-import-options-metabox-panels').css({ minHeight: minHeight })
            }
          }
        })
        
        // Move the orphaned settings to the top
        $(this).find('.wpmdm-import-options-metabox-panels > .wpmdm-import-options-format-settings').prependTo($(this))
        
        // Remove a bunch of classes to stop style conflicts.
        $(this).find('.wpmdm-import-options-metabox-tabs').removeClass('ui-widget ui-widget-content ui-corner-all')
        $(this).find('.wpmdm-import-options-metabox-nav').removeClass('ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all')
        $(this).find('.wpmdm-import-options-metabox-nav li').removeClass('ui-state-default ui-corner-top ui-tabs-active ui-tabs-active')
        $(this).find('.wpmdm-import-options-metabox-nav li').on('mouseenter mouseleave', function() { $(this).removeClass('ui-state-hover') })

      }

    })
     
  })
  
}(window.jQuery);

/*!
 * Adds theme option tabs
 */
!function ($) {

  $(document).on('ready', function () {
    
    // Loop over the theme options
    $('#wpmdm-import-options-settings-api .inside').each( function() {
    
      // Only if there is a tab option
      if ( $(this).find('.wpmdm-import-options-type-tab').length ) {
        
        // Add .wpmdm-import-options-theme-option-panels
        $(this).find('.wpmdm-import-options-type-tab').parents('.inside').wrapInner('<div class="wpmdm-import-options-theme-option-panels" />')
        
        // Wrap with .wpmdm-import-options-theme-option-tabs & add .wpmdm-import-options-theme-option-nav before .wpmdm-import-options-theme-option-panels
        $(this).find('.wpmdm-import-options-theme-option-panels').wrap('<div class="wpmdm-import-options-theme-option-tabs" />').before('<ul class="wpmdm-import-options-theme-option-nav" />')
        
        // Loop over settings and build the tabs nav
        $(this).find('.wpmdm-import-options-format-settings').each( function() {
      
          if ( $(this).find('.wpmdm-import-options-type-tab').length > 0 ) {
            var title = $(this).find('.wpmdm-import-options-type-tab').prev().find('.label').text()
              , id = $(this).attr('id')
  
            // Add a class, hide & append nav item 
            $(this).addClass('is-panel').hide()
            $(this).parents('.wpmdm-import-options-theme-option-panels').prev('.wpmdm-import-options-theme-option-nav').append('<li><a href="#' + id + '">' + title + '</a></li>')
            
          } else {
          
          }
          
        })
        
        // Loop over the panels and wrap and ID them.
        $(this).find('.is-panel').each( function() {
          var id = $(this).attr('id')
          
          $(this).add( $(this).nextUntil('.is-panel') ).wrapAll('<div id="' + id + '" class="tab-content" />')
          
        })
        
        // Create the tabs
        $(this).find('.wpmdm-import-options-theme-option-tabs').tabs({
          
        })
        
        // Move the orphaned settings to the top
        $(this).find('.wpmdm-import-options-theme-option-panels > .wpmdm-import-options-format-settings').prependTo($(this).find('.wpmdm-import-options-theme-option-tabs'))
      
      }
    
    })
     
  })
  
}(window.jQuery);

/*!
 * Fixes the state of metabox radio buttons after a Drag & Drop event.
 */
!function ($) {
  
  $(document).on('ready', function () {

    // detect mousedown and store all checked radio buttons
    $('.hndle').on('mousedown', function () {
      
      // get parent element of .hndle selected. 
      // We only need to monitor radios insde the object that is being moved.
      var parent_id = $(this).closest('div').attr('id')
      
      // set live event listener for mouse up on the content .wrap 
      // then give the dragged div time to settle before firing the reclick function
      $('.wrap').on('mouseup', function () {
        
        var wpmdm_import_options_checked_radios = {}
        
        // loop over all checked radio buttons inside of parent element
        $('#' + parent_id + ' input[type="radio"]').each( function () {
          
          // stores checked radio buttons
          if ( $(this).is(':checked') ) {
            
            wpmdm_import_options_checked_radios[$(this).attr('name')] = $(this).val()
          
          }
          
          // write to the object
          $(document).data('wpmdm_import_options_checked_radios', wpmdm_import_options_checked_radios)
          
        })
        
        // restore all checked radio buttons 
        setTimeout( function () {
      
          // get object of checked radio button names and values
          var checked = $(document).data('wpmdm_import_options_checked_radios')
          
          // step thru each object element and trigger a click on it's corresponding radio button
          for ( key in checked ) {
            
            $('input[name="' + key + '"]').filter('[value="' + checked[key] + '"]').trigger('click')
            
          }
          
          $('.wrap').unbind('mouseup')
          
        }, 50 )
      
      })
      
    })
  
  })
  
}(window.jQuery);

/*!
 * Adds opacity to the default colorpicker
 *
 * Derivative work of the Codestar WP Color Picker.
 */
;(function ( $, window, document, undefined ) {
  'use strict';

  // adding alpha support for Automattic Color.js toString function.
  if( typeof Color.fn.toString !== undefined ) {

    Color.fn.toString = function () {

      // check for alpha
      if ( this._alpha < 1 ) {
        return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
      }

      var hex = parseInt( this._color, 10 ).toString( 16 );

      if ( this.error ) { return ''; }

      // maybe left pad it
      if ( hex.length < 6 ) {
        for (var i = 6 - hex.length - 1; i >= 0; i--) {
          hex = '0' + hex;
        }
      }

      return '#' + hex;

    };

  }

  $.wpmdm_import_options_ParseColorValue = function( val ) {

    var value = val.replace(/\s+/g, ''),
        alpha = ( value.indexOf('rgba') !== -1 ) ? parseFloat( value.replace(/^.*,(.+)\)/, '$1') * 100 ) : 100,
        rgba  = ( alpha < 100 ) ? true : false;

    return { value: value, alpha: alpha, rgba: rgba };

  };

  $.fn.wpmdm_import_options_wpColorPicker = function() {

    return this.each(function() {

      var $this = $(this);

      // check for rgba enabled/disable
      if( $this.data('rgba') !== false ) {

        // parse value
        var picker = $.wpmdm_import_options_ParseColorValue( $this.val() );

        // wpColorPicker core
        $this.wpColorPicker({

          // wpColorPicker: change
          change: function( event, ui ) {

            // update checkerboard background color
            $this.closest('.wp-picker-container').find('.wpmdm-import-options-opacity-slider-offset').css('background-color', ui.color.toString());
            $this.trigger('keyup');

          },

          // wpColorPicker: create
          create: function( event, ui ) {

            // set variables for alpha slider
            var a8cIris       = $this.data('a8cIris'),
                $container    = $this.closest('.wp-picker-container'),

                // appending alpha wrapper
                $alpha_wrap   = $('<div class="wpmdm-import-options-opacity-wrap">' +
                                  '<div class="wpmdm-import-options-opacity-slider"></div>' +
                                  '<div class="wpmdm-import-options-opacity-slider-offset"></div>' +
                                  '<div class="wpmdm-import-options-opacity-text"></div>' +
                                  '</div>').appendTo( $container.find('.wp-picker-holder') ),

                $alpha_slider = $alpha_wrap.find('.wpmdm-import-options-opacity-slider'),
                $alpha_text   = $alpha_wrap.find('.wpmdm-import-options-opacity-text'),
                $alpha_offset = $alpha_wrap.find('.wpmdm-import-options-opacity-slider-offset');

            // alpha slider
            $alpha_slider.slider({

              // slider: slide
              slide: function( event, ui ) {

                var slide_value = parseFloat( ui.value / 100 );

                // update iris data alpha && wpColorPicker color option && alpha text
                a8cIris._color._alpha = slide_value;
                $this.wpColorPicker( 'color', a8cIris._color.toString() );
                $alpha_text.text( ( slide_value < 1 ? slide_value : '' ) );

              },

              // slider: create
              create: function() {

                var slide_value = parseFloat( picker.alpha / 100 ),
                    alpha_text_value = slide_value < 1 ? slide_value : '';

                // update alpha text && checkerboard background color
                $alpha_text.text(alpha_text_value);
                $alpha_offset.css('background-color', picker.value);

                // wpColorPicker clear button for update iris data alpha && alpha text && slider color option
                $container.on('click', '.wp-picker-clear', function() {

                  a8cIris._color._alpha = 1;
                  $alpha_text.text('');
                  $alpha_slider.slider('option', 'value', 100).trigger('slide');

                });

                // wpColorPicker default button for update iris data alpha && alpha text && slider color option
                $container.on('click', '.wp-picker-default', function() {

                  var default_picker = $.wpmdm_import_options_ParseColorValue( $this.data('default-color') ),
                      default_value  = parseFloat( default_picker.alpha / 100 ),
                      default_text   = default_value < 1 ? default_value : '';

                  a8cIris._color._alpha = default_value;
                  $alpha_text.text(default_text);
                  $alpha_slider.slider('option', 'value', default_picker.alpha).trigger('slide');

                });

                // show alpha wrapper on click color picker button
                $container.on('click', '.wp-color-result', function() {
                  $alpha_wrap.toggle();
                });

                // hide alpha wrapper on click body
                $('body').on( 'click.wpcolorpicker', function() {
                  $alpha_wrap.hide();
                });

              },

              // slider: options
              value: picker.alpha,
              step: 1,
              min: 1,
              max: 100

            });
          }

        });

      } else {

        // wpColorPicker default picker
        $this.wpColorPicker({
          change: function() {
            $this.trigger('keyup');
          }
        });

      }

    });

  };

  $(document).ready( function() {
    $('.wpmdm-import-options-hide-color-picker.wpmdm-import-options-colorpicker-opacity').wpmdm_import_options_wpColorPicker();
  });


})( jQuery, window, document );