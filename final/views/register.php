<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!-- Own CSS -->
        <link rel="stylesheet" href="/DDWT19_FINAL_PROJECT/final/css/main.css">

        <title><?= $page_title ?></title>
    </head>
    <body>
        <!-- Menu -->
        <?= $navigation ?>

        <!-- Content -->
        <div class="container" >
            <!-- Breadcrumbs -->
            <div class="pd-15">&nbsp</div>


            <div class="row">

                <!-- Left column -->
                <div class="col-md-12">
                    <!-- Error message -->
                    <?php if (isset($error_msg)){echo $error_msg;} ?>

                    <h1><?= $page_title ?></h1>
                    <h5><?= $page_subtitle ?></h5>


                </div>
                <div class="col-md-12">
                    <form action="<?= $form_action ?>" method="POST">
                        <?php $host = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                        if($host === 'localhost/DDWT19_FINAL_PROJECT/final/register') {
                            echo '<div class="form-group">
                            <div class="col-md-3">
                            <label for="inputUsername">Username</label>
                            <input type="text" class="form-control" id="inputUsername" placeholder="jannejanzz" name="username" value="<?php if (isset($user_info)){echo $user_info[\'username\'];} ?>" required>
                            </div>
                        </div>'
                            ;}?>

                        <?php $host = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                        if($host === 'localhost/DDWT19_FINAL_PROJECT/final/register') {
                            echo '<div class="form-group">
                            <div class="col-md-3">
                            <label for="inputPassword">Password</label>
                            <input type="password" class="form-control"  id="inputPassword" placeholder="******" name="password" value="<?php if (isset($user_info)){echo $user_info[\'password\'];} ?>" required>
                            </div>
                        </div>'
                            ;}?>

                        <div class="form-group">
                            <div class="col-md-3">
                            <label for="inputUsername">First name</label>
                            <input type="text" class="form-control" id="inputUsername" placeholder="Jan" name="firstname" value="<?php if (isset($user_info)){echo $user_info['firstname'];} ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">
                            <label for="inputUsername">Last name</label>
                            <input type="text" class="form-control" id="inputUsername" placeholder="Jansen" name="lastname" value="<?php if (isset($user_info)){echo $user_info['lastname'];} ?>" required>
                            </div>
                        </div>

                        <?php $host = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                        if($host === 'localhost/DDWT19_FINAL_PROJECT/final/register') {
                            echo '<div class=\"form-group\">
                            <div class=\"col-md-3\">
                                <label for=\"inputBirthdate\">Birthdate</label>
                                <input class=\"form-control\" type=\"date\" value=\"year-month-day\" id=\"birthdate\" name=\"birthdate\" required>
                            </div>
                        </div>'
                            ;}?>

                        <div class="form-group">
                            <div class="col-md-3">
                                <label for="inputProfession">Profession</label>
                                <input type="text" class="form-control"  placeholder = "carpenter" id="inputProfession" name="profession" value="<?php if (isset($user_info)){echo $user_info['profession'];} ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">
                            <label for="inputLanguages">Language(s)</label>
                            <input type="text" class="form-control" id="inputLanguages" placeholder="English,Dutch,Etc" name="languages" value="<?php if (isset($user_info)){echo $user_info['languages'];} ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">
                            <label for="inputTelephone">Telephone</label>
                            <input type="text" class="form-control" id="inputTelephone" placeholder="+316 67567491" name="telephone" value="<?php if (isset($user_info)){echo $user_info['telephone'];} ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">
                            <label for="inputEmail">E-mail</label>
                            <input type="text" class="form-control" id="inputEmail" placeholder="johnnyxxx@gmail.com" name="email" value="<?php if (isset($user_info)){echo $user_info['email'];} ?>" required>
                            </div>
                        </div>
                        <?php $host = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                            if($host === 'localhost/DDWT19_FINAL_PROJECT/final/register') {
                                echo "<div class=\"form-group\">
                            <div class=\"col-md-3\">
                            <label for=\"InputRole\">Role</label>
                            <select class=\"form-control\" id=\"Role\" name=\"role\">
                                <option>Owner</option>
                                <option>Tenant</option>
                            </select>
                            </div>"
                                ;}?>

                        <div class="form-group">
                            <div class="col-md-3">
                                <label for="inputBiography">Biography</label>
                                <textarea placeholder="Write a short introduction about yourself!" class="form-control" id="inputBiography" name="biography" required><?php if (isset($user_info)){echo $user_info['biography'];} ?></textarea>
                            </div>
                        </div>
                        <?php if(isset($user_id)){ ?><input type="hidden" name="user_id" value="<?php echo $user_id ?>"><?php } ?>

                        <div class="form-group">
                            <div class="col-md-3">
                            <button type="submit" class="btn btn-primary"><?= $submit_btn ?></button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>

            </div>
        </div>


        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
