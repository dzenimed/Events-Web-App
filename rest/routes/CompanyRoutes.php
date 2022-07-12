<?php
/**
 * @OA\Get(path="/company/{name}", tags={"company"}, security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(@OA\Schema(type="string"), in="path", name="name", description="Name of company"),
 *     @OA\Response(response="200", description="Fetch individual company")
 * )
 */
Flight::route('GET /company/@name', function ($name) {
    Flight::json(Flight::companyService()->get_company_by_name($name));
});
