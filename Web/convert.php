<?php
  include("headerOrchestrAI.php");
?>
<link href="style/convertOrchestAI.css" rel="stylesheet">
<div class="container-fluid">
  <div class="page-holder">

      <section class="convert-section sign-in px-3">
        <div class="container-fluid">
            <div class="convert-content py-4 d-flex">
              <div class="convert-form my-auto">
                <div class="contain">
                  <h2 class="form-title">Conversion de la musique</h2>

                  <div class="category">
                    <h4 class="">Fournissez la musique</h4>
                  	<label class="m-2 btn-accueil" id="file-name" for="choose-file">
                      Choisir votre fichier MP3
                  	</label>
                    <input type="file" id="choose-file" class="input-ytb" accept="audio/mp3">
                    <h5> ou </h5>

                    <div class="input-group m-2">
                        <label for="youtube-link" style="top:13%"><i class="fa-brands fa-youtube fa-shake fa-2xl" style="color: #cd2e23;"></i></label>
                        <input type="text" name="youtube-link" placeholder="Lien youtube"/>
                    </div>

                  </div>
                  <div class="sub-category mt-3" style="margin-inline:25%">
                    <button type="button" name="bibite" class="btn-accueil" onclick="loadDoc()"> Démarrer </button>
                  </div>
                </div>

                <div class="image-form hidden">
                  <figure><img id="img-catégorie" src="images/orchestAI-accueil.png" class="img-fluid" alt=""></figure>
                </div>

              </div>
              <div class="convert-image text-center my-auto">
                  <div class="image">
                    <figure><img id="img-catégorie" src="images/orchestAI-accueil.png" class="img-fluid" alt=""></figure>
                    <a href="#" class="">Voir vos conversion</a>
                  </div>
                  <div class="convert-result py-4 hidden">
                    <div class="convert-result-content my-auto">
                      <h2 class="form-title">Conversion réussie !!</h2>
                      <midi-player
                        src="midi/midi.mid"
                        sound-font visualizer="#myPianoRollVisualizer">
                      </midi-player>
                      <midi-visualizer type="staff" id="myPianoRollVisualizer"
                        src="https://cdn.jsdelivr.net/gh/cifkao/html-midi-player@2b12128/twinkle_twinkle.mid"
                        style="overflow: hidden;max-width: 30em;background: wheat;margin: auto;">
                      </midi-visualizer>
                      <?php include('notations.php');?>
                      <div class="sub-category mt-3" style="margin-inline:25%">
                        <form action="downloadBlob.php" method="post">
                          <input type="text" name="idMidi" id="idMidi" style="display:none;"/>
                          <button type="submit" name="download" class="btn-accueil"> Télécharger <i class="ms-2 fa-solid fa-download fa-xl fa-bounce"></i> </button>
                        </form>
                      </div>

                    </div>
                  </div>
              </div>

          </div>

          <div id="animation" class="hidden">
            <?php include('animationConvert.php');?>
          </div>
        </div>
      </section>

  </div>
</div>
<script type="text/javascript" src="javascript/convert.js"></script>
<script type="text/javascript" src="javascript/test.js"></script>
</main>
<?php include('footerOrchestrAI.php');?>
</body>
</html>
