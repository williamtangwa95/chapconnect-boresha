@extends('layouts.app')

@section('title', 'Chap Connect - Talent Directory')

@section('search_bar')
<form action="{{ route('home') }}" method="GET" class="Search">
    @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
    <input class="Srch" type="search" name="search" value="{{ request('search') }}" placeholder="Search talents...">
    <button type="submit" class="btn">Search</button>
</form>
@endsection

@section('content')
<!-- Quick Filter Category Bar -->
<div class="quick-filters">
    <div class="filter-title">Browse Categories</div>
    <div class="filter-tags">
        <a href="{{ route('home', ['search' => request('search')]) }}" 
           class="filter-tag {{ $currentCategory === 'all' ? 'active' : '' }}">
            All Talents <span class="filter-badge">{{ $totalTalents }}</span>
        </a>
        @foreach($categories as $slug => $label)
            <a href="{{ route('home', ['category' => $slug, 'search' => request('search')]) }}" 
               class="filter-tag {{ $currentCategory === $slug ? 'active' : '' }}">
                {{ $label }}s <span class="filter-badge">{{ $categoryCounts[$slug] ?? 0 }}</span>
            </a>
        @endforeach
    </div>
</div>

<main class="main">
    <div class="talent-grid">
        @forelse($talents as $talent)
            <div class="container">
                <div class="image">
                    @if($talent->profile_image)
                        <img src="{{ $talent->profile_image }}" alt="{{ $talent->name }}">
                    @else
                        <!-- Unsplash placeholder based on category -->
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80" alt="{{ $talent->name }}">
                    @endif
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
                No talents registered yet under this selection.
            </div>
        @endforelse
    </div>
</main>
@endsection
