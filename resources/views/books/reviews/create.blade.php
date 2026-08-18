@extends('layouts.app')

@section('content')

    <h1 class="mb-10 text-2x1">Add Review for {{ $book->title }}</h1>

   {{-- @if (session()->has('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        ⚠️ Attention: {{ session('error') }}
    </div>
    @endif --}}
    
    <form method="POST" action="{{ route('books.reviews.store', $book) }}">
        @csrf
        <label for="review">Review</label>
        <textarea name="review" id="review" required class="input mb-4"></textarea>

        <label for="rating">Rating</label>
        <select name="rating" id="rating" class="input mb-4" required>
            <option value="">Select a Rating</option>
            @for ($i=1; $i<=5; $i++)
                <option value="{{$i}}">{{$i}}</option>
            @endfor
        </select>
        <button type="submit" class="btn">Add Review</button>
    </form>


@endsection