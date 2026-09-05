@extends('layouts.master')

@section('title')
    Todo Dashboard - Todo App
@endsection

@section('content')
<!-- Page Header -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Todo Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Todos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Stats Widgets Row -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate bg-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-white-50 text-truncate mb-0">Total Todos</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-3">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-white" id="stat-total">{{ $totalCount }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white bg-opacity-25 rounded fs-3 text-white">
                            <i class="ri-task-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate bg-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-white-50 text-truncate mb-0">Pending Tasks</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-3">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-white" id="stat-pending">{{ $pendingCount }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white bg-opacity-25 rounded fs-3 text-white">
                            <i class="ri-time-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate bg-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-white-50 text-truncate mb-0">Completed Tasks</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-3">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-white" id="stat-completed">{{ $completedCount }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white bg-opacity-25 rounded fs-3 text-white">
                            <i class="ri-checkbox-circle-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate bg-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-white-50 text-truncate mb-0">High Priority</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-3">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-white" id="stat-high">{{ $highCount }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white bg-opacity-25 rounded fs-3 text-white">
                            <i class="ri-error-warning-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Todo Card & Filters -->
<div class="row">
    <div class="col-lg-12">
        <div class="card" id="tasksList">
            <div class="card-header border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 flex-grow-1"><i class="ri-list-check me-2 text-primary"></i> Task Manager</h5>
                    <div class="flex-shrink-0">
                        <button class="btn btn-danger add-btn" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                            <i class="ri-add-line align-bottom me-1"></i> Add Task
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form id="filterForm">
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-12">
                            <div class="search-box">
                                <input type="text" id="searchInput" class="form-control search" placeholder="Search by title or description...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <div class="col-xxl-2 col-sm-4">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="col-xxl-2 col-sm-4">
                            <select class="form-select" id="priorityFilter">
                                <option value="">All Priorities</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>

                        <div class="col-xxl-3 col-sm-4">
                            <button type="button" class="btn btn-primary w-100" id="btnResetFilters">
                                <i class="ri-refresh-line me-1 align-bottom"></i> Reset Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive table-card mb-4">
                    <table class="table align-middle table-nowrap mb-0" id="tasksTable">
                        <thead class="table-light text-muted">
                            <tr>
                                <th scope="col" style="width: 40px;">Done</th>
                                <th scope="col">Title & Description</th>
                                <th scope="col">Priority</th>
                                <th scope="col">Status</th>
                                <th scope="col">Due Date</th>
                                <th scope="col" style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="todo-table-body">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>

                    <div id="loading-spinner" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="no-result" class="text-center py-4" style="display: none;">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:72px;height:72px"></lord-icon>
                        <h5 class="mt-3">No Tasks Found</h5>
                        <p class="text-muted mb-0">Try adjusting your filters or create a new task!</p>
                    </div>
                </div>

                <!-- Pagination Container -->
                <div class="d-flex justify-content-end mt-2" id="pagination-container">
                    <!-- Pagination links via JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Create Task -->
<div class="modal fade zoomIn" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-3 bg-primary-subtle">
                <h5 class="modal-title" id="createTaskModalLabel">Create New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createTaskForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-title" class="form-label">Task Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="create-title" required placeholder="Enter task title">
                    </div>

                    <div class="mb-3">
                        <label for="create-description" class="form-label">Description</label>
                        <textarea class="form-control" id="create-description" rows="3" placeholder="Enter task detailed description..."></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="create-status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="create-status" required>
                                <option value="Pending" selected>Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="create-priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select" id="create-priority" required>
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="create-due-date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="create-due-date">
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="create-comments" class="form-label">Comments / Notes</label>
                        <input type="text" class="form-control" id="create-comments" placeholder="Additional notes...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="btn-save-task"><i class="ri-save-line me-1"></i> Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Task -->
<div class="modal fade zoomIn" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-3 bg-info-subtle">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTaskForm">
                <input type="hidden" id="edit-task-id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-title" class="form-label">Task Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit-title" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit-description" rows="3"></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="edit-status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-status" required>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="edit-priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-priority" required>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="edit-due-date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="edit-due-date">
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="edit-comments" class="form-label">Comments / Notes</label>
                        <input type="text" class="form-control" id="edit-comments">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="ri-save-line me-1"></i> Update Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Delete Confirmation -->
<div class="modal fade zoomIn" id="deleteTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-md-5">
                <div class="text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2">
                        <h4>Are you sure?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this task?</p>
                    </div>
                </div>
                <input type="hidden" id="delete-task-id">
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="btn-confirm-delete">Yes, Delete It!</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    const API_TOKEN = "{{ $token }}";
    let currentPage = 1;

    document.addEventListener("DOMContentLoaded", function () {
        fetchTodos();

        // Real-time search & filters
        document.getElementById("searchInput").addEventListener("input", debounce(fetchTodos, 400));
        document.getElementById("statusFilter").addEventListener("change", fetchTodos);
        document.getElementById("priorityFilter").addEventListener("change", fetchTodos);

        document.getElementById("btnResetFilters").addEventListener("click", function () {
            document.getElementById("searchInput").value = "";
            document.getElementById("statusFilter").value = "";
            document.getElementById("priorityFilter").value = "";
            fetchTodos();
        });

        // Create Task
        document.getElementById("createTaskForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const data = {
                title: document.getElementById("create-title").value,
                description: document.getElementById("create-description").value,
                status: document.getElementById("create-status").value,
                priority: document.getElementById("create-priority").value,
                due_date: document.getElementById("create-due-date").value || null,
                comments: document.getElementById("create-comments").value
            };

            fetch("/api/todos", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": "Bearer " + API_TOKEN,
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.status_code === 201 || res.data) {
                    bootstrap.Modal.getInstance(document.getElementById("createTaskModal")).hide();
                    document.getElementById("createTaskForm").reset();
                    fetchTodos();
                } else {
                    alert(res.message || "Failed to create task");
                }
            })
            .catch(err => console.error(err));
        });

        // Update Task
        document.getElementById("editTaskForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const id = document.getElementById("edit-task-id").value;
            const data = {
                title: document.getElementById("edit-title").value,
                description: document.getElementById("edit-description").value,
                status: document.getElementById("edit-status").value,
                priority: document.getElementById("edit-priority").value,
                due_date: document.getElementById("edit-due-date").value || null,
                comments: document.getElementById("edit-comments").value
            };

            fetch(`/api/todos/${id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": "Bearer " + API_TOKEN,
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.status_code === 200 || res.data) {
                    bootstrap.Modal.getInstance(document.getElementById("editTaskModal")).hide();
                    fetchTodos();
                } else {
                    alert(res.message || "Failed to update task");
                }
            })
            .catch(err => console.error(err));
        });

        // Confirm Delete Task
        document.getElementById("btn-confirm-delete").addEventListener("click", function () {
            const id = document.getElementById("delete-task-id").value;
            if (!id) return;

            fetch(`/api/todos/${id}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "Authorization": "Bearer " + API_TOKEN,
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(res => {
                bootstrap.Modal.getInstance(document.getElementById("deleteTaskModal")).hide();
                fetchTodos();
            })
            .catch(err => console.error(err));
        });
    });

    function fetchTodos(page = 1) {
        currentPage = page;
        document.getElementById("loading-spinner").style.display = "block";
        document.getElementById("no-result").style.display = "none";
        document.getElementById("todo-table-body").innerHTML = "";

        const search = document.getElementById("searchInput").value;
        const status = document.getElementById("statusFilter").value;
        const priority = document.getElementById("priorityFilter").value;

        let url = `/api/todos?page=${page}&rowsPerPage=10`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (status) url += `&status=${encodeURIComponent(status)}`;
        if (priority) url += `&priority=${encodeURIComponent(priority)}`;

        fetch(url, {
            headers: {
                "Accept": "application/json",
                "Authorization": "Bearer " + API_TOKEN
            }
        })
        .then(res => res.json())
        .then(response => {
            document.getElementById("loading-spinner").style.display = "none";
            const paginatedData = response.data;
            const todos = paginatedData ? paginatedData.data : [];

            if (!todos || todos.length === 0) {
                document.getElementById("no-result").style.display = "block";
                return;
            }

            renderTable(todos);
            renderPagination(paginatedData);
        })
        .catch(err => {
            document.getElementById("loading-spinner").style.display = "none";
            console.error(err);
        });
    }

    function renderTable(todos) {
        const tbody = document.getElementById("todo-table-body");
        tbody.innerHTML = "";

        todos.forEach(todo => {
            const isCompleted = todo.status === "Completed";

            let priorityBadge = '<span class="badge bg-info-subtle text-info">Low</span>';
            if (todo.priority === "High") priorityBadge = '<span class="badge bg-danger-subtle text-danger">High</span>';
            if (todo.priority === "Medium") priorityBadge = '<span class="badge bg-warning-subtle text-warning">Medium</span>';

            let statusBadge = '<span class="badge bg-primary-subtle text-primary">Pending</span>';
            if (todo.status === "Completed") statusBadge = '<span class="badge bg-success-subtle text-success">Completed</span>';
            if (todo.status === "In Progress") statusBadge = '<span class="badge bg-secondary-subtle text-secondary">In Progress</span>';

            const formattedDueDate = todo.due_date ? new Date(todo.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'No due date';

            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>
                    <input class="form-check-input" type="checkbox" ${isCompleted ? 'checked' : ''} onchange="toggleStatus('${todo.id}', this.checked)">
                </td>
                <td>
                    <h6 class="fs-14 mb-1 ${isCompleted ? 'text-decoration-line-through text-muted' : ''}">${escapeHtml(todo.title)}</h6>
                    <small class="text-muted">${escapeHtml(todo.description || '')}</small>
                </td>
                <td>${priorityBadge}</td>
                <td>${statusBadge}</td>
                <td><i class="ri-calendar-event-line align-bottom me-1"></i> ${formattedDueDate}</td>
                <td>
                    <div class="hstack gap-2">
                        <button class="btn btn-sm btn-soft-info" onclick="openEditModal('${todo.id}')"><i class="ri-pencil-fill"></i></button>
                        <button class="btn btn-sm btn-soft-danger" onclick="openDeleteModal('${todo.id}')"><i class="ri-delete-bin-5-fill"></i></button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function renderPagination(meta) {
        const container = document.getElementById("pagination-container");
        if (!meta || meta.last_page <= 1) {
            container.innerHTML = "";
            return;
        }

        let html = '<ul class="pagination pagination-separated mb-0">';
        for (let i = 1; i <= meta.last_page; i++) {
            html += `<li class="page-item ${i === meta.current_page ? 'active' : ''}">
                <a href="javascript:void(0)" class="page-link" onclick="fetchTodos(${i})">${i}</a>
            </li>`;
        }
        html += '</ul>';
        container.innerHTML = html;
    }

    function toggleStatus(id, isChecked) {
        const newStatus = isChecked ? "Completed" : "Pending";
        fetch(`/api/todos/${id}/status`, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "Authorization": "Bearer " + API_TOKEN,
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(res => res.json())
        .then(() => fetchTodos(currentPage))
        .catch(err => console.error(err));
    }

    function openEditModal(id) {
        fetch(`/api/todos/${id}`, {
            headers: {
                "Accept": "application/json",
                "Authorization": "Bearer " + API_TOKEN
            }
        })
        .then(res => res.json())
        .then(res => {
            const todo = res.data;
            if (!todo) return;
            document.getElementById("edit-task-id").value = todo.id;
            document.getElementById("edit-title").value = todo.title || '';
            document.getElementById("edit-description").value = todo.description || '';
            document.getElementById("edit-status").value = todo.status || 'Pending';
            document.getElementById("edit-priority").value = todo.priority || 'Medium';
            document.getElementById("edit-due-date").value = todo.due_date ? todo.due_date.split('T')[0] : '';
            document.getElementById("edit-comments").value = todo.comments || '';

            new bootstrap.Modal(document.getElementById("editTaskModal")).show();
        });
    }

    function openDeleteModal(id) {
        document.getElementById("delete-task-id").value = id;
        new bootstrap.Modal(document.getElementById("deleteTaskModal")).show();
    }

    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    function escapeHtml(text) {
        return text ? text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;") : '';
    }
</script>
@endsection
