(function($){

  // instantiate other JS
  new TZ();

  // add redraw plugin for use on css transitions of ajax loaded content
  $.fn.redraw = function(){
    return $(this).each(function(){
      this.clientHeight;
    });
  };

  $(function() {

    var Core = {

      initialized: false,
      vh: $(window).height(),
      vw: $(window).width(),

      initialize: function () {
        // Bail if already initialized
        if (this.initialized) { return; }
        this.initialized = true;

        this.build();
        this.events();
      },

      build: function () {
        this.domManipulation();

        this.initSlideshows();

        this.portfolioLoadMore();

        this.coverPage();

        this.portfolioFilterToggle();

        this.portfolioIsotope();
      },

      events: function () {
        var self = this;

        $(window).resize(function () {
          self.vh = $(window).height();
          self.vw = $(window).width();
        });

        $('.gallery-tile').addClass('reveal');

        // Portfolio permalink navigation
        $(document).on('click', '.permalink-navigation .next', function () {
          var currentArticle = $(this).closest("article"),
              nextArticle = currentArticle.next();

          if (nextArticle.length) {
            nextArticle.fadeIn(275);
            currentArticle.hide();
          }
        });

        $(document).on('click', '.permalink-navigation .prev', function () {
          var currentArticle = $(this).closest("article"),
              prevArticle = currentArticle.prev();

          if (prevArticle.length) {
            prevArticle.fadeIn(275);
            currentArticle.hide();
          }
        });

        // Filter toggle
        $('.filter-toggle').on('click', function () {
          var $filters = $(this).parent('.portfolio-filters'),
              filterHeight = $filters.outerHeight();

          $filters.toggleClass('show');

          if ($filters.hasClass('show')) {
            $('#gallery').css('padding-top', filterHeight);
          } else {
            if ($('.gapless-portfolio').length) {
              $('#gallery').css('padding-top', 0);
            } else {
              $('#gallery').css('padding-top', '50px');
            }
          }
        });
      },

      domManipulation: function () {
        //bump featured items up to the top
        $('#gallery .featured').prependTo($('#gallery .gallery-inner'));

        $('#cover-content').prependTo('body');

        $('.comment-form label[for="comment"]').append(' <span class="required">*</span>');
      },

      scrollToContent: function (target, speed) {
        target = (target === '#') ? $('body') : $(target);

        speed = typeof speed !== 'undefined' ?  speed : 'slow';

        $('html, body').animate({
          scrollTop: target.offset().top
        }, speed);
      },

      initSlideshows: function () {
        if ( $.fn.cycle ) {
          var $gallery = $('.zilla-gallery.slideshow');

          $gallery.each(function() {
            var $this = $(this),
                timeout = $this.data('gallery-timeout'),
                fx = $this.data('gallery-fx');

            $this.cycle({
              autoHeight: 'calc',
              slides: '> figure',
              swipe: true,
              next:  $(this).find('.cycle-next'),
              prev:  $(this).find('.cycle-prev'),
              timeout: timeout,
              fx: fx,
              loader: 'wait',
              log: false
            });

            $(document.documentElement).keyup(function (e) {
              if (e.keyCode === 37) {
                $this.cycle('prev');
              } else if (e.keyCode === 39) {
                $this.cycle('next');
              }
            });
          });
        }
      },

      portfolioLoadMore: function () {
        $(document).on('click', '.btn-load-more', function () {
          var $button = $(this),
              pageNumber = $button.data('nextpage');

          $button.html('<i class="spinner-ring" />');

          /*
          * Fetch the next set of posts using
          * frame_portfolio_load_more() and append them
          * to the recent section
          */
          $.ajax({
            url: zillaFrame.ajaxurl,
            type: 'post',
            data: {
              action: 'frame_portfolio_grid_load_more',
              page: pageNumber
            },
            success: function(html) {
              var $html = $(html),
                  $gridItems = $html.filter('.grid-items').html(),
                  $permalinkItems = $html.filter('.permalink-items').html();

              $button.parent().remove();

              // Append the new items to the container
              $('.gallery-inner')
                .append($gridItems)
                .find('.type-portfolio')
                .filter(function() {
                   return $(this).css('position') == 'static';
                })
                .addClass('new')
                .find('.gallery-tile')
                .addClass('reveal');

                // Relayout the isotope grid with the new items
                if( $.fn.isotope ) {
                  var $container = $('.gallery-inner').imagesLoaded( function() {
                    var $newElems = $('.new');
                    $container.isotope('insert', $newElems);
                    $newElems.removeClass('new');
                  });
                }

              // Append new items to the permalink slides
              $('#permalinks').append($permalinkItems);
            }
          });
        });
      },

      portfolioFilterToggle: function () {
        if ($('.page-template-portfolio').length) {
          var controller = new ScrollMagic.Controller();

          var filterScene = new ScrollMagic.Scene({
                triggerHook: 'onLeave',
                triggerElement: '#gallery'
              })
              .setPin('.portfolio-filters', {spacerClass: 'filter-spacer'})
              .setClassToggle('.portfolio-filters', 'pinned')
              .addTo(controller);
        }
      },

      portfolioIsotope: function () {
        if( $.fn.isotope ) {
          // Init isotope
          var $container = $('.gallery-inner').imagesLoaded( function() {
            $container.isotope({
              // options
              itemSelector: '.gallery-inner .type-portfolio',
              transitionDuration: '0.6s',
              percentPosition: true,
              masonry: {
                columnWidth: '.type-portfolio:not(.featured)'
              },
              hiddenStyle: {
                opacity: 0
              },
              visibleStyle: {
                opacity: 1
              }
            }).isotope(); // attempt to fix the "gap" caused by % width layout
          });

          var $checkboxes = $('.filter-terms-list input');
          $checkboxes.change(function () {
            var filters = [];
            // get checked checkbox values
            $(this).filter(':checked').each(function(){
              filters.push(this.value);
            });

            // Convert the array of vals to a string
            filters = filters.join(', ');

            // Apply filters
            $container.isotope({ filter: filters });
          });

          $(document).on('click', '.gallery-tile', function () {
            $container.isotope('layout');
          });
        }
      },

      coverPage: function () {
        var has_scroll = $('.page-template-page-cover #main').attr('data-number-posts') != 0 ? true : false;
        // Check if we're on the cover page, not touch and displaying featured projects
        if ($('.page-template-page-cover').length && ! $('.touch').length && has_scroll) {
          var self = this,
              controller = new ScrollMagic.Controller(),
              timeline = new TimelineMax(),
              Tween = {
                sidebar: TweenMax.fromTo('.site-header', 1,
                    {width: '100%'},
                    {width: '22%'}
                  ),
                sidebarContent: TweenMax.fromTo('.site-header-inner', 1,
                    {opacity: 0},
                    {opacity: 1}
                  ),
                content: TweenMax.fromTo('.site-content', 1,
                    {x: '100%'},
                    {x: '0%'}
                  ),
                coverContent: TweenMax.fromTo('#cover-content header, .btn-scroll', 0.33,
                    {opacity: 1},
                    {opacity: 0}
                  ),
              };

          // Add tweens to the timeline
          timeline.add([Tween.sidebar, Tween.content, Tween.coverContent, Tween.sidebarContent]);

          // Set up the scene
          var navScene = new ScrollMagic.Scene({
                triggerHook: 'onLeave',
                duration: '100%'
              })
              .setTween(timeline)
              .setPin('#page')
              .addTo(controller);

          // custom scene scroll
          controller.scrollTo(function (newScrollPos, speed) {
            $('html, body').animate({
              scrollTop: newScrollPos
            }, speed);
          });

          $('.btn-scroll').on('click', function (e) {
            e.preventDefault();
            controller.scrollTo(self.vh, 1600);
          });
        }

        $('#page, .cover-content').addClass('reveal');
      }

    };



  Core.initialize();

  });

})(jQuery);
