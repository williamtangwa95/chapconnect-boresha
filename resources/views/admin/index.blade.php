@extends('layouts.app')

@section('title', 'Chap Connect - Super Admin Panel')

@section('content')
<!-- Fixed Sidebar (Left) -->
<aside class="admin-sidebar">
    <!-- Logo Header -->
    <div class="admin-sidebar-logo">
        <img src="/logo.png" alt="Amstroom Logo" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop'">
        <h1 style="color: #facc15; font-size: 1.15rem; font-weight: 800;">AMSTROOM</h1>
        <p>Admin Portal</p>
    </div>

    <!-- User Profile block -->
    <div class="admin-sidebar-user">
        <div class="avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="info">
            <h4>{{ auth()->user()->name }}</h4>
            <p>Admin</p>
        </div>
    </div>

    <!-- Sidebar Menu Navigation -->
    <ul class="admin-sidebar-menu">
        <li id="li-dashboard">
            <a href="#dashboard" class="tab-link" data-tab="dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Dashboard Overview
            </a>
        </li>
        <li id="li-talents">
            <a href="#talents" class="tab-link" data-tab="talents">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 10.025 20.5a11.381 11.381 0 0 1-5.055-1.264v-.109A9.336 9.336 0 0 1 9 15.178a9.364 9.364 0 0 1 6 .876ZM15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6.563 2a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0ZM7.5 12a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" />
                </svg>
                Registered Talents
            </a>
        </li>
        <li id="li-categories">
            <a href="#categories" class="tab-link" data-tab="categories">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a1.5 1.5 0 0 0 2.122 0l4.318-4.318a1.5 1.5 0 0 0 0-2.122L10.15 3.659A2.25 2.25 0 0 0 9.568 3Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                </svg>
                Manage Categories
            </a>
        </li>
        <li>
            <a href="{{ route('home') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18" />
                </svg>
                Categories Directory
            </a>
        </li>
        <li>
            <a href="{{ route('dashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                My Profile settings
            </a>
        </li>
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #f87171;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                </svg>
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</aside>

