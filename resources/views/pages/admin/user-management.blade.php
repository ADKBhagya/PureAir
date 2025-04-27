@extends('layouts.admin')

@section('title', 'Admin User Management')

@section('content')

<style>
     body {
        background: #ffffff;
        font-family: 'Poppins', sans-serif;
    }
    .admin-wrapper {
        color: #22577A;
    }

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .header-bar h3 {
        font-weight: 600;
        color: #22577A;
        font-size: 17px;
    }

    .add-btn {
        background-color: #22577A;
        color: white;
        padding: 6px 16px;
        font-size: 13px;
        border: none;
        border-radius: 6px;
        font-weight: 500;
    }

    .admin-list-headings {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
        font-size: 14px;
        font-weight: 600;
        color: #22577A;
        margin-bottom: 10px;
    }

    .admin-card {
        background-color: rgba(228, 228, 228, 0.45);
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 14px;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
        align-items: center;
        font-size: 13px;
    }

    .admin-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .admin-info i {
        font-size: 22px;
        color: #22577A;
    }

    .admin-details h6 {
        font-size: 14px;
        margin: 0;
        font-weight: 600;
    }

    .admin-details small {
        color: #555;
        font-size: 12px;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 12px;
    }

    .status-active {
        background-color: #198754;
        color: white;
    }

    .status-inactive {
        background-color: #dc3545;
        color: white;
    }

    .action-btn {
        background: none;
        border: none;
        font-size: 15px;
        color: #22577A;
        cursor: pointer;
    }

    .action-btn.text-danger {
        color: #dc3545;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .modal-box {
        background: #fff;
        padding: 24px;
        border-radius: 14px;
        width: 360px;
        position: relative;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .modal-box h5 {
        text-align: center;
        font-size: 17px;
        font-weight: 600;
        color: #22577A;
        margin-bottom: 18px;
    }

    .modal-box label {
        font-size: 13px;
        color: #22577A;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .modal-box input,
    .modal-box select {
        font-size: 13px;
        padding: 8px 10px;
        border-radius: 6px;
        border: 2px solid #ccc;
        width: 100%;
        margin-bottom: 12px;
        color: #000;
    }

    .modal-box input:focus,
    .modal-box select:focus {
        border-color: #80bdff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }

    .btn-submit {
        background-color: #22577A;
        color: white;
        font-size: 13px;
        font-weight: 500;
        padding: 6px;
        border: none;
        border-radius: 6px;
        width: 100%;
    }

    .modal-close {
        position: absolute;
        top: 10px;
        right: 14px;
        font-size: 18px;
        font-weight: bold;
        color: #22577A;
        cursor: pointer;
    }

    .btn-confirm {
        background-color: #FF7700;
        color: white;
        padding: 10px 0;
        width: 100%;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        margin-top: 12px;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-cancel {
        background-color: #E4E4E4;
        color: #22577A;
        padding: 10px 0;
        width: 100%;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        margin-top: 10px;
        font-size: 14px;
        cursor: pointer;
    }

</style>

<div class="admin-wrapper">
    <div class="header-bar">
        <h3>Admin User Management</h3>
        <div class="text-end">
            <div class="text-muted mb-2" style="font-size:13px;">
                Hello, {{ auth()->user()->full_name }} <i class="bi bi-person-circle ms-1"></i>
            </div>

            @if(auth()->user()->role === 'web_master')
                <button class="add-btn" onclick="showModal()">
                    <i class="bi bi-person-plus me-2"></i>Add Admins
                </button>
            @endif
        </div>
    </div>

    @if(auth()->user()->role === 'web_master')
        <div class="admin-list-headings">
            <div>Admin</div>
            <div>Status</div>
            <div>Date</div>
            <div>Role</div>
            <div>Actions</div>
        </div>

        @foreach($users as $user)
        <div class="admin-card" data-id="{{ $user->id }}">
            <div class="admin-info">
                <i class="bi bi-person-circle"></i>
                <div class="admin-details">
                    <h6>{{ $user->full_name }}</h6>
                    <small>{{ $user->email }}</small>
                </div>
            </div>
            <div>
                <span class="status-badge {{ $user->status === 'Active' ? 'status-active' : 'status-inactive' }}">
                    {{ $user->status }}
                </span>
            </div>
            <div>{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}</div>
            <div>{{ $user->role === 'super_admin' ? 'Super Admin' : 'Admin' }}</div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <button class="action-btn" onclick="fillEditModal({{ $user->id }}, '{{ e($user->full_name) }}', '{{ e($user->email) }}', '{{ e($user->role) }}', '{{ e($user->status) }}')">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="action-btn text-danger" onclick="showDeleteModal({{ $user->id }})">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
        </div>
        @endforeach
    @else
    <div style="height: 80vh; display: flex; justify-content: center; align-items: center;">
        <div class="text-center p-4" style="background: #fff3f3; border: 2px dashed #dc3545; border-radius: 12px; max-width: 600px; width: 100%;">
            <div style="font-size: 50px; color: #dc3545; margin-bottom: 12px;">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h5 style="color: #dc3545; font-weight: 600; font-size: 18px;">Access Restricted</h5>
            <p style="color: #444; font-size: 14px;">
                Sorry! You do not have permission to access this section.<br>
                Only <strong>Web Masters</strong> are allowed to manage admin users.
            </p>
            <button onclick="window.location.href='{{ route('admin.dashboard') }}'" class="btn mt-2"
                style="background-color: #dc3545; color: white; padding: 6px 18px; font-size: 13px; border-radius: 8px; transition: background 0.3s;">
                Back to Dashboard
            </button>
        </div>
    </div>
    @endif
</div>

@if(auth()->user()->role === 'web_master')
{{-- Add Admin Modal --}}
<div class="modal-overlay" id="addAdminModal">
    <div class="modal-box">
        <div class="modal-close" onclick="hideModal()">&times;</div>
        <h5>New Admin</h5>
        <form method="POST" action="{{ route('admin.user.store') }}">
            @csrf
            <label for="addName">Full Name</label>
            <input type="text" id="addName" name="full_name" required>

            <label for="addEmail">Email</label>
            <input type="email" id="addEmail" name="email" required>

            <label for="addPassword">Password</label>
            <input type="password" id="addPassword" name="password" required>

            <div class="d-flex gap-2">
                <div class="w-50">
                    <label for="addRole">Role</label>
                    <select id="addRole" name="role">
                        <option>Admin</option>
                        <option>Super Admin</option>
                    </select>
                </div>
                <div class="w-50">
                    <label for="addStatus">Status</label>
                    <select id="addStatus" name="status">
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-submit mt-2">Add</button>
        </form>
    </div>
</div>

{{-- Edit Admin Modal --}}
<div class="modal-overlay" id="editAdminModal" data-editing-card-id="">
    <div class="modal-box">
        <div class="modal-close" onclick="hideEditModal()">&times;</div>
        <h5>Edit Admin</h5>
        <form method="POST" onsubmit="updateAdmin(event)">
            @csrf
            <label>Full Name</label>
            <input type="text" name="editName" required>

            <label>Email</label>
            <input type="email" name="editEmail" required>

            <label>Password</label>
            <input type="password" name="editPassword" placeholder="Leave blank to keep current">

            <div class="d-flex gap-2">
                <div class="w-50">
                    <label>Role</label>
                    <select name="editRole">
                        <option>Admin</option>
                        <option>Super Admin</option>
                    </select>
                </div>
                <div class="w-50">
                    <label>Status</label>
                    <select name="editStatus">
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-submit mt-2">Update</button>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal-overlay" id="deleteConfirmModal">
    <div class="modal-box text-center" style="width: 400px;">
        <div class="modal-close" onclick="hideDeleteModal()">&times;</div>
        <h5 style="color: #22577A; font-size: 18px;">Confirm Deletion</h5>
        <p style="margin-top: 8px; font-size: 14px;">Are you sure you want to delete this admin?</p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-confirm">Yes, Delete</button>
        </form>
        <button onclick="hideDeleteModal()" class="btn-cancel">Cancel</button>
    </div>
</div>

@endif

<script>
    function showModal() {
        document.getElementById('addAdminModal').style.display = 'flex';
    }

    function hideModal() {
        document.getElementById('addAdminModal').style.display = 'none';
    }

    function hideEditModal() {
        document.getElementById('editAdminModal').style.display = 'none';
    }

    function hideDeleteModal() {
        document.getElementById('deleteConfirmModal').style.display = 'none';
    }

    function fillEditModal(id, name, email, role, status) {
        const modal = document.getElementById('editAdminModal');
        modal.dataset.editingCardId = id;
        modal.querySelector('input[name="editName"]').value = name;
        modal.querySelector('input[name="editEmail"]').value = email;
        modal.querySelector('select[name="editRole"]').value = role === 'super_admin' ? 'Super Admin' : 'Admin';
        modal.querySelector('select[name="editStatus"]').value = status;
        modal.style.display = 'flex';
    }

    function updateAdmin(e) {
        e.preventDefault();
        const form = e.target;
        const modal = document.getElementById('editAdminModal');
        const id = modal.dataset.editingCardId;
        const methodInput = document.createElement('input');
        methodInput.setAttribute('type', 'hidden');
        methodInput.setAttribute('name', '_method');
        methodInput.setAttribute('value', 'PUT');
        form.appendChild(methodInput);
        form.setAttribute('action', `/admin/user-management/${id}`);
        form.submit();
    }
    function showDeleteModal(id) {
    const modal = document.getElementById('deleteConfirmModal');
    const form = document.getElementById('deleteForm');
    form.setAttribute('action', `/admin/user-management/${id}`);
    modal.style.display = 'flex';
    }

    function hideDeleteModal() {
        document.getElementById('deleteConfirmModal').style.display = 'none';
    }

</script>

@endsection
