// Enhanced focus effects
const inputs = document.querySelectorAll('input');
inputs.forEach(input => {
    input.addEventListener('focus', () => {
        input.parentElement.classList.add('focused');
    });
    
    input.addEventListener('blur', () => {
        if (!input.value) {
            input.parentElement.classList.remove('focused');
        }
    });
    
    // For pre-filled inputs
    if (input.value) {
        input.parentElement.classList.add('focused');
    }
});

// Add pulse animation to login button after a delay
setTimeout(() => {
    document.querySelector('button').classList.add('pulse');
}, 3000);

// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('login-error');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            
            if (firstName === '' || lastName === '') {
                // Show error message
                errorMessage.style.display = 'block';
            } else {
                // Store login information
                const username = firstName + ' ' + lastName;
                sessionStorage.setItem('username', username);
                sessionStorage.setItem('isLoggedIn', 'true');
                
                // If "Remember me" is checked, also store in localStorage
                if (document.querySelector('input[type="checkbox"]').checked) {
                    localStorage.setItem('isLoggedIn', 'true');
                }
                
                // Redirect to login.html
                window.location.href = 'login.html';
            }
        });
    }
});