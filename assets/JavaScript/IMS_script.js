        // Toggle password visibility
        function togglePasswordVisibility(id) {
            const passwordField = document.getElementById(id); // Get the password field by its ID
            const toggleButton = passwordField.nextElementSibling; // Get the toggle button next to the password field
            if (passwordField.type === 'password') { // If the password field is currently of type 'password'
                passwordField.type = 'text'; // Change the type to 'text' to show the password
                toggleButton.textContent = 'Hide'; // Update the button text to 'Hide'
            } else { // If the password field is currently of type 'text'
                passwordField.type = 'password'; // Change the type back to 'password' to hide the password
                toggleButton.textContent = 'Show'; // Update the button text to 'Show'
            }
        }

        // Additional JavaScript for managing modals and form submissions
        document.addEventListener('DOMContentLoaded', () => { // Wait for the DOM to fully load before executing
            // Manage the modal functionality for user actions
            const deleteModal = document.getElementById('deleteModal'); // Get the delete modal element
            const addUserModal = document.getElementById('addUserModal'); // Get the add user modal element
            const editUserModal = document.getElementById('editUserModal'); // Get the edit user modal element
            const deleteForm = document.getElementById('deleteForm'); // Get the delete form element
            const deleteId = document.getElementById('deleteId'); // Get the hidden input for the ID in the delete form
            const cancelBtn = document.getElementById('cancelBtn'); // Get the cancel button for the delete modal
            const closeModal = document.getElementById('closeModal'); // Get the close button for the delete modal

            const openAddUserModal = document.getElementById('openAddUserModal'); // Get the button to open the add user modal
            const closeAddUserModal = document.getElementById('closeAddUserModal'); // Get the button to close the add user modal
            const closeEditUserModal = document.getElementById('closeEditUserModal'); // Get the button to close the edit user modal

            if (openAddUserModal && addUserModal && closeAddUserModal) {
                // Open add user modal when button is clicked
                openAddUserModal.addEventListener('click', () => {
                    addUserModal.style.display = 'block'; // Show the add user modal
                });

                // Close add user modal when button is clicked
                closeAddUserModal.addEventListener('click', () => {
                    addUserModal.style.display = 'none'; // Hide the add user modal
                });

                // Close add user modal when clicking outside of it
                window.addEventListener('click', (event) => {
                    if (event.target === addUserModal) { // If the user clicks outside the modal
                        addUserModal.style.display = 'none'; // Hide the add user modal
                    }
                });
            }

            const editBtns = document.querySelectorAll('.edit-btn'); // Get all edit buttons
            if (editBtns.length > 0 && editUserModal && closeEditUserModal) {
                // Open edit user modal and fill with current user data
                editBtns.forEach(button => { // Iterate through each edit button
                    button.addEventListener('click', () => {
                        const userId = button.getAttribute('data-id'); // Get the user ID from the button's data attribute
                        const username = button.getAttribute('data-username'); // Get the username from the button's data attribute
                        const accountType = button.getAttribute('data-account-type'); // Get the account type from the button's data attribute

                        document.getElementById('edit_user_id').value = userId; // Set the user ID in the edit form
                        document.getElementById('edit_username').value = username; // Set the username in the edit form
                        document.getElementById('edit_account_type').value = accountType; // Set the account type in the edit form

                        editUserModal.style.display = 'block'; // Show the edit user modal
                    });
                });

                // Close edit user modal when button is clicked
                closeEditUserModal.addEventListener('click', () => {
                    editUserModal.style.display = 'none'; // Hide the edit user modal
                });

                // Close edit user modal when clicking outside of it
                window.addEventListener('click', (event) => {
                    if (event.target === editUserModal) { // If the user clicks outside the modal
                        editUserModal.style.display = 'none'; // Hide the edit user modal
                    }
                });
            }

            const deleteBtns = document.querySelectorAll('.delete-btn'); // Get all delete buttons
            if (deleteBtns.length > 0 && deleteModal && deleteId && deleteForm) {
                // Open delete modal with the user ID set in the delete form
                deleteBtns.forEach(button => { // Iterate through each delete button
                    button.addEventListener('click', () => {
                        deleteId.value = button.getAttribute('data-id'); // Set the user ID in the delete form
                        deleteModal.style.display = 'block'; // Show the delete modal
                    });
                });

                if (cancelBtn) {
                    // Close delete modal when cancel button is clicked
                    cancelBtn.addEventListener('click', () => {
                        deleteModal.style.display = 'none'; // Hide the delete modal
                    });
                }

                if (closeModal) {
                    // Close delete modal when close button is clicked
                    closeModal.addEventListener('click', () => {
                        deleteModal.style.display = 'none'; // Hide the delete modal
                    });

                    // Close delete modal when clicking outside of it
                    window.addEventListener('click', (event) => {
                        if (event.target === deleteModal) { // If the user clicks outside the modal
                            deleteModal.style.display = 'none'; // Hide the delete modal
                        }
                    });
                }

                // Password verification before deleting an account
                deleteForm.addEventListener('submit', function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    const adminPassword = document.getElementById('admin_password').value; // Get the entered admin password
                    const userId = deleteId.value; // Get the user ID to be deleted

                    // Send an AJAX request to verify the password
                    const xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
                    xhr.open('POST', 'verify_password.php', true); // Open a POST request to the verification script
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Set the request header for form data
                    xhr.onreadystatechange = function() { // Define the callback for when the request state changes
                        if (xhr.readyState === 4 && xhr.status === 200) { // Check if the request is complete and successful
                            if (xhr.responseText === 'success') { // If the password verification is successful
                                deleteForm.submit(); // Submit the form to delete the account
                            } else { // If the password verification fails
                                alert('Wrong Password. Please try again.'); // Show an error message
                            }
                        }
                    };
                    xhr.send(`admin_password=${adminPassword}&user_id=${userId}`); // Send the form data via the AJAX request
                });
            }

            // Add user form validation to check if passwords match
            const addUserForm = document.getElementById('addUserForm'); // Get the add user form element
            if (addUserForm) {
                addUserForm.addEventListener('submit', function(event) {
                    const password = document.getElementById('password').value; // Get the password from the form
                    const repeatPassword = document.getElementById('repeat_password').value; // Get the repeated password from the form

                    if (password !== repeatPassword) { // Check if the passwords do not match
                        alert('Passwords do not match.'); // Show an error message
                        event.preventDefault(); // Prevent form submission
                    }
                });
            }

            // Edit user form validation to check if all fields are filled out
            const editUserForm = document.getElementById('editUserForm'); // Get the edit user form element
            if (editUserForm) {
                editUserForm.addEventListener('submit', function(event) {
                    const username = document.getElementById('edit_username').value; // Get the username from the form
                    const accountType = document.getElementById('edit_account_type').value; // Get the account type from the form

                    if (!username || !accountType) { // Check if any field is empty
                        alert('Please fill out all fields.'); // Show an error message
                        event.preventDefault(); // Prevent form submission
                    }
                });
            }

            // Entries controller functionality to paginate table
            const entriesSelect = document.getElementById('entriesSelect'); // Get the dropdown for entries per page
            const userTable = document.getElementById('userTable'); // Get the user table element
            const rows = userTable ? userTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr') : []; // Get all rows in the table body
            const entriesInfo = document.getElementById('entriesInfo'); // Get the element to display entries information
            const pageController = document.getElementById('pageController'); // Get the page controller element
            const prevPage = document.getElementById('prevPage'); // Get the previous page button
            const nextPage = document.getElementById('nextPage'); // Get the next page button
            const pageNumbers = document.getElementById('pageNumbers'); // Get the container for page numbers

            let currentPage = 1; // Initialize the current page to 1
            let rowsPerPage = parseInt(entriesSelect ? entriesSelect.value : 10, 10); // Get the initial rows per page from the dropdown or default to 10
            let totalPages = Math.ceil(rows.length / rowsPerPage); // Calculate the total number of pages

            if (entriesSelect) {
                // Update table and page numbers when entries per page is changed
                entriesSelect.addEventListener('change', () => {
                    rowsPerPage = parseInt(entriesSelect.value, 10); // Update rows per page based on the dropdown
                    currentPage = 1; // Reset to the first page
                    totalPages = Math.ceil(rows.length / rowsPerPage); // Recalculate total pages
                    updateTable(); // Update the table display
                    updatePageNumbers(); // Update the page numbers
                });
            }

            if (prevPage && nextPage) {
                // Go to previous page
                prevPage.addEventListener('click', () => {
                    if (currentPage > 1) { // Check if the current page is not the first
                        currentPage--; // Move to the previous page
                        updateTable(); // Update the table display
                        updatePageNumbers(); // Update the page numbers
                    }
                });

                // Go to next page
                nextPage.addEventListener('click', () => {
                    if (currentPage < totalPages) { // Check if the current page is not the last
                        currentPage++; // Move to the next page
                        updateTable(); // Update the table display
                        updatePageNumbers(); // Update the page numbers
                    }
                });
            }

            function updateTable() {
                let start = (currentPage - 1) * rowsPerPage; // Calculate the starting index of the rows to show
                let end = start + rowsPerPage; // Calculate the ending index of the rows to show
                let showingTo = 0; // Initialize the count of rows being shown

                for (let i = 0; i < rows.length; i++) { // Iterate through all rows
                    if (i >= start && i < end) { // Check if the row is within the range for the current page
                        rows[i].style.display = ''; // Show the row
                        showingTo++; // Increment the count of rows being shown
                    } else { // If the row is outside the range for the current page
                        rows[i].style.display = 'none'; // Hide the row
                    }
                }

                if (entriesInfo) {
                    entriesInfo.textContent = `Showing ${start + 1} to ${start + showingTo} of ${rows.length} entries`; // Update the displayed entries information
                }

                if (prevPage) {
                    prevPage.disabled = currentPage === 1; // Disable the previous page button if on the first page
                }
                if (nextPage) {
                    nextPage.disabled = currentPage === totalPages; // Disable the next page button if on the last page
                }
            }

            function updatePageNumbers() {
                if (pageNumbers) {
                    pageNumbers.innerHTML = ''; // Clear the current page numbers

                    for (let i = 1; i <= totalPages; i++) { // Loop through all pages
                        const pageButton = document.createElement('button'); // Create a button for the page number
                        pageButton.textContent = i; // Set the page number as the button's text
                        if (i === currentPage) { // Highlight the current page button
                            pageButton.style.fontWeight = 'bold';
                        }
                        pageButton.addEventListener('click', () => { // Add click event listener to the button
                            currentPage = i; // Set the clicked page as the current page
                            updateTable(); // Update the table display
                            updatePageNumbers(); // Update the page numbers
                        });
                        pageNumbers.appendChild(pageButton); // Add the button to the page numbers container
                    }
                }
            }

            // Initial display
            if (entriesSelect) {
                entriesSelect.dispatchEvent(new Event('change')); // Trigger the change event for the dropdown to initialize the table and pagination
            }
        });
