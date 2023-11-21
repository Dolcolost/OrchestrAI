<?php

  if(isset($_POST['deconnect'])){
    session_start()     ;
    $_session = array() ;
    session_destroy()   ;
    header("Location: indexV2");
  }

  if(isset($_POST['inscription'])) //regarde si le formulaire inscription existe
  {
    $pseudo    = str_replace(' ', '', htmlspecialchars($_POST['pseudo-signup']));
    $email = htmlspecialchars($_POST['mail-signup'])   ;
    $psw    = sha1($_POST['password-signup'])				 ; //Sécurise mot de passe

    $url="https://publicapi.nationsglory.fr/user/".$pseudo;
  	$ch = curl_init($url);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$apiKey));
  	// Get response
  	$response = curl_exec($ch);
  	// Decode
  	$result = json_decode($response,true);

    if(!empty($_POST['pseudo-signup']) ) //Vérifie si les cases d'inscription sont remplis
    {
      $pseudoLenght = strlen($pseudo); //Vérifier si nom pas trop long
      if($pseudoLenght <= 20)
      {
        if(!isset($result['error'])){
          $reqPseudo = $bdd->prepare("SELECT * FROM user WHERE pseudo = ?");
          $reqPseudo->execute(array($pseudo));
          $pseudoExist = $reqPseudo->rowCount();
          if($pseudoExist == 0){

            if(filter_var($email, FILTER_VALIDATE_EMAIL)){

              $reqMail = $bdd->prepare("SELECT * FROM user WHERE email = ?");
              $reqMail->execute(array($email));
              $mailExist = $reqMail->rowCount();
              if($mailExist == 0){
                $emaillenght = strlen($email); //Vérifier si email pas trop long
                if($emaillenght <= 100)
                {
                  if ($_POST['password-signup'] === $_POST['password-signup-confirm']) {
                    $token = sha1($_POST['mail-signup']).rand(10,9999);
                    include('mail/mailInscription.php');

                    $insertUser = $bdd->prepare("INSERT INTO user(pseudo , email , password,is_verif,link_verification) VALUES(?,?,?,?,?)") ;
                    $insertUser->execute(array($pseudo,$email,$psw,0,$token)) ;

                    $reqmail = $bdd->prepare("SELECT * FROM user WHERE email = ?");
                    $reqmail->execute(array($email));
                    $user = $reqmail -> fetch();
                    $id_user = $user['id_user'];

                    $insertUserRole = $bdd->prepare("INSERT INTO user_role(id_user , id_role) VALUES(?,?)") ;
                    $insertUserRole->execute(array($id_user ,2)) ;

                    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                        $ip = $_SERVER['HTTP_CLIENT_IP'];
                    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    } else {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    }

                    $insertIP = $bdd->prepare("SELECT * FROM user_ip WHERE user = ? and ip = ?") ;
              			$insertIP->execute(array($userInfo['id_user'],$ip));

                    if($insertIP->rowCount() == 0){
                      $insertIP = $bdd->prepare("INSERT INTO user_ip(user , ip) VALUES(?,?)") ;
              			  $insertIP->execute(array($userInfo['id_user'],$ip));
                    }
                    $erreurSignUp = "";
                    $validateSignUp = 1;
                  }else {$erreurSignUp = "Les mots de passe ne correspondent pas";}
                }else{$erreurSignUp = "Cet email est trop long !";}
              }else{$erreurSignUp = "Cet email est déjà pris !";}
            }else {$erreurSignUp="Le format de l'email est invalide";}
          }else{$erreurSignUp = "Ce pseudo est déjà pris !";}
        }else{$erreurSignUp = "Ce pseudo n'existe pas sur NG";}
      }else {$erreurSignUp="Votre pseudo est trop long";}
    }else {$erreurSignUp="Merci d'indiquer un pseudo";}
  }else {$erreurSignUp="";}


  if(isset($_POST['login'])) //regarde si le formulaire inscription existe
  {
    $pseudo    = htmlspecialchars($_POST['pseudo-connect']) ;
    $psw    = sha1($_POST['password-connect'])				 ; //Sécurise mot de passe

    if(!empty($_POST['pseudo-connect']) AND !empty($_POST['password-connect']) ) //Vérifie si les cases d'inscription sont remplis
    {
      $reqPseudo = $bdd->prepare("SELECT * FROM user WHERE pseudo = ?");
      $reqPseudo->execute(array($pseudo));
      $pseudoExist = $reqPseudo->rowCount();
      if($pseudoExist != 0){
        $userInfo = $reqPseudo->fetch();
        if($userInfo["password"] == $psw){
          if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
              $ip = $_SERVER['HTTP_CLIENT_IP'];
          } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
              $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
          } else {
              $ip = $_SERVER['REMOTE_ADDR'];
          }

          $insertIP = $bdd->prepare("SELECT * FROM user_ip WHERE user = ? and ip = ?") ;
          $insertIP->execute(array($userInfo['id_user'],$ip));

          if($insertIP->rowCount() == 0){
            $insertIP = $bdd->prepare("INSERT INTO user_ip(user , ip) VALUES(?,?)") ;
            $insertIP->execute(array($userInfo['id_user'],$ip));
          }
          $isVerif = $userInfo['is_verif'];
          if($isVerif !=0){
            if($userInfo["isBan"] == 0){
              if($userInfo["isDesac"] == 0){
                $_SESSION['id'] = $userInfo['id_user'];
                $_SESSION['pseudo'] = $pseudo;

                header("Location:index");
              }else {$erreurLogin = "Ce compte est désactivé";}
            }else {$erreurLogin = "Ce compte est banni";}
          }else {$erreurLogin = "Ce compte n'est pas vérifié";}
        }else {$erreurLogin = "Le mot de passe est incorrect";}
      }else {$erreurLogin = "Ce compte n'existe pas";}
    }else {$erreurLogin = "Tous les champs doivent être complétés";}
  }else{$erreurLogin = "";}

