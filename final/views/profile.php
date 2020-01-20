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
            <div class="pd-15">&nbsp
            </div>
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
                            <th scope="row">Full Name</th>
                            <td><?= $user ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Biography</th>
                            <td style="word-break:break-all;"><?= $user_info['biography'] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Languages</th>
                            <td><?= $user_info['languages'] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Profession</th>
                            <td><?= $user_info['profession'] ?> </td>
                        </tr>
                        <tr>
                            <th scope="row">Telephone</th>
                            <td><?= $user_info['telephone'] ?> </td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td><?= $user_info['email'] ?> </td>
                        </tr>
                        <?php if ($user_info['role'] == "Owner")
                        /* only display number of rooms owned if the user is a landlord */
                        { ?>
                        <tr>
                            <th scope="row">Number of rooms owned</th>
                            <td><?= $nbr_rooms_by_owner ?></td>
                        </tr>
                <?php } ?>
                                        </tbody>
                    </table>
                <?php
                /* only display edit and remove buttons if the user page is in ownership of the session user*/
                if($_SESSION['user_id'] === $user_info['id']) {?>
                    <div class='row'>
                        <div class ='col-sm-2'>
                            <a href="/DDWT19_FINAL_PROJECT/final/myprofile/edit/?user_id=<?=
                            $user_info['id'] ?>" role="button" class="btn btn-warning">Edit</a>
                        </div>
                        <div class="col-sm-2">
                            <form action="/DDWT19_FINAL_PROJECT/final/myprofile/remove/" method="POST">
                                <input type="hidden" value="<?=$user_info['id']
                                ?>" name="user_id">
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </div>
                    </div>
                <?php } ?>
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
