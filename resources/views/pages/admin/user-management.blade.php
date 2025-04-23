@extends('layouts.admin')

@section('title', 'Admin User Management')

@section('content')
<style>
    .admin-wrapper { color: #22577A; }

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .header-bar h3 {
        font-weight: 600;
        color: #22577A;
        font-size: 21px;
    }

    .add-btn {
        background-color: #22577A;
        color: white;
        padding: 8px 20px;
        font-weight: 500;
        font-size: 14px;
        border-radius: 8px;
        border: none;
    }

    .admin-list-headings {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
        font-weight: 600;
        font-size: 15px;
        color: #22577A;
        margin-bottom: 12px;
    }

    .admin-card {
        background-color: rgba(228, 228, 228, 0.45);
        border-radius: 16px;
        padding: 20px 30px;
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
        align-items: center;
        gap: 10px;
    }

    .admin-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .admin-info i {
        font-size: 28px;
        color: #22577A;
    }

    .admin-details h6 {
        font-weight: 600;
        margin-bottom: 3px;
        font-size: 16px;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
    }

    .status-active { background-color: #198754; color: white; }
    .status-inactive { background-color: #dc3545; color: white; }

    .action-btn {
        background: none;
        border: none;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        color: #22577A;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn.text-danger { color: #dc3545; }

    .modal-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .modal-box {
        background: #fff;
        padding: 30px;
        border-radius: 18px;
        width: 400px;
        position: relative;
        box-shadow: 0 8px 18px rgba(0,0,0,0.2);
    }

    .modal-box h5 {
        font-weight: 700;
        text-align: center;
        color: #22577A;
        margin-bottom: 24px;
    }

    .modal-box input,
    .modal-box select {
        border-radius: 8px;
        border: 2px solid #ccc;
        width: 100%;
        padding: 8px 12px;
        margin-bottom: 16px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .modal-box input:focus,
    .modal-box select:focus {
        border-color: #80bdff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }

    .modal-box input::placeholder {
        color: #999;
        font-size: 12.5px;
    }

    .btn-submit {
        background-color: #22577A;
        color: white;
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 8px;
        font-weight: 500;
    }

    .modal-close {
        position: absolute;
        top: 12px;
        right: 16px;
        font-size: 20px;
        color: #FF7700;
        cursor: pointer;
        font-weight: bold;
    }
</style>

<div class="admin-wrapper">
    <div class="header-bar">
        <h3>Admin User Management</h3>
        <div class="text-end">
            <div class="text-muted mb-2">Hello, User! <i class="bi bi-person-circle ms-1"></i></div>
            <button class="add-btn" onclick="showModal()">
                <i class="bi bi-person-plus me-2"></i>Add Admins
            </button>
        </div>
    </div>

    <div class="admin-list-headings">
        <div>Admin</div>
        <div>Status</div>
        <div>Date</div>
        <div>Role</div>
        <div>Actions</div>
    </div>

    {{-- Admin Cards (Insert dynamically or blade loop here) --}}
</div>

{{-- Add Admin Modal --}}
<div class="modal-overlay" id="addAdminModal">
    <div class="modal-box">
        <div class="modal-close" onclick="hideModal()">&times;</div>
        <h5>New Admin</h5>
        <form id="addAdminForm" onsubmit="addAdmin(event)">
            <input type="text" id="addName" placeholder="Full Name" required>
            <input type="email" id="addEmail" placeholder="Email" required>
            <input type="password" id="addPassword" placeholder="Password (min 6 characters)" required>
            <div class="d-flex gap-2">
                <select id="addRole"><option>Admin</option><option>Super Admin</option></select>
                <select id="addStatus"><option>Active</option><option>Inactive</option></select>
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
        <form onsubmit="updateAdmin(event)">
            <input type="text" name="editName" placeholder="Full Name" required>
            <input type="email" name="editEmail" placeholder="Email" required>
            <input type="password" name="editPassword" placeholder="New Password (optional)">
            <div class="d-flex gap-2">
                <select name="editRole"><option>Admin</option><option>Super Admin</option></select>
                <select name="editStatus"><option>Active</option><option>Inactive</option></select>
            </div>
            <button type="submit" class="btn-submit mt-2">Update</button>
        </form>
    </div>
</div>

<script>
    function showModal() {
        document.getElementById('addAdminModal').style.display = 'flex';
    }

    function hideModal() {
        document.getElementById('addAdminModal').style.display = 'none';
    }

    function openEditModal(btn) {
        const card = btn.closest('.admin-card');
        const name = card.querySelector('.admin-details h6').innerText;
        const email = card.querySelector('.admin-details small').innerText;
        const role = card.children[3].innerText.trim();
        const status = card.children[1].innerText.trim();

        document.getElementById('editAdminModal').dataset.editingCardId = card.dataset.id;
        document.querySelector('input[name="editName"]').value = name;
        document.querySelector('input[name="editEmail"]').value = email;
        document.querySelector('select[name="editRole"]').value = role;
        document.querySelector('select[name="editStatus"]').value = status;

        document.getElementById('editAdminModal').style.display = 'flex';
    }

    function hideEditModal() {
        document.getElementById('editAdminModal').style.display = 'none';
    }

    function deleteAdmin(btn) {
        const card = btn.closest('.admin-card');
        card.remove();
    }

    function addAdmin(e) {
        e.preventDefault();
        const name = document.getElementById('addName').value;
        const email = document.getElementById('addEmail').value;
        const password = document.getElementById('addPassword').value;
        const role = document.getElementById('addRole').value;
        const status = document.getElementById('addStatus').value;

        const card = `
        <div class="admin-card" data-id="${Date.now()}">
            <div class="admin-info">
                <i class="bi bi-person-circle"></i>
                <div class="admin-details">
                    <h6>${name}</h6>
                    <small>${email}</small>
                </div>
            </div>
            <div><span class="status-badge ${status === 'Active' ? 'status-active' : 'status-inactive'}">${status}</span></div>
            <div>${new Date().toLocaleDateString()}</div>
            <div>${role}</div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <button class="action-btn" onclick="openEditModal(this)"><i class="bi bi-pencil-square"></i></button>
                <button class="action-btn text-danger" onclick="deleteAdmin(this)"><i class="bi bi-trash-fill"></i></button>
            </div>
        </div>`;
        document.querySelector('.admin-wrapper').insertAdjacentHTML('beforeend', card);
        hideModal();
        e.target.reset();
    }

    function updateAdmin(e) {
        e.preventDefault();
        const form = e.target;
        const name = form.querySelector('input[name="editName"]').value;
        const email = form.querySelector('input[name="editEmail"]').value;
        const password = form.querySelector('input[name="editPassword"]').value;
        const role = form.querySelector('select[name="editRole"]').value;
        const status = form.querySelector('select[name="editStatus"]').value;

        const cardId = document.getElementById('editAdminModal').dataset.editingCardId;
        const card = document.querySelector(`.admin-card[data-id="${cardId}"]`);

        card.querySelector('.admin-details h6').innerText = name;
        card.querySelector('.admin-details small').innerText = email;
        card.children[3].innerText = role;
        const badge = card.querySelector('.status-badge');
        badge.innerText = status;
        badge.className = 'status-badge ' + (status === 'Active' ? 'status-active' : 'status-inactive');

        hideEditModal();
    }
</script>
@endsection
