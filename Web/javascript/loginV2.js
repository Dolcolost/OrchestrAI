jQuery($ => {
  var $loginSection = $('.login-section');
  var $signupSection = $('.signup-section');

  $body.on('click', '.js-menu-toggle', function(e) {
      e.preventDefault();
      $body.toggleClass('offcanvas-menu');
  });

  function toggleOffCanvasClass(className, $sectionToToggle, $sectionToHide) {
    if ($body.hasClass(className)) {
        if ($sectionToHide.is(':visible')) {
          $sectionToHide.hide();
          $sectionToToggle.show();
        } else {
          $body.removeClass();
          $sectionToToggle.hide();
        }
    } else {
      $body.addClass(className);
      $sectionToToggle.show();
    }
  }

  $body.on('click', '.connect-button', function(e) {
      e.preventDefault();
      toggleOffCanvasClass('offcanvas-popup', $loginSection, $signupSection);
  });

  $body.on('click', '.signup-button', function(e) {
      e.preventDefault();
      toggleOffCanvasClass('offcanvas-popup', $signupSection, $loginSection);
  });

  $(document).on('mouseup', function(e) {
      if (!$loginSection.is(e.target) && $loginSection.has(e.target).length === 0 && !$signupSection.is(e.target) && $signupSection.has(e.target).length === 0) {
        if ($body.hasClass('offcanvas-popup')) {
            $body.removeClass();
            $loginSection.hide();
            $signupSection.hide();
        }
      }
  });

  $('#password-signup').on('keyup', function() {

    var passwordValue = $(this).val();
    var passwordConfirmValue = $('#password-signup-confirm').val();
    var hasLowercase = /[a-z]/.test(passwordValue);
    var hasUppercase = /[A-Z]/.test(passwordValue);
    var hasNumber = /[0-9]/.test(passwordValue);

    if(passwordValue.length >= 12){$('#lengthFa').removeClass('fa-xmark').addClass('fa-check');}
    else{$('#lengthFa').removeClass('fa-check').addClass('fa-xmark');}

    if(hasLowercase){$('#letterFa').removeClass('fa-xmark').addClass('fa-check');}
    else{$('#letterFa').removeClass('fa-check').addClass('fa-xmark');}

    if(hasUppercase){$('#capitalFa').removeClass('fa-xmark').addClass('fa-check');}
    else{$('#capitalFa').removeClass('fa-check').addClass('fa-xmark');}

    if(hasNumber){$('#numberFa').removeClass('fa-xmark').addClass('fa-check');}
    else{$('#numberFa').removeClass('fa-check').addClass('fa-xmark');}

    if(passwordValue === passwordConfirmValue){$('#same').removeClass('fa-xmark').addClass('fa-check');}
    else{$('#same').removeClass('fa-check').addClass('fa-xmark');}
  });

  $('#password-signup-confirm').on('keyup', function() {
    var passwordValue = $(this).val();
    var passwordConfirmValue = $('#password-signup').val();

    if(passwordValue === passwordConfirmValue){$('#same').removeClass('fa-xmark').addClass('fa-check');}
    else{$('#same').removeClass('fa-check').addClass('fa-xmark');}
  });


  $('.showPsw').on('click', function() {
    var pswInput = $(this).prevAll('input').first();

    if (pswInput.attr('type') === 'password') {
      pswInput.attr('type', 'text');
      $(this).removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
      pswInput.attr('type', 'password');
      $(this).removeClass('fa-eye-slash').addClass('fa-eye');
    }
  });

  $body.on('click', '#restriction', function(e) {
    if ($('.restrictions').hasClass('d-none')) {
      $('.restrictions').removeClass('d-none').addClass('d-grid')
      $('.no-restrictions').addClass('d-none')
    }else {
      $('.restrictions').addClass('d-none').removeClass('d-grid')
      $('.no-restrictions').removeClass('d-none')
    }
  });

  $('#pseudo-signup').on('keyup', function() {
    var pseudo = $(this).val();
    if (pseudo.length > 3) {$('#img-pseudo-signup').attr("src", "https://skins.nationsglory.fr/face/"+pseudo+"/3");
    }else {$('#img-pseudo-signup').attr("src", "https://skins.nationsglory.fr/face/player/3");}
  });

  $('#pseudo-connect').on('keyup', function() {
    var pseudo = $(this).val();
    if (pseudo.length > 3) {$('#img-pseudo-connect').attr("src", "https://skins.nationsglory.fr/face/"+pseudo+"/3");
    }else {$('#img-pseudo-connect').attr("src", "https://skins.nationsglory.fr/face/player/3");}
  });

});
