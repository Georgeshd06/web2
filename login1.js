// Script to check authentication status on all protected pages
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in
    const isLoggedIn = sessionStorage.getItem('isLoggedIn') === 'true' || 
                        localStorage.getItem('isLoggedIn') === 'true';
    
    // Current path
    const currentPath = window.location.pathname;
    const isIndexPage = currentPath.includes('index.html') || 
                        currentPath === '/' || 
                        currentPath.endsWith('/');
    const isLoginPage = currentPath.includes('login.html');
    
    // If not logged in and not on index page, redirect to index
    if (!isLoggedIn && !isIndexPage && !isLoginPage) {
        window.location.href = 'index.html';
        return;
    }
    
    // If logged in and on login page or index page, make sure we can stay on login.html
    // (We don't redirect away from login.html even when logged in)
    
    // If we're on a protected page and logged in, update UI with username
    if (isLoggedIn) {
        const username = sessionStorage.getItem('username') || 'User';
        
        // Add logout button to navigation if it doesn't exist
        const nav = document.querySelector('nav ul');
        if (nav) {
            // Check if logout button already exists
            if (!document.querySelector('.logout-button')) {
                const logoutItem = document.createElement('li');
                const logoutLink = document.createElement('a');
                logoutLink.href = '#';
                logoutLink.className = 'logout-button';
                logoutLink.innerHTML = '<i class="fas fa-sign-out-alt"></i> Logout';
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Clear login status
                    sessionStorage.removeItem('isLoggedIn');
                    localStorage.removeItem('isLoggedIn');
                    sessionStorage.removeItem('username');
                    // Redirect to index.html
                    window.location.href = 'index.html';
                });
                logoutItem.appendChild(logoutLink);
                nav.appendChild(logoutItem);
            }
        }
        
        // Add welcome message in header if it doesn't exist
        const header = document.querySelector('header');
        if (header && !document.querySelector('.welcome-message')) {
            const welcomeDiv = document.createElement('div');
            welcomeDiv.className = 'welcome-message';
            welcomeDiv.innerHTML = `<p>Welcome, <strong>${username}</strong>!</p>`;
            welcomeDiv.style.cssText = `
                color: white;
                margin-top: 10px;
                font-size: 0.9rem;
                opacity: 0.9;
            `;
            header.appendChild(welcomeDiv);
        }
    }
});