document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.login-form');
    form.addEventListener('submit', function (event) {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        if (username === '' || password === '') {
            alert('Please fill in all fields');
            event.preventDefault(); // Prevent form submission
        }
    });
});
