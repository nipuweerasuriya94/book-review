<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter', '');

        $books = Book::when($title, fn($query, $title) => $query->title($title), null); //If the title is not empty it will run the function.
    
        $books = match($filter){
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6month' => $books->popularLast6Month(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Month(),
            default => $books->latest()->withAvgRating()->withReviewsCount()
        };
        //$books = $books->get();
        $cacheKey = 'books:' . $filter . ':' . $title; //Need to have a cache key to make sure the filters are included in the result.
        $books = 
            // cache()->remember(
            // $cacheKey, 
            // 3600, 
            // fn() => 
            $books->paginate();
        // ); //This cache result replaces the regular get() function. This is used as a optimization technique.

        // $books = cache()->remember($cacheKey, 3600, function() use($books){
        //     dd('Not from cache!');
        //     return $books->get();
        // }); //Use this dump and die function to make sure the caching is working.
        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    
    /*
    public function show(Book $book)
    {
        // return view(
        //     'books.show', 
        //     ['book' => $book->load([
        //         'reviews' => fn($query)=> $query->latest()
        //     ])]  
        // );//Method is loading a book and the arrow function arrange the reviews in a certain order. This code can be used when cache optimization is not used.

        $cacheKey = 'book:' . $book->id;
        $book = cache()->remember($cacheKey, 3600, fn() => $book->load([
                 'reviews' => fn($query)=> $query->latest()
        ]));
        return view('books.show',['book' => $book]);
    }
    */
    //This show function has been recreated in a different way without route model binding to fetch the book.
    public function show(int $id)
    {
        $cacheKey = 'book:' . $id;
        $book = cache()->remember(
            $cacheKey, 
            3600, 
            fn() => Book::with([
                 'reviews' => fn($query)=> $query->latest()
            ])->withAvgRating()->withReviewsCount()->findOrfail($id)
        );
        //Load method is for models that are already loaded. Load() is an instance method. 
        //Instead we could use With() a more static method to fetch relations together with the model at the same time.
        return view('books.show',['book' => $book]);
    }





    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
