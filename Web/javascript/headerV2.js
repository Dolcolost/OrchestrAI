var $body = $('body');

$(function() {

  var siteSticky = function() {
		$(".js-sticky-header").sticky({topSpacing:0});
	};
	siteSticky();

	var siteMenuClone = function() {

		$('.js-clone-nav').each(function() {
			var $this = $(this);
			$this.clone().attr('class', 'site-nav-wrap').appendTo('.site-mobile-menu-body');
		});


		setTimeout(function() {

			var counter = 0;
      $('.site-mobile-menu .has-children').each(function(){
        var $this = $(this);

        $this.prepend('<span class="arrow-collapse collapsed">');

        $this.find('.arrow-collapse').attr({
          'data-toggle' : 'collapse',
          'data-target' : '#collapseItem' + counter,
        });

        $this.find('> ul').attr({
          'class' : 'collapse',
          'id' : 'collapseItem' + counter,
        });

        counter++;

      });

    }, 1000);

		$body.on('click', '.arrow-collapse', function(e) {
      var $this = $(this);
      if ( $this.closest('li').find('.collapse').hasClass('show') ) {
        $this.addClass('collapsed');
        $this.closest('li').find('.collapse').removeClass('show');
      } else {
        $this.removeClass('collapsed');
        $this.closest('li').find('.collapse').addClass('show');
      }
      e.preventDefault();

    });

		$(window).resize(function() {
			var $this = $(this),
				w = $this.width();

			if ( w > 767 ) {
				if ( $body.hasClass('offcanvas-menu') ) {
					$body.removeClass('offcanvas-menu');
				}
			}
		})

    var $offCanvasContainer = $('.site-mobile-menu');

    $(document).on('mouseup', function(e) {
        if (!$offCanvasContainer.is(e.target) && $offCanvasContainer.has(e.target).length === 0 ) {
            if ($body.hasClass('offcanvas-menu')) {
                $body.removeClass();
            }
        }
    });

	};
	siteMenuClone();

  $(document).ready(function() {
    $('#profil-dropdown-link').click(function(event) {
      event.stopPropagation(); // empêche la propagation de l'événement de clic pour éviter que l'événement de clic ne se propage à d'autres éléments
      $('#dropdown-menu-profile').toggleClass('show');
    });

    $(document).click(function(event) {
      if (!$(event.target).closest('#dropdown-menu-profile').length && $('#dropdown-menu-profile').hasClass('show')) {
        $('#dropdown-menu-profile').removeClass('show');
      }
    });
  });


});
