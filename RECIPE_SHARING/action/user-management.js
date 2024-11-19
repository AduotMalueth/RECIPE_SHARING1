// Initialize users array with sample data
        let users = [
            { id: 1, name: 'John Doe', email: 'john@example.com', date: '01/10/2024' },
            { id: 3, name: 'Johnson Micheal', email: 'johnson@example.com', date: '01/10/2024' },
            { id: 9, name: 'Amok John', email: 'amok@example.com', date: '01/10/2024' },
            { id: 10, name: 'John Jok', email: 'jok@example.com', date: '01/10/2024' }
        ];

        // DOM Elements
        const userForm = document.getElementById('userForm');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const submitBtn = document.getElementById('submitBtn');
        const userList = document.getElementById('userList').getElementsByTagName('tbody')[0];

        let editingId = null;

        // Display users
        function displayUsers() {
            userList.innerHTML = '';
            users.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.date}</td>
                    <td>
                        <button class="btn btn-edit" onclick="editUser(${user.id})">Edit</button>
                        <button class="btn btn-delete" onclick="deleteUser(${user.id})">Delete</button>
                    </td>
                `;
                userList.appendChild(row);
            });
        }

        // Add/Edit user
        userForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            if (!nameInput.value.trim() || !emailInput.value.trim()) {
                alert('Please fill in all fields');
                return;
            }

            if (editingId === null) {
                // Add new user
                const newUser = {
                    id: users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1,
                    name: nameInput.value.trim(),
                    email: emailInput.value.trim(),
                    date: new Date().toLocaleDateString()
                };
                users.push(newUser);
            } else {
                // Update existing user
                const index = users.findIndex(u => u.id === editingId);
                if (index !== -1) {
                    users[index] = {
                        ...users[index],
                        name: nameInput.value.trim(),
                        email: emailInput.value.trim()
                    };
                }
                editingId = null;
                submitBtn.textContent = 'Add User';
            }

            userForm.reset();
            displayUsers();
        });

        // Edit user
        function editUser(id) {
            const user = users.find(u => u.id === id);
            if (user) {
                nameInput.value = user.name;
                emailInput.value = user.email;
                editingId = id;
                submitBtn.textContent = 'Update User';
                nameInput.focus();
            }
        }

        // Delete user
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                users = users.filter(user => user.id !== id);
                displayUsers();
                if (editingId === id) {
                    userForm.reset();
                    editingId = null;
                    submitBtn.textContent = 'Add User';
                }
            }
        }

        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '/';
            }
        }

        // Initial display
        displayUsers();
    