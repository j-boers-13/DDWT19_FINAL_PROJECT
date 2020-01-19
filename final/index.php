<?php
/**
 * Controller
 * User: Jeroen Boers, Yoni Hollander, Quin Kroon
 * Date: 1/19/2020
 * Time: 14:23
 */

include 'model.php';
/* Connect to DB */
$db = connect_db('remotemysql.com', '1cJD522I73', '1cJD522I73','MHJUWcQxxb');
$nav_array = Array(
    1 => Array (
        'name' => 'Home',
        'url' => '/DDWT19_FINAL_PROJECT/final/'
    ),
    2 => Array(
        'name' => 'Rooms',
        'url' => '/DDWT19_FINAL_PROJECT/final/overview'
    ));

if(check_login() === true) {
    $nav_array[3] =  Array(
        'name' => 'My Account',
        'url' => '/DDWT19_FINAL_PROJECT/final/myaccount'
    );
    if(check_owner($db)) {
    $nav_array[4] =  Array(
        'name' => 'Add a room',
        'url' => '/DDWT19_FINAL_PROJECT/final/add'
    );}
    $nav_array[5] = Array(
        'name' => 'Log out',
        'url' => '/DDWT19_FINAL_PROJECT/final/logout');
}
else{
    $nav_array[6] =  Array(
        'name' => 'Register',
        'url' => '/DDWT19_FINAL_PROJECT/final/register'
    );
    $nav_array[7] =  Array(
        'name' => 'Log in',
        'url' => '/DDWT19_FINAL_PROJECT/final/login'
    );
}


/* Redudant code is added here */
/* Get Number of rooms */
$nbr_rooms = count_rooms($db);
/* Get Number of Users */
$nbr_users = count_users($db);
$nbr_owners = count_owners($db);
$nbr_tenants = count_tenants($db);
$nbr_optins = count_optins($db);

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
    $page_content = 'Latest added rooms:';
    $left_content = get_room_table(get_available_rooms($db, true), $db);


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
    $left_content = get_room_table(get_available_rooms($db, false), $db);

    /* Get error msg from remove post route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('main');
}
/* Owner overview page */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/myrooms/', 'get')) {

    /* Page info */
    $page_title = 'All your rooms';
    $breadcrumbs = get_breadcrumbs([
        'DDWT19' => na('/DDWT19_FINAL_PROJECT/', False),
        'final' => na('/DDWT19/final/', False),
        'My Rooms' => na('/DDWT19_FINAL_PROJECT/final/myrooms', True)
    ]);
    $navigation = get_navigation($nav_array, 2);

    /* Page content */
    $page_subtitle = 'The Overview of all the rooms you have posted';
    $page_content = 'Here you find all your rooms listed on ROOM.NET';
    $left_content = get_room_table(get_owner_rooms($db), $db);

    /* Get error msg from remove post route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('main');
}
/* Single Room */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/room', 'get')) {
    /* Get rooms from db */
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
    $page_subtitle = $room_info['city'];
    $page_content = $room_info['description'];
    $added_by = get_user_name($db, $room_info['owner_id']);
    $date_added = $room_info['created_at'];
    $is_owner = check_if_owner($room_info['owner_id']);
    $user_is_owner = check_owner($db);

    /* Get error msg from POST route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }
    /* Choose Template */
    include use_template('room');
}

/* profile page */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/profile/', 'get')) {
    $user_info = get_userinfo($db);
    $user_is_owner = check_owner($db);
    var_dump($user_info);

    /* Page info */
    $page_title = 'Profile';
    $breadcrumbs = get_breadcrumbs([
        'DDWT19' => na('/DDWT19_FINAL_PROJECT/', False),
        'final' => na('/DDWT19/final/', False),
        'Profile' => na('/DDWT19_FINAL_PROJECT/final/profile', True)
    ]);
    $navigation = get_navigation($nav_array, 2);


    /* Page content */
    $page_subtitle = 'Your profile';
    $page_content = 'Here you can look at your profile and edit information that might have changed';
    #$left_content = get_userinfo($db);
    $user = get_user_name($db, $_SESSION['user_id']);
    $nbr_rooms_by_owner = count_rooms_by_owner($db);

    /* Get error msg from remove post route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('profile');
}

/* Add room GET */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/add/', 'get')) {
    /* check if logged in */
    if ( !check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/login/');
    }
    if ( !check_owner($db) ) {
        $feedback = [
            'type' => 'error',
            'message' => 'Tenants can\'t add or edit rooms.'
        ];;
        /* Redirect to room GET route */
        redirect(sprintf('/DDWT19_FINAL_PROJECT/final/?error_msg=%s',
            json_encode($feedback)));
    }

    /* Page info */
    $page_title = 'Add room';
    $breadcrumbs = get_breadcrumbs([
        'ROOM.NET' => na('/DDWT19_FINAL_PROJECT/', False),
        'Rooms' => na('/DDWT19_FINAL_PROJECT/final/', False),
        'Add Room' => na('/DDWT19_FINAL_PROJECT/final/new/', True)
    ]);
    $navigation = get_navigation($nav_array,5);

    /* Page content */
    $page_subtitle = 'Add your room';
    $page_content = 'Fill in the details of the room you have available.';
    $submit_btn = "Add Room";
    $form_action = '/DDWT19_FINAL_PROJECT/final/add/';
    /* Get error msg from POST route */
    if (isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET["error_msg"]);
    }

    /* Choose Template */
    include use_template('new');
}

