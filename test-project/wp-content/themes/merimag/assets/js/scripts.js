/* SHARE */
(function($) {
  $(document).ready( function() {
    $(document).on('click', '.merimag-share-item.more', function() {
      $(this).parent('.merimag-inline-sharing').toggleClass('collapsed-sharing');
      if( $(this).parent('.merimag-inline-sharing').hasClass('prio') === true ) {
        $(this).addClass('parenthasprio');
      }
      if( $(this).hasClass('parenthasprio') === true ) {
        $(this).parent('.merimag-inline-sharing').toggleClass('prio');
      }
      return false;
    });
  });
})(jQuery);

/* SCRIPTS */
(function($) {
  merimag_options_UI = {

    sidebars: [],
    init: function() {
     
      var in_customizer = false;
      // check for wp.customize return boolean
      if ( typeof wp !== 'undefined' ) {
          in_customizer =  typeof wp.customize !== 'undefined' ? true : false;
      }
      this.init_elements();
      this.woocommerce();
      this.back_to_top();
    },
    load_styles: function() {
      var styles = merimag_theme.styles;
      for(var i in styles) {
        $('body').append('<link rel="stylesheet" href="'  +  styles[i] + '" lazyload>');
      }
    },

    back_to_top: function() {
      $(document).ready(function() {
        show_back_to_top();
      })
      $(window).scroll(function() {
        show_back_to_top();
      })
      $(document).on('click', '.merimag-back-to-top', function() {
        document.body.scrollTop = 0;
      });
      function show_back_to_top() {
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
          $('.merimag-back-to-top').fadeIn();
        } else {
          $('.merimag-back-to-top').fadeOut();
        }
      }
    },

    load_images: function( elem ) {
      $(elem).find('[data-src]').each( function() {
        var image = $(this).data('src');
        $(this).css('background-image', image );
      })
    },
    woocommerce: function() {
      $.initialize('.products', function() {
        $(this).find('.woocommerce-LoopProduct-link').matchHeight();
      });
      $(document).ready(function() {
        $('.woocommerce-tabs').tabs( {
          beforeActivate: function(event, ui) {
            $(ui.oldTab).css('background', 'transparent');
            var background = merimag_options_UI.get_bgcolor( $(ui.newPanel) );
            $(ui.newTab).css('background',background );
          },
          create: function(event, ui ) {
            var background = merimag_options_UI.get_bgcolor( $(ui.panel) );
            $(ui.tab).css('background',background );
          }
        });
      })
      
      $.initialize('.flex-control-thumbs', function() {
          $(this).slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            focusOnSelect: true,
            arrows: true,
            infinite: false,
          });
          $(this).on('beforeChange', function(e, slick, currentSlide, nextSlide) {
            var elm = $(this).find('.slick-slide[data-slick-index="' + nextSlide + '"]');
            $(elm).find('img').trigger('click');
          });
      });
      $(document).on('click', '.merimag-promo-bar-close', function() {
          $(this).parents('.merimag-promo-bar').slideUp();
          return false;
      });
     
      $(document).on('added_to_cart removed_from_cart updated_cart_totals updated_wc_div ', function() {
        var elm = $('.merimag-cart-count');
        $.ajax({
          type: 'POST',
          url: merimag_theme.ajax,
          data:{ nonce: merimag_theme.nonce, action: 'merimag_get_cart_count' },
          success: function(data) {
            var count = parseInt(data);
            $(elm).text(count);
          },

        });
      });
      
    },
    get_bgcolor: function(obj) {
        var real = obj.css('background-color');
        var t = real.split('(');
        if ( t[0] === 'rgba') {
            return obj.parents().filter(function() {
               var bg = $(this).css('background-color');
                var h = bg.split('(');
                return h[0] !== 'rgba'
            }).first().css('background-color');
        } else {
            return real
        }
    },

    get_offset: function() {
      if( $('body').hasClass('logged-in') === true ) {
        var fixed = $('#wpadminbar').css('position');
        return fixed === 'fixed' ? $('#wpadminbar').outerHeight() : 0;
      }
      return 0;
    },
    init_elements: function() {
      $.initialize('.comment-reply-title', function() {
        var elem = $(this);
        $(this).find('.block-title small').appendTo($(elem));
      });
      $.initialize('#merimag-toc', function() {
          $(this).toc({content: ".merimag-article-content", headings: "h2:not(.block-infos-title),h3:not(.block-infos-title),h4:not(.block-infos-title),h5:not(.block-infos-title),h6:not(.block-infos-title)"});
      });

      $(document).on('click', '.merimag-toc-toggle', function() {
          $('#merimag-toc').slideToggle();
          return false;
      });
      function isAnyPartOfElementInViewport(el) {

          const rect = el.getBoundingClientRect();
          // DOMRect { x: 8, y: 8, width: 100, height: 100, top: 8, right: 108, bottom: 108, left: 8 }
          const windowHeight = (window.innerHeight || document.documentElement.clientHeight);
          const windowWidth = (window.innerWidth || document.documentElement.clientWidth);

          // http://stackoverflow.com/questions/325933/determine-whether-two-date-ranges-overlap
          const vertInView = ((rect.top  + (rect.height/5)) <= windowHeight ) && ((rect.top + rect.height) >= 0);
          const horInView = (rect.left <= windowWidth) && ((rect.left + rect.width) >= 0);

          return (vertInView && horInView);
      }

      $(window).scroll(function() {
        if($('.merimag-article-content').length > 0 ) {
          var isInView = isAnyPartOfElementInViewport($('.merimag-article-content')[0]);
          if( isInView ) {
            $('.merimag-toc-container').show();
          } else {
            $('.merimag-toc-container').hide();
          }
        }
      })
      $("body").one("inview", '.merimag-article-content', function(event, isInView) {
        if( isInView ) {
          $('.merimag-toc-container').show();
        } else {
          $('.merimag-toc-container').hide();
        }
      });
      $.initialize('.merimag-sidebar', function() {
        var id = $(this).attr('id');
        $('.merimag-sidebar').appendTo('body');
        $(this).children('merimag-sidebar-content').append('')
        $('body').append('<div data-id="' +  id + '" class="merimag-sidebar-content"></div>');
         $('.merimag-sidebar-content[data-id="' + id + '"]').append($(this).html());
         $(this).html('');
        $('.merimag-sidebar-content[data-id="' + id + '"]').appendTo( $(this));
        var elm = $(this);
        var position = $(this).data('position') && $(this).data('position') === 'right' ? $(this).data('position') : 'left';
        if( $('body').hasClass('rtl') === true ) {
          position = position === 'right' ? 'left' : 'right';
        }
        merimag_options_UI.sidebars[id] = $('#' + id).slideReveal({
          trigger: $('.merimag-sidebar-opener[data-id=' + id + ']'),
          position: position,
          overlay: true,
          width:400,
          push: false,
          zIndex:999999,
          show: function() {
            $(elm).show();
          }
        });
        $(this).mCustomScrollbar({
          theme:"dark"
        });
        
      });
      $(document).on('click', '.merimag-sidebar-opener', function() {
        return false;
      })
      $(document).on('click', '.merimag-sidebar-close', function() {
        var id = $(this).data('id');
        merimag_options_UI.sidebars[id].slideReveal("hide");
        return false;
      })

      

      var window_width = $(window).width();
      $(document).on('click', '.widget_nav_menu li.menu-item-has-children > a', function() {
        $(this).parent().children('.sub-menu').slideToggle();
        $(this).parent().toggleClass('sub-menu-opened');
        return false;
      });
      $(document).on('click', '.merimag-mobile-sidebar-menu li.menu-item-has-children > a', function() {
        $(this).parent().children('.sub-menu, .mega-menu').slideToggle();
        $(this).parent().toggleClass('sub-menu-opened');
        return false;
      });
     
      // sticky sidebar
      sticky_sidebar();
      function sticky_sidebar() {
          var bottomSpacing = parseInt( $('html').css('margin-top') ) + 30;
      }
      $(window).resize(function() {
        $('.merimag-mobile-menu-sidebar').removeClass('reveal');
        $('body').removeClass('hidden-overflow');
        $('.merimag-mobile-search-form').hide();
        $('.merimag-mobile-menu-search').find('i').removeClass('icofont-close-line');
        $('.merimag-mobile-menu-search').find('i').addClass('fa fa-search ');
      });
      // sticky header
      $(document).ready(function() {
        if( $('body').hasClass('merimag-sticky-header-desktop') === true ) {
          var position = $(window).scrollTop();
          var header_container_position = $('.merimag-site-header').outerHeight();
          $(window).resize(function() {
            hide_sticky_header();
          });
          $(window).scroll(function() {
              var scroll = $(window).scrollTop();
              if( scroll <= position && scroll >= header_container_position ) {
                show_sticky_header()
              } else {
                hide_sticky_header();
              }
              position = scroll;
          });
          function hide_sticky_header() {
            $('.merimag-sticky-header').removeClass('animated slideInDown faster').addClass('animated slideOutUp faster');
            $('.sticky-header-menu').children().appendTo('.main-menu-dynamic');
          }
          function show_sticky_header() {
            $('.main-menu-dynamic').children().appendTo('.sticky-header-menu');
            $('.merimag-sticky-header').css('top', merimag_options_UI.get_offset()).removeClass('animated slideOutUp faster').addClass('animated slideInDown faster').show();
          }
        }
      })

      $(document).ready(function(){
        var direction = $('body').hasClass('rtl') === true ? 'right' : 'left';
        $('.merimag-footer-tags-inline .merimag-footer-tags-list ').marquee({
            duration: 30000,
            delayBeforeStart: 2000,
            startVisible: true,
            duplicated: true,
            gap: 0,
            pauseOnHover:true,
            direction: direction,
        });
      });
      $(window).load(function() {
        $.initialize( '[data-video-background]', function() {
          var video_id = $(this).data('video-background');
          $(this).YTPlayer({
              fitToBackground: true,
              videoId: video_id,
              playerVars: {
                modestbranding: 1,
                autoplay: 1,
                controls: 0,
                showinfo: 0,
                branding: 0,
                rel: 0,
                autohide: 1,
                start: 0
              }
          });
        });
      });
      $.initialize('.merimag-fitvids', function() {
        $(this).fitVids();
      });
      $(window).load(function() {
        merimag_options_UI.load_images( $('.merimag-marquee') );
      })
      $.initialize('.merimag-marquee', function() {
        var elm = $(this);
        
        $(elm).marquee({
          duration: 20000,
          delayBeforeStart: 1000,
          startVisible: true,
          duplicated: true,
          gap: 0,
          pauseOnHover: true,
        })
        
      });
      $.initialize('.gallery-item figcaption', function() {
        $(this).attr('title', $(this).text());
      })
      $(window).load(function() {
        $('.price_slider_wrapper').show();
      })
     
      $(document).on("mouseenter", ".menu-item-has-children:not(.click-event)", function(t) {
          var a = $(this);
          if( $(a).parents('.elementor-widget-wrap').length > 0 ) {
            return;
          }
          a.siblings("li:not(.click-event)").stop().clearQueue().removeClass("merimag-enter").addClass("merimag-leave");
          a.stop().clearQueue().removeClass("merimag-leave").addClass("merimag-enter");
          var e_right_offset = ($(window).width() - ($(this).offset().left + $(this).outerWidth()));
          var e_left_offset = $(this).offset().left;
          var sub_menu_width = $(this).children('.sub-menu').outerWidth();
          var in_sub_menu = $(this).parent('.sub-menu').length  > 0 ? true : false;
          if( $(this).hasClass('menu-item-has-mega-menu') === false && $(this).parents('.menu-item-has-mega-menu').length === 0 ) {
            if( e_right_offset < sub_menu_width && e_left_offset > sub_menu_width ) {
              $(this).addClass('right-side-sub-menu');
            } else {
              $(this).removeClass('right-side-sub-menu');
            }
            if( e_left_offset < sub_menu_width && e_right_offset > sub_menu_width ) {
               $(this).addClass('left-side-sub-menu');
            } else {
              $(this).removeClass('left-side-sub-menu');
            }
            if( e_left_offset < sub_menu_width && e_right_offset < sub_menu_width ) {
               $(this).addClass('mobile-sub-menu');
            } else {
              $(this).removeClass('mobile-sub-menu');
            }
          }
          
      }).on("mouseleave", ".menu-item-has-children:not(.click-event)", function() {
          $(this).stop().delay(10).queue(function(t) {
              $(this).removeClass("merimag-enter");
          }).delay(10).queue(function(t) {
              $(this).addClass("merimag-leave");
          });
      });
      $(document).on('click',".menu-item-has-children.click-event > a", function() {
        var parent = $(this).parent();
        var e_right_offset = ($(window).width() - ($(parent).offset().left + $(parent).outerWidth()));
          var e_left_offset = $(parent).offset().left;
          var sub_menu_width = $(parent).children('.sub-menu').outerWidth();
          var in_sub_menu = $(parent).parent('.sub-menu').length  > 0 ? true : false;
          if( $(this).parents('.menu-item-has-mega-menu').length === 0 ) {
            if( e_right_offset < sub_menu_width && e_left_offset > sub_menu_width ) {
              $(parent).addClass('right-side-sub-menu');
            } else {
              $(parent).removeClass('right-side-sub-menu');
            }
            if( e_left_offset < sub_menu_width && e_right_offset > sub_menu_width ) {
               $(parent).addClass('left-side-sub-menu');
            } else {
              $(parent).removeClass('left-side-sub-menu');
            }
            if( e_left_offset < sub_menu_width && e_right_offset < sub_menu_width ) {
               $(parent).addClass('mobile-sub-menu');
            } else {
              $(parent).removeClass('mobile-sub-menu');
            }
          }
          $(parent).toggleClass('merimag-enter').toggleClass('merimag-leave').toggleClass('active-menu-item');
          return false;
         
      });
    }
  };
        
 
     merimag_options_UI.init();
})(jQuery);

