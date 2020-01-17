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
                            <a href="/DDWT19_FINAL_PROJECT/final/logout/" class="btn btn-primary">Logout</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Quick overview
                        </div>
                        <?php if(check_owner($db) === True) { ?>
                        <div class="card-body">
                            <p class="count"> You currently have</p>
                            <h2> <?= $nbr_rooms_by_owner ?></h2>
                            <p> Rooms listed</p>
                            <a href="/DDWT19_FINAL_PROJECT/final/add" class="btn btn-primary">List another room</a>
                        </div>
                        <?php } else { ?>
                            <div class="card-body">
                                <p class="count"> You currently have</p>
                                <h2> <?= $nbr_optins ?></h2>
                                <p> Number of opt-ins</p>
                                <a href="/DDWT19_FINAL_PROJECT/final/optins" class="btn btn-primary">Go to opt-in overview</a>
                                <p class="count"> You currently have</p>
                                <h3> (number of viewing days planned)</h3>
                                <p>Number of opt-ins accepted</p>
                                <<a href="/DDWT19_FINAL_PROJECT/final/viewings" class="btn btn-primary">go to viewing overview</a>
                            </div>
                        <?php } ?>

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
