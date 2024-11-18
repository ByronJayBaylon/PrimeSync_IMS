        // Toggle password visibility
        function togglePasswordVisibility(id) {
            const passwordField = document.getElementById(id);
            const toggleButton = passwordField.nextElementSibling;
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleButton.textContent = 'Hide';
            } else {
                passwordField.type = 'password';
                toggleButton.textContent = 'Show';
            }
        }

        // Additional JavaScript for managing modals and form submissions
        document.addEventListener('DOMContentLoaded', () => {
            // Manage the modal functionality for user actions
            const deleteModal = document.getElementById('deleteModal');
            const addUserModal = document.getElementById('addUserModal');
            const editUserModal = document.getElementById('editUserModal');
            const deleteForm = document.getElementById('deleteForm');
            const deleteId = document.getElementById('deleteId');
            const cancelBtn = document.getElementById('cancelBtn');
            const closeModal = document.getElementById('closeModal');

            const openAddUserModal = document.getElementById('openAddUserModal');
            const closeAddUserModal = document.getElementById('closeAddUserModal');
            const closeEditUserModal = document.getElementById('closeEditUserModal');

            if (openAddUserModal && addUserModal && closeAddUserModal) {
                // Open add user modal when button is clicked
                openAddUserModal.addEventListener('click', () => {
                    addUserModal.style.display = 'block';
                });

                // Close add user modal when button is clicked
                closeAddUserModal.addEventListener('click', () => {
                    addUserModal.style.display = 'none';
                });

                // Close add user modal when clicking outside of it
                window.addEventListener('click', (event) => {
                    if (event.target === addUserModal) {
                        addUserModal.style.display = 'none';
                    }
                });
            }

            const editBtns = document.querySelectorAll('.edit-btn');
            if (editBtns.length > 0 && editUserModal && closeEditUserModal) {
                // Open edit user modal and fill with current user data
                editBtns.forEach(button => {
                    button.addEventListener('click', () => {
                        const userId = button.getAttribute('data-id');
                        const username = button.getAttribute('data-username');
                        const accountType = button.getAttribute('data-account-type');

                        document.getElementById('edit_user_id').value = userId;
                        document.getElementById('edit_username').value = username;
                        document.getElementById('edit_account_type').value = accountType;

                        editUserModal.style.display = 'block';
                    });
                });

                // Close edit user modal when button is clicked
                closeEditUserModal.addEventListener('click', () => {
                    editUserModal.style.display = 'none';
                });

                // Close edit user modal when clicking outside of it
                window.addEventListener('click', (event) => {
                    if (event.target === editUserModal) {
                        editUserModal.style.display = 'none';
                    }
                });
            }

            const deleteBtns = document.querySelectorAll('.delete-btn');
            if (deleteBtns.length > 0 && deleteModal && deleteId && deleteForm) {
                // Open delete modal with the user ID set in the delete form
                deleteBtns.forEach(button => {
                    button.addEventListener('click', () => {
                        deleteId.value = button.getAttribute('data-id');
                        deleteModal.style.display = 'block';
                    });
                });

                if (cancelBtn) {
                    // Close delete modal when cancel button is clicked
                    cancelBtn.addEventListener('click', () => {
                        deleteModal.style.display = 'none';
                    });
                }

                if (closeModal) {
                    // Close delete modal when close button is clicked
                    closeModal.addEventListener('click', () => {
                        deleteModal.style.display = 'none';
                    });

                    // Close delete modal when clicking outside of it
                    window.addEventListener('click', (event) => {
                        if (event.target === deleteModal) {
                            deleteModal.style.display = 'none';
                        }
                    });
                }

                // Password verification before deleting an account
                deleteForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const adminPassword = document.getElementById('admin_password').value;
                    const userId = deleteId.value;

                    // Send an AJAX request to verify the password
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'verify_password.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            if (xhr.responseText === 'success') {
                                deleteForm.submit();
                            } else {
                                alert('Wrong Password. Please try again.');
                            }
                        }
                    };
                    xhr.send(`admin_password=${adminPassword}&user_id=${userId}`);
                });
            }

            // Add user form validation to check if passwords match
            const addUserForm = document.getElementById('addUserForm');
            if (addUserForm) {
                addUserForm.addEventListener('submit', function(event) {
                    const password = document.getElementById('password').value;
                    const repeatPassword = document.getElementById('repeat_password').value;

                    if (password !== repeatPassword) {
                        alert('Passwords do not match.');
                        event.preventDefault();
                    }
                });
            }

            // Edit user form validation to check if all fields are filled out
            const editUserForm = document.getElementById('editUserForm');
            if (editUserForm) {
                editUserForm.addEventListener('submit', function(event) {
                    const username = document.getElementById('edit_username').value;
                    const accountType = document.getElementById('edit_account_type').value;

                    if (!username || !accountType) {
                        alert('Please fill out all fields.');
                        event.preventDefault();
                    }
                });
            }

            // Entries controller functionality to paginate table
            const entriesSelect = document.getElementById('entriesSelect');
            const userTable = document.getElementById('userTable');
            const rows = userTable ? userTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr') : [];
            const entriesInfo = document.getElementById('entriesInfo');
            const pageController = document.getElementById('pageController');
            const prevPage = document.getElementById('prevPage');
            const nextPage = document.getElementById('nextPage');
            const pageNumbers = document.getElementById('pageNumbers');

            let currentPage = 1;
            let rowsPerPage = parseInt(entriesSelect ? entriesSelect.value : 10, 10);
            let totalPages = Math.ceil(rows.length / rowsPerPage);

            if (entriesSelect) {
                // Update table and page numbers when entries per page is changed
                entriesSelect.addEventListener('change', () => {
                    rowsPerPage = parseInt(entriesSelect.value, 10);
                    currentPage = 1;
                    totalPages = Math.ceil(rows.length / rowsPerPage);
                    updateTable();
                    updatePageNumbers();
                });
            }

            if (prevPage && nextPage) {
                // Go to previous page
                prevPage.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        updateTable();
                        updatePageNumbers();
                    }
                });

                // Go to next page
                nextPage.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        updateTable();
                        updatePageNumbers();
                    }
                });
            }

            function updateTable() {
                let start = (currentPage - 1) * rowsPerPage;
                let end = start + rowsPerPage;
                let showingTo = 0;

                for (let i = 0; i < rows.length; i++) {
                    if (i >= start && i < end) {
                        rows[i].style.display = '';
                        showingTo++;
                    } else {
                        rows[i].style.display = 'none';
                    }
                }

                if (entriesInfo) {
                    entriesInfo.textContent = `Showing ${start + 1} to ${start + showingTo} of ${rows.length} entries`;
                }

                if (prevPage) {
                    prevPage.disabled = currentPage === 1;
                }
                if (nextPage) {
                    nextPage.disabled = currentPage === totalPages;
                }
            }

            function updatePageNumbers() {
                if (pageNumbers) {
                    pageNumbers.innerHTML = '';

                    for (let i = 1; i <= totalPages; i++) {
                        const pageButton = document.createElement('button');
                        pageButton.textContent = i;
                        if (i === currentPage) {
                            pageButton.style.fontWeight = 'bold';
                        }
                        pageButton.addEventListener('click', () => {
                            currentPage = i;
                            updateTable();
                            updatePageNumbers();
                        });
                        pageNumbers.appendChild(pageButton);
                    }
                }
            }

            // Initial display
            if (entriesSelect) {
                entriesSelect.dispatchEvent(new Event('change'));
            }
        });