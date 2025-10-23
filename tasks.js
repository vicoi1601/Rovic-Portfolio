let tasks = [];
let editingId = null;
let nextId = 1;

// Local Storage functions
function saveToLocalStorage() {
    localStorage.setItem('tasks', JSON.stringify(tasks));
    localStorage.setItem('nextId', nextId.toString());
}

function loadFromLocalStorage() {
    const savedTasks = localStorage.getItem('tasks');
    const savedNextId = localStorage.getItem('nextId');
    
    if (savedTasks) {
        tasks = JSON.parse(savedTasks);
        nextId = savedNextId ? parseInt(savedNextId) : 1;
    } else {
        // Load sample data only if no data exists in localStorage
        loadSampleData();
    }
}

// Load sample data (only used when no localStorage data exists)
function loadSampleData() {
    const sampleTasks = [
        { id: 1, name: "Complete project proposal", category: "Work", status: "in-progress", created: new Date().toLocaleDateString() },
        { id: 2, name: "Buy groceries", category: "Personal", status: "pending", created: new Date().toLocaleDateString() },
        { id: 3, name: "Schedule doctor appointment", category: "Health", status: "completed", created: new Date().toLocaleDateString() },
        { id: 4, name: "Review quarterly reports", category: "Work", status: "pending", created: new Date().toLocaleDateString() }
    ];
    
    tasks = sampleTasks;
    nextId = 5;
    saveToLocalStorage(); // Save sample data to localStorage
}

function addTask() {
    const name = document.getElementById('taskName').value.trim();
    const category = document.getElementById('taskCategory').value;
    const status = document.getElementById('taskStatus').value;

    if (!name) {
        alert('Please enter a task name');
        return;
    }

    const newTask = {
        id: nextId++,
        name: name,
        category: category,
        status: status,
        created: new Date().toLocaleDateString()
    };

    tasks.push(newTask);
    saveToLocalStorage(); // Save to localStorage
    clearForm();
    displayTasks();
    updateStats();
}

function deleteTask(id) {
    if (confirm('Are you sure you want to delete this task?')) {
        tasks = tasks.filter(task => task.id !== id);
        saveToLocalStorage(); // Save to localStorage
        displayTasks();
        updateStats();
    }
}

function editTask(id) {
    const task = tasks.find(t => t.id === id);
    if (task) {
        document.getElementById('taskName').value = task.name;
        document.getElementById('taskCategory').value = task.category;
        document.getElementById('taskStatus').value = task.status;
        
        editingId = id;
        document.getElementById('addBtn').style.display = 'none';
        document.getElementById('updateBtn').style.display = 'inline-block';
        document.getElementById('cancelBtn').style.display = 'inline-block';
    }
}

function updateTask() {
    const name = document.getElementById('taskName').value.trim();
    const category = document.getElementById('taskCategory').value;
    const status = document.getElementById('taskStatus').value;

    if (!name) {
        alert('Please enter a task name');
        return;
    }

    const taskIndex = tasks.findIndex(t => t.id === editingId);
    if (taskIndex !== -1) {
        tasks[taskIndex] = {
            ...tasks[taskIndex],
            name: name,
            category: category,
            status: status
        };
    }

    saveToLocalStorage(); // Save to localStorage
    cancelEdit();
    displayTasks();
    updateStats();
}

function cancelEdit() {
    editingId = null;
    clearForm();
    document.getElementById('addBtn').style.display = 'inline-block';
    document.getElementById('updateBtn').style.display = 'none';
    document.getElementById('cancelBtn').style.display = 'none';
}

function clearForm() {
    document.getElementById('taskName').value = '';
    document.getElementById('taskCategory').value = 'Work';
    document.getElementById('taskStatus').value = 'pending';
}

function displayTasks(filteredTasks = tasks) {
    const tbody = document.getElementById('taskTableBody');
    const emptyState = document.getElementById('emptyState');
    
    if (filteredTasks.length === 0) {
        tbody.innerHTML = '';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    
    tbody.innerHTML = filteredTasks.map(task => `
        <tr>
            <td>#${task.id}</td>
            <td>${task.name}</td>
            <td>${task.category}</td>
            <td><span class="status-badge status-${task.status}">${task.status.replace('-', ' ')}</span></td>
            <td>${task.created}</td>
            <td>
                <button class="btn btn-edit" onclick="editTask(${task.id})" title="Edit Task">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L10.5 8.207l-3-3L12.146.146zM11.207 9l-3-3L2.5 11.707V13.5a.5.5 0 0 0 .5.5h1.793L11.207 9zM5 12V9.5a.5.5 0 0 1 .146-.354L8.5 5.793l3 3L8.146 12.354a.5.5 0 0 1-.354.146H5z"/>
                    </svg>
                </button>
                <button class="btn btn-danger" onclick="deleteTask(${task.id})" title="Delete Task">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                    </svg>
                </button>
            </td>
        </tr>
    `).join('');
}

function filterTasks() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('filterCategory').value;
    const statusFilter = document.getElementById('filterStatus').value;

    let filtered = tasks.filter(task => {
        const matchesSearch = task.name.toLowerCase().includes(searchTerm);
        const matchesCategory = !categoryFilter || task.category === categoryFilter;
        const matchesStatus = !statusFilter || task.status === statusFilter;
        
        return matchesSearch && matchesCategory && matchesStatus;
    });

    displayTasks(filtered);
}

function updateStats() {
    const total = tasks.length;
    const completed = tasks.filter(t => t.status === 'completed').length;
    const inProgress = tasks.filter(t => t.status === 'in-progress').length;
    const pending = tasks.filter(t => t.status === 'pending').length;

    document.getElementById('stats').innerHTML = `
        <div class="stat-card">
            <div class="stat-number">${total}</div>
            <div class="stat-label">Total Tasks</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${pending}</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${inProgress}</div>
            <div class="stat-label">In Progress</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${completed}</div>
            <div class="stat-label">Completed</div>
        </div>
    `;
}

// Initialize with data from localStorage when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadFromLocalStorage();
    displayTasks();
    updateStats();
});