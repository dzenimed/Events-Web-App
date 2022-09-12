<?php
/**
 * @OA\Get(path="/events", tags={"event"},
 *     @OA\Parameter(@OA\Schema(type="integer"), in="query", name="offset", description="Offset for pagination"),
 *     @OA\Parameter(@OA\Schema(type="integer"), in="query", name="limit", description="Limit for pagination"),
 *     @OA\Parameter(@OA\Schema(type="string"), in="query", name="search", description="Search string for events. Case insensitive search."),
 *     @OA\Parameter(@OA\Schema(type="string"), in="query", name="order", description="Sorting for return elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="List events from database")
 * )
 */
Flight::route('GET /events', function () {
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 6);
    $search = Flight::query('search', '');
    $order = Flight::query('order', "-id");

    Flight::json(Flight::eventService()->get_events_by_name($search, $offset, $limit, $order));
});

/**
 * @OA\Get(path="/admin/activeevents/{status}", tags={"x-admin", "event"}, security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(@OA\Schema(type="string"), in="query", name="status", description="status of event", example="ACTIVE"),
 *     @OA\Response(response="200", description="List number of active users from database")
 * )
 */
Flight::route('GET /admin/activeevents/@status', function ($status) {
  Flight::json(Flight::eventService()->get_events_number($status));
});

/**
 * @OA\Get(path="/event/{id}", tags={"event"},
 *     @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", description="Id of event"),
 *     @OA\Response(response="200", description="Fetch individual event")
 * )
 */
Flight::route('GET /event/@id', function ($id) {
    Flight::json(Flight::eventService()->get_by_id($id));
});

/**
 * @OA\Get(path="/event/{city}", tags={"event"},
 *     @OA\Parameter(@OA\Schema(type="string"), in="path", name="city", description="City where event is held"),
 *     @OA\Response(response="200", description="Fetch events by city")
 * )
 */
Flight::route('GET /event/@city', function ($city) {
    Flight::json(Flight::eventService()->get_event_by_city($city));
});


/**
*  @OA\Put(path="/admin/update/event/{id}", description = "Change event info.", tags={"event"}, security={{"ApiKeyAuth":{}}},
*   @OA\RequestBody(description="Update event info", required=true,
*     @OA\MediaType(mediaType="application/json",
*    		@OA\Schema(
*         @OA\Property(property="id", type="integer", example="1", description="Id of event"),
*         @OA\Property(property="status", type="string", example="CANCELLED", description="Event status")
*     )
*      )),
 *    @OA\Response(
 *       response="200",
 *       description="Update event.")
 * )
 */
Flight::route('PUT /admin/update/event/@id', function ($id) {
    Flight::eventService()->update($id, Flight::request()->data->getData());
});

/**
*  @OA\Post(path="/admin/add/event", description = "Add event to system.", tags={"x-admin", "event"}, security={{"ApiKeyAuth":{}}},
*   @OA\RequestBody(description="Basic event info", required=true,
*     @OA\MediaType(mediaType="application/json",
*    		@OA\Schema(
*         @OA\Property(property="name", type="string", example="ExampleName", description="Name of the event"),
*         @OA\Property(property="status", type="string", example="ACTIVE", description="Status of  event"),
*         @OA\Property(property="city", type="string", example="Sarajevo", description="Name of city where the event is held"),
*         @OA\Property(property="address", type="string", example="ExampleAddress", description="Address of  event"),
*         @OA\Property(property="date_held", type="string", example="ExampleName", description="Name of the type of  event"),
*         @OA\Property(property="num_of_tickets", type="integer", example="50", description="Number of tickets for event"),
*         @OA\Property(property="description", type="string", example="Example description", description="More details about the event"),
*         @OA\Property(property="type_name", type="string", example="ExampleName", description="Name of the type of  event"),
*         @OA\Property(property="company_name", type="string", example="ExampleName", description="Name of the company hosting the  event")
*     )
*      )),
 *    @OA\Response(
 *       response="200",
 *       description="Added event to system.")
 * )
 */
Flight::route('POST /admin/add/event', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::eventService()->add_event($data));
});


/**
*  @OA\DELETE(path="/admin/delete/event/{id}", description = "Change event info.", tags={"event"}, security={{"ApiKeyAuth":{}}},
*   @OA\RequestBody(description="Update event info", required=true,
*     @OA\MediaType(mediaType="application/json",
*    		@OA\Schema(
*         @OA\Property(property="id", type="integer", example="1", description="Id of event")
*     )
*      )),
 *    @OA\Response(
 *       response="200",
 *       description="Delete event.")
 * )
 */
Flight::route('DELETE /admin/delete/event/@id', function($id){
  Flight::eventService()->delete($id);
  Flight::json(["message" => "deleted"]);
});
