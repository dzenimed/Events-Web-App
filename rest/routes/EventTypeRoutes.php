<?php
/**
*  @OA\Post(path="/add/eventType", description = "Add event type to system.", tags={"eventType"}, security={{"ApiKeyAuth":{}}},
*   @OA\RequestBody(description="Basic event type info", required=true,
*     @OA\MediaType(mediaType="application/json",
*    		@OA\Schema(
*         @OA\Property(property="type_name", type="string", example="ExampleName", description="Name of the type of  event"),
*         @OA\Property(property="description", type="string", example="Example description", description="More details about the description")
*     )
*      )),
 *    @OA\Response(
 *       response="200",
 *       description="Added event type to system.")
 * )
 */
Flight::route('POST /add/eventType', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::eventTypeService()->add_event_type($data));
});
