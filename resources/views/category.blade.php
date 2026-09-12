@extends('layouts.app')

@section('title', 'Chap Connect - ' . $categoryLabel)

@section('search_bar')
<form action="{{ route('home') }}" method="GET" class="Search">
    <input type="hidden" name="category" value="{{ $category }}">
    <input class="Srch" type="search" name="search" placeholder="Search {{ strtolower($categoryLabel) }}s...">
    <button type="submit" class="btn">Search</button>
</form>
@endsection

@section('content')
<main class="main" style="margin-top: 100px;">
    <div class="willshow">
        <h2>{{ $categoryLabel }}s</h2>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 5px;">Discover elite profiles under this category</p>
    </div>
    
    <div class="talent-grid">
        @forelse($talents as $talent)
            <div class="container">
                <div class="imagea">
                        <img src="{{ $talent->avatar_url }}" alt="{{ $talent->name }}">
                </div>
                <div class="details">
                    <h2>{{ $talent->name }}</h2>
                    <h5>{{ $talent->category_label }}</h5>
                    <p class="card-desc">{{ $talent->description ?: 'No bio description provided yet.' }}</p>
                    <a href="{{ route('profile', $talent->id) }}">
                        <button class="vbtn">View Profile</button>
                    </a>
                </div>
            </div>
        @empty
            <div class="no-results">
                No talents registered under {{ strtolower($categoryLabel) }} yet.
            </div>
        @endforelse
    </div>
</main>
@endsection
