<?php
/**
 * Created by PhpStorm.
 * User: melon
 * Date: 11/17/15
 * Time: 8:06 PM
 */

use Illuminate\Database\Seeder;
use App\Model\Course;
use App\Model\User;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserCourseTableSeeder extends Seeder
{
    public function run()
    {

        $faker = Faker::create();
        $userIds = User::lists('id');
        $courseIds = Course::lists('id');

        foreach (range(1, 50) as $index) {

            $pair = $faker->unique()->randomElement([
                [$faker->randomElement($userIds), $faker->randomElement($courseIds)],
                [$faker->randomElement($userIds), $faker->randomElement($courseIds)],
                [$faker->randomElement($userIds), $faker->randomElement($courseIds)]
            ]);

            DB::table('course_user')->insert([
                'course_id' => $pair[1],
                'user_id' => $pair[0]
            ]);
        }
    }

}