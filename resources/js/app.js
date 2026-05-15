import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Chart = Chart;

window.adminUi = () => ({
    dark: false,
    mobileOpen: false,
    collapsed: false,
    notifOpen: false,
    profileOpen: false,
    searchOpen: false,
    logoutOpen: false,
    init() {
        this.dark = localStorage.getItem('fc-theme') === 'dark';
        this.collapsed = localStorage.getItem('fc-sidebar') === '1';
        document.documentElement.classList.toggle('dark', this.dark);
    },
    toggleDark() {
        this.dark = !this.dark;
        localStorage.setItem('fc-theme', this.dark ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', this.dark);
    },
    toggleCollapsed() {
        this.collapsed = !this.collapsed;
        localStorage.setItem('fc-sidebar', this.collapsed ? '1' : '0');
    },
    closePanels() {
        this.mobileOpen = false;
        this.notifOpen = false;
        this.profileOpen = false;
        this.searchOpen = false;
    },
});

window.Alpine = Alpine;
Alpine.start();
