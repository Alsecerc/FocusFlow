function changeTheme(theme) {
    if (theme === 'default') {
        document.documentElement.removeAttribute('data-theme'); // Removes the attribute
    } else {
        document.documentElement.setAttribute('data-theme', theme);
    }
}

