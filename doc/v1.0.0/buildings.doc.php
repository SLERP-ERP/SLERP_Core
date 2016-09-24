<?php
/**
 * Created by PhpStorm.
 * User: melon
 * Date: 1/6/16
 * Time: 10:11 PM
 */

/**
 * @api {post} /buildings/ POST: Create/Update Building
 * @apiVersion 1.0.0
 * @apiGroup Buildings
 * @apiDescription This method creates a new building, or updates a building with the specified `code`.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiUse InternalServerErrors
 * @apiUse CreateSuccessResultExample
 * @apiUse UpdateSuccessResultExample
 * @apiUse UnprocessableEntityErrors
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o buildings -m post -d "campus_id=1&name=CAR469&code=Carter Turnpike Court"
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" \
 *      -X "POST" \
 *      --data "campus_id=1" \
 *      --data "name=CAR469" \
 *      --data "code=Carter Turnpike Court" \
 *      --url https://databridge.sage.edu/api/v1/buildings/
 *
 * @apiUse BuildingParameters1.0.0
 */

/**
 * @api {post} /buildings/campus/code POST: Create/Update with Campus Code
 * @apiVersion 1.0.0
 * @apiGroup Buildings
 * @apiDescription This method creates a new building, or updates a building with the specified `code`, a campus code is supplied instead of a campus id.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiUse InternalServerErrors
 * @apiUse CreateSuccessResultExample
 * @apiUse UpdateSuccessResultExample
 * @apiUse UnprocessableEntityErrors
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o buildings -m post -p 'campus/code' -d "campus_code=TRY&name=CAR469&code=Carter Turnpike Court"
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" \
 *      -X "POST" \
 *      --data "campus_code=TRY" \
 *      --data "name=CAR469" \
 *      --data "code=Carter Turnpike Court" \
 *      --url https://databridge.sage.edu/api/v1/buildings/
 *
 * @apiUse BuildingParametersCampusCode1.0.0
 */

/**
 * @api {get} /buildings/campus/:id GET: By Campus ID
 * @apiVersion 1.0.0
 * @apiGroup Buildings
 * @apiDescription This method returns a Building object, a campus `id` is supplied.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiUse InternalServerErrors
 * @apiParam {Integer} id The campuses' unique database ID.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o buildings -p campus/2
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/buildings/campus/2
 *
 * @apiUse PaginatedSuccess
 * @apiUse BuildingSuccess1.0.0
 * @apiUse GetBuildingsSuccessResultExample1.0.0
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {get} /buildings/campus/code/:code GET: By Campus Code
 * @apiVersion 1.0.0
 * @apiGroup Buildings
 * @apiDescription This method returns a Building object, a campus unique `code` is supplied.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiUse InternalServerErrors
 * @apiParam {String} code The campuses' unique identifier string.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o buildings -p campus/code/TRY
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/buildings/campus/code/TRY
 *
 * @apiUse PaginatedSuccess
 * @apiUse BuildingSuccess1.0.0
 * @apiUse GetBuildingsSuccessResultExample1.0.0
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {delete} /buildings/:id DELETE: Destroy Building
 * @apiVersion 1.0.0
 * @apiGroup Buildings
 * @apiDescription This method deletes a Building object, the database ID value is supplied to the API.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiSuccessExampleDestroy
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiUse InternalServerErrors
 * @apiParam {Integer} id The building's unique ID.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o buildings -m delete -p 14
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" \
 *      -X "DELETE" \
 *      --url https://databridge.sage.edu/api/v1/buildings/14
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {delete} /buildings/code/:code DELETE: Destroy by Code
 * @apiVersion 1.0.0
 * @apiGroup Buildings
 * @apiDescription This method deletes a Building object, the objects unique `code` is supplied.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiSuccessExampleDestroy
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiUse InternalServerErrors
 * @apiParam {String} code The building's unique identifier string.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o buildings -m delete -p code/WES514
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" \
 *      -X "DELETE" \
 *      --url https://databridge.sage.edu/api/v1/buildings/code/WES514
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {get} /buildings/ GET: Request Buildings
 * @apiVersion 1.0.0
 * @apiGroup Buildings
 * @apiDescription This method returns pages of Building objects.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiUse InternalServerErrors
 * @apiUse PaginationParams
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o buildings
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/buildings/
 *
 * @apiUse PaginatedSuccess
 * @apiUse BuildingSuccess1.0.0
 * @apiUse GetBuildingsSuccessResultExample1.0.0
 */

/**
 * @api {get} /buildings/:id GET: Request Building
 * @apiVersion 1.0.0
 * @apiGroup Buildings
 * @apiDescription This method returns a Building object, an id is supplied to the API.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiUse InternalServerErrors
 * @apiParam {Integer} id The building's unique ID.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o buildings -p 14
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/buildings/14
 *
 * @apiUse BuildingSuccess1.0.0
 * @apiUse GetBuildingSuccessResultExample1.0.0
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {get} /buildings/code/:code GET: Request by Code
 * @apiVersion 1.0.0
 * @apiGroup Buildings
 * @apiDescription This method returns a Building object, the objects unique `code` is supplied.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiUse InternalServerErrors
 * @apiParam {String} code The building's unique identifier string.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o buildings -p code/WES514
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/buildings/code/WES514
 *
 * @apiUse BuildingSuccess1.0.0
 * @apiUse GetBuildingSuccessResultExample1.0.0
 *
 * @apiUse ModelNotFoundError
 */