<!-- Main Content Wrapper (Right) -->
<main class="admin-main-content">
    
    <!-- ==========================================
         TAB 1: Dashboard Overview content
         ========================================== -->
    <div id="tab-dashboard" class="tab-content">
        <!-- Header banner -->
        <div class="admin-header">
            <h1>Dashboard Overview</h1>
            <p>Welcome back to your administration portal control center.</p>
        </div>

        <!-- Stats Cards Grid -->
        <div class="admin-stats-grid" style="margin-bottom: 30px;">
            <!-- Card 1: Registered Talents -->
            <div class="stat-card-custom blue">
                <div class="stat-info">
                    <h3>Registered Talents</h3>
                    <p>{{ $totalUsers }}</p>
                </div>
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#2563eb">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 10.025 20.5a11.381 11.381 0 0 1-5.055-1.264v-.109A9.336 9.336 0 0 1 9 15.178a9.364 9.364 0 0 1 6 .876ZM15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6.563 2a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0ZM7.5 12a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" />
                    </svg>
                </div>
            </div>

            <!-- Card 2: Portfolio Photos -->
            <div class="stat-card-custom indigo">
                <div class="stat-info">
                    <h3>Portfolio Photos</h3>
                    <p>{{ $totalPhotos }}</p>
                </div>
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4f46e5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </div>
            </div>

            <!-- Card 3: Portfolio Videos -->
            <div class="stat-card-custom pink">
                <div class="stat-info">
                    <h3>Portfolio Videos</h3>
                    <p>{{ $totalVideos }}</p>
                </div>
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#ec4899">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Quick Actions Panel -->
        <div class="admin-card">
            <h2 style="margin-bottom: 15px;">Quick Actions</h2>
            <div style="display: flex; gap: 15px; justify-content: space-between; flex-wrap: wrap; width: 100%;">
                <a href="{{ route('register') }}" class="quick-action-btn">
                    <span style="font-size: 1.2rem; line-height: 1;">+</span> Register Admin/User
                </a>
                <a href="{{ route('dashboard') }}" class="quick-action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    Edit My Profile
                </a>
                <a href="{{ route('home') }}" class="quick-action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                    View Live Site
                </a>
            </div>
        </div>
    </div>

    <!-- ==========================================
         TAB 2: Registered Talents content
         ========================================== -->
    <div id="tab-talents" class="tab-content">
        <div class="admin-header">
            <h1>Registered Talents Directory</h1>
            <p>Manage, view, or moderate creative talent profiles in Chap Connect.</p>
        </div>

        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                <h2 style="margin: 0;">Users List</h2>
                
                <!-- Search bar -->
                <form action="{{ route('admin.dashboard') }}" method="GET" style="display: flex; gap: 8px; max-width: 400px; width: 100%;">
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search by name, category..." style="padding: 8px 14px; background: #fff; color: #1e293b;">
                    <button type="submit" class="btn-submit" style="padding: 8px 20px; font-size: 0.85rem; box-shadow: none;">Search</button>
                </form>
            </div>

            <!-- Wrap table inside a bulk delete form -->
            <form id="bulk-delete-form" action="{{ route('admin.users.bulk-delete') }}" method="POST" style="margin: 0;">
                @csrf
                @method('DELETE')

                <!-- Users Table -->
                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;">
                                    <input type="checkbox" id="select-all-talents" style="width: auto; cursor: pointer;">
                                </th>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Category</th>
                                <th>Country</th>
                                <th>Status</th>
                                <th>Registered Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $u)
                                <tr>
                                    <td style="text-align: center;">
                                        <input type="checkbox" name="ids[]" value="{{ $u->id }}" class="talent-checkbox" style="width: auto; cursor: pointer;">
                                    </td>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $u->name }}</td>
                                    <td style="color: #64748b;">{{ $u->email }}</td>
                                    <td><span style="color: #2563eb; font-weight: 500;">{{ $u->category_label }}</span></td>
                                    <td>{{ $u->country }}</td>
                                    <td>
                                        @if($u->is_published)
                                            <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.72rem;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.25);"><span style="width:6px;height:6px;border-radius:50%;background:#10b981;display:inline-block;"></span>Live</span>
                                        @else
                                            <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.72rem;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(100,100,100,0.1);color:#888;border:1px solid rgba(255,255,255,0.08);"><span style="width:6px;height:6px;border-radius:50%;background:#888;display:inline-block;"></span>Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $u->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                                            <!-- View Profile -->
                                            <a href="{{ route('profile', $u->id) }}" target="_blank" class="social-btn" style="padding: 6px 12px; margin-top: 0; font-size: 0.75rem; border: 1px solid #cbd5e1; color: #334155; border-radius: 6px; font-weight: 600; background: #fff; text-decoration: none;">
                                                View
                                            </a>

                                            <!-- Edit User (Triggers Edit Modal) -->
                                            <button type="button" class="social-btn btn-edit-user" 
                                                    data-id="{{ $u->id }}" 
                                                    data-name="{{ $u->name }}" 
                                                    data-email="{{ $u->email }}" 
                                                    data-phone="{{ $u->phone }}" 
                                                    data-category="{{ $u->category }}" 
                                                    style="padding: 6px 12px; margin-top: 0; font-size: 0.75rem; border: 1px solid #cbd5e1; color: #0284c7; border-radius: 6px; font-weight: 600; background: #fff; cursor: pointer;">
                                                Edit
                                            </button>

                                            <!-- Toggle Publish / Unpublish -->
                                            <form action="{{ route('admin.user.toggle-publish', $u->id) }}" method="POST" style="margin:0;display:inline;">
                                                @csrf
                                                <button type="submit" style="padding: 6px 12px; margin-top: 0; font-size: 0.75rem; border: 1px solid {{ $u->is_published ? 'rgba(239,68,68,0.4)' : 'rgba(16,185,129,0.4)' }}; color: {{ $u->is_published ? '#ef4444' : '#10b981' }}; border-radius: 6px; font-weight: 600; background: {{ $u->is_published ? 'rgba(239,68,68,0.08)' : 'rgba(16,185,129,0.08)' }}; cursor: pointer;">
                                                    {{ $u->is_published ? 'Unpublish' : 'Publish' }}
                                                </button>
                                            </form>

                                            <!-- Reset Password (POST Form) -->
                                            <button type="button" class="social-btn btn-reset-password" 
                                                    data-id="{{ $u->id }}" 
                                                    data-name="{{ $u->name }}"
                                                    style="padding: 6px 12px; margin-top: 0; font-size: 0.75rem; border: 1px solid #cbd5e1; color: #d97706; border-radius: 6px; font-weight: 600; background: #fff; cursor: pointer;">
                                                Reset Pass
                                            </button>

                                            <!-- Delete User (Standard Button Triggered Form) -->
                                            <button type="button" class="btn-delete btn-single-delete" 
                                                    data-id="{{ $u->id }}" 
                                                    data-name="{{ $u->name }}"
                                                    style="padding: 6px 12px; border-radius: 6px; cursor: pointer;">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #64748b; padding: 40px 0;">No registered users found matching search criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="pagination-wrapper">
                    {{ $users->links() }}
                </div>

                <!-- Floating Bulk Action Panel (Hidden inside form) -->
                <div id="bulk-action-bar" class="bulk-action-bar">
                    <div class="bulk-action-info"><span id="selected-count">0</span> talents selected</div>
                    <div class="bulk-action-buttons">
                        <button type="button" class="bulk-btn-cancel" id="bulk-cancel-btn">Cancel</button>
                        <button type="button" id="bulk-publish-btn" style="padding: 9px 18px; background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.35); border-radius: 8px; font-weight: 700; font-size: 0.83rem; cursor: pointer; transition: all 0.2s;">🌐 Publish Selected</button>
                        <button type="button" id="bulk-unpublish-btn" style="padding: 9px 18px; background: rgba(239,68,68,0.12); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; font-weight: 700; font-size: 0.83rem; cursor: pointer; transition: all 0.2s;">🔒 Unpublish Selected</button>
                        <button type="submit" class="bulk-btn-delete" onclick="return confirm('WARNING: Are you sure you want to permanently delete all selected users and their media files?');">Delete Selected</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ==========================================
         TAB 3: Manage Categories content
         ========================================== -->
    <div id="tab-categories" class="tab-content">
        <div class="admin-header">
            <h1>Manage Talent Categories</h1>
            <p>Define new creative categories for users to register under.</p>
        </div>

        <div class="admin-card">
            <h2 style="margin-bottom: 6px;">Dynamic Categories Directory</h2>
            <p style="margin-bottom: 25px; font-size: 0.9rem;">Add new registration categories dynamically, or clean up unused category labels.</p>

            <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                <!-- Add Category Form -->
                <div style="flex: 1; min-width: 300px; border-right: 1px solid #cbd5e1; padding-right: 30px;">
                    <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.05rem; color: #0f172a; font-weight: 700;">Add New Category</h3>
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="cat_name" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Category Name</label>
                            <input type="text" id="cat_name" name="name" class="form-control" placeholder="e.g. Model" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1;">
                            <small style="color: #64748b; font-size: 0.75rem; display: block; margin-top: 4px;">Slug will be generated automatically from the name.</small>
                        </div>
                        <button type="submit" class="btn-submit" style="width: 100%; margin-top: 10px; padding: 10px 20px; font-size: 0.85rem;">Create Category</button>
                    </form>
                </div>

                <!-- Categories List Table -->
                <div style="flex: 2; min-width: 350px;">
                    <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.05rem; color: #0f172a; font-weight: 700;">Active Categories</h3>
                    <div class="admin-table-container" style="max-height: 400px; overflow-y: auto;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $cat)
                                    <tr>
                                        <td style="font-weight: 600; color: #0f172a;">{{ $cat->name }}</td>
                                        <td style="color: #64748b; font-family: monospace;">{{ $cat->slug }}</td>
                                        <td>
                                            <div style="display: flex; gap: 6px; align-items: center;">
                                                <!-- View Category Page -->
                                                <a href="{{ route('category', $cat->slug) }}" target="_blank" class="social-btn" style="padding: 6px 12px; margin-top: 0; font-size: 0.75rem; border: 1px solid #cbd5e1; color: #334155; border-radius: 6px; font-weight: 600; background: #fff; text-decoration: none;">
                                                    View
                                                </a>
                                                
                                                <!-- Edit Category Button -->
                                                <button type="button" class="social-btn btn-edit-category" 
                                                        data-id="{{ $cat->id }}" 
                                                        data-name="{{ $cat->name }}" 
                                                        style="padding: 6px 12px; margin-top: 0; font-size: 0.75rem; border: 1px solid #cbd5e1; color: #0284c7; border-radius: 6px; font-weight: 600; background: #fff; cursor: pointer;">
                                                    Edit
                                                </button>

                                                <!-- Delete Category Form -->
                                                <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this category?');" style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-delete" style="padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; cursor: pointer;">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- ==========================================
     MODALS SECTION
     ========================================== -->

