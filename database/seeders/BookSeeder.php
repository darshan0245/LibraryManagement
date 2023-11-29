<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create();

        $categories = ['Fiction', 'Non-fiction', 'Mystery', 'Science Fiction', 'Fantasy', 'Romance', 'Biography'];
        $languages = ['Hindi', 'English', 'Gujarati'];

        foreach(range(1,50) as $index){
            DB::table('books')->insert([
                'book_name' => $faker->sentence(3),
                'book_author' => $faker->name,
                'category' =>  $faker->randomElement($categories),
                'language' => $faker->randomElement($languages),
                'created_at' => now(),
                'updated_at' => now(),


            ]);

        }

     }
}
