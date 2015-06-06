<?php
/**
 * Created by PhpStorm.
 * User: melon
 * Date: 6/6/15
 * Time: 10:40 AM
 */
require dirname(dirname(__FILE__)) . '/vendor/autoload.php';
include_once dirname(__FILE__) . '/lib/api/API.php';
include_once dirname(__FILE__) . '/lib/ud2sql/helpers/MySQLHelper.php';
include_once dirname(__FILE__) . '/lib/ud2sql/app/Config.php';

// Init a slim object
$slim = new \Slim\Slim();
// Init an API object
$api = new API();
// Check the API authorization
if ($slim->request->headers->get('X-Authorization') && $apiKey = $api->checkAPIKey($slim->request->headers->get('X-Authorization'))) {

    $MySQLiHelper = new MySQLHelper();
    $mysqli = $MySQLiHelper->getMySQLi(Config::getSQLConf());

    $slim->group('/user', function () use ($slim, $api, $apiKey, $mysqli, $MySQLiHelper) {

        /**
         * @api {get} /user/id/:id Get By ID
         * @apiVersion 1.0.0
         * @apiHeader {String} X-Authorization The application's unique access-key.
         * @apiGroup Users
         * @apiParam {Int} id Users's unique API ID.
         *
         * @apiSuccess {String} application The name of the application that is accessing the API.
         * @apiSuccess {Boolean} success Tells the application if the request was successful.
         * @apiSuccess {Object} result The user record object.
         * @apiSuccessExample Success-Response:
         *     HTTP/1.1 200 OK
         *     {
         *          "application": "Awesome Application",
         *          "success": true,
         *          "result": {
         *              "id": "1",
         *              "id_num": "999998",
         *              "username": "buildb3",
         *              "name_first": "Bob",
         *              "name_middle": "T.",
         *              "name_last": "Builder",
         *              "email": "buildb3@sage.edu",
         *              "email2": "bob@gmail.com",
         *              "dorm": "5",
         *              "role": "1",
         *              "active": "1",
         *              "phone": "5182444777",
         *              "room": "302",
         *              "has_photo_id": "1",
         *              "photo_id_url": "http://idmanager.sage.edu/pics/accepted/0999998.jpg",
         *              "photo_id_filename": "999998.jpg
         *           }
         *     }
         *
         * @apiError {String} application The name of the application that is accessing the API.
         * @apiError {Boolean} success Tells the application if the request was successful.
         * @apiError {String} UserNotFound The id of the user was not found.
         * @apiErrorExample Error-Response:
         *      HTTP/1.1 404 Not Found
         *      {
         *          "application": "Awesome Application",
         *          "success": false,
         *          "error": "UserNotFound"
         *      }
         */
        $slim->get('/id/:id', function ($id) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->simpleSelect($mysqli, Config::getSQLConf()['db_user_table'], 'id', $id)->fetch_assoc()) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'UserNotFound'));
            }
        });

        /**
         * @api {get} /user/idnum/:idnum Get By Sage ID
         * @apiVersion 1.0.0
         * @apiHeader {String} X-Authorization The application's unique access-key.
         * @apiGroup Users
         * @apiParam {Int} idnum Users's unique Sage ID.
         *
         * @apiSuccess {String} application The name of the application that is accessing the API.
         * @apiSuccess {Boolean} success Tells the application if the request was successful.
         * @apiSuccess {Object} result The user record object.
         * @apiSuccessExample Success-Response:
         *     HTTP/1.1 200 OK
         *     {
         *          "application": "Awesome Application",
         *          "success": true,
         *          "result": {
         *              "id": "1",
         *              "id_num": "999998",
         *              "username": "buildb3",
         *              "name_first": "Bob",
         *              "name_middle": "T.",
         *              "name_last": "Builder",
         *              "email": "buildb3@sage.edu",
         *              "email2": "bob@gmail.com",
         *              "dorm": "5",
         *              "role": "1",
         *              "active": "1",
         *              "phone": "5182444777",
         *              "room": "302",
         *              "has_photo_id": "1",
         *              "photo_id_url": "http://idmanager.sage.edu/pics/accepted/0999998.jpg",
         *              "photo_id_filename": "999998.jpg
         *           }
         *     }
         *
         * @apiError {String} application The name of the application that is accessing the API.
         * @apiError {Boolean} success Tells the application if the request was successful.
         * @apiError {String} UserNotFound The id of the user was not found.
         * @apiErrorExample Error-Response:
         *      HTTP/1.1 404 Not Found
         *      {
         *          "application": "Awesome Application",
         *          "success": false,
         *          "error": "UserNotFound"
         *      }
         */

        $slim->get('/idnum/:idnum', function ($idnum) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->simpleSelect($mysqli, Config::getSQLConf()['db_user_table'], 'id_num', $idnum)->fetch_assoc()) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'UserNotFound'));
            }
        });

        /**
         * @api {get} /user/username/:username Get By Sage Username
         * @apiVersion 1.0.0
         * @apiHeader {String} X-Authorization The application's unique access-key.
         * @apiGroup Users
         * @apiParam {String} username Users's unique Sage username.
         *
         * @apiSuccess {String} application The name of the application that is accessing the API.
         * @apiSuccess {Boolean} success Tells the application if the request was successful.
         * @apiSuccess {Object} result The user record object.
         * @apiSuccessExample Success-Response:
         *     HTTP/1.1 200 OK
         *     {
         *          "application": "Awesome Application",
         *          "success": true,
         *          "result": {
         *              "id": "1",
         *              "id_num": "999998",
         *              "username": "buildb3",
         *              "name_first": "Bob",
         *              "name_middle": "T.",
         *              "name_last": "Builder",
         *              "email": "buildb3@sage.edu",
         *              "email2": "bob@gmail.com",
         *              "dorm": "5",
         *              "role": "1",
         *              "active": "1",
         *              "phone": "5182444777",
         *              "room": "302",
         *              "has_photo_id": "1",
         *              "photo_id_url": "http://idmanager.sage.edu/pics/accepted/0999998.jpg",
         *              "photo_id_filename": "999998.jpg
         *           }
         *     }
         *
         * @apiError {String} application The name of the application that is accessing the API.
         * @apiError {Boolean} success Tells the application if the request was successful.
         * @apiError {String} UserNotFound The id of the user was not found.
         * @apiErrorExample Error-Response:
         *      HTTP/1.1 404 Not Found
         *      {
         *          "application": "Awesome Application",
         *          "success": false,
         *          "error": "UserNotFound"
         *      }
         */

        $slim->get('/username/:username', function ($username) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->simpleSelect($mysqli, Config::getSQLConf()['db_user_table'], 'username', $username)->fetch_assoc()) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'UserNotFound'));
            }
        });

        /**
         * @api {get} /user/:limit Get X Amount of Records
         * @apiVersion 1.0.0
         * @apiHeader {String} X-Authorization The application's unique access-key.
         * @apiGroup Users
         * @apiParam {Int} limit The max amount of users to return, 0 form all users.
         *
         * @apiSuccess {String} application The name of the application that is accessing the API.
         * @apiSuccess {Boolean} success Tells the application if the request was successful.
         * @apiSuccess {Array} result An array of user record objects.
         * @apiSuccessExample Success-Response:
         *     HTTP/1.1 200 OK
         *     {
         *          "application": "Awesome Application",
         *          "success": true,
         *          "result": [
         *            {
         *              "id": "1",
         *              "id_num": "999998",
         *              "username": "buildb3",
         *              "name_first": "Bob",
         *              "name_middle": "T.",
         *              "name_last": "Builder",
         *              "email": "buildb3@sage.edu",
         *              "email2": "bob@gmail.com",
         *              "dorm": "5",
         *              "role": "1",
         *              "active": "1",
         *              "phone": "5182444777",
         *              "room": "302",
         *              "has_photo_id": "1",
         *              "photo_id_url": "http://idmanager.sage.edu/pics/accepted/0999998.jpg",
         *              "photo_id_filename": "999998.jpg
         *           },
         *           {
         *              "id": "2",
         *              "id_num": "999997",
         *              "username": "dorae",
         *              "name_first": "Dora",
         *              "name_middle": "T.",
         *              "name_last": "Explorer",
         *              "email": "dorae@sage.edu",
         *              "email2": "dora@gmail.com",
         *              "dorm": "5",
         *              "role": "1",
         *              "active": "1",
         *              "phone": "5182444779",
         *              "room": "301",
         *              "has_photo_id": "1",
         *              "photo_id_url": "http://idmanager.sage.edu/pics/accepted/0999997.jpg",
         *              "photo_id_filename": "999997.jpg
         *           }
         *         ]
         *     }
         *
         * @apiError {String} application The name of the application that is accessing the API.
         * @apiError {Boolean} success Tells the application if the request was successful.
         * @apiError {String} UsersNotFound The id of the user was not found.
         * @apiErrorExample Error-Response:
         *      HTTP/1.1 404 Not Found
         *      {
         *          "application": "Awesome Application",
         *          "success": false,
         *          "error": "UsersNotFound"
         *      }
         */

        $slim->get('/:limit', function ($limit) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->selectAllFrom($mysqli, Config::getSQLConf()['db_user_table'], $limit)->fetch_all(MYSQLI_ASSOC)) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'UsersNotFound'));
            }
        });

        $slim->get('/', function () use ($api, $apiKey) {
            echo json_encode(array(
                'application' => $apiKey['app'],
                'success' => true,
                'result' => array(
                    'get' => array(
                        '/id/:id',
                        '/id_num/:id_num',
                        '/username/:username',
                        '/:limit'
                    ),
                    'post' => array()
                ),
            ));
        });
    });

    $slim->group(('/role'), function () use ($slim, $api, $apiKey, $mysqli, $MySQLiHelper) {

        /**
         * @api {get} /role/id/:id Get By ID
         * @apiVersion 1.0.0
         * @apiHeader {String} X-Authorization The application's unique access-key.
         * @apiGroup Roles
         * @apiParam {Int} id Role's unique API ID.
         *
         * @apiSuccess {String} application The name of the application that is accessing the API.
         * @apiSuccess {Boolean} success Tells the application if the request was successful.
         * @apiSuccess {Object} result The role record object.
         * @apiSuccessExample Success-Response:
         *     HTTP/1.1 200 OK
         *     {
         *          "application": "Awesome Application",
         *          "success": true,
         *          "result": {
         *              "id": "1",
         *              "datatel_name": "STUDENT",
         *              "common_name": "Student"
         *           }
         *     }
         *
         * @apiError {String} application The name of the application that is accessing the API.
         * @apiError {Boolean} success Tells the application if the request was successful.
         * @apiError {String} RoleNotFound The id of the role was not found.
         * @apiErrorExample Error-Response:
         *      HTTP/1.1 404 Not Found
         *      {
         *          "application": "Awesome Application",
         *          "success": false,
         *          "error": "RoleNotFound"
         *      }
         */

        $slim->get('/id/:id', function ($id) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->simpleSelect($mysqli, Config::getSQLConf()['db_role_table'], 'id', $id)->fetch_assoc()) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'RoleNotFound'));
            }
        });

        /**
         * @api {get} /role/code/:code Get Datatel Code
         * @apiVersion 1.0.0
         * @apiHeader {String} X-Authorization The application's unique access-key.
         * @apiGroup Roles
         * @apiParam {String} Datatel code that corresponds with that role.
         *
         * @apiSuccess {String} application The name of the application that is accessing the API.
         * @apiSuccess {Boolean} success Tells the application if the request was successful.
         * @apiSuccess {Object} result The role record object.
         * @apiSuccessExample Success-Response:
         *     HTTP/1.1 200 OK
         *     {
         *          "application": "Awesome Application",
         *          "success": true,
         *          "result": {
         *              "id": "1",
         *              "datatel_name": "STUDENT",
         *              "common_name": "Student"
         *           }
         *     }
         *
         * @apiError {String} application The name of the application that is accessing the API.
         * @apiError {Boolean} success Tells the application if the request was successful.
         * @apiError {String} RoleNotFound The Datatel code of the role was not found.
         * @apiErrorExample Error-Response:
         *      HTTP/1.1 404 Not Found
         *      {
         *          "application": "Awesome Application",
         *          "success": false,
         *          "error": "RoleNotFound"
         *      }
         */

        $slim->get('/code/:code', function ($code) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->simpleSelect($mysqli, Config::getSQLConf()['db_role_table'], 'datatel_name', $code)->fetch_assoc()) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'RoleNotFound'));
            }
        });

        $slim->get('/:limit', function ($limit) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->selectAllFrom($mysqli, Config::getSQLConf()['db_role_table'], $limit)->fetch_all(MYSQLI_ASSOC)) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'RoleNotFound'));
            }
        });
        $slim->get('/', function () use ($api, $apiKey) {
            echo json_encode(array(
                'application' => $apiKey['app'],
                'success' => true,
                'result' => array(
                    'get' => array(
                        '/id/:id',
                        '/datatel_code/:datatel_code',
                        '/:limit'
                    ),
                    'post' => array()
                )
            ));
        });
    });

    $slim->group(('/building'), function () use ($slim, $api, $apiKey, $mysqli, $MySQLiHelper) {

        /**
         * @api {get} /building/id/:id Get By ID
         * @apiVersion 1.0.0
         * @apiHeader {String} X-Authorization The application's unique access-key.
         * @apiGroup Buildings
         * @apiParam {Int} id Buildings's unique API ID.
         *
         * @apiSuccess {String} application The name of the application that is accessing the API.
         * @apiSuccess {Boolean} success Tells the application if the request was successful.
         * @apiSuccess {Object} result The building record object.
         * @apiSuccessExample Success-Response:
         *     HTTP/1.1 200 OK
         *     {
         *          "application": "Awesome Application",
         *          "success": true,
         *          "result": {
         *              "id": "1",
         *              "campus": "1",
         *              "datatel_name": "37-1",
         *              "common_name": "37 First Street"
         *           }
         *     }
         *
         * @apiError {String} application The name of the application that is accessing the API.
         * @apiError {Boolean} success Tells the application if the request was successful.
         * @apiError {String} BuildingNotFound The id of the building was not found.
         * @apiErrorExample Error-Response:
         *      HTTP/1.1 404 Not Found
         *      {
         *          "application": "Awesome Application",
         *          "success": false,
         *          "error": "BuildingNotFound"
         *      }
         */

        $slim->get('/id/:id', function ($id) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->simpleSelect($mysqli, Config::getSQLConf()['db_building_table'], 'id', $id)->fetch_assoc()) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'BuildingNotFound'));
            }
        });

        /**
         * @api {get} /building/code/:code Get Datatel Code
         * @apiVersion 1.0.0
         * @apiHeader {String} X-Authorization The application's unique access-key.
         * @apiGroup Roles
         * @apiParam {String} Datatel code that corresponds with that building.
         *
         * @apiSuccess {String} application The name of the application that is accessing the API.
         * @apiSuccess {Boolean} success Tells the application if the request was successful.
         * @apiSuccess {Object} result The building record object.
         * @apiSuccessExample Success-Response:
         *     HTTP/1.1 200 OK
         *     {
         *          "application": "Awesome Application",
         *          "success": true,
         *          "result": {
         *              "id": "1",
         *              "campus": "1",
         *              "datatel_name": "37-1",
         *              "common_name": "37 First Street"
         *           }
         *     }
         *
         * @apiError {String} application The name of the application that is accessing the API.
         * @apiError {Boolean} success Tells the application if the request was successful.
         * @apiError {String} BuildingNotFound The Datatel code of the building was not found.
         * @apiErrorExample Error-Response:
         *      HTTP/1.1 404 Not Found
         *      {
         *          "application": "Awesome Application",
         *          "success": false,
         *          "error": "BuildingNotFound"
         *      }
         */

        $slim->get('/code/:code', function ($code) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->simpleSelect($mysqli, Config::getSQLConf()['db_building_table'], 'datatel_name', $code)->fetch_assoc()) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'BuildingNotFound'));
            }
        });

        $slim->get('/:limit', function ($limit) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->selectAllFrom($mysqli, Config::getSQLConf()['db_building_table'], $limit)->fetch_all(MYSQLI_ASSOC)) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'BuildingNotFound'));
            }
        });
        $slim->get('/', function () use ($api, $apiKey) {
            echo json_encode(array(
                'application' => $apiKey['app'],
                'success' => true,
                'result' => array(
                    'get' => array(
                        '/id/:id',
                        '/datatel_code/:datatel_code',
                        '/:limit'
                    ),
                    'post' => array()
                )
            ));
        });
    });

    $slim->group(('/campus'), function () use ($slim, $api, $apiKey, $mysqli, $MySQLiHelper) {

        $slim->get('/id/:id', function ($id) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->simpleSelect($mysqli, Config::getSQLConf()['db_campus_table'], 'id', $id)->fetch_assoc()) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'CampusNotFound'));
            }
        });

        $slim->get('/code/:code', function ($code) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->simpleSelect($mysqli, Config::getSQLConf()['db_campus_table'], 'datatel_name', $code)->fetch_assoc()) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'CampusNotFound'));
            }
        });

        $slim->get('/:limit', function ($limit) use ($api, $apiKey, $mysqli, $MySQLiHelper) {
            if ($result = $MySQLiHelper->selectAllFrom($mysqli, Config::getSQLConf()['db_campus_table'], $limit)->fetch_all(MYSQLI_ASSOC)) {
                echo json_encode(array(
                    'application' => $apiKey['app'],
                    'success' => true,
                    'result' => $result,
                ));
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(array('application' => $apiKey['app'], 'success' => false, 'error' => 'CampusNotFound'));
            }

        });
        $slim->get('/', function () use ($api, $apiKey) {
            echo json_encode(array(
                'application' => $apiKey['app'],
                'success' => true,
                'result' => array(
                    'get' => array(
                        '/id/:id',
                        '/datatel_code/:datatel_code',
                        '/:limit'
                    ),
                    'post' => array()
                )
            ));
        });
    });
    $slim->run();
    $mysqli->close();
} else {
    // Throw a 401 unauthorized, since the app is not authorized
    $api->unauthorized();
}