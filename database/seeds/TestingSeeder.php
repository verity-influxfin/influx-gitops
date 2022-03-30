<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestingSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // 新增 10 組捐款紀錄
        foreach(range(1, 10) as $row)
        {
            DB::table('charity_event')->insert([
                'name'   => Str::random(5),
                'type'   => $type = rand(0, 1),
                'amount' => rand(1, 500) * 1000,
                'weight' => $type == 0 ? 0 : rand(1, 9)
            ]);
        }
    }
}
