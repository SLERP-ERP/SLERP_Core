<?php
/**
 * Created by PhpStorm.
 * User: melon
 * Date: 1/7/16
 * Time: 9:20 PM
 */

/**
 * @api {post} /courses/ POST: Create/Update Course
 * @apiVersion 1.1.1
 * @apiGroup Courses
 * @apiDescription This method creates a new course, or updates a course with the specified `number`.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiUse CreateSuccessResultExample
 * @apiUse UpdateSuccessResultExample
 * @apiUse UnprocessableEntityErrors
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o courses -m post -d "department_id=34&code=SPAN101&name=Spanish 101&course_level=100"
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" \
 *      -X "POST" \
 *      --data "department_id=34" \
 *      --data "code=SPAN101" \
 *      --data "name=Spanish 101" \
 *      --data "course_level=100" \
 *      --url https://databridge.sage.edu/api/v1/courses/
 *
 * @apiUse CourseParameters
 */

/**
 * @api {delete} /courses/:id DELETE: Destroy Course
 * @apiVersion 1.1.1
 * @apiGroup Courses
 * @apiDescription This method deletes a Course object, the database ID value is supplied to the API.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiSuccessExampleDestroy
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiParam {Integer} id The courses' unique ID.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o courses -m delete -p 4
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" \
 *      -X "DELETE" \
 *      --url https://databridge.sage.edu/api/v1/courses/4
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {delete} /courses/code/:code DELETE: Destroy By Code
 * @apiVersion 1.1.1
 * @apiGroup Courses
 * @apiDescription This method deletes a Course object,  a courses' unique code is supplied.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiSuccessExampleDestroy
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiParam {String} code The courses' unique code.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o courses -m delete -p code/SPAN101
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" \
 *      -X "DELETE" \
 *      --url https://databridge.sage.edu/api/v1/courses/code/SPAN101
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {get} /courses/ GET: Request Courses
 * @apiVersion 1.1.1
 * @apiGroup Courses
 * @apiDescription This method returns pages of Course objects.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiUse PaginationParams
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o courses
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/courses/
 *
 * @apiUse PaginatedSuccess
 * @apiUse CourseSuccess
 * @apiUse GetCoursesSuccessResultExample
 */

/**
 * @api {get} /courses/:id GET: Request Course
 * @apiVersion 1.1.1
 * @apiGroup Courses
 * @apiDescription This method returns a Course object, an id is supplied to the API.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiParam {Integer} id The courses' unique ID.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o courses -p 12
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/courses/12
 *
 * @apiUse CourseSuccess
 * @apiUse GetCourseSuccessResultExample
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {get} /courses/code/:code GET: Course by Code
 * @apiVersion 1.1.1
 * @apiGroup Courses
 * @apiDescription This method returns a Course object, a courses' unique code is supplied.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiParam {String} code The courses' unique code.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o courses -p code/SPAN101
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/courses/code/SPAN101
 *
 * @apiUse CourseSuccess
 * @apiUse GetCourseSuccessResultExample
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {get} /courses/user/:id GET: By User ID
 * @apiVersion 1.1.1
 * @apiGroup Courses
 * @apiDescription This method returns Course objects associated with the user's database id.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiParam {Integer} id The users unique ID.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o courses -p user/153
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/courses/user/153
 *
 * @apiUse CourseSuccess
 * @apiUse GetCoursesSuccessResultExample
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {get} /courses/username/:username GET: By Username
 * @apiVersion 1.1.1
 * @apiGroup Courses
 * @apiDescription This method returns Course objects associated with the Username that was supplied.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiParam {String} username The users unique username.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o courses -p username/skywal
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/courses/username/skywal
 *
 * @apiUse CourseSuccess
 * @apiUse GetCoursesSuccessResultExample
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {get} /courses/user_id/:user_identifier GET: By User Identifier
 * @apiVersion 1.1.1
 * @apiGroup Courses
 * @apiDescription This method returns Course objects associated with the Identifier that was supplied.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiParam {String} user_identifier The user's unique identifier string.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o courses -p user_id/979659
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/courses/user_id/979659
 *
 * @apiUse CourseSuccess
 * @apiUse GetCoursesSuccessResultExample
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {get} /courses/department/:id GET: By Department ID
 * @apiVersion 1.1.1
 * @apiGroup Courses
 * @apiDescription This method returns Course objects associated with the department's database id.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiParam {Integer} id The departments unique ID.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o courses -p department/23
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/courses/department/23
 *
 * @apiUse CourseSuccess
 * @apiUse GetCoursesSuccessResultExample
 *
 * @apiUse ModelNotFoundError
 */

/**
 * @api {get} /courses/department/code/:code GET: By Department Code
 * @apiVersion 1.1.1
 * @apiGroup Courses
 * @apiDescription This method returns Course objects associated with the department's code.
 *
 * @apiUse ApiSuccessFields
 * @apiUse ApiErrorFields
 * @apiUse AuthorizationHeader
 * @apiParam {string} code The departments unique code.
 *
 * @apiExample {bash} UUD Client
 *      # This example uses the UUD Client: https://gitlab.sage.edu/UniversalUserData/uud-client
 *      uud -o courses -m department/code/MIS
 *
 * @apiExample {bash} Curl
 *      curl -H "X-Authorization: <Your-API-Key>" --url https://databridge.sage.edu/api/v1/courses/department/code/MIS
 *
 * @apiUse CourseSuccess
 * @apiUse GetCoursesSuccessResultExample
 *
 * @apiUse ModelNotFoundError
 */
