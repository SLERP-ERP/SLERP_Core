<?php
/**
 * Created by PhpStorm.
 * User: melon
 * Date: 12/31/15
 * Time: 4:30 PM
 */

/**
 * @apiDefine PhoneParameters
 * @apiParam (Department Phone) {Integer} user_id The user that this phone belongs to.
 * @apiParam (Department Phone) {Integer} number The phone number.
 * @apiParam (Department Phone) {Integer} ext The phone number's extension, if there is one.
 * @apiParam (Department Phone) {Boolean} is_cell Signifies if the phone number is a mobile number.
 * @apiParam (Department Phone) {String} carrier The mobile carrier, if known.
 */

/**
 * @apiDefine PhoneSuccess
 * @apiSuccess (Success 2xx: Phone) {Integer} id The numeric id assigned to the email by the database.
 * @apiSuccess (Success 2xx: Phone) {Integer} user_id The user that this phone belongs to.
 * @apiSuccess (Success 2xx: Phone) {Integer} number The phone number.
 * @apiSuccess (Success 2xx: Phone) {Integer} ext The phone number's extension, if there is one.
 * @apiSuccess (Success 2xx: Phone) {Boolean} is_cell Signifies if the phone number is a mobile number.
 * @apiSuccess (Success 2xx: Phone) {String} carrier The mobile carrier, if known.
 */

/**
 * @apiDefine GetPhonesSuccessResultExample
 * @apiSuccessExample {json} Success Response:
 *      HTTP/1.1 200 OK
 *      {
 *          "success": true,
 *          "status_code": 200,
 *          "pagination": {
 *              "total_pages": 100,
 *              "current_page": 1,
 *              "result_limit": 5,
 *              "next_page": "api\/v1\/phones?limit=5&page=2",
 *              "previous_page": null
 *          },
 *          "result": [
 *              {
 *                  "id": 1,
 *                  "user_id": 67,
 *                  "number": 16441181126,
 *                  "ext": 0,
 *                  "is_cell": false,
 *                  "carrier": null
 *              },
 *              {
 *                  "id": 2,
 *                  "user_id": 83,
 *                  "number": 14235907536,
 *                  "ext": 0,
 *                  "is_cell": false,
 *                  "carrier": null
 *              },
 *              {
 *                  "id": 3,
 *                  "user_id": 85,
 *                  "number": 13716372143,
 *                  "ext": 4165,
 *                  "is_cell": true,
 *                  "carrier": "Vodaphone"
 *              },
 *              {
 *                  "id": 4,
 *                  "user_id": 83,
 *                  "number": 11862830925,
 *                  "ext": 0,
 *                  "is_cell": true,
 *                  "carrier": "MetroPCS"
 *              },
 *              {
 *                  "id": 5,
 *                  "user_id": 3,
 *                  "number": 19551878346,
 *                  "ext": 769,
 *                  "is_cell": false,
 *                  "carrier": null
 *              }
 *          ]
 *      }
 */

/**
 * @apiDefine GetPhoneSuccessResultExample
 * @apiSuccessExample {json} Success Response:
 *      HTTP/1.1 200 OK
 *      {
 *          "success": true,
 *          "status_code": 200,
 *          "pagination": [],
 *          "result": {
 *              "id": 1,
 *              "user_id": 67,
 *              "number": 16441181126,
 *              "ext": 0,
 *              "is_cell": false,
 *              "carrier": null
 *          }
 *      }
 */