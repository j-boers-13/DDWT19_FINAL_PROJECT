<?php
/**
 * Model
 * User: reinardvandalen
 * Date: 05-11-18
 * Time: 15:25
 */

/* Enable error reporting */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Connects to the database using PDO
 * @param string $host database host
 * @param string $db database name
 * @param string $user database user
 * @param string $pass database password
 * @return pdo object
 */
function connect_db($host, $db, $user, $pass){
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        echo sprintf("Failed to connect. %s",$e->getMessage());
    }
    return $pdo;
}

/**
 * Register a user
 * @param object $pdo database object
 * @param array $form_action with POST data
 * @return array
 */
function register_user($pdo, $form_data){
    /* check if all fields are set */
    if (
        empty($form_data['username']) or
        empty($form_data['password']) or
        empty($form_data['firstname']) or
        empty($form_data['lastname']) or
        empty($form_data['birthdate']) or
        empty($form_data['profession']) or
        empty($form_data['languages']) or
        empty($form_data['telephone'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'You Should enter a username, password, first- and last name.'
        ];
    }
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username =?');
        $stmt->execute([$form_data['username']]);
        $user_exists = $stmt->rowCount();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }
    /* Return Error message for Existing username */
    if (!empty($user_exists) ) {
        return [
            'type' => 'danger',
            'message' => 'The Username you entered does already exist!'
        ];
    }
    /* Hash password */
    $password = password_hash($form_data['password'], PASSWORD_DEFAULT);
    /* Save user to the database */
    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, password, firstname, lastname, birthdate, profession, languages, biography, telephone, email, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$form_data['username'], $password, $form_data['firstname'], $form_data['lastname'], $form_data['birthdate'],$form_data['profession'], $form_data['languages'], $form_data['biography'], $form_data['telephone'], $form_data['email'], $form_data['role']]);
        $user_id = $pdo->lastInsertId();
    } catch (\PDOException $e) {
        return [
            'type' => 'Danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }
    /* Login user and redirect */
    session_start();
    $_SESSION['user_id'] = $user_id;
    $feedback = [
        'type' => 'success',
        'message' => sprintf('%s, your account was successfully created!',
         get_user_name($pdo, $_SESSION['user_id']))
    ];
    redirect(sprintf('/DDWT19_FINAL_PROJECT/final/myaccount/?error_msg=%s',
    json_encode($feedback)));
}

/**
 * Login user
 * @param object $pdo database object
 * @param array $form_data with POST data
 * @return array
 */
function login_user($pdo, $form_data){
    /*check if all fields are set */
    if (
        empty($form_data ['username']) or
        empty($form_data['password'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'You should enter a username and password.'
        ];
    }
    /* Check if user exists */
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$form_data['username']]);
        $user_info = $stmt->fetch();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }
    /* Return error message for wrong username */
    if ( empty($user_info) ) {
        $feedback = [
            'type' => 'danger',
            'message' => 'The username you entered is does not exist!'
        ];
        redirect(sprintf('/DDWT19_FINAL_PROJECT/final/login/?error_msg=%s',
            json_encode($feedback)));
    }
    /* Check password */
    if ( !password_verify($form_data['password'], $user_info['password'])){
        $feedback = [
            'type' => 'danger',
            'message' => 'The password you entered is incorrect!'
        ];
        redirect(sprintf('/DDWT19_FINAL_PROJECT/final/login/?error_msg=%s',
        json_encode($feedback)));
    }
    else {
        session_start();
        $_SESSION['user_id'] = $user_info['id'];
        $feedback = [
            'type' => 'success',
            'message' => sprintf('%s, you were logged in successfully!',
                get_user_name($pdo, $_SESSION['user_id']))
        ];
        redirect(sprintf('/DDWT19_FINAL_PROJECT/final/myaccount/?error_msg=%s',
            json_encode($feedback)));
    }
}

/**
 * Updates a users profile in the database using post array
 * @param object $pdo db object
 * @param array $user_info post array
 * @return array
 */
