let tabLinks = document.querySelectorAll('.tab-link');
let menuTabLinks = document.querySelectorAll('.tab-link-m');
let tabs = document.querySelectorAll('.tab');

for(let i = 0; i < tabLinks.length; i++) {
    tabLinks[i].addEventListener('click', e => {
        e.preventDefault();
        tabs.forEach(tab => {
            tab.classList.remove('active');
        });
        let targetTab = tabs[i];
        targetTab.classList.add('active');
        tabLinks.forEach(link => {
            link.classList.remove('active');
        });
        tabLinks[i].classList.add('active');
    });
}
for(let i = 0; i < menuTabLinks.length; i++) {
    menuTabLinks[i].addEventListener('click', e => {
        e.preventDefault();
        tabs.forEach(tab => {
            tab.classList.remove('active');
        });
        let targetTab = tabs[i];
        targetTab.classList.add('active');
        menuTabLinks.forEach(link => {
            link.classList.remove('active');
        });
        menuTabLinks[i].classList.add('active');
    });
}

let venueName = document.getElementById('venue-name');
let venues = document.querySelectorAll('.venue');

venues.forEach(venue => {
        venue.addEventListener('click', () => {
            venueName.value = venue.childNodes[3].textContent;
            venues.forEach(v => v.classList.remove('active'));
            venue.classList.add('active');
        });
});

let menuIcon = document.getElementById('menu-icon');
let menu = document.getElementById('menu');

menuIcon.addEventListener('click', () => {
    menu.classList.toggle('active');
});