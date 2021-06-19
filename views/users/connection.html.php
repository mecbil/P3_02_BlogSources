<section class="conninscri text-light">
    <div class="text-light col-11 col-lg-5 col-md-9 col-sm-11 pt-5">

        <!-- zone de connexion -->
        <div class="login-form">
            <?php if (!empty($erreur)): ?>
            <div class="alert alert-danger">
            <?php print_r($erreur) ?>
            </div>
            <?php endif; ?>
           
            <form action="index.php?controller=UserController&task=connect" method="post">
                <h2 class="text-center">Connexion</h2>
                <div class="form-group">
                    <input type="email" name="emailconnect" value="<?php $gemailconnect = filter_input(INPUT_POST, 'emailconnect') ; if (isset($gemailconnect)){echo $gemailconnect ;} ?>" class="form-control" placeholder="Adresse mail"  autocomplete="off">
                </div>
                <div class=" form-group ">
                    <input type="password" name="passwordconnect" value="<?php $passwordconnect = filter_input(INPUT_POST, 'password'); if (isset($passwordconnect)){echo $passwordconnect;} ?>" class="form-control" placeholder="Mot de passe" title="Minimum 8 caractères avec au moin une lettre Majuscule et une miniscule et un caractère special " autocomplete="off">
                </div>
                <div class=" form-group ">
                    <button type="submit" class="btn btn-dark btn-outline-light btn-block mt-2">Connexion</button>
                </div>
            </form>
        </div>
    </div>

    <!-- zone de d'enregistrement -->
    <div class="text-light col-11 col-lg-5 col-md-9 col-sm-11 mt-5">
        <div class="login-form">

            <?php if (!empty($erreurAdd)): ?>
            <div class="alert alert-danger">
            <?php print_r($erreurAdd) ?>
            </div>
            <?php endif; ?>

            <form action="index.php?controller=UserController&task=insertUser" method="post">
                <h2 class=" text-center "> Enregistrement </h2>

                <div class=" form-group ">
                    <input type="text" name="pseudo" value="<?php $pseudo = filter_input(INPUT_POST, 'pseudo'); if (isset($pseudo)) {echo $pseudo ;} ?>" class="form-control" placeholder="Pseudo" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" name="email" value="<?php $email = filter_input(INPUT_POST, 'email'); if (isset($email)) {echo $email ;} ?>" class="form-control" placeholder="Adresse mail" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="password" name="password" value="<?php $password = filter_input(INPUT_POST, 'password'); if (isset($password)){echo $password ;} ?>" class="form-control" placeholder="Mot de passe" title="Minimum 8 caractères avec au moin une lettre Majuscule et une miniscule et un caractère special " autocomplete="off">
                </div>
                <div class=" form-group ">
                    <button type=" submit " class="btn btn-dark btn-outline-light btn-block mt-2"> Enregistrer </button>
                </div>                
            </form>
        </div>
    </div>
</section>>