<!-- Edit User Modal Popup -->
<div id="edit-user-modal" class="admin-modal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3>Edit Talent Profile</h3>
            <button type="button" class="admin-modal-close" id="close-edit-modal">&times;</button>
        </div>
        <form id="edit-user-form" method="POST" action="">
            @csrf
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="edit_name" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Stage / Full Name</label>
                <input type="text" id="edit_name" name="name" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="edit_email" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Email Address</label>
                <input type="email" id="edit_email" name="email" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="edit_category_input" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Primary Category</label>
                <div class="searchable-select-container">
                    <input type="text" id="edit_category_input" class="form-control select-search-input" placeholder="Type to search category..." autocomplete="off" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1;">
                    <input type="hidden" name="category" class="select-hidden-value" id="edit_category" required>
                    <div class="select-dropdown-options">
                        @foreach($categories as $cat)
                            <div class="select-option-item" data-slug="{{ $cat->slug }}" data-name="{{ $cat->name }}">
                                {{ $cat->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="edit_phone" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Phone Number (WhatsApp)</label>
                <input type="text" id="edit_phone" name="phone" class="form-control" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1;">
            </div>

            <button type="submit" class="btn-submit" style="width: 100%; padding: 12px 20px; font-weight: 600;">Save Profile Updates</button>
        </form>
    </div>
</div>

<!-- Edit Category Modal Popup -->
<div id="edit-category-modal" class="admin-modal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3>Edit Category Name</h3>
            <button type="button" class="admin-modal-close" id="close-category-modal">&times;</button>
        </div>
        <form id="edit-category-form" method="POST" action="">
            @csrf
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="edit_cat_name" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Category Name</label>
                <input type="text" id="edit_cat_name" name="name" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1;">
                <small style="color: #64748b; font-size: 0.75rem; display: block; margin-top: 4px;">Slug will be updated and existing talent links will be preserved.</small>
            </div>
            <button type="submit" class="btn-submit" style="width: 100%; padding: 12px 20px; font-weight: 600;">Save Category Name</button>
        </form>
    </div>
</div>

<!-- Standalone Hidden Forms for Single Row Actions -->
<form id="single-delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="reset-password-form" action="" method="POST" style="display: none;">
    @csrf
</form>

<!-- Bulk Publish / Unpublish forms (ids[] populated by JS) -->
<form id="bulk-publish-form" action="{{ route('admin.users.bulk-publish') }}" method="POST" style="display: none;">
    @csrf
    <div id="bulk-publish-ids"></div>
</form>

<form id="bulk-unpublish-form" action="{{ route('admin.users.bulk-unpublish') }}" method="POST" style="display: none;">
    @csrf
    <div id="bulk-unpublish-ids"></div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // -------------------------------------------------------------
        // 1. SPA Tab Navigation Switching
        // -------------------------------------------------------------
        const tabLinks = document.querySelectorAll(".admin-sidebar-menu li a.tab-link");
        const tabContents = document.querySelectorAll(".tab-content");

        function switchTab(tabId) {
            tabContents.forEach(content => {
                content.classList.remove("active");
            });

            const activeContent = document.getElementById("tab-" + tabId);
            if (activeContent) {
                activeContent.classList.add("active");
            }

            tabLinks.forEach(link => {
                const li = link.closest("li");
                if (link.getAttribute("data-tab") === tabId) {
                    li.classList.add("active");
                } else {
                    li.classList.remove("active");
                }
            });

            history.pushState(null, null, "#" + tabId);
        }

        tabLinks.forEach(link => {
            link.addEventListener("click", (e) => {
                const tabId = link.getAttribute("data-tab");
                if (tabId) {
                    e.preventDefault();
                    switchTab(tabId);
                }
            });
        });

        let defaultTab = "dashboard";
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search') || urlParams.has('page')) {
            defaultTab = "talents";
        } else if (window.location.hash) {
            const hash = window.location.hash.substring(1);
            if (["dashboard", "talents", "categories"].includes(hash)) {
                defaultTab = hash;
            }
        }
        switchTab(defaultTab);

        // -------------------------------------------------------------
        // 2. Checkboxes & Floating Bulk Action Panel
        // -------------------------------------------------------------
        const selectAllCheckbox = document.getElementById("select-all-talents");
        const talentCheckboxes = document.querySelectorAll(".talent-checkbox");
        const bulkActionBar = document.getElementById("bulk-action-bar");
        const selectedCountSpan = document.getElementById("selected-count");
        const bulkCancelBtn = document.getElementById("bulk-cancel-btn");

        function updateBulkPanelState() {
            const checkedCheckboxes = document.querySelectorAll(".talent-checkbox:checked");
            const checkedCount = checkedCheckboxes.length;

            selectedCountSpan.textContent = checkedCount;

            if (checkedCount > 0) {
                bulkActionBar.classList.add("active");
            } else {
                bulkActionBar.classList.remove("active");
            }

            // Sync Header Checkbox
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = (checkedCount === talentCheckboxes.length && talentCheckboxes.length > 0);
            }
        }

        // Select All handler
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener("change", () => {
                talentCheckboxes.forEach(cb => {
                    cb.checked = selectAllCheckbox.checked;
                });
                updateBulkPanelState();
            });
        }

        // Individual Checkbox handlers
        talentCheckboxes.forEach(cb => {
            cb.addEventListener("change", () => {
                updateBulkPanelState();
            });
        });

        // Bulk Cancel click
        if (bulkCancelBtn) {
            bulkCancelBtn.addEventListener("click", () => {
                talentCheckboxes.forEach(cb => {
                    cb.checked = false;
                });
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                }
                updateBulkPanelState();
            });
        }

        // Helper: collect checked IDs and inject into a target form container, then submit
        function bulkSubmitWithIds(formId, idsContainerId, confirmMsg) {
            const checked = document.querySelectorAll(".talent-checkbox:checked");
            if (checked.length === 0) return;
            if (!confirm(confirmMsg)) return;

            const form = document.getElementById(formId);
            const container = document.getElementById(idsContainerId);
            container.innerHTML = "";
            checked.forEach(cb => {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "ids[]";
                input.value = cb.value;
                container.appendChild(input);
            });
            form.submit();
        }

        // Bulk Publish click
        const bulkPublishBtn = document.getElementById("bulk-publish-btn");
        if (bulkPublishBtn) {
            bulkPublishBtn.addEventListener("click", () => {
                bulkSubmitWithIds(
                    "bulk-publish-form",
                    "bulk-publish-ids",
                    "Publish all selected talent profiles? They will become visible on the homepage."
                );
            });
        }

        // Bulk Unpublish click
        const bulkUnpublishBtn = document.getElementById("bulk-unpublish-btn");
        if (bulkUnpublishBtn) {
            bulkUnpublishBtn.addEventListener("click", () => {
                bulkSubmitWithIds(
                    "bulk-unpublish-form",
                    "bulk-unpublish-ids",
                    "Unpublish all selected talent profiles? They will be hidden from the homepage."
                );
            });
        }

        // Initialize Custom Searchable Select Dropdowns in Admin
        document.querySelectorAll(".searchable-select-container").forEach(container => {
            const input = container.querySelector(".select-search-input");
            const hidden = container.querySelector(".select-hidden-value");
            const optionsPanel = container.querySelector(".select-dropdown-options");
            const optionItems = container.querySelectorAll(".select-option-item");

            input.addEventListener("focus", () => {
                optionsPanel.classList.add("active");
                optionItems.forEach(item => item.style.display = "block");
            });

            document.addEventListener("click", (e) => {
                if (!container.contains(e.target)) {
                    optionsPanel.classList.remove("active");
                    const selected = container.querySelector(".select-option-item.selected");
                    if (selected) {
                        input.value = selected.getAttribute("data-name");
                        hidden.value = selected.getAttribute("data-slug");
                    } else {
                        input.value = "";
                        hidden.value = "";
                    }
                }
            });

            input.addEventListener("input", () => {
                const query = input.value.toLowerCase().trim();
                optionItems.forEach(item => {
                    const name = item.getAttribute("data-name").toLowerCase();
                    if (name.includes(query)) {
                        item.style.display = "block";
                    } else {
                        item.style.display = "none";
                    }
                });
            });

            optionItems.forEach(item => {
                item.addEventListener("click", () => {
                    input.value = item.getAttribute("data-name");
                    hidden.value = item.getAttribute("data-slug");
                    optionItems.forEach(opt => opt.classList.remove("selected"));
                    item.classList.add("selected");
                    optionsPanel.classList.remove("active");
                });
            });
        });

        // -------------------------------------------------------------
        // 3. Edit User Modal Toggling
        // -------------------------------------------------------------
        const editModal = document.getElementById("edit-user-modal");
        const closeEditModalBtn = document.getElementById("close-edit-modal");
        const editUserForm = document.getElementById("edit-user-form");
        
        const editNameInput = document.getElementById("edit_name");
        const editEmailInput = document.getElementById("edit_email");
        const editPhoneInput = document.getElementById("edit_phone");

        document.querySelectorAll(".btn-edit-user").forEach(btn => {
            btn.addEventListener("click", () => {
                const id = btn.getAttribute("data-id");
                const name = btn.getAttribute("data-name");
                const email = btn.getAttribute("data-email");
                const phone = btn.getAttribute("data-phone");
                const category = btn.getAttribute("data-category");

                // Set Action Form Path
                editUserForm.setAttribute("action", `/admin/user/${id}/update`);

                // Set Input Fields
                editNameInput.value = name;
                editEmailInput.value = email;
                editPhoneInput.value = phone || "";

                // Populate and sync searchable category select in modal
                const modalCatContainer = document.querySelector("#edit-user-modal .searchable-select-container");
                const catInput = modalCatContainer.querySelector(".select-search-input");
                const catHidden = modalCatContainer.querySelector(".select-hidden-value");
                const catOptions = modalCatContainer.querySelectorAll(".select-option-item");

                catHidden.value = category;
                catOptions.forEach(opt => {
                    if (opt.getAttribute("data-slug") === category) {
                        opt.classList.add("selected");
                        catInput.value = opt.getAttribute("data-name");
                    } else {
                        opt.classList.remove("selected");
                    }
                });

                // Open modal
                editModal.classList.add("active");
            });
        });

        // Close Modal Handlers
        if (closeEditModalBtn) {
            closeEditModalBtn.addEventListener("click", () => {
                editModal.classList.remove("active");
            });
        }

        window.addEventListener("click", (e) => {
            if (e.target === editModal) {
                editModal.classList.remove("active");
            }
        });

        // -------------------------------------------------------------
        // 4. Edit Category Modal Toggling
        // -------------------------------------------------------------
        const editCatModal = document.getElementById("edit-category-modal");
        const closeCatModalBtn = document.getElementById("close-category-modal");
        const editCatForm = document.getElementById("edit-category-form");
        const editCatNameInput = document.getElementById("edit_cat_name");

        document.querySelectorAll(".btn-edit-category").forEach(btn => {
            btn.addEventListener("click", () => {
                const id = btn.getAttribute("data-id");
                const name = btn.getAttribute("data-name");

                // Set Action Form Path
                editCatForm.setAttribute("action", `/admin/categories/${id}/update`);

                // Set Name Input Field
                editCatNameInput.value = name;

                // Open modal
                editCatModal.classList.add("active");
            });
        });

        // Close Modal Handlers
        if (closeCatModalBtn) {
            closeCatModalBtn.addEventListener("click", () => {
                editCatModal.classList.remove("active");
            });
        }

        window.addEventListener("click", (e) => {
            if (e.target === editCatModal) {
                editCatModal.classList.remove("active");
            }
        });

        // -------------------------------------------------------------
        // 5. Single Row Action Form Triggers
        // -------------------------------------------------------------
        
        // Single Delete
        document.querySelectorAll(".btn-single-delete").forEach(btn => {
            btn.addEventListener("click", () => {
                const id = btn.getAttribute("data-id");
                const name = btn.getAttribute("data-name");

                if (confirm(`WARNING: Are you sure you want to permanently delete user account '${name}' and all their photos/videos?`)) {
                    const deleteForm = document.getElementById("single-delete-form");
                    deleteForm.setAttribute("action", `/admin/user/${id}`);
                    deleteForm.submit();
                }
            });
        });

        // Password Reset
        document.querySelectorAll(".btn-reset-password").forEach(btn => {
            btn.addEventListener("click", () => {
                const id = btn.getAttribute("data-id");
                const name = btn.getAttribute("data-name");

                if (confirm(`Are you sure you want to reset the password for talent '${name}' to 'password123'?`)) {
                    const resetForm = document.getElementById("reset-password-form");
                    resetForm.setAttribute("action", `/admin/user/${id}/reset-password`);
                    resetForm.submit();
                }
            });
        });
    });
</script>
@endsection
