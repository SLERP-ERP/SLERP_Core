<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Base Tables
        $this->call('UserTableSeeder');
        $this->call('EmailTableSeeder');
        $this->call('PhoneTableSeeder');
        $this->call('CampusTableSeeder');
        $this->call('BuildingTableSeeder');
        $this->call('RoomTableSeeder');
        $this->call('DepartmentTableSeeder');
        $this->call('CourseTableSeeder');
        $this->call('RoleTableSeeder');
        $this->call('ApikeyTableSeeder');
        // Pivot Tables
        $this->call('UserCourseTableSeeder');
        $this->call('UserRoleTableSeeder');
        $this->call('UserRoomTableSeeder');

        Model::reguard();
    }
}
