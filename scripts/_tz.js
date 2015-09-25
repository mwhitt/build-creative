(function() {
  window.TZ = (function() {
    var $, NAVIGATION_FADE_DURATION;

    $ = jQuery;

    $( document ).ready(function() {
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
          var viewportHeight = $(window).height();
          if (viewportHeight > 500) {
            $(".site-header").css('height', viewportHeight + "px");
          } else {
            $(".site-header").css('height', 500 + "px");
          };
        }
    });

    function TZ() {
      this.$header = $("header");
      this.$navigation = $(".primary-navigation");
      this.$navToggle = $(".navigation-toggle");
      this.bindEvents();
      this.setupNavigation();
      this.setupPortfolio();
      this.setupSharing();
    }

    TZ.prototype.onResize = function() {
      var articleWidth, maxArticleWidth, portfolioRightOverflow;
      articleWidth = $("article").width();
      maxArticleWidth = 700;
      portfolioRightOverflow = 5;
      $(".full-image").css({
        width: articleWidth + portfolioRightOverflow,
        marginLeft: -(articleWidth - maxArticleWidth) / 2
      });
      this.fixedHeader();
      return this.resizeVideos();
    };

    TZ.prototype.onToggleNavButtonClick = function() {
      if (this.$navToggle.hasClass('active')) {
        return this._closeNavigation();
      } else {
        return this._openNavigation();
      }
    };

    NAVIGATION_FADE_DURATION = 125;

    TZ.prototype._closeNavigation = function(animationDuration) {
      if (animationDuration == null) {
        animationDuration = NAVIGATION_FADE_DURATION;
      }
      this.$navToggle.toggleClass("active", false);
      return this.$navigation.animate({
        opacity: 0
      }, {
        duration: animationDuration,
        complete: (function(_this) {
          return function() {
            _this.$navigation.toggleClass("active", false);
            return $("#page").css({
              height: "",
              overflow: ""
            });
          };
        })(this)
      });
    };

    TZ.prototype._openNavigation = function() {
      var headerHeight;
      this.$navToggle.toggleClass("active", true);
      this.$navigation.toggleClass("active", true);
      headerHeight = this.$header.outerHeight();
      this.$navigation.css({
        opacity: 0.5,
        top: headerHeight,
        "min-height": $(window).height() - headerHeight
      }).animate({
        opacity: 1
      }, NAVIGATION_FADE_DURATION);
      return $("#page").css({
        height: headerHeight + this.$navigation.outerHeight(),
        overflow: "hidden"
      });
    };

    TZ.prototype.bindEvents = function() {
      var galleryContainer;
      $(window).resize((function(_this) {
        return function() {
          return _this.onResize();
        };
      })(this)).trigger("resize");
      $(document.body).on("post-load", (function(_this) {
        return function(e) {
          _this.setupSharing();
          return $(".navigation").remove();
        };
      })(this));
      $("#main").on('click', '.share-button', function() {
        $(this).siblings(".like-button.active, .likes-wrap.active").removeClass('active');
        $(this).toggleClass('active');
        return $(this).siblings(".share-wrap").toggleClass('active');
      });
      $("#main").on('click', '.like-button', function() {
        $(this).siblings(".share-button.active, .share-wrap.active").removeClass('active');
        $(this).toggleClass('active');
        return $(this).siblings(".likes-wrap").toggleClass('active');
      });
      this.$navToggle.click((function(_this) {
        return function() {
          return _this.onToggleNavButtonClick();
        };
      })(this));
      galleryContainer = $("#gallery");
      if (!galleryContainer.hasClass('direct-link')) {
        return $(document).on("click", ".type-portfolio .dimmer", (function(_this) {
          return function(event) {
            var post_class;
            event.preventDefault();
            post_class = $(event.target).closest(".type-portfolio").attr("class").match(/post\-.+?\b/)[0];
            $("#gallery, #permalinks").addClass("active");
            $("#permalinks article").hide();
            $("html, body").scrollTop($("#content").offset().top);
            if (!$('html').hasClass('touch')) {
              $("#permalinks ." + post_class).css({
                display: "inline-block",
                'margin-top': -50,
                opacity: 0.5
              }).animate({
                opacity: 1,
                marginTop: 0
              }, 275);
              $(window).trigger("resize");
            } else {
              $("#permalinks ." + post_class).css({
                display: "inline-block"
              });
            }
            return _this.resizeVideos($("#permalinks ." + post_class));
          };
        })(this));
      }
    };

    TZ.prototype.setupPortfolio = function() {
      var articleWidth, maxArticleWidth, portfolioRightOverflow;
      articleWidth = $("article").width();
      maxArticleWidth = 700;
      portfolioRightOverflow = 5;
      $(".full-image").css({
        width: articleWidth + portfolioRightOverflow,
        marginLeft: -(articleWidth - maxArticleWidth) / 2
      });
      $('article.portfolio-post').each((function(_this) {
        return function(i, el) {
          var imageData, imageURL, postContent, postHeader, video;
          postHeader = $(el).find('.post-header');
          postContent = $(el).find('.post-content');
          if ($(el).hasClass('use-video')) {
            video = postContent.find('iframe').first() || postContent.find('embed').first() || postContent.find('object').first();
            if (video) {
              video.remove();
              postHeader.find('.post-title').after($('<div class="featured-video" />').html(video));
              return _this.resizeVideos($(el));
            }
          } else {
            imageData = postHeader.find('a[data-featured-image]');
            imageURL = imageData.data("featured-image");
            if (imageURL) {
              return imageData.after("<img class='featured-image' src=\"" + imageURL + "\"/>");
            }
          }
        };
      })(this));
      return $("#gallery").before($("#permalinks"));
    };

    TZ.prototype.resizeVideos = function(content) {
      var videos;
      videos = content ? content.find('iframe, embed, object') : $('article').find('iframe, embed, object');
      return videos.each(function() {
        var $video, aspect, height, maxWidth, width;
        $video = $(this).css({
          width: "",
          height: ""
        });
        width = $video.attr("width") || $video.width();
        height = $video.attr("height") || $video.height();
        maxWidth = $video.parent().innerWidth();
        aspect = width / height;
        return $video.css({
          width: maxWidth,
          height: maxWidth / aspect
        });
      });
    };

    TZ.prototype.setupSharing = function() {
      return $('article:not(.processed)').each(function() {
        var article;
        article = $(this);
        if ((article.find(".share-wrap .sharedaddy").length)) {
          article.find(".share-button").css("display", "inline-block");
        }
        if ((article.find(".likes-wrap div").length)) {
          article.find(".like-button").css("display", "inline-block");
        }
        return article.addClass('processed');
      });
    };

    TZ.prototype.fixedHeader = function() {
      var contactBox, contactEl, dimmerHeight, navigationBox, overlap, windowHeight;
      windowHeight = $(window).height();
      dimmerHeight = $('.site-header-dimmer').outerHeight();
      if ($('.site-header').hasClass('no-sticky')) {
        if (dimmerHeight < windowHeight) {
          return $('.site-header').removeClass('no-sticky');
        }
      } else {
        navigationBox = this.$navigation[0].getBoundingClientRect();
        contactEl = $('.contact');
        if (contactEl.length) {
          contactBox = contactEl[0].getBoundingClientRect();
          overlap = !(navigationBox.right < contactBox.left || navigationBox.left > contactBox.right || navigationBox.bottom < contactBox.top || navigationBox.top > contactBox.bottom);
          if (overlap) {
            return $('.site-header').addClass('no-sticky');
          }
        }
      }
    };

    TZ.prototype.setupNavigation = function() {
      this.$navigation.find("li").each(function() {
        var listItem;
        listItem = $(this);
        if (listItem.children().length === 2) {
          return listItem.children("a").addClass("menu-label");
        }
      });
      $(".primary-navigation ul ul").each(function() {
        return $(this).wrap("<div class=\"sub-menu-wrap\" />").prepend("<div class=\"sub-menu-border\" />");
      });
      return this.fixedHeader();
    };

    return TZ;

  })();


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


;

}).call(this);
