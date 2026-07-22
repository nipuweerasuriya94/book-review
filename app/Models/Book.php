<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;



class Book extends Model
{
    use HasFactory;

    public function reviews(){
        // A book can have many reviews declaration
        return $this->hasMany(Review::class);
    }
    //Query Scopes
    //Builder can be from the both Eloquent and Query classes.
    public function scopeTitle(Builder $query, string $title): Builder | QueryBuilder{
        return $query->where('title', 'LIKE', '%'. $title . '%');
    }
    //Get the most popular books by the number of reviews.
    public function scopePopular(Builder $query, $from = null, $to = null): Builder{
        return $query->withCount(['reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)])->orderBy('reviews_count', 'desc');//Arrow function
    }
    //Get the highest rated books by sorting the books by using reviews average rating.
    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder | QueryBuilder{
        return $query->withAvg(['reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)], 'rating')
        ->orderBy('reviews_avg_rating', 'desc');
    }
    //To implement a minimum number of reviews to be considered.
    public function scopeMinReviews(Builder $query, int $minReviews): Builder | QueryBuilder{
        return $query->having('reviews_count', '>=', $minReviews);//When we are using results with aggregate function we have to use having instead of where.
    }
    //Reviews filtering function implemented seperately for re-usage.
    //Made it a private function so that it can accessed inside the method only.
    private function  dateRangeFilter(Builder $query, $from = null, $to = null){
            if($from && !$to){
                $query->where('created_at', '>=', $from);
            }elseif(!$from && $to){
                $query->where('created_at', '<=', $to);
            }elseif($from && $to){
                $query->whereBetween('created_at', [$from, $to]);
            }
            //$query is an object. Objects are passed by reference not by copy. We are modifying an existing object. 
            //Therefore, we don't need to return anything.
    }
}
