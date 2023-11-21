<link href="style/footerOrchestrAI.css" rel="stylesheet">
<footer class="py-4" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,0.75)!important;">
  <div class="container">
    <div class="row">

      <div class="col-6">
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <li class="nav-header">NAVIGATION</li>
            <ul class="nav nav-treeview nav-sidebar" style="display: block;">
              <li class="nav-item">
                <a href="commerce" class="nav-link" style="display:flex">
                  <i class="fas fa-shopping-cart nav-icon"></i>
                  <p class="m-0">Conversion</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="mandat" class="nav-link" style="display:flex">
                  <i class="fa-solid fa-handcuffs nav-icon"></i>
                  <p class="m-0">Notre histoire</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="event" class="nav-link" style="display:flex">
                  <i class="fa-solid fa-calendar-days nav-icon"></i>
                  <p class="m-0">Support</p>
                </a>
              </li>

            </ul>
          </li>
        </ul>
      </div>


      <div class="col-6 ">
        <p class="nav-header">A PROPOS</p>
        <p>OrchestrAI est un site de conversion d'un audio en fichier MIDI à partir d'une IA</p>
        <p>OrchestrAI est un projet scolaire d'étudiants spécialisés en Intelligence Artificielle et Big DATA.</p>
      </div>
    </div>

    <div class="row mt-5">
      <div class="col text-center">
        © <?php echo date("Y"); ?> Akael • Tous droits réservés
      </div>
    </div>
  </div>
</footer>
<?php ob_end_flush(); ?>
