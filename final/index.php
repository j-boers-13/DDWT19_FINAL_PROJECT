<?php
/**
 * Controller
 * User: reinardvandalen
 * Date: 05-11-18
 * Time: 15:25
 */

include 'model.php';
$nav_array = Array(
    1 => Array (
        'name' => 'Home',
        'url' => '/DDWT19_FINAL_PROJECT/final/'
    ),
    2 => Array(
        'name' => 'Rooms',
        'url' => '/DDWT19_FINAL_PROJECT/final/overview'
    ),
    3 => Array(
        'name' => 'My Account',
        'url' => '/DDWT19_FINAL_PROJECT/final/myaccount'
    ),
    4 => Array(
        'name' => 'Register',
        'url' => '/DDWT19_FINAL_PROJECT/final/register'
    ),
    5 => Array(
        'name' => 'Add a room',
        'url' => '/DDWT19_FINAL_PROJECT/final/add'
    )
);

/* Connect to DB */
$db = connect_db('remotemysql.com', '1cJD522I73', '1cJD522I73','MHJUWcQxxb');

/* Redudant code is added here */
/* Get Number of Series */
$nbr_rooms = count_rooms($db);
/* Get Number of Users */
$nbr_users = count_users($db);
$nbr_owners = count_owners($db);
$nbr_tenants = count_tenants($db);

/* set the right column as default on every route instead constantly calling it */
$right_column = use_template('cards');


/* Landing page */
if (new_route('/DDWT19_FINAL_PROJECT/final/', 'get')) {

    /* Page info */
    $page_title = 'Home';
    $breadcrumbs = get_breadcrumbs([
        'ROOM.NET' => na('/DDWT19_FINAL_PROJECT/final/', False),
        'Home' => na('/DDWT19_FINAL_PROJECT/final/', True)
    ]);
    $navigation = get_navigation($nav_array,1);

    /* Page content */
    $page_subtitle = 'The online platform for room tenants and -owners!';
    $page_content = 'On room overview you can see all rooms.';

    /* Get error msg from remove post route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('main');
}

/* Overview page */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/overview/', 'get')) {

    /* Page info */
    $page_title = 'Overview';
    $breadcrumbs = get_breadcrumbs([
        'DDWT19' => na('/DDWT19_FINAL_PROJECT/', False),
        'Week 2' => na('/DDWT19/final/', False),
        'Overview' => na('/DDWT19_FINAL_PROJECT/final/overview', True)
    ]);
    $navigation = get_navigation($nav_array, 2);

    /* Page content */
    $page_subtitle = 'The overview of all available rooms';
    $page_content = 'Here you find all rooms listed on ROOM.NET';
    $left_content = get_room_table(get_available_rooms($db), $db);

    /* Get error msg from remove post route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('main');
}

/* Single Room */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/room', 'get')) {
    /* Get series from db */
    $room_id = $_GET['room_id'];
    $room_info = get_roominfo($db, $room_id);

    /* Page info */
    $page_title = $room_info['street_address'];
    $breadcrumbs = get_breadcrumbs([
        'DDWT19' => na('/DDWT19_FINAL_PROJECT/', False),
        'Week 2' => na('/DDWT19_FINAL_PROJECT/final', False),
        'Overview' => na('/DDWT19_FINAL_PROJECT/final/overview/', False),
        $room_info['street_address'] => na('/DDWT19_FINAL_PROJECT/final/room/?room_id='.$room_id, True)
    ]);
    $navigation = get_navigation($nav_array,2);

    /* Page content */
    $page_subtitle = sprintf("Information about %s", $room_info['street_address']);
    $page_content = $room_info['description'];
    $nbr_seasons = $room_info['seasons'];
    $added_by = get_user_name($db, $room_info['owner_id']);
    $display_button =  check_if_users($serie_info['user']);


    /* Get error msg from POST route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }
    /* Choose Template */
    include use_template('room');
}

/* Add serie GET */
elseif (new_route('//DDWT19_FINAL_PROJECT/final/add/', 'get')) {
    /* check if logged in */
    if ( !check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/login/');
    }


    /* Page info */
    $page_title = 'Add Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT19' => na('/DDWT19_FINAL_PROJECT/', False),
        'Week 2' => na('/DDWT19_FINAL_PROJECT/final/', False),
        'Add Series' => na('/DDWT19_FINAL_PROJECT/final/new/', True)
    ]);
    $navigation = get_navigation($nav_array,5);

    /* Page content */
    $page_subtitle = 'Add your favorite series';
    $page_content = 'Fill in the details of you favorite series.';
    $submit_btn = "Add Series";
    $form_action = '/DDWT19_FINAL_PROJECT/final/add/';
    /* Get error msg from POST route */
    if (isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET["error_msg"]);
    }

    /* Choose Template */
    include use_template('new');
}

/* Add serie POST */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/add/', 'post')) {
    /* check if logged in */
    if ( !check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/login/');
    }
    /* add serie to database */
    $feedback = add_serie($db, $_POST);
    /* Redirect to serie GET route */
    redirect(sprintf('/DDWT19_FINAL_PROJECT/final/add/?error_msg=%s',
        json_encode($feedback)));
}

