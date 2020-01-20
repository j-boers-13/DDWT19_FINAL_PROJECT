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
            <h5><?= $page_subtitle ?><?= $sent_by ?></h5>
            <p><?= $page_content ?></p>
            <p></p>
            <p><?= $date_sent ?></p>
            <?php if ($user_is_sender) { ?>
                <div class='row'>
                    <div class="col-sm-2">
                        <form action="/DDWT19_FINAL_PROJECT/final/optins/remove" method="POST">
                            <input type="hidden" value="<?=$optin_info['id'] ?>" name="optin_id">
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    </div>
                </div>
            <?php } else { if(isset($_SESSION['user_id']) AND ($is_owner)) {?>
                <div class='row'>
                    <div class ='col-sm-2'>
                        <a href="/DDWT19_FINAL_PROJECT/final/invites/add/?room_id=<?=
                        $optin_info["room_id"] ?>&tenant_id=<?= $optin_info["tenant_id"] ?>" role="button" class="btn btn-warning">Respond</a>
                    </div>
                    <div class ='col-sm-2'>
                        <td><a href="/DDWT19_FINAL_PROJECT/final/profile/?user_id=<?= $optin_info['tenant_id'] ?>" role="button" class="btn btn-info">Show users' profile</a></td>
                    </div>
                </div>


            <?php } } ?>
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
