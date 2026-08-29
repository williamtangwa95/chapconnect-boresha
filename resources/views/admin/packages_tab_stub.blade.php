        <!-- ==========================================
         TAB: Membership Packages (Packages CRUD)
         ========================================== -->
        <div id="tab-packages" class="tab-content">
            <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px;">
                <div>
                    <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-box-seam-fill" style="color: #f59e0b;"></i> Membership Packages & Limits
                    </h1>
                    <p style="color: #64748b; margin: 0; font-size: 0.92rem;">Configure talent access tiers, phone number visibility, upload limits, and subscription prices.</p>
                </div>
                <button type="button" onclick="$('#add-package-modal').fadeIn(200);" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff; border: none; border-radius: 30px; font-weight: 700; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 15px rgba(245,158,11,0.35); transition: all 0.3s ease;">
                    <i class="bi bi-plus-circle-fill"></i> Create New Package
                </button>
            </div>

            <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                <div class="admin-table-container">
                    <table class="admin-table display nowrap" id="packages-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Package Name</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Duration</th>
                                <th>Phone Visibility</th>
                                <th>Images Limit</th>
                                <th>Videos Limit</th>
                                <th>News Limit</th>
                                <th>Status</th>
                                <th>Active Users</th>
                                <th style="text-align: right; width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($packages as $pkg)
                            <tr>
                                <td style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">{{ $pkg->name }}</td>
                                <td>
                                    <span style="font-size: 0.76rem; font-weight: 700; padding: 3px 8px; border-radius: 6px; {{ $pkg->package_type === 'Free' ? 'background: rgba(16,185,129,0.1); color: #10b981;' : 'background: rgba(99,102,241,0.1); color: #6366f1;' }}">
                                        {{ $pkg->package_type }}
                                    </span>
                                </td>
                                <td style="font-weight: 700; color: #1e293b;">TZS {{ number_format($pkg->price) }}</td>
                                <td>{{ $pkg->duration }} {{ $pkg->duration_unit }}</td>
                                <td>
                                    <span style="font-size: 0.76rem; font-weight: 700; padding: 3px 8px; border-radius: 6px; {{ $pkg->phone_visibility === 'Yes' ? 'background: rgba(16,185,129,0.1); color: #10b981;' : 'background: rgba(239,68,68,0.1); color: #ef4444;' }}">
                                        {{ $pkg->phone_visibility === 'Yes' ? 'Visible' : 'Hidden' }}
                                    </span>
                                </td>
                                <td style="font-weight: 700; color: #475569; text-align: center;">{{ $pkg->max_images }}</td>
                                <td style="font-weight: 700; color: #475569; text-align: center;">{{ $pkg->max_videos }}</td>
                                <td style="font-weight: 700; color: #475569; text-align: center;">{{ $pkg->max_news }}</td>
                                <td>
                                    <span style="font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; {{ $pkg->status === 'Active' ? 'background: rgba(16,185,129,0.1); color: #10b981;' : 'background: rgba(100,100,100,0.1); color: #64748b;' }}">
                                        {{ $pkg->status }}
                                    </span>
                                </td>
                                <td style="font-weight: 700; color: #6366f1; text-align: center;">{{ $pkg->user_packages_count }}</td>
                                <td style="text-align: right; display: flex; justify-content: flex-end; gap: 6px; align-items: center;">
                                    <button type="button" class="btn-edit-package"
                                        data-id="{{ $pkg->id }}"
                                        data-name="{{ $pkg->name }}"
                                        data-description="{{ $pkg->description }}"
                                        data-phone="{{ $pkg->phone_visibility }}"
                                        data-images="{{ $pkg->max_images }}"
                                        data-videos="{{ $pkg->max_videos }}"
                                        data-news="{{ $pkg->max_news }}"
                                        data-price="{{ $pkg->price }}"
                                        data-duration="{{ $pkg->duration }}"
                                        data-unit="{{ $pkg->duration_unit }}"
                                        data-type="{{ $pkg->package_type }}"
                                        data-status="{{ $pkg->status }}"
                                        style="padding: 6px 12px; font-size: 0.78rem; border: 1px solid #cbd5e1; color: var(--primary); border-radius: 8px; font-weight: 600; background: #fff; cursor: pointer;">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    @if($pkg->user_packages_count == 0)
                                    <form action="{{ url('/admin/packages/' . $pkg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');" style="margin: 0; display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 12px; font-size: 0.78rem; border: 1px solid #fca5a5; color: #ef4444; border-radius: 8px; font-weight: 600; background: #fff; cursor: pointer;">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
