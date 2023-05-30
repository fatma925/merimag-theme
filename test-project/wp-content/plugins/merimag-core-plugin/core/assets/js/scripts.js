
/* BLOCKS */

(function($) {
  merimag_blocks_UI = {
    ajax_loading : false,
    sliders: [],
    slider_selectors: false,
    video_palyers: [],
    init: function() {
      // set var
      var in_customizer = false;
      // check for wp.customize return boolean
      if ( typeof wp !== 'undefined' ) {
          in_customizer =  typeof wp.customize !== 'undefined' ? true : false;
      }
      
     
      this.init_tabs();
	    this.init_filters();
      $(window).load(function() {
        merimag_blocks_UI.init_ticker();
      });
	     $('body').addClass('body-js');
      this.init_load_more();
      this.init_animations();
         this.infinite_scroll();
    },

    init_tabs: function() {
      $.initialize('.merimag-tabs-shortcode', function() {
        var id = $(this).attr('id');
        $(this).tabs({
          show:function(event,ui) {
            $(ui.panel).effect("slide",options,1500);
          },
          activate: function(event, ui) {
            $(ui.newPanel).find( merimag_blocks_UI.slider_selectors).each( function() {
              var id = $(this).attr('id');
              $('#' + id).slick('setPosition');
            })
          },
          beforeActivate: function(event, ui) {
            $(ui.oldTab).css('background', 'transparent');
            var background = merimag_blocks_UI.get_bgcolor( $(ui.newPanel) );
            $(ui.newTab).css('background',background );
          },
          create: function(event, ui ) {
            var background = merimag_blocks_UI.get_bgcolor( $(ui.panel) );
            $(ui.tab).css('background',background );
            $(document).trigger('tabs_' + id + '_created' );
            $('.merimag-loader-container[data-id=' + id + ']').hide();
            $('.merimag-tabs-container[data-id=' + id + ']').show();
            
          }
        });
      })
      $.initialize('.merimag-accordion-shortcode', function() {
        var collapsible = $(this).data('collapsible') ? true : false;
        $(this).accordion({
          collapsible: collapsible,
          heightStyle: "content"
        });
      })
    },
    get_bgcolor: function(obj) {
        var real = obj.css('background-color');
        var none = 'rgba(0, 0, 0, 0)';
        if (real === none) {
            return obj.parents().filter(function() {
                return $(this).css('background-color') != none
            }).first().css('background-color');
        } else {
            return real
        }
    },
    init_filters: function() {
      $.initialize('.merimag-box-filter', function() {
        var container = $(this);
        var event = $(this).data('event');
        event = event == 'mouseover' ? 'mouseenter click' : 'click',
        $(this).tabs({
          event: event,
          create: function(event, ui) {
            if( container.hasClass('vertical-tabs') === false ) {
              $(container).find(".merimag-block-filters").addClass('flexmenu-init').flexMenu({
                popupClass: 'flexMenu-popup',
                cutoff: false,
                showOnHover: true,
                linkText: merimag_options.strings.flex_menu_more,
                linkTitle: merimag_options.strings.flex_menu_title,
                linkTextAll: merimag_options.strings.flex_menu_menu,
                linkTitleAll: merimag_options.strings.flex_menu_menu_all,
              }).addClass('flexmenu-added');
            }
            

          },
          activate: function(event, ui) {
            $(ui.newPanel).find( merimag_blocks_UI.slider_selectors).each( function() {
              var id = $(this).attr('id');
              $('#' + id).slick('setPosition');
            })
          },
          beforeActivate:function(event, ui) {
            if( $(container).hasClass('ajax-loading') === true ) {
              return false;
            }
            
            var filters_input_id = $(ui.newTab).data('filters-input');
            var atts = $('#' + filters_input_id).val();
            if( $(ui.newTab).hasClass('tab-loaded') === false ) {
              $.ajax({
                type: 'POST',
                url: merimag_options.ajax,
                data:{ nonce: merimag_options.nonce, action: 'merimag_get_box', atts : atts },
                success: function(data) {
                  $(ui.newPanel).append(data);
                  $(ui.newTab).addClass('tab-loaded')
                },
                beforeSend: function(data) {
                  $(ui.newPanel).addClass('loading');
                  $(container).addClass('ajax-loading');
                },
                complete: function(data) {
                  $(ui.newPanel).removeClass('loading');
                  $(container).removeClass('ajax-loading');
                  
                }
              });
            }
          }
        });
      })
    },
    init_load_more: function() {
      $.initialize('.merimag-block-pagination', function() {
        var id = $(this).data('id');
        $(this).find('.prev').attr('data-id', id );
        $(this).find('.prev').addClass('merimag-load-prev');
        $(this).find('.next').attr('data-id', id );
        $(this).find('.next').addClass('merimag-load-next');
      });
      $.initialize('.merimag-load-next', function() {
        $(this).on('click', function() {
          if( $(this).hasClass('merimag-button-disabled') === false ) {
            var delete_exiting = $(this).hasClass('merimag-load-more') === true ? false : true;
            merimag_blocks_UI.load_more( $(this), delete_exiting );
          }
          return false;
        });
      });
      $.initialize('.merimag-load-prev', function() {
        $(this).on('click', function() {
          if( $(this).hasClass('merimag-button-disabled') === false ) {
            merimag_blocks_UI.load_more( $(this), true, true );
          }
          return false;
        });
      });
      $(document).on('click', '.merimag-button-disabled', function() {
        return false;
      });
      $.initialize('.merimag-block-pagination', function() {
        var page = $(this).data('page');
        var total = $(this).data('total');
        var per_page = $(this).data('per-page');
        var btn = $(this);
        $(this).pagination({
            pages: total,
            itemOnPage: per_page,
            currentPage: page,
            cssStyle: 'light-theme',
            displayedPages: 3,
            edges: 1,
            listStyle: 'page-numbers',
            onPageClick: function (page, evnt) {
               merimag_blocks_UI.load_more( btn, true, false, page );
            }
        });
      });
    },
    infinite_scroll: function() {
        $.initialize('.merimag-block-data-container.infinite-scroll', function() {
          var id = $(this).data('id');
          var elm = $(this);
          $('.merimag-load-next[data-id=' + id + ']').trigger('click');
          $(window).scroll(function() {
            $(elm).on('inview', function(e, isInview) {
              if( isInview ) {
                if( $('.merimag-load-next[data-id=' + id + ']').hasClass('loading') === false ) {
                  $('.merimag-load-next[data-id=' + id + ']').trigger('click');
                }
                $(this).addClass('inview');
              } else {
                $(this).removeClass('inview');
              }
            });
          })
        })
        
    },
    load_more: function( btn, delete_exiting = false, prev = false, set_page = false ) {
      var id = $(btn).data('id');
      var atts = $('#atts-input-' + id ).val();
      var block = $('#block-input-' + id ).val();
      var current_page = parseInt( $('#page-input-' + id ).val() );
      var page = prev === true ? current_page - 1 : current_page + 1;
      page = set_page !== false ? set_page : page;
      var data_container = $('.merimag-block-data-container[data-id="' + id + '"]').children('.merimag-block-data-content');
      var append = data_container.children('.merimag-block-grid').length > 0 ? data_container.children('.merimag-block-grid') : data_container;
      
      $.ajax({
        type: 'POST',
        url: merimag_options.ajax,
        data: { nonce: merimag_options.nonce, action: 'merimag_block_load_next', atts: atts, block: block, page: page, query_vars: merimag_options.query_vars },
        beforeSend: function(data) {
          btn.addClass('loading');
          if( $('.merimag-block-data-container[data-id=' + id + ']').hasClass('infinite-scroll') === false ) {
            $('.merimag-block-data-container[data-id="' + id + '"]').addClass('loading');
          }
          merimag_blocks_UI.ajax_loading = true;
        },
        success: function(data) {
          if( delete_exiting === true ) {
            append.html( data );
          } else {
            append.append( data );
          }
          var new_page = prev === true ? current_page - 1 : current_page + 1;
          new_page = set_page !== false ? set_page : new_page;
          $('#page-input-' + id ).val( new_page );
          //$('#page-input-' + id ).trigger('change');
          merimag_blocks_UI.update_buttons_status( $('#page-input-' + id ) );
        },
        error: function(data) {
          btn.removeClass('loading');
          $('.merimag-block-data-container[data-id="' + id + '"]').removeClass('loading');
          merimag_blocks_UI.ajax_loading = false;
        },
        complete: function(data) {
          btn.removeClass('loading');
          $('.merimag-block-data-container[data-id="' + id + '"]').removeClass('loading');
          merimag_blocks_UI.ajax_loading = false;
          if( $('.merimag-block-data-container[data-id=' + id + ']').hasClass('infinite-scroll') === true ) {
            if( $('.merimag-block-data-container[data-id=' + id + ']').hasClass('inview') === true ) {
                if( $(btn).hasClass('loading') === false ) {
                  $(btn).trigger('click');
                }
            };
          }
          $('.merimag-block-data-container[data-id="' + id + '"]').find('.merimag-grid-equal-height:not(.merimag-grid-side-infos)').each(function() {
            $(this).find('.merimag-block-infos-content').matchHeight();
          })
          $('.merimag-block-data-container[data-id="' + id + '"]').find('.merimag-grid-equal-height.merimag-grid-side-infos').each(function() {
            $(this).find('.merimag-side-infos').matchHeight({
              byRow: false
            });
          })
        }
      });
    },
    update_buttons_status: function( input ) {
       var id = $(input).data('id');
       var page = $(input).val();
       var current = parseInt( page );
       var next = $('.merimag-load-next[data-id="' + id + '"]');
       var prev = $('.merimag-load-prev[data-id="' + id + '"]');
       $('.merimag-load-next[data-id="' + id + '"]').each( function() {
          var max  = parseInt( $(input).data('max') );
          if( current === max ) {
            next.addClass('merimag-button-disabled');
          } else {
            next.removeClass('merimag-button-disabled');
          }
       });
       $('.merimag-filter-page-current[data-id="' + id + '"]').text(page);
       $('.merimag-load-prev[data-id="' + id + '"]').each( function() {
          var min  = parseInt( $(input).data('min') );
          if( current === min ) {
            prev.addClass('merimag-button-disabled');
          } else {
            prev.removeClass('merimag-button-disabled');
          }
       });
       // if( $('.merimag-block-pagination[data-id="' + id + '"]').length > 0 ) {
       //    $('.merimag-block-pagination[data-id="' + id + '"]').pagination('selectPage', page);
       // }
    },
    
    verify_tabs_container: function( elm ) {
      if( $(elm).parents('.merimag-shortcode-tabs').length > 0 ) {
        return elm.parents('.merimag-shortcode-tabs').attr('id');
      }
      return false;
    },
    init_ticker: function() {
      $.initialize('.merimag-news-ticker', function() {
        var id = $(this).attr('id');
        var width = $(this).parent().outerWidth();
        $('#' + id).css( 'max-width', width - 10 ).show();
        var direction = $('body').hasClass('rtl') === true ? 'right' : 'left';
        $('#' + id).bind('beforeStarting', function () {
          $(this).addClass('ticker-init');
        }).marquee({
          duration: 20000,
          delayBeforeStart: 2000,
          startVisible: true,
          duplicated: true,
          pauseOnCycle: true,
          gap: 0,
          pauseOnHover: true,
          direction: direction,
        });
      })
    },
    init_animations: function() {
       $.initialize('[data-animation-classes]', function() {
          var elm = $(this);
          var in_slider = $(elm).parents('.merimag-slider-block').length;
          var menu_item = $(elm).parents('.menu-item')[0] ? $(elm).parents('.menu-item')[0] : false;
          $(elm).on('inview', function(e, isInview) {
            var hidden_parent = false;
            $(this).parents().each( function() {
              if( $(this).css('visibility') === 'hidden' ) {
                hidden_parent = $(this);
              }
            });
            if( isInview && !in_slider && hidden_parent === false ) {
              animate_element( elm );
            }
          });
          
          var animate_element = function( elm ) {
            var animation = $(elm).data('animation-classes');
            var delay = $(elm).data('animation-delay') ? $(elm).data('animation-delay') : 20;
            var menu_item = $(elm).parents('.menu-item').length > 0 ? true : false;
            var animate = $(elm).hasClass('viewed') === false ? true : false;
            var animate = menu_item === true ? true : animate;
            if( delay && animate === true ) {
              setTimeout(function() {
                $(elm).addClass(animation);
                $(elm).addClass('viewed');
              }, delay);
              setTimeout(function() {
                $(elm).removeClass(animation);
              }, delay + 500 );

            }
          }
       });
       $.initialize('.merimag-review-score-display.circle, .merimag-review-score-content.circle', function() {
          var elm = $(this);
          var val = $(this).data('width');
          var fill = $(this).css('color') ? $(this).css('color') : 'white';
          var size = $(this).hasClass('merimag-review-score-content') === true ? 100 : 40;
          $(elm).circleProgress({ 
            value: val,
            size: size,
            startAngle: 0,
            fill: fill,
            emptyFill: 'transparent',
          });
       });
       $.initialize('.merimag-review-score-display.bar', function() {
          var elm = $(this);
          $(elm).on('inview', function(e, isInview) {
            if( isInview ) {
              var width = $(this).data('width');
              if( width ) {
                $(elm).css('width', width);
              }
            }
          });
       })
    },
    
  };

  	
  merimag_blocks_UI.init();
})(jQuery);


