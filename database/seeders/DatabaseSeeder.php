<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory(33)->create()->each(function ($book){
        //Create 33 book records

            $numReviews = random_int(5, 30); //For each book random number of reviews
            Review::factory()->count($numReviews)
                    ->good() // Create good reviews
                    ->for($book)// Update the book_id column
                    ->create();
        });
         Book::factory(33)->create()->each(function ($book){
        //Create 33 book records

            $numReviews = random_int(5, 30); //For each book random number of reviews
            Review::factory()->count($numReviews)
                    ->average() // Create average reviews
                    ->for($book)// Update the book_id column
                    ->create();
        });
         Book::factory(34)->create()->each(function ($book){
        //Create 34 book records

            $numReviews = random_int(5, 30); //For each book random number of reviews
            Review::factory()->count($numReviews)
                    ->bad() // Create bad reviews
                    ->for($book)// Update the book_id column
                    ->create();
        });
    }
}