function update_profile($pdo, $user_info){
    /* Check if all fields are set */
    if (
        empty($user_info['firstname']) or
        empty($user_info['lastname']) or
        empty($user_info['profession']) or
        empty($user_info['languages']) or
        empty($user_info['telephone']) or
        empty($user_info['email']) or
        empty($user_info['biography'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Not all fields were filled in.'
        ];
    }
    $user_data = get_userinfo($pdo);
    if ($_SESSION['user_id'] !== $user_data['id']){
        return[
            'type' => 'danger',
            'message' => 'There was an error. You cannot edit this room'
        ];
    }

    /* check if email already exists before editing */
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$user_data['id']]);
    $user = $stmt->fetch();
    $current_email = $user['email'];

    /* Check if room already exists */
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$user_info['email']]);
    $user = $stmt->fetch();
    if ($user_data['email'] == $user['email'] and $user['email'] != $current_email){
        return [
            'type' => 'danger',
            'message' => sprintf("your email cannot be changed. Since %s already exists for another account.", $user_info['email'])
        ];
    }

    /* Update Serie */
    $stmt = $pdo->prepare('UPDATE users SET firstname = ?, lastname = ?, profession = ?, languages = ?, telephone = ?, email = ?, biography = ? WHERE id = ?');
    $stmt->execute([
        $user_info['firstname'],
        $user_info['lastname'],
        $user_info['profession'],
        $user_info['languages'],
        $user_info['telephone'],
        $user_info['email'],
        $user_info['biography'],
        $_SESSION['user_id']
    ]);
    $updated = $stmt->rowCount();
    if ($updated ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Profile '%s' was edited!", $user_data['username'])
        ];
    }
    else {
        return [
            'type' => 'danger',
            'message' => 'The room was not edited. No changes were detected.'
        ];
    }
}

/**
 * Removes a room with a specific room-ID
 * @param object $pdo db object
 * @param int $room_id id of the to be deleted room
 * @return array
 */
function remove_profile($pdo, $user_id){
    /* Get room info */
    $user_info = get_other_userinfo($user_id, $pdo);


    /* Delete Room */
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $deleted = $stmt->rowCount();
    if ($deleted ==  1) {
        if (isset($_SESSION['user_id']) ) {
            session_destroy();
            $feedback = [
                'type' => 'success',
                'message' => "You're account was removed succesfully"
            ];
        } else {
            $feedback = [
                'type' => 'danger',
                'message' => 'you"re account was deleted but the log out seems to have failed please try logging out to experience the website bug-free'
            ];
        }
        redirect(sprintf('/DDWT19_FINAL_PROJECT/final/?error_msg=%s',
            json_encode($feedback)));
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'An error occurred. Your profile was not removed.'
        ];
    }
}

/**
 * Check Login
 *
 */
function check_login(){
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_SESSION['user_id'])){
        return True;
    } else {
        return False;
    }
}

function check_owner($pdo) {
    if (isset($_SESSION['user_id'])){
        if (get_user_role($_SESSION['user_id'], $pdo) == "Owner") {
            return True;
        }
        else {
            return False;
        }
    }
    else {
        return null;
    }
}

function check_if_sender($pdo, $tenant_id) {
    if (isset($_SESSION['user_id'])){
        if ($_SESSION['user_id'] === $tenant_id) {
            return True;
        }
        else {
            return False;
        }
    }
    else {
        return null;
    }
}

function check_if_owner($owner_id) {
    if (isset($_SESSION['user_id'])){
        if ($_SESSION['user_id'] === $owner_id) {
            return True;
        }
        else {
            return False;
        }
    }
    else {
        return null;
    }
}

function get_user_role($user_id, $pdo){
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch();
    return $user_info['role'];
}


function check_room_owners($room_ownerid){
    if ($_SESSION['user_id'] == $room_ownerid){
        return True;
    }
}

/**
 * Get the first and last name of the a user based on a specific user_id
 * @param object $pdo database object
 * @param int $user_id the user_id from the dstabase
 *
 */
function get_user_name($pdo, $user_id){
    $stmt = $pdo->prepare("SELECT firstname, lastname FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch();

   /* creates a string with user first name and last name */

    $user_name = $user_info['firstname'] . ' ' . $user_info['lastname'];
    return $user_name;
}
/**
 * Generates an array with user information
 * @param object $pdo db object
 * @param int $user_id from the user
 * @return mixed
 */
function get_userinfo($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user_info = $stmt->fetch();
    $user_info_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($user_info as $key => $value){
        $user_info_exp[$key] = htmlspecialchars($value);
    }
    return $user_info_exp;
}

function get_other_userinfo($user_id, $pdo) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch();
    $user_info_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($user_info as $key => $value){
        $user_info_exp[$key] = htmlspecialchars($value);
    }
    return $user_info_exp;
}

/**
 * Check if the route exist
 * @param string $route_uri URI to be matched
 * @param string $request_type request method
 * @return bool
 *
 */
