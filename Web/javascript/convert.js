function loadDoc() {
  var idMidi = 'MIDI-';
  let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  let charactersLength = characters.length;

  for (let i = 0; i < 7; i++) {
      idMidi += characters[Math.floor(Math.random() * charactersLength)];
  }

  var input = document.getElementsByName("youtube-link")[0].value;

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      $('.convert-content').append('<div class="animation-overlay"></div>');
      $('.container-fluid').addClass("p-0");
      $('.container-fluid').css('pointer-events', 'none');
      $('#animation').removeClass('hidden');
      checkJobStatus(idMidi);
    }
  };
  var url = "createJob.php?youtube-link=" + encodeURIComponent(input)+"&id-midi="+encodeURIComponent(idMidi);
  xhttp.open("GET", url, true);
  xhttp.send();
}

function checkJobStatus(idMidi) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == "gg") {
        $('.animation-overlay').remove();
        $('.container-fluid').removeClass("p-0");
        $('.container-fluid').css('pointer-events', 'auto');
        $('#animation').addClass('hidden');

        $('.contain').addClass('hidden');
        $('.image-form').removeClass('hidden');

        $('.image').addClass('hidden');
        $('.convert-result').removeClass('hidden');
        $("#idMidi").val(idMidi);
        console.log(idMidi);
        return; // Arrête la fonction
      } else if (this.responseText == "not yet") {
        checkJobStatus(idMidi);
      }

    }
  };
  xhttp.open("GET", "checkJob.php", true);
  xhttp.send();
}

$(document).ready(function() {
  $('#choose-file').on('change', function() {
    $('#file-name').text($(this).val().split('\\').pop());
  });

  $('.launch-load').on('click', function() {
    $('.convert-content').append('<div class="animation-overlay"></div>');
    $('.container-fluid').addClass("p-0");
    $('.container-fluid').css('pointer-events', 'none');
    $('#animation').removeClass('hidden');

    setTimeout(function() {
      $('.animation-overlay').remove();
      $('.container-fluid').removeClass("p-0");
      $('.container-fluid').css('pointer-events', 'auto');
      $('#animation').addClass('hidden');

      $('.contain').addClass('hidden');
      $('.image-form').removeClass('hidden');

      $('.image').addClass('hidden');
      $('.convert-result').removeClass('hidden');
    }, 5000); // 10 secondes
  });

  $("#download-button").on("click", function() {
    var downloadLink = document.createElement("a");
    downloadLink.href = "midi/midi.mid";
    downloadLink.download = "golden.mid";
    $(downloadLink).get(0).click();
  });

});
