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
        <div class="container">
            <!-- Breadcrumbs -->
            <div class="pd-15">&nbsp</div>
            <?= $breadcrumbs ?>

            <div class="row">

                <!-- Left column -->
                <div class="col-md-12">
                    <!-- Error message -->
                    <?php if (isset($error_msg)){echo $error_msg;} ?>

                    <h1><?= $page_title ?></h1>
                    <h5><?= $page_subtitle ?></h5>

                    <div class="pd-15">&nbsp;</div>

                    <form action="/DDWT19_FINAL_PROJECT/final/register/" method="POST">
                        <div class="form-group">
                            <div class="col-xs-2">
                            <label for="inputUsername">Username</label>
                            <input type="text" class="form-control" id="inputUsername" placeholder="jannejanzz" name="username" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">Password</label>
                            <input type="password" class="form-control" size="small" id="inputPassword" placeholder="******" name="password" required>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-2">
                            <label for="inputUsername">First name</label>
                            <input type="text" class="form-control" id="inputUsername" placeholder="Jan" name="firstname" required>
                            </div>
                            <div class="col-xs-2">
                            <label for="inputUsername">Last name</label>
                            <input type="text" class="form-control" id="inputUsername" placeholder="Jansen" name="lastname" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputBirthdate" class="col-2 col-form-label">Birthdate</label>
                            <div class="col-xs-2">
                                <input class="form-control" type="date" value="year-month-day" id="birthdate" name="birthdate" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputProfession">Profession</label>
                            <div class="col-xs-2">
                                <input type="text" class="form-control"  placeholder = "carpenter" id="inputProfession" name="profession" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLanguages">Language(s)</label>
                            <input type="text" class="form-control" id="inputLanguages" placeholder="English,Dutch,Etc" name="languages" required>
                        </div>
                        <div class="form-group">
                            <label for="inputBiography">Biography</label>
                            <input type="text" class="form-control" id="inputUsername" placeholder="Write a short introduction about yourself!" name="biography" required>
                        </div>
                        <div class="form-group">
                            <label for="inputTelephone">Telephone</label>
                            <input type="text" class="form-control" id="inputUsername" placeholder="+316 67567491" name="telephone" required>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail">E-mail</label>
                            <input type="text" class="form-control" id="inputUsername" placeholder="x@gmail.com" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="InputRole">Role</label>
                            <select class="form-control" id="Role" name="role">
                                <option>Owner</option>
                                <option>Tenant</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Register now</button>
                    </form>

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
