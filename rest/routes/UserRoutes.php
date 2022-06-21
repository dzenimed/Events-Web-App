<?php

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
 Flight::route('POST /register', function(){
   $data = Flight::request()->data->getData();
   $email = Flight::request()->data->email;
   $password = Flight::request()->data->password;

   if(strlen($password) < 8) throw new \Exception("The password must have at least 8 characters.", 400);

   if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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

   if(strpos($response, $remaining_hash) !== false){ //localy check is the remaining part of the pass in the db
     Flight::json("Please choose another password.");
     die();
   }else{
     echo "";
   }
   Flight::json(Flight::userService()->register($data));
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
 Flight::route('POST /login', function(){
   $data = Flight::request()->data->getData();
   Flight::json(Flight::userService()->login($data));
 });


?>