?>

<link href="style/loginV2.css" rel="stylesheet">
<section class="login-section sign-in px-3" <?php if(!empty($erreurLogin) ){echo "style='display:block;'";}?>>
    <div class="container-fluid">
        <div class="login-content py-4 d-flex">
            <div class="login-image text-center">
                <figure><img src="images/connexion.png" class="img-fluid" alt=""></figure>
                <a href="#" class="signup-button">Créer un compte</a>
            </div>

            <div class="login-form my-auto">
                <h2 class="form-title">Se connecter</h2>
                <form method="POST">
                    <div class="input-group">
                        <label for="pseudo-connect" style="top:13%"><img id="img-pseudo-connect" src="https://skins.nationsglory.fr/face/player/3" alt="User Image"></label>
                        <input type="text" name="pseudo-connect" id="pseudo-connect" placeholder="Pseudo"/>
                    </div>
                    <div class="input-group">
                        <label for="password-connect" style="top:20%"><i class="fa-solid fa-solid fa-unlock-keyhole fa-xl"></i></label>
                        <input type="password" name="password-connect" id="password-connect" placeholder="Mot de passe"/>
                        <i class="fa-solid fa-eye my-auto showPsw ms-3"></i>
                    </div>
                    <div class="input-group">
                        <input type="checkbox" name="rememberMe" id="rememberMe" class="rememberMe" style="width:auto" <?php if(isset($_COOKIE['rememberMe'])){echo "checked";}?>/>
                        <label for="rememberMe" class="rememberMe">Restez connecté</label>
                    </div>
                    <?php if(!empty($erreurLogin)) {?>
                      <div class="input-group">
                          <span><i class="fa-lg fa-solid fa-triangle-exclamation" style="color: #d71d1d;"></i> Erreur: <?php echo $erreurLogin ; ?></span>
                      </div>
                    <?php } ?>

                    <div class="input-group input-group-button">
                        <button type="submit" name="login" id="login" class="loginBtn">Se connecter</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

