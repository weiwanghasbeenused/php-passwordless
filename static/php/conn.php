<?

// connect to database (called in head.php)
function db_connect($remote_user) {
	$adminURLString = getenv("MYSQL_FULL_DATABASE_URL");
    $readWriteURLString = getenv("MYSQL_RW_DATABASE_URL");
    $readOnlyURLString = getenv("MYSQL_R_DATABASE_URL");

	$users = array();
	$creds = array();

	if ($adminURLString && $readWriteURLString && $readOnlyURLString) {
		// IF YOU ARE USING ENVIRONMENTAL VARIABLES (you should)
		$urlAdmin = parse_url($adminURLString);
		$host = $urlAdmin["host"];
		$dbse = substr($urlAdmin["path"], 1);

        // full access
        $creds['full']['db_user'] = $urlAdmin["user"];
        $creds['full']['db_pass'] = $urlAdmin["pass"];

        // read / write access
        // (can't create / drop tables)
        $urlReadWrite = parse_url($readWriteURLString);
		$creds['rw']['db_user'] = $urlReadWrite["user"];
		$creds['rw']['db_pass'] = $urlReadWrite["pass"];

        // read-only access
		$urlReadOnly = parse_url($readOnlyURLString);
		$creds['r']['db_user'] = $urlReadOnly["user"];
		$creds['r']['db_pass'] = $urlReadOnly["pass"];

	} else {
		// IF YOU ARE NOT USING ENVIRONMENTAL VARIABLES
		$host = "localhost";
		$dbse = "database";

		// full access
		$creds['full']['db_user'] = "user_full";
		$creds['full']['db_pass'] = "pass_full";

		// read / write access
		// (can't create / drop tables)
		$creds['rw']['db_user'] = "user_rw";
		$creds['rw']['db_pass'] = "pass_rw";

		// read-only access
		$creds['r']['db_user'] = "user_r";
		$creds['r']['db_pass'] = "pass_r";
	}

	// users
	$users["admin"] = $creds['full'];
	$users["main"] = $creds['rw'];
	$users["guest"] = $creds['r'];

	$user = $users[$remote_user]['db_user'];
	$pass = $users[$remote_user]['db_pass'];

	$db = new mysqli($host, $user, $pass, $dbse);
	if($db->connect_errno)
		echo "Failed to connect to MySQL: " . $db->connect_error;
	return $db;
}
