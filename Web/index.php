<?php
  include("headerOrchestrAI.php");
?>

<div class="page-holder">
  <div class="container-fluid">
    <div class="row mt-3">
      <div class="col-md-6 col-sm-12 d-flex align-items-center" style="font-family:Montserrat">
        <div class="container my-auto pad-left">
          <p class="h1">
            <strong>Une conversion ? Besoin d'une partition ?</strong>
          </p>
          <p class="fs-4">
            <text>Ce site a pour but de convertir des vidéos Youtube ou des fichiers MP3 en parition MIDI.</text>
          </p>
          <?php if(!isset($_SESSION['id'])) { ?>
            <button class="btn-accueil connect-button"> Connexion </button>
            <button class="ms-3 btn-accueil signup-button"> inscription </button>
          <?php } ?>
        </div>
      </div>
      <div class="col-md-6 col-sm-12">
        <img src="images/orchestAI-accueil.png" class="img-fluid" alt="">
      </div>
    </div>
  </div>
  <div class="container-fluid actualites">
    <p class="fs-4 pt-3" style="padding-left: 10%;">
      <text>Les actualités IA</text>
    </p>
    <div class="row" style="padding-inline: 10%;">
      <?php
        $getActualites = $bdd -> prepare('SELECT * FROM actualites ORDER BY date_publication DESC LIMIT 9');
        $getActualites->execute(array());
        while($actualites = $getActualites -> fetch()){
      ?>
      <div class="col-md-4 col-sm-6 p-3">
        <a class="card-actualites p-3" href="<?php echo $actualites['lien'];?>" target="_blank" style="position:relative" >
          <img class="card-image" src="<?php echo $actualites['image'];?>" alt="<?php echo $actualites['titre'];?>">
          <div class="card-text">
            <?php echo $actualites['titre'];?>
          </div>
        </a>

      </div>
      <?php } ?>

    </div>
  </div>

  <div class="container-fluid">
    <p class="fs-4 pt-3" style="padding-left: 10%;">
      <text>Une communauté active</text>
    </p>
    <div class="row" style="padding-inline: 10%;color:white">
      <?php
        $getVideos = $bdd -> prepare('SELECT * FROM videos ORDER BY id_videos DESC LIMIT 6');
        $getVideos->execute(array());
        while($videos = $getVideos -> fetch()){
      ?>
      <div class="col-md-4 col-sm-6 p-3">
        <a class="card-actualites p-3" href="<?php echo $videos['link'];?>" target="_blank" style="position:relative" >
          <img class="card-image" src="<?php echo $videos['image'];?>" alt="<?php echo $videos['title'];?>">
          <div class="card-text">
            <?php echo $videos['author']."<br />".$videos['title'];?>
          </div>
        </a>
      </div>
      <?php } ?>

    </div>
  </div>

</div>
<script type="text/javascript" src="javascript/index.js"></script>
</main>
<?php include('footerOrchestrAI.php');?>
</body>
</html>
