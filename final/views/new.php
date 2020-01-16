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
                <div class="col-md-8">
                    <!-- Error message -->
                    <?php if (isset($error_msg)){echo $error_msg;} ?>

                    <h1><?= $page_title ?></h1>
                    <h5><?= $page_subtitle ?></h5>
                    <p><?= $page_content ?></p>
                    <form action="<?= $form_action ?>" method="POST">
                        <div class="form-group row">
                            <label for="inputPrice" class="col-sm-2 col-form-label">Price</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputPrice" name="price" value="<?php if (isset($room_info)){echo $room_info['price'];} ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label for="inputTemp">Is the room temporary?</label>
                                <select class="form-control" id="inputTemp" name="role">
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputSquaremeters" class="col-sm-2 col-form-label">Amount of square meters</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputSquaremeters" name="square_meters" value="<?php if (isset($room_info)){echo $room_info['square_meters'];} ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputAddress" class="col-sm-2 col-form-label">Street Address</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputAddress" name="street_address" value="<?php if (isset($room_info)){echo $room_info['street_address'];} ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputCity" class="col-sm-2 col-form-label">City</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputCity" name="city" value="<?php if (isset($room_info)){echo $room_info['city'];} ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputZipcode" class="col-sm-2 col-form-label">Zip Code</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputZipcode" name="zipcode" value="<?php if (isset($room_info)){echo $room_info['zipcode'];} ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputDescription" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="inputDescription" rows="3" name="description" required><?php if (isset($room_info)){echo $room_info['description'];} ?></textarea>
                            </div>
                        </div>
                        <?php if(isset($room_id)){ ?><input type="hidden" name="room_id" value="<?php echo $room_id ?>"><?php } ?>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"><?= $submit_btn ?></button>
                            </div>
                        </div>
                    </form>
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
