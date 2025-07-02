function applyTheme() {
    const theme = localStorage.getItem('theme');


    if (theme) {

        document.documentElement.setAttribute('data-theme', theme);
    } else {
        console.log('apply from system preference');
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    }
}

// Jalankan saat halaman pertama kali dimuat
applyTheme();

// Jalankan ulang setiap kali Livewire navigate
document.addEventListener('livewire:navigated', () => {

    applyTheme();
});

