<?php
/**
 * @OA\Get(path="/reservation/{id}", tags={"reservation"}, security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", description="Id of reservation"),
 *     @OA\Response(response="200", description="Fetch individual reservation")
 * )
 */
Flight::route('GET /reservation/@id', function ($id) {
    Flight::json(Flight::reservationService()->get_reservation_by_id($id));
});

/**
 * @OA\Get(path="/user/reservations", tags={"reservation"}, security={{"ApiKeyAuth": {}}},
 *         @OA\Response( response=200, description="List of reservations.")
 * )
 */
Flight::route('GET /user/reservations', function () {
    $user = Flight::get('user');
    Flight::json(Flight::reservationService()->get_user_reservations($user));
});

/**
*  @OA\Post(path="/user/add/reservation", description = "Add reservation to system.", tags={"reservation"}, security={{"ApiKeyAuth":{}}},
*   @OA\RequestBody(description="Basic reservation info", required=true,
*     @OA\MediaType(mediaType="application/json",
*    		@OA\Schema(
*         @OA\Property(property="event_id", type="integer", example="1", description="Id of event"),
*         @OA\Property(property="user_id", type="integer", example="1", description="Id of user"),
*     )
*      )),
 *    @OA\Response(
 *       response="200",
 *       description="Added reservation to system.")
 * )
 */
Flight::route('POST /user/add/reservation', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reservationService()->add_reservation($data));
    // Flight::json(Flight::reservationService()->add_reservation($data, $event_id, Flight::get('user')['id']));
});

/**
*  @OA\Put(path="/user/delete/reservation/{id}", description = "Add event type to system.", tags={"reservation"}, security={{"ApiKeyAuth":{}}},
*   @OA\RequestBody(description="Remove reservation", required=true,
*     @OA\MediaType(mediaType="application/json",
*    		@OA\Schema(
*         @OA\Property(property="status", type="string", example="CANCELLED", description="Reservation status")
*     )
*      )),
 *    @OA\Response(
 *       response="200",
 *       description="Cancelled event reservation.")
 * )
 */
Flight::route('PUT /user/delete/reservation/@id', function ($id) {
    Flight::json(Flight::reservationService()->update($id, Flight::request()->data->getData()));
    //  Flight::json(["message" => "deleted"]);
});
