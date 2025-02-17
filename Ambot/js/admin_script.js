let body = document.body;

// Profile dropdown
let profile = document.querySelector('.header .flex .profile');
let userBtn = document.querySelector('#user-btn');
let searchForm = document.querySelector('.header .flex .search-form');
let searchBtn = document.querySelector('#search-btn');
let sideBar = document.querySelector('.side-bar');
let menuBtn = document.querySelector('#menu-btn');
let closeSideBarBtn = document.querySelector('.side-bar .close-side-bar');
let toggleBtn = document.querySelector('#toggle-btn');

// Admin Side Behavior (Same as User Side)
userBtn?.addEventListener('click', () => {
    profile.classList.toggle('active');
    searchForm?.classList.remove('active');
});

searchBtn?.addEventListener('click', () => {
    searchForm.classList.toggle('active');
    profile.classList.remove('active');
});

menuBtn?.addEventListener('click', () => {
    sideBar.classList.toggle('active');
    body.classList.toggle('active');
});

closeSideBarBtn?.addEventListener('click', () => {
    sideBar.classList.remove('active');
    body.classList.remove('active');
});

// On scroll behavior
window.addEventListener('scroll', () => {
    profile?.classList.remove('active');
    searchForm?.classList.remove('active');

    if (window.innerWidth < 1200) {
        sideBar.classList.remove('active');
        body.classList.remove('active');
    }
});

// Dark mode logic
let darkMode = localStorage.getItem('dark-mode');
const enableDarkMode = () => {
    toggleBtn?.classList.replace('fa-sun', 'fa-moon');
    body.classList.add('dark');
    localStorage.setItem('dark-mode', 'enabled');
};
const disableDarkMode = () => {
    toggleBtn?.classList.replace('fa-moon', 'fa-sun');
    body.classList.remove('dark');
    localStorage.setItem('dark-mode', 'disabled');
};

if (darkMode === 'enabled') enableDarkMode();

toggleBtn?.addEventListener('click', () => {
    let darkMode = localStorage.getItem('dark-mode');
    darkMode === 'enabled' ? disableDarkMode() : enableDarkMode();
});