function new_route($route_uri, $request_type){
    $route_uri_expl = array_filter(explode('/', $route_uri));
    $current_path_expl = array_filter(explode('/',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    if ($route_uri_expl == $current_path_expl && $_SERVER['REQUEST_METHOD'] == strtoupper($request_type)) {
        return True;
    }
}

/**
 * Creates a new navigation array item using url and active status
 * @param string $url The url of the navigation item
 * @param bool $active Set the navigation item to active or inactive
 * @return array
 */
function na($url, $active){
    return [$url, $active];
}

/**
 * Creates filename to the template
 * @param string $template filename of the template without extension
 * @return string
 */
function use_template($template){
    $template_doc = sprintf("views/%s.php", $template);
    return $template_doc;
}

/**
 * Creates breadcrumb HTML code using given array
 * @param array $breadcrumbs Array with as Key the page name and as Value the corresponding url
 * @return string html code that represents the breadcrumbs
 */
function get_breadcrumbs($breadcrumbs) {
    $breadcrumbs_exp = '
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">';
    foreach ($breadcrumbs as $name => $info) {
        if ($info[1]){
            $breadcrumbs_exp .= '<li class="breadcrumb-item active" aria-current="page">'.$name.'</li>';
        }else{
            $breadcrumbs_exp .= '<li class="breadcrumb-item"><a href="'.$info[0].'">'.$name.'</a></li>';
        }
    }
    $breadcrumbs_exp .= '
    </ol>
    </nav>';
    return $breadcrumbs_exp;
}

/**
 * Creates navigation HTML code using given array
 * @param array $navigation Array with as Key the page name and as Value the corresponding url
 * @return string html code that represents the navigation
 */
function get_navigation($template,$active_id){
    $navigation_exp = '
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #c3babc;">
    <a class="navbar-brand">ROOM.NET</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">';
    foreach ($template as $id => $array) {
        if ($id[$active_id]){
            $navigation_exp .= '<li class="nav-item active">';
            $navigation_exp .= '<a class="nav-link" href="'.$array['url'].'">'.$array['name'].'</a>';
        }else{
            $navigation_exp .= '<li class="nav-item">';
            $navigation_exp .= '<a class="nav-link" href="'.$array['url'].'">'.$array['name'].'</a>';
        }

        $navigation_exp .= '</li>';
    }
    $navigation_exp .= '
    </ul>
    </div>
    </nav>';
    return $navigation_exp;
}

/**
 * Creates a Bootstrap table with a list of rooms
 * @param array $rooms with rooms from the db
 * @return string
 */
function get_room_table($rooms,$pdo){
    $table_exp = '
    <div class="table-responsive">
    <table class="table table-hover">
    <thead
    <tr>
        <th scope="col">Address</th>
        <th scope="col">Price</th>
        <th scope="col">Temporary</th>
        <th scope="col">Square Meters</th>
        <th scope="col">Added By</th>
        <th scope="col">Date Added</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>';
    foreach($rooms as $key => $value){
        $table_exp .= '
        <tr>
            <th scope="row">'.$value['street_address'].'</th>
            <td>'.$value['price'].'</td>
            <td>'.$value['temporary'].'</td>
            <td>'.$value['square_meters'].'</td>
            <td>'.get_user_name($pdo,$value['owner_id']).'</td>
            <td>'.date_format(date_create($value['created_at']), "F j").'</td>
            <td><a href="/DDWT19_FINAL_PROJECT/final/room/?room_id='.$value['id'].'" role="button" class="btn btn-basic">More info</a></td>
        </tr>
        ';
    }
    $table_exp .= '
    </tbody>
    </table>
    </div>
    ';
    return $table_exp;
}

/**
 * Logout user
 */
function logout_user(){
    session_start();
    if (isset($_SESSION['user_id']) ) {
        session_destroy();
        $feedback = [
            'type' => 'success',
            'message' => 'You were logged out succesfully'
        ];
    } else {
        $feedback = [
            'type' => 'danger',
            'message' => 'You were not logged out succesfully, you might be logged out already!'
            ];
    }
    redirect(sprintf('/DDWT19_FINAL_PROJECT/final/?error_msg=%s',
        json_encode($feedback)));
}

/**
 * Pretty Print Array
 * @param $input
 */
function p_print($input){
    echo '<pre>';
    print_r($input);
    echo '</pre>';
}

/**
 * Get array with all listed series from the database
 * @param object $pdo database object,
 * @param bool $limit quantity of rows (if false then show all rows)
 * @return array Associative array with all series
 */
function get_available_rooms($pdo, $limit){
    if($limit === true) {
        $stmt = $pdo->prepare('SELECT rooms.*, address.street_address, address.city, address.zipcode FROM rooms LEFT OUTER JOIN address ON rooms.address_id = address.id  WHERE is_available = 1 ORDER BY rooms.id DESC LIMIT 5');
    }
    else{
        $stmt = $pdo->prepare('SELECT rooms.*, address.street_address, address.city, address.zipcode FROM rooms LEFT OUTER JOIN address ON rooms.address_id = address.id  WHERE is_available = 1 ORDER BY rooms.id DESC');
    }
    $stmt->execute();
    $rooms = $stmt->fetchAll();
    $room_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($rooms as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $room_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $room_exp;
}

/**
 * Generates an array with room information
 * @param object $pdo db object
 * @param int $room_id id from the serie
 * @return mixed
 */
function get_roominfo($pdo, $room_id){
    $stmt = $pdo->prepare('SELECT rooms.*, address.street_address, address.city, address.zipcode FROM rooms LEFT OUTER JOIN address ON rooms.address_id = address.id  WHERE rooms.id = ?');
    $stmt->execute([$room_id]);
    $room_info = $stmt->fetch();
    $room_info_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($room_info as $key => $value){
        $room_info_exp[$key] = htmlspecialchars($value);
    }
    return $room_info_exp;
}

function get_optininfo($pdo, $optin_id){
    $stmt = $pdo->prepare('SELECT opt_ins.*, rooms.owner_id, address.street_address, address.city, address.zipcode FROM opt_ins JOIN rooms ON opt_ins.room_id = rooms.id JOIN address ON rooms.address_id = address.id WHERE opt_ins.id = ?');
    $stmt->execute([$optin_id]);
    $optin_info = $stmt->fetch();
    $optin_info_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($optin_info as $key => $value){
        $optin_info_exp[$key] = htmlspecialchars($value);
    }
    return $optin_info_exp;
}

function get_inviteinfo($pdo, $invite_id){
    $stmt = $pdo->prepare('SELECT viewing_invites.*, rooms.owner_id, address.street_address, address.city, address.zipcode FROM viewing_invites JOIN rooms ON viewing_invites.room_id = rooms.id JOIN address ON rooms.address_id = address.id WHERE viewing_invites.id = ?');
    $stmt->execute([$invite_id]);
    $optin_info = $stmt->fetch();
    $optin_info_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($optin_info as $key => $value){
        $optin_info_exp[$key] = htmlspecialchars($value);
    }
    return $optin_info_exp;
}

/**
 * Get array with all rooms added by current user
 * @param object $pdo database object
 * @return array Associative array with all series
 */
function get_owner_rooms($pdo){
    $stmt = $pdo->prepare('SELECT rooms.*, address.street_address, address.city, address.zipcode FROM rooms LEFT OUTER JOIN address ON rooms.address_id = address.id  WHERE rooms.owner_id = ? ');
    $stmt->execute([$_SESSION['user_id']]);
    $rooms = $stmt->fetchAll();
    $room_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($rooms as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $room_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $room_exp;
}

/**
 * count  all listed rooms by owner_id
 * @param object $pdo database object
 * @return array Associative array with all listed rooms by owner
 */
function count_rooms_by_owner($pdo){
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE owner_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $rooms = $stmt->rowCount();
    return $rooms;
}

/**
 * Creates HTML alert code with information about the success or failure
 * @param array $feedback Array with keys 'type' and 'message'.
 * @return string
 */
function get_error($feedback){
  $feedback = json_decode($feedback, True);
    $error_exp = '
        <div class="alert alert-'.$feedback['type'].'" role="alert">
            '.$feedback['message'].'
        </div>';
    return $error_exp;
}

/**
 * Get array with the rooms a tenant has opted-in for
 * @param object $pdo database object
 * @return array Associative array with all series
 */
function get_tenant_optins($pdo){
    if(!isset($_SESSION)) {
        session_start();
    }
    $stmt = $pdo->prepare('SELECT opt_ins.*, rooms.owner_id, rooms.square_meters, address.street_address, address.city, address.zipcode FROM opt_ins INNER JOIN rooms ON opt_ins.room_id = rooms.id JOIN address ON rooms.address_id = address.id WHERE tenant_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $optins = $stmt->fetchAll();
    $optins_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($optins as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $optins_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $optins_exp;
}

/**
 * Get array with the rooms a tenant has opted-in for
 * @param object $pdo database object
 * @return array Associative array with all series
 */
function get_tenant_invites($pdo){
    if(!isset($_SESSION)) {
        session_start();
    }
    $stmt = $pdo->prepare('SELECT viewing_invites.*, rooms.square_meters, address.street_address, address.city, address.zipcode FROM viewing_invites INNER JOIN rooms ON viewing_invites.room_id = rooms.id JOIN address ON rooms.address_id = address.id WHERE tenant_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $optins = $stmt->fetchAll();
    $optins_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($optins as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $optins_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $optins_exp;
}

/**
 * Get array with the rooms a user has opted-in for
 * @param object $pdo database object
 * @return array Associative array with all series
 */
function get_owner_optins($pdo){
    if(!isset($_SESSION)) {
        session_start();
    }
    $stmt = $pdo->prepare('SELECT opt_ins.*, rooms.owner_id, rooms.square_meters, address.street_address, address.city, address.zipcode FROM opt_ins INNER JOIN rooms ON opt_ins.room_id = rooms.id JOIN address ON rooms.address_id = address.id WHERE owner_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $optins = $stmt->fetchAll();
    $optins_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($optins as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $optins_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $optins_exp;
}

/**
 * Get array with the rooms a user has opted-in for
 * @param object $pdo database object
 * @return array Associative array with all series
 */
function get_owner_invites($pdo){
    if(!isset($_SESSION)) {
        session_start();
    }
    $stmt = $pdo->prepare('SELECT viewing_invites.*, rooms.square_meters, address.street_address, address.city, address.zipcode FROM viewing_invites INNER JOIN rooms ON viewing_invites.room_id = rooms.id JOIN address ON rooms.address_id = address.id WHERE viewing_invites.owner_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $optins = $stmt->fetchAll();
    $optins_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($optins as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $optins_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $optins_exp;
}

/**
 * Creates a Bootstrap table with a list of opted-in rooms for the user
 * @param array $optins with rooms from the db
 * @param object $pdo database object
 * @return string
 */
function get_optin_table($optins,$pdo, $is_owner){
    if ($is_owner) {
        $table_exp = '
        <table class="table table-hover">
        <thead
        <tr>
            <th scope="col">Address</th>
            <th scope="col">Square Meters</th>
            <th scope="col">Sent by</th>
        </tr>
        </thead>
        <tbody>';

        foreach ($optins as $key => $value) {
            $table_exp .= '
            <tr>
                <th scope="row">' . $value['street_address'] . '</th>
                <td>' . $value['square_meters'] . '</td>
                <td>' . get_user_name($pdo, $value['tenant_id']) . '</td>
                <td><a href="/DDWT19_FINAL_PROJECT/final/optin/?optin_id=' . $value['id'] . '" role="button" class="btn btn-primary">Show message and respond</a></td>
                <td><a href="/DDWT19_FINAL_PROJECT/final/profile/?user_id=' . $value['tenant_id'] . '" role="button" class="btn btn-primary">Show profile</a></td>

            </tr>
            ';
        }
        $table_exp .= '
        </tbody>
        </table>
        ';
    }
    else {
        $table_exp = '
        <table class="table table-hover">
        <thead
        <tr>
            <th scope="col">Address</th>
            <th scope="col">Square Meters</th>
            <th scope="col">Added By</th>
        </tr>
        </thead>
        <tbody>';

        foreach ($optins as $key => $value) {
            $table_exp .= '
            <tr>
                <th scope="row">' . $value['street_address'] . '</th>
                <td>' . $value['square_meters'] . '</td>
                <td>' . get_user_name($pdo, $value['owner_id']) . '</td>
                <td><a href="/DDWT19_FINAL_PROJECT/final/optin/?optin_id=' . $value['id'] . '" role="button" class="btn btn-primary">Show</a></td>
                <td><a href="/DDWT19_FINAL_PROJECT/final/profile/?user_id=' . $value['owner_id'] . '" role="button" class="btn btn-primary">Show profile</a></td>

            </tr>
            ';
        }
        $table_exp .= '
        </tbody>
        </table>
        ';
    }
    return $table_exp;
}

/**
 * Creates a Bootstrap table with a list of viewing invites for the user
 * @param array $invites with rooms from the db
 * @param object $pdo database object
 * @return string
 */
function get_invite_table($invites ,$pdo, $is_owner){
    if ($is_owner) {
        $table_exp = '
        <table class="table table-hover">
        <thead
        <tr>
            <th scope="col">Address</th>
            <th scope="col">Square Meters</th>
            <th scope="col">Viewing Date</th>
            <th scope="col">Sent by</th>
        </tr>
        </thead>
        <tbody>';

        foreach ($invites as $key => $value) {
            $table_exp .= '
            <tr>
                <th scope="row">' . $value['street_address'] . '</th>
                <td>' . $value['square_meters'] . '</td>
                <td>'. $value['date'] .'</td>
                <td>' . get_user_name($pdo, $value['tenant_id']) . '</td>
                <td><a href="/DDWT19_FINAL_PROJECT/final/room/?room_id=' . $value['room_id'] . '" role="button" class="btn btn-primary">Show room</a></td>
                <td><a href="/DDWT19_FINAL_PROJECT/final/profile/?user_id=' . $value['tenant_id'] . '" role="button" class="btn btn-primary">Show profile</a></td>
                <td><form action="/DDWT19_FINAL_PROJECT/final/invites/remove" method="POST">
                            <input type="hidden" value='. $value['id'] .' name="invite_id">
                            <button type="submit" class="btn btn-danger">Remove</button>
                </form></td>

            </tr>
            ';
        }
        $table_exp .= '
        </tbody>
        </table>
        ';
    }
    else {
        $table_exp = '
        <table class="table table-hover">
        <thead
        <tr>
            <th scope="col">Address</th>
            <th scope="col">Square Meters</th>
            <th scope="col">Added By</th>
        </tr>
        </thead>
        <tbody>';

        foreach ($invites as $key => $value) {
            $table_exp .= '
            <tr>
                <th scope="row">' . $value['street_address'] . '</th>
                <td>' . $value['square_meters'] . '</td>
                <td>'. $value['date'] .'</td>
                <td>' . get_user_name($pdo, $value['owner_id']) . '</td>
                <td><a href="/DDWT19_FINAL_PROJECT/final/room/?room_id=' . $value['room_id'] . '" role="button" class="btn btn-primary">Show invite</a></td>
                <td><a href="/DDWT19_FINAL_PROJECT/final/profile/?user_id=' . $value['owner_id'] . '" role="button" class="btn btn-primary">Show profile</a></td>

            </tr>
            ';
        }
        $table_exp .= '
        </tbody>
        </table>
        ';
    }
    return $table_exp;
}


/**
 * Add room to the database
 * @param object $pdo db object
 * @param array $room_info post array
 * @return array with message feedback
 */
function add_room($pdo, $room_info){
    /* Check data type */
    if (!is_numeric($room_info['square_meters']) or !is_numeric($room_info['price'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You should enter a number in the fields for square meters and price.'
        ];
    }
    /* Check if all fields are set */
    if (
        empty($room_info['street_address']) or
        empty($room_info['city']) or
        empty($room_info['zipcode']) or
        empty($room_info['description']) or
        empty($room_info['price']) or
        empty($room_info['temporary']) or
        empty($room_info['square_meters'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Not all fields were filled in.'
        ];
    }
    if (!check_owner($pdo)){
        return[
            'type' => 'danger',
            'message' => 'There was an error. You cannot add series as a tenant.'
        ];

    }

    /* Check if room already exists */
    $stmt = $pdo->prepare('SELECT rooms.*, address.street_address, address.city, address.zipcode FROM rooms LEFT OUTER JOIN address ON rooms.address_id = address.id WHERE street_address = ?');
    $stmt->execute([$room_info['street_address']]);
    $room = $stmt->rowCount();
    if ($room){
        return [
            'type' => 'danger',
            'message' => 'This series was already added.'
        ];
    }

    /* Add Room */
    $stmt = $pdo->prepare("INSERT INTO address (street_address, city, zipcode) VALUES (?, ?, ?)");
    $stmt->execute([
        $room_info['street_address'],
        $room_info['city'],
        $room_info['zipcode'],
    ]);
    $address_id = $pdo->lastInsertId();
    $inserted = $stmt->rowCount();
    if ($inserted ==  1) {
        $stmt = $pdo->prepare("INSERT INTO rooms (description, price, temporary, square_meters, owner_id, address_id) VALUES (?, ?, ?, ?, ?, ? )");
        $stmt->execute([
            $room_info['description'],
            $room_info['price'],
            $room_info['temporary'],
            $room_info['square_meters'],
            $_SESSION['user_id'],
            $address_id
        ]);
        $inserted = $stmt->rowCount();
        if ($inserted ==  1) {
            return [
                'type' => 'success',
                'message' => sprintf("Room '%s' added to Room Overview.", $room_info['street_address'])
            ];
        }
        else {
            return [
                'type' => 'danger',
                'message' => 'There was an error. The series was not added. Try it again.'
            ];
        }
    }     else {
        return [
            'type' => 'danger',
            'message' => 'There was an error executing the sql statement for the address bar. check if the inputs are correct'
        ];
    }
}

function send_optin($pdo, $opt_in_form)
{
    /* Check if all fields are set */
    if (
        empty($opt_in_form['room_id']) or
        empty($opt_in_form['message'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Not all fields were filled in.'
        ];
    }
    if (check_owner($pdo)) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You cannot opt-in as an owner.'
        ];

    }

    /* Check if room already exists */
    $stmt = $pdo->prepare('SELECT * FROM opt_ins WHERE room_id = ? AND tenant_id = ?');
    $stmt->execute([$opt_in_form['room_id'], $_SESSION['user_id']]);
    $room = $stmt->rowCount();
    if ($room) {
        return [
            'type' => 'danger',
            'message' => 'You already opted into this room.'
        ];
    }

    /* Add Opt-in */
    $stmt = $pdo->prepare("INSERT INTO opt_ins (room_id, tenant_id, message) VALUES (?, ?, ?)");
    $stmt->execute([
        $opt_in_form['room_id'],
        $_SESSION['user_id'],
        $opt_in_form['message'],
    ]);

    $inserted = $stmt->rowCount();
    if ($inserted == 1) {
        return [
            'type' => 'success',
            'message' =>"Optin sent!"
        ];
    } else {
        return [
            'type' => 'danger',
            'message' => 'There was an error. The series was not added. Try it again.'
        ];
    }
}


function send_viewing_invite($pdo, $invite_form)
{
    /* Check if all fields are set */
    if (
        empty($invite_form['room_id']) or
        empty($invite_form['date'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Not all fields were filled in.'
        ];
    }
    $room = get_roominfo($pdo, $invite_form['room_id']);
    if (!check_if_owner($room['owner_id'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You can\'t invite someone for this room.'
        ];

    }

    /* Check if room already exists */
    $stmt = $pdo->prepare('SELECT * FROM viewing_invites WHERE tenant_id = ? AND room_id = ?');
    $stmt->execute([$invite_form['tenant_id'], $invite_form['room_id']]);
    $invite = $stmt->rowCount();
    if ($invite) {
        return [
            'type' => 'danger',
            'message' => 'You already invited this tenant for this room.'
        ];
    }

    /* Add Opt-in */
    $stmt = $pdo->prepare("INSERT INTO viewing_invites (room_id, tenant_id, owner_id, date) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $invite_form['room_id'],
        $invite_form['tenant_id'],
        $_SESSION['user_id'],
        $invite_form['date']
    ]);

    $inserted = $stmt->rowCount();
    if ($inserted == 1) {
        return [
            'type' => 'success',
            'message' =>"Invite sent!"
        ];
    } else {
        return [
            'type' => 'danger',
            'message' => 'There was an error. The invite was not sent. Try it again.'
        ];
    }
}


/**
 * Updates a room in the database using post array
 * @param object $pdo db object
 * @param array $room_info post array
 * @return array
 */
function update_room($pdo, $room_info){
    /* Check data type */
    if (!is_numeric($room_info['square_meters']) or !is_numeric($room_info['price'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You should enter a number in the fields for square meters, and price.'
        ];
    }
    /* Check if all fields are set */
    if (
        empty($room_info['street_address']) or
        empty($room_info['city']) or
        empty($room_info['zipcode']) or
        empty($room_info['description']) or
        empty($room_info['price']) or
        empty($room_info['temporary']) or
        empty($room_info['square_meters']) or
        empty($room_info['room_id'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Not all fields were filled in.'
        ];
    }

    /* Get current room name */
    $stmt = $pdo->prepare('SELECT rooms.*, address.street_address, address.city, address.zipcode FROM rooms LEFT OUTER JOIN address ON rooms.address_id = address.id WHERE rooms.id = ?');
    $stmt->execute([$room_info['room_id']]);
    $room = $stmt->fetch();
    if ($_SESSION['user_id'] !== $room['owner_id']){
        return[
            'type' => 'danger',
            'message' => 'There was an error. You cannot edit this room'
        ];
    }
    $current_address = $room['street_address'];

    /* Check if room already exists */
    $stmt = $pdo->prepare('SELECT rooms.*, address.street_address, address.city, address.zipcode FROM rooms LEFT OUTER JOIN address ON rooms.address_id = address.id WHERE street_address = ?');
    $stmt->execute([$room_info['street_address']]);
    $room = $stmt->fetch();
    if ($room_info['street_address'] == $room['street_address'] and $room['street_address'] != $current_address){
        return [
            'type' => 'danger',
            'message' => sprintf("The address of the room cannot be changed. %s already exists.", $room_info['street_address'])
        ];
    }

    /* Update Room */
    $pdo->prepare('ALTER TABLE rooms DROP FOREIGN KEY FK_address');
    $stmt1 = $pdo->prepare('UPDATE rooms SET description = ?, price = ?, square_meters = ?, temporary = ?, owner_id = ? WHERE id = ?');
    $stmt1->execute([
        $room_info['description'],
        $room_info['price'],
        $room_info['square_meters'],
        $room_info['temporary'],
        $_SESSION['user_id'],
        $room_info['room_id']
    ]);
    $updated = $stmt1->rowCount();
    $stmt2 = $pdo->prepare('UPDATE address SET street_address = ?, city = ?, zipcode = ? WHERE id = ?');
    $stmt2->execute([
        $room_info['street_address'],
        $room_info['city'],
        $room_info['zipcode'],
        $room['address_id'],
    ]);
    $pdo->prepare('ALTER TABLE rooms ADD CONSTRAINT FK_address FOREIGN KEY (address_id) REFERENCES address(id);');
    $updated = $updated + $stmt->rowCount();
    if ($updated >=  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Room '%s' was edited!", $room_info['street_address'])
        ];
    }
    else {
        return [
            'type' => 'danger',
            'message' => 'The room was not edited. No changes were detected.'
        ];
    }
}

/**
 * Removes a room with a specific room-ID
 * @param object $pdo db object
 * @param int $room_id id of the to be deleted room
 * @return array
 */
function remove_room($pdo, $room_id){
    /* Get room info */
    $room_info = get_roominfo($pdo, $room_id);

    /* Delete Room */
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->execute([$room_id]);
    $deleted = $stmt->rowCount();
    if ($deleted ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Room '%s' was removed!", $room_info['street_address'])
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'An error occurred. The room was not removed.'
        ];
    }
}

function remove_invite($pdo, $invite_id){
    /* Get invite info */
    $invite_info = get_inviteinfo($pdo, $invite_id);

    /* Delete Room */
    $stmt = $pdo->prepare("DELETE FROM viewing_invites WHERE id = ?");
    $stmt->execute([$invite_id]);
    $deleted = $stmt->rowCount();
    if ($deleted ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Invite to '%s' was removed!", $invite_info['street_address'])
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'An error occurred. The invite was not removed.'
        ];
    }
}

function remove_optin($pdo, $optin_id){
    /* Get invite info */
    $invite_info = get_inviteinfo($pdo, $optin_id);

    /* Delete Room */
    $stmt = $pdo->prepare("DELETE FROM opt_ins WHERE id = ?");
    $stmt->execute([$optin_id]);
    $deleted = $stmt->rowCount();
    if ($deleted ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Opt-in to '%s' was removed!", $invite_info['street_address'])
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'An error occurred. The opt-in was not removed.'
        ];
    }
}

/**
 * Count the number of users
 * @param object $pdo database object
 * @return mixed
 */
function count_users($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM users');
    $stmt->execute();
    $users = $stmt->rowCount();
    return $users;
}

/**
 * Count the number of rooms
 * @param object $pdo database object
 * @return mixed
 */
function count_rooms($pdo){
    /* Get series */
    $stmt = $pdo->prepare('SELECT * FROM rooms');
    $stmt->execute();
    $rooms = $stmt->rowCount();
    return $rooms;
}

/**
 * Count the number of owners
 * @param object $pdo database object
 * @return mixed
 */
function count_owners($pdo){
    $stmt = $pdo->prepare('SELECT * FROM users WHERE role = "Owner"');
    $stmt->execute();
    $owners = $stmt->rowCount();
    return $owners;
}

/**
 * Count the number of tenants
 * @param object $pdo database object
 * @return mixed
 */
function count_tenants($pdo){
    $stmt = $pdo->prepare('SELECT * FROM users WHERE role = "Tenant"');
    $stmt->execute();
    $tenants = $stmt->rowCount();
    return $tenants;
}

/**
 * Count the number of optins for a user
 * @param object $pdo database object
 * @return mixed
 */
function count_optins($pdo){
    if (isset($_SESSION['user_id'])){
        if (!check_owner($pdo)) {
            $stmt = $pdo->prepare('SELECT * FROM opt_ins WHERE tenant_id = ?');
            $stmt->execute([$_SESSION['user_id']]);
            $optins = $stmt->rowCount();
            return $optins;
        }
        else {
            $stmt = $pdo->prepare('SELECT * FROM opt_ins INNER JOIN rooms ON opt_ins.room_id = rooms.id JOIN address ON rooms.address_id = address.id WHERE owner_id = ?');
            $stmt->execute([$_SESSION['user_id']]);
            $optins = $stmt->rowCount();
            return $optins;
        }
    }
    else {
        return 0;
    }
}

/**
 * Count the number of invites for a user
 * @param object $pdo database object
 * @return mixed
 */
function count_invites($pdo){
    if (isset($_SESSION['user_id'])){
        if (!check_owner($pdo)) {
            $stmt = $pdo->prepare('SELECT * FROM viewing_invites WHERE viewing_invites.tenant_id = ?');
            $stmt->execute([$_SESSION['user_id']]);
            $optins = $stmt->rowCount();
            return $optins;
        }
        else {
            $stmt = $pdo->prepare('SELECT * FROM viewing_invites INNER JOIN rooms ON viewing_invites.room_id = rooms.id JOIN address ON rooms.address_id = address.id WHERE viewing_invites.owner_id = ?');
            $stmt->execute([$_SESSION['user_id']]);
            $optins = $stmt->rowCount();
            return $optins;
        }
    }
    else {
        return 0;
    }
}

/**
 * Changes the HTTP Header to a given location
 * @param string $location location to be redirected to
 */
function redirect($location){
    header(sprintf('Location: %s', $location));
    die();
}

/**
 * Get current user id
 * @return bool current user id or False if not logged in
 */
function get_user_id(){
    if (isset($_SESSION['user_id'])){
        return $_SESSION['user_id'];
    } else {
        return False;
    }
}


