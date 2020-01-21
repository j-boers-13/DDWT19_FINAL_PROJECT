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

                <!-- Left column -->
                <div class="col-md-8">
                    <!-- Error message -->
                    <?php if (isset($error_msg)){echo $error_msg;} ?>

                    <h1><?= $page_title ?></h1>
                    <h5><?= $page_subtitle ?></h5>
                    <p><?= $page_content ?></p>
                    <table class="table">
                        <tbody>
                        <tr>
                            <th scope="row">Square Meters</th>
                            <td><?= $room_info['square_meters'] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Price</th>
                            <td><?= $room_info['price'] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Temporary</th>
                            <td><?= $room_info['temporary'] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Added by owner</th>
                            <td><?= $added_by ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <?php if ($is_owner) { ?>
                            <div class ='col-sm-2'>
                                <a href="/DDWT19_FINAL_PROJECT/final/edit/?room_id=<?=
                                $room_id ?>" role="button" class="btn btn-warning">Edit</a>
                            </div>
                            <div class="col-sm-2">
                                <form action="/DDWT19_FINAL_PROJECT/final/remove/" method="POST">
                                    <input type="hidden" value="<?=$room_id
                                    ?>" name="room_id">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </div>
                        <?php } else { if(isset($_SESSION['user_id'])) {?>
                            <?php if (!($user_opted_in)) { ?>
                            <div class='row'>

                                <div class ='col-sm-2'>
                                    <a href="/DDWT19_FINAL_PROJECT/final/optins/add/?room_id=<?=
                                    $room_id ?>" role="button" class="btn btn-warning">Opt in</a>
                                </div>
                            </div>
                            <?php } ?>
                        <?php ;}
                        ;}
                        ?>
                        <div class ='col-sm-2'>
                            <td><a href="/DDWT19_FINAL_PROJECT/final/profile/?user_id=<?= $room_info['owner_id'] ?>" role="button" class="btn btn-info">Show owners' profile</a></td>
                        </div>
                </div>

                <!-- Right column -->
                <div class="col-md-4">

                    <?php include $right_column ?>

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
