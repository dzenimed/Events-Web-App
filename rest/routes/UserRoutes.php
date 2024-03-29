<?php

/**
 * @OA\Get(path="/admin/users", tags={"x-admin", "user"}, security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(@OA\Schema(type="integer"), in="query", name="offset", description="Offset for pagination"),
 *     @OA\Parameter(@OA\Schema(type="integer"), in="query", name="limit", description="Limit for pagination"),
 *     @OA\Parameter(@OA\Schema(type="string"), in="query", name="search", description="Search string for users. Case insensitive search."),
 *     @OA\Parameter(@OA\Schema(type="string"), in="query", name="order", description="Sorting for return elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="List users from database")
 * )
 */
Flight::route('GET /admin/users', function () {
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 6);
    $search = Flight::query('search');
    $order = Flight::query('order', "-id");

    Flight::json(Flight::userService()->get_user($search, $offset, $limit, $order));
});

/**
 * @OA\Get(path="/admin/countusers/{role}", tags={"x-admin", "user"}, security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(@OA\Schema(type="string"), in="query", name="role", description="status of event", example="user"),
 *     @OA\Response(response="200", description="List number of users from database")
 * )
 */
Flight::route('GET /admin/countusers/@role', function ($role) {
  Flight::json(Flight::userService()->get_user_number($role));
});

/**
 * @OA\Get(path="/admin/countusers/{status}", tags={"x-admin", "user"}, security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(@OA\Schema(type="string"), in="query", name="status", description="status of user", example="ACTIVE"),
 *     @OA\Response(response="200", description="List number of active users from database")
 * )
 */
Flight::route('GET /admin/countactiveusers/@status', function ($status) {
  Flight::json(Flight::userService()->get_active_user_number($status));
});

/**
 * @OA\Get(path="/user/{id}", tags={"user"}, security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", description="Id of user"),
 *     @OA\Response(response="200", description="Fetch individual user")
 * )
 */
Flight::route('GET /user/@id', function ($id) {
    if (Flight::get('user')['id'] != $id) {
        throw new Exception("This user is not for you.", 401);
    }
    Flight::json(Flight::userService()->get_user_by_id($id));
});

/**
 * @OA\Get(path="/users/{token}", tags={"user"}, security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(@OA\Schema(type="string"), in="path", name="token", description="Token of user"),
 *     @OA\Response(response="200", description="Fetch individual user")
 * )
 */
Flight::route('GET /users/@token', function ($token) {
    Flight::json(Flight::userService()->get_user_by_token($token));
});

/**
 * @OA\Get(path="/users/information/{token}", tags={"user"}, security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(@OA\Schema(type="string"), in="path", name="token", description="Token of user"),
 *     @OA\Response(response="200", description="Fetch individual user information")
 * )
 */
Flight::route('GET /users/information/@token', function ($token) {
    Flight::json(Flight::userService()->get_username_by_token($token));
});

/**
 * @OA\Put(path="/admin/user/{id}", tags={"x-admin", "user"}, security={{"ApiKeyAuth":{}}},
 *  @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id"),
 *   @OA\RequestBody(description="Basic user info that is going to be updated", required=true,
 *     @OA\MediaType(mediaType="application/json",
 *    		@OA\Schema(
 *    		  @OA\Property(property="username", type="string", example="My Test Account2", description="Username of the user"),
 *    				@OA\Property(property="status", type="string", example="ACTIVE", description="Status of user")
 *         )
 *     )
 *      ),
 *     @OA\Response(response="200", description="Update user based on parameter")
 * )
 */
Flight::route('PUT /admin/user/@id', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->update($id, $data));
});

 /**
 *  @OA\Post(path="/register", description = "Register on the system.", tags={"user"},
 *   @OA\RequestBody(description="Basic user info", required=true,
 *     @OA\MediaType(mediaType="application/json",
 *    		@OA\Schema(
 *         @OA\Property(property="username", type="string", example="ExampleName", description="Username of the user"),
 *         @OA\Property(property="name", type="string", example="Name", description="First name of the user"),
 *         @OA\Property(property="surname", type="string", example="ExampleSurname", description="Last name of the user"),
 *         @OA\Property(property="email", type="string", example="firstlastName@gmail.com", description="Email of the user"),
 *    		  @OA\Property(property="password", type="string", example="password123", description="Password of the user")
 *     )
 *      )),
  *    @OA\Response(
  *       response="200",
  *       description="Registered as user on system."
  *    ),
  *    @OA\Response(
  *       response=404,
  *       description="Wrong Password | User doesn't exist"
  *     )
  * )
  */
 Flight::route('POST /register', function () {
     $data = Flight::request()->data->getData();
     $email = Flight::request()->data->email;
     $password = Flight::request()->data->password;

     if (strlen($password) < 8) {
         throw new \Exception("The password must have at least 8 characters.", 400);
     }

     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         Flight::json(array(
       'status' => 'error',
       'message' => 'The email ' . $email . ' is not valid.'
     ));
         die();
     }

     $hashed_password = strtoupper(hash("sha1", $password));
     $first_5_hash = substr($hashed_password, 0, 5);
     $remaining_hash = substr($hashed_password, 5);
     $response = file_get_contents('http://api.pwnedpasswords.com/range/'. $first_5_hash);

     if (strpos($response, $remaining_hash) !== false) { //localy check is the remaining part of the pass in the db
         Flight::json("Please choose another password.");
         die();
     } else {
         echo "";
     }
     Flight::userService()->register($data);
     Flight::json(["message" => "Conformation email has been sent. Please confirm your account."]);
 });

 /**
 *  @OA\Post(path="/login", description = "Login to the system.", tags={"user"},
 *   @OA\RequestBody(description="Basic user info", required=true,
 *     @OA\MediaType(mediaType="application/json",
 *    		@OA\Schema(
 *         @OA\Property(property="username",  type="string", example="MyUsername", description="Email of the user"),
 *         @OA\Property(property="password", type="string", example="MyPassword", description="Password of the user")
 *     )
 *      )),
 *    @OA\Response(
 *         response="200",
 *         description="JWT token on successful response"),
 *     @OA\Response(
 *         response=404,
 *         description="Wrong Password | User doesn't exist"
 *     )
 * )
  */
 Flight::route('POST /login', function () {
     Flight::json(Flight::jwt(Flight::userService()->login(Flight::request()->data->getData())));
     // $data = Flight::request()->data->getData();
   // Flight::json(Flight::userService()->login($data));
 });

 /**
  * @OA\Get(path="/confirm/{token}", tags={"user"},
  *     @OA\Parameter(@OA\Schema(type="string"), in="path", name="token", description="Temporary token for activating account"),
  *     @OA\Response(response="200", description="Message upon successfull activation.")
  * )
  */
 Flight::route('GET /confirm/@token', function ($token) {
     Flight::json(Flight::userService()->confirm($token));
     header("Location: ".'//'.$_SERVER["SERVER_NAME"].str_replace("/rest/index.php", "/login.html", $_SERVER["SCRIPT_NAME"]));
     exit();
 });
