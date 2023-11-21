<?php

	session_start();

	function debug_to_console($data) {
	    $output = $data;
	    if (is_array($output))
	        $output = implode(',', $output);
	    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
	}

	#$bdd = new PDO('mysql:host=localhost;dbname=eywempvk_serveur_cyan','eywempvk_Akael', 'CyanRP-92290',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")) ;
	#ob_start();
	#setlocale(LC_ALL, 'fr_FR.utf8','fra');

	if(!isset($_GET['key']) || $_GET['key']!="uwu"){header("Location:indexV2");}

?>
<!DOCTYPE html>
<html style="height: 100%;overflow-x: hidden;">
	<head>
		<title>NG - Cyan</title>
    <meta charset="utf-8" />
		<meta name="referrer" content="no-referrer"/>

		<meta name="viewport" content="width=device-width, initial-scale=0.6">

		<link rel="icon" sizes="any" href="images/support/logoSupportCyan.svg">
		<link href="images/support/apple-touch-icon.png" rel="apple-touch-icon" sizes="152x152">
		<link href="images/support/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180">
		<link href="images/support/apple-touch-icon.png" rel="apple-touch-icon" sizes="167x167">


		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">


		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
		<link href = "./style/headerOrchestrAI.css" rel="stylesheet">
		<link href="./style/icomoon/style.css" rel="stylesheet">
		<link href = "./style/headerOrchestrAI-dark-mode.css" rel="stylesheet">

		<script src="https://kit.fontawesome.com/e55c63417e.js" crossorigin="anonymous"></script>


  </head>


  <body>

		<div class="site-mobile-menu">
			<div class="site-mobile-menu-header">
				<div class="site-mobile-menu-logo">
					<a class="" href="indexV2">
						<img src="images/cyan-test.png" >
					</a>
				</div>
				<div class="site-mobile-menu-close mt-3">
					<span class="icon-close2 js-menu-toggle"></span>
				</div>
			</div>
			<div class="site-mobile-menu-body d-grid">
			</div>
			<div class="site-mobile-menu-footer ">
				<div class="form-switch">
					<input class="form-check-input darkSwitch" type="checkbox" id="darkSwitch">
					<i class="fa-regular fa-sun fa-lg"></i> <i class="fa fa-angle-right"></i> <i class="fa-regular fa-moon fa-lg"></i>
				</div>

				<?php if(isset($_SESSION['id'])) { ?>
					<form method="POST">
						<button type="submit" name="deconnect" class="menu-deconnect"><i class="fa-solid fa-door-open"></i> Déconnexion</button>
					</form>
				<?php } ?>
			</div>
		</div>


    <header class="p-2 js-sticky-header d-flex align-items-center justify-content-center front" role="banner">
			<a class="navbar-brand" href="indexV2">
				<img src="images/cyan-test.png" class="d-inline-block align-top">
			</a>
			<a class="navbar-brand-mobile" href="index.html">
				<img src="images/logo-full.png" class="d-inline-block align-top">
			</a>

			<nav class="navbar-accueil navbar navbar-expand-md ftco-navbar-light" role="navigation">
		    <div class="container-fluid">
					<div class="toggle-container">
						<div class="toggle-button d-inline-block d-md-none">
							<a href="#" class="site-menu-toggle px-0 js-menu-toggle"><span class="icon-menu h3"></span></a>
						</div>
					</div>
		      <div class="collapse navbar-collapse">
		        <ul class="my-0 navbar-nav js-clone-nav ml-auto mr-md-3">
		        	<li class="nav-item"><a href="index?key=uwu" class="nav-link">Accueil</a></li>
							<li class="nav-item"><a href="convert?key=uwu" class="nav-link">Conversion</a></li>
							<li class="nav-item"><a href="#" class="nav-link">Histoire</a></li>
							<li class="nav-item"><a href="#" class="nav-link">Support</a></li>
		        </ul>
		      </div>
		    </div>


		  </nav>
			<div class="form-switch my-auto me-3">
				<input class="form-check-input darkSwitch" id="darkSwitch" type="checkbox" >
				<i class="fa-regular fa-sun fa-xl"></i> <i class="mx-1 fa-solid fa-angle-right fa-lg"></i> <i class="fa-regular fa-moon fa-xl"></i>
			</div>
			<div class="logo-profil my-auto me-3">
				<a href="#" data-toggle="dropdown" id="profil-dropdown-link" class="profil-dropdown-link" aria-haspopup="true" aria-expanded="false">
					<i class="fa-regular fa-user-circle fa-2xl"></i>
				</a>
				<div id="dropdown-menu-profile" class="dropdown-menu-profile">
					<ul class="p-0 menu-profile-ul">
						<?php if(!isset($_SESSION['id'])) { ?>
							<li>
								<a href="#" class="d-flex btn-accueil first-link connect-button"> Connexion </a>
							</li>
							<li>
								<a href="#" class="d-flex btn-accueil last-link signup-button"> Inscription </a>
							</li>
						<?php }else{ ?>
							<li>
								<a href="#" class="d-flex btn-accueil first-link"> Mon profil </a>
							</li>
							<li>
								<a href="#" class="d-flex btn-accueil middle-link"> Mes conversions </a>
							</li>
								<form method="POST" class="m-0">
									<button href="#" type="submit" name="deconnect" class="d-flex btn-accueil deconnect-menu last-link justify-content-center align-items-center"><i class="fa-solid fa-door-open me-2"></i> Déconnexion </button>
								</form>
							</li>
						<?php }?>
					</ul>
				</div>
			</div>

    </header>

		<script src="https://kit.fontawesome.com/e55c63417e.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
		<script src="javascript/jquery.sticky.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
		<script src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
		<script src="javascript/headerV2.js" charset="utf-8"></script>
		<script src="javascript/dark-mode-switchV2.min.js"></script>

		<main class="d-flex flex-column align-items-center">
			<?php include("loginV2OrchestrAI.php"); ?>