/* Add room POST */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/add/', 'post')) {
    /* check if logged in */
    if ( !check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/login/');
    }
    /* add room to database */
    $feedback = add_room($db, $_POST);
    /* Redirect to room GET route */
    redirect(sprintf('/DDWT19_FINAL_PROJECT/final/add/?error_msg=%s',
        json_encode($feedback)));
}

/* Edit room GET */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/edit/', 'get')) {
    /* check if logged in */
    if ( !check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/login/');
    }
    if ( !check_owner($db) ) {
        $feedback = [
            'type' => 'error',
            'message' => 'Tenants can\'t add rooms.'
        ];;
        /* Redirect to room GET route */
        redirect(sprintf('/DDWT19_FINAL_PROJECT/final/?error_msg=%s',
            json_encode($feedback)));
    }

    /* Get room info from db */
    $room_id = $_GET['room_id'];
    $room_info = get_roominfo($db, $room_id);

    /* Page info */
    $page_title = 'Edit Room';
    $breadcrumbs = get_breadcrumbs([
        'DDWT19' => na('/DDWT19_FINAL_PROJECT/', False),
        'Week 2' => na('/DDWT19_FINAL_PROJECT/final/', False),
        sprintf("Edit Room %s", $room_info['street_address']) => na('/DDWT19_FINAL_PROJECT/final/edit/', True)
    ]);
    $navigation = get_navigation($nav_array,6);

    /* Page content */
    $page_subtitle = sprintf("Edit %s", $room_info['street_address']);
    $page_content = 'Edit the room below.';
    $submit_btn = "Edit Room";
    $form_action = '/DDWT19_FINAL_PROJECT/final/edit/';


    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('new');
}

/* Edit room POST */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/edit/', 'post')) {
    /* check if logged in */
    if ( !check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/login/');
    }
    if ( !check_owner($db) ) {
        $feedback = [
            'type' => 'error',
            'message' => 'Tenants can\'t add rooms.'
        ];;
        /* Redirect to room GET route */
        redirect(sprintf('/DDWT19_FINAL_PROJECT/final/edit?error_msg=%s',
            json_encode($feedback)));
    }
    /* Update room in database */
    $feedback = update_room($db, $_POST);

    /* Get room info from db */
    $room_id = $_POST['room_id'];
    $room_info = get_roominfo($db, $room_id);

    /* Redirect to room get route */
    redirect(sprintf('/DDWT19_FINAL_PROJECT/final/room/?error_msg=%s&room_id=%s',
        json_encode($feedback), $_POST['room_id']));
}

/* Remove room POST */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/remove/', 'post')) {
    /* check if logged in */
    if ( !check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/login/');
    }

    /* Remove room in database */
    $room_id = $_POST['room_id'];
    $feedback = remove_room($db, $room_id);

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
    $user = get_user_name($db, $_SESSION['user_id']);
    $nbr_rooms_by_owner = count_rooms_by_owner($db);
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
    $submit_btn = "Register Account";
    $form_action = "/DDWT19_FINAL_PROJECT/final/register/";

    /* Get Error msg from POST route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* choose template */
    include use_template('register');
}
/* Register POST */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/register/', 'post')) {
    /* Update register in database */
    $error_msg = register_user($db, $_POST);

    /* Redirect to register get route */
    redirect(sprintf('/DDWT19_FINAL_PROJECT/final/register/?error_msg=%s',
    json_encode($error_msg)));
}

/* edit profile GET */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/profile/edit', 'get')) {
    /* check if logged in */
    if ( !check_login()) {
        redirect('/DDWT19_FINAL_PROJECT/final/login/');
    }

    /* Get room info from db */
    $user_info = get_userinfo($db);

    /* Page info */
    $page_title = 'Edit Profile';
    $navigation = get_navigation($nav_array,6);

    /* Page content */
    $page_subtitle = sprintf("Edit %s", $user_info['username']);
    $page_content = 'Edit the profile below.';
    $submit_btn = "Edit Profile";
    $form_action = '/DDWT19_FINAL_PROJECT/final/profile/edit/';


    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('register');
}

/* Edit profile POST */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/profile/edit', 'post')) {
    /* Update register in database */
    $error_msg = update_profile($db, $_POST);

    /* Redirect to register get route */
    redirect(sprintf('/DDWT19_FINAL_PROJECT/final/profile/edit/?error_msg=%s',
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

/* get opt-ins page */
elseif (new_route('/DDWT19_FINAL_PROJECT/final/optins/', 'get')) {

    /* Page info */

    $page_title = 'Opt-ins';
    $breadcrumbs = get_breadcrumbs([
        'DDWT19' => na('/DDWT19_FINAL_PROJECT/', False),
        'final' => na('/DDWT19/final/', False),
        'Opt-ins' => na('/DDWT19_FINAL_PROJECT/final/optins', True)
    ]);
    $navigation = get_navigation($nav_array, 2);

    /* Page content */
    $page_subtitle = 'The overview of all your opt-ins';
    $page_content = 'This is an overview of all the rooms you opted-in for';
    $left_content = get_optin_table(get_optin_rooms($db), $db);

    /* Get error msg from remove post route */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('main');
}



else {
    http_response_code(404);
}

