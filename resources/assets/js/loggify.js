let body = document.querySelector('body');
let light_icon = document.querySelector('#light_icon');
let dark_icon = document.querySelector('#dark_icon');

function updateLocalStorage(value){
    localStorage.setItem('theme', value);
}

lightMode = function (){
    body.setAttribute('data-bs-theme', 'light');
    light_icon.classList.add('d-none');
    dark_icon.classList.remove('d-none');
    updateLocalStorage('light');
}

darkMode = function (){
    body.setAttribute('data-bs-theme', 'dark');
    dark_icon.classList.add('d-none');
    light_icon.classList.remove('d-none');
    updateLocalStorage('dark');
}

light_icon.addEventListener('click', lightMode, false);
dark_icon.addEventListener('click', darkMode, false);

window.addEventListener('load', function (){
    let theme = localStorage.getItem('theme');

    switch (theme){
        case 'light':
            lightMode();
            break;
        case 'dark':
            darkMode();
            break;
    }
})