<section class="signup-section sign-in px-3" <?php if(!empty($erreurSignUp) or $validateSignUp==1){echo "style='display:block;'";}?>>
    <div class="container-fluid">
        <div class="signup-content py-4 d-flex">

            <div class="signup-form my-auto">
                <h2 class="form-title">S'inscrire</h2>
                <form method="POST">
                    <div class="input-group">
                        <label for="pseudo-signup" style="top:20%"><i class="fa-solid fa-user fa-xl"></i></label>
                        <input type="text" name="pseudo-signup" id="pseudo-signup" placeholder="Pseudo"/>
                    </div>
                    <div class="input-group">
                        <label for="mail-signup" style="top:20%"><i class="fa-solid fa-at fa-xl"></i></label>
                        <input type="email" name="mail-signup" id="mail-signup" placeholder="Email"/>
                        <i class="fa-solid fa-circle-info my-auto ms-3"></i>
                    </div>
                    <div class="input-group">
                        <label for="password-signup" style="top:20%"><i class="fa-solid fa-unlock-keyhole fa-xl"></i></label>
                        <input type="password" name="password-signup" id="password-signup" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{12,}" placeholder="Mot de passe"/>
                        <i class="fa-solid fa-eye my-auto showPsw ms-3"></i>
                    </div>
                    <div class="input-group">
                        <label for="password-signup-confirm" style="top:20%"><i class="fa-solid fa-unlock-keyhole fa-xl"></i></label>
                        <input type="password" name="password-signup-confirm" id="password-signup-confirm" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{12,}" placeholder="Confirmer mot de passe"/>
                        <i class="fa-solid fa-eye my-auto showPsw ms-3"></i>
                    </div>

                    <div class="input-group">
                        <span id="restriction"><i class="fa-solid fa-info-circle pe-2"></i>Afficher les restrictions du mot de passe</span>
                    </div>

                    <?php if(!empty($erreurSignUp)) {?>
                      <div class="input-group">
                          <span><i class="fa-lg fa-solid fa-triangle-exclamation" style="color: #d71d1d;"></i> Erreur: <?php echo $erreurSignUp ; ?></span>
                      </div>
                    <?php } ?>

                    <?php if($validateSignUp != 1) {?>
                    <div class="input-group input-group-button">
                        <button type="submit" name="inscription" class="loginBtn"> S'inscrire </button>
                    </div>
                  <?php }else { ?>
                    <div class="input-group">
                        <span><i class="fa-lg fa-check fa-solid" style="color:limegreen;"></i> Inscription réussie ! Pensez à vérifier vos mails ! </span>
                    </div>
                  <?php } ?>
                </form>

            </div>
            <div class="signup-image text-center my-auto">
              <div class="container restrictions d-none text-start mb-4">
                <h4 class="form-title">Les restrictions du mot de passe</h4>
                <span class="py-1"><i id="letterFa" class="fa-solid fa-xmark fa-lg"></i> 1 minucule</span>
                <span class="py-1"><i id="capitalFa" class="fa-solid fa-xmark fa-lg"></i> 1 majuscule</span>
                <span class="py-1"><i id="numberFa" class="fa-solid fa-xmark fa-lg"></i> 1 chiffre</span>
                <span class="py-1"><i id="lengthFa" class="fa-solid fa-xmark fa-lg"></i> 12 caractères minimum</span>
                <span class="py-1"><i id="same" class="fa-solid fa-xmark fa-lg"></i> Mot de passe identiques</span>

              </div>
              <figure class="no-restrictions"><img src="images/inscription.png" class="img-fluid" alt=""></figure>
              <a href="" class="connect-button">Se connecter</a>
            </div>
        </div>
    </div>
</section>
<script src="javascript/loginV2.js" charset="utf-8"></script>