/* Edit serie GET */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/edit/', 'get')) {
    /* check if logged in */
    if ( !check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/login/');
    }

    /* Get serie info from db */
    $serie_id = $_GET['serie_id'];
    $serie_info = get_serieinfo($db, $serie_id);

    /* Page info */
    $page_title = 'Edit Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT19' => na('/DDWT19_FINAL_PROJECT/', False),
        'Week 2' => na('/DDWT19_FINAL_PROJECT/final/', False),
        sprintf("Edit Series %s", $serie_info['name']) => na('/DDWT19_FINAL_PROJECT/final//new/', True)
    ]);
    $navigation = get_navigation($nav_array,6);

    /* Page content */
    $page_subtitle = sprintf("Edit %s", $serie_info['name']);
    $page_content = 'Edit the series below.';
    $submit_btn = "Edit Series";
    $form_action = '/DDWT19_FINAL_PROJECT/final/edit/';


    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('new');
}

/* Edit serie POST */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/edit/', 'post')) {
    /* check if logged in */
    if ( !check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/login/');
    }
    /* Update serie in database */
    $feedback = update_serie($db, $_POST);

    /* Get serie info from db */
    $serie_id = $_POST['serie_id'];
    $serie_info = get_serieinfo($db, $serie_id);

    /* Redirect to serie get route */
    redirect(sprintf('/DDWT19_FINAL_PROJECT/final/room/?error_msg=%s&room_id=%s',
        json_encode($feedback), $_POST['room_id']));
}

/* Remove serie POST */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/remove/', 'post')) {
    /* check if logged in */
    if ( !check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/login/');
    }

    /* Remove serie in database */
    $serie_id = $_POST['room_id'];
    $feedback = remove_serie($db, $serie_id);

    /* Redirect to overview GET route */
    redirect(sprintf('/DDWT19_FINAL_PROJECT/final/overview/?error_msg=%s',
    json_encode($feedback)));


}
/* Myaccount GET */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/myaccount/', 'get')) {
   /* check if logged in */
    if ( !check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/login/');
    }


    /* Page info */
    $page_title = 'My Account';

    $breadcrumbs = get_breadcrumbs([
        'DDWT19' => na('/DDWT19_FINAL_PROJECT/', False),
        'Week 2' => na('/DDWT19_FINAL_PROJECT/final/', False),
        'Overview' => na('/DDWT19_FINAL_PROJECT/final/myaccount', True)
    ]);
    $navigation = get_navigation($nav_array, 3);
    /* Page content */
    $page_subtitle = sprintf("Check out your account");
    $page_content = "Check out your account below";
    $user = get_user_name ($db, $_SESSION['user_id']);
    /* Get Error msg from POST route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* choose template */
    include use_template('account');
}

/* Register GET */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/register', 'get')) {
    /* Page info */
    $page_title = 'Register';
    $breadcrumbs = get_breadcrumbs([
        'DDWT19' => na('/DDWT19_FINAL_PROJECT/', False),
        'Week 2' => na('/DDWT19_FINAL_PROJECT/final/', False),
        'Overview' => na('/DDWT19_FINAL_PROJECT/final/register/', True)
    ]);
    $navigation = get_navigation($nav_array, 4  );
    /* Page content */
    $page_subtitle = sprintf("Register an account");
    $page_content = "Fill in the form below";

    /* Get Error msg from POST route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* choose template */
    include use_template('register');
}
/* Register POST */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/register/', 'post')) {
    /* Update serie in database */
    $error_msg = register_user($db, $_POST);

    /* Redirect to serie get route */
    redirect(sprintf('/DDWT19_FINAL_PROJECT/final/register/?error_msg=%s',
    json_encode($error_msg)));
}

/* Login GET */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/login/', 'get')) {
    /* check if logged in */
    if ( check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/myaccount/');
    }
    /* Page info */
    $page_title = 'Login';
    $breadcrumbs = get_breadcrumbs([
        'DDWT19' => na('/DDWT19_FINAL_PROJECT', False),
        'Week 2' => na('/DDWT19_FINAL_PROJECT/final/', False),
        'Overview' => na('/DDWT19_FINAL_PROJECT/final/login', True)
    ]);
    $navigation = get_navigation($nav_array,0);
    /* Page content */
    $page_subtitle = sprintf("Log in to your account");
    $page_content = "Login Below";

    /* Get Error msg from POST route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* choose template */
    include use_template('login');
}
/* Login POST */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/login/', 'post')) {
    /* Log in user */
    $feedback = login_user($db, $_POST);
    /* Redirect to my account screen*/
    redirect(sprintf('/DDWT19_FINAL_PROJECT/final/myaccount/?error_msg=%s',
    json_encode($feedback)));
}

/* Log out GET */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/logout', 'get')) {
    /*log out user */
    $feedback = logout_user();
    /* redirect to home page */
    redirect(sprintf('/DDWT19_FINAL_PROJECT/final/myaccount/?error_msg=%s',
    json_encode($feedback)));
}
else {
    http_response_code(404);
}
