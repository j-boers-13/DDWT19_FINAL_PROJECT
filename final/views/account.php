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


            <div class="row">

                <div class="col-md-12">
                    <!-- Error message -->
                    <?php if (isset($error_msg)){echo $error_msg;} ?>

                    <h1><?= $page_title ?></h1>
                    <h5><?= $page_subtitle ?></h5>
                    <p><?= $page_content ?></p>
                </div>

            </div>

            <div class="pd-15">&nbsp;</div>

            <div class="row">

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Welcome, <?= $user ?>
                        </div>
                        <div class="card-body">
                            <p>You're logged in to Account Overview.</p>
                            <a href="/DDWT19_FINAL_PROJECT/final/myprofile/" class="btn btn-primary">My Profile</a>
                            <a href="/DDWT19_FINAL_PROJECT/final/logout/" class="btn btn-warning">Logout</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Quick overview
                        </div>
                        <div class="card-body">
                            <p> You currently have: </p>
                            <?php if(check_owner($db) === True) { ?>
                            <a href="/DDWT19_FINAL_PROJECT/final/myrooms" class="btn btn-primary"> <span class = "emphasis"> <?=$nbr_rooms_by_owner?> </span> rooms listed.</a>
                            <a href="/DDWT19_FINAL_PROJECT/final/optins" class="btn btn-primary"><span class = "emphasis"> <?= $nbr_optins ?></span> Opt-ins received. </a>
                            <?php } else { ?>
                            <a href="/DDWT19_FINAL_PROJECT/final/optins" class="btn btn-primary"> <span class = "emphasis"> <?=$nbr_optins?> </span> opt-ins </a>
                            <a href="/DDWT19_FINAL_PROJECT/final/invites" class="btn btn-primary"> <span class = "emphasis"> 0 </span> invites </a>
                            <?php } ?>
                        </div>


                        </div>
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
