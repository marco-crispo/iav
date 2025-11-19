import { home, person, filter, logOut, notifications } from 'ionicons/icons';

const menus = [
    {
        id: 'mainMenu',
        items:
        {
            'Home': {
                icon: home,
                route: '/home'
            },
            'Filter': {
                icon: filter,
                route: '/filter'
            },
            'Profile': {
                icon: person,
                route: '/profile'
            },
            'Notification': {
                icon: notifications,
                route: '/notifications'
            },
            'Logout': {
                icon: logOut,
                route: '/logout'
            },
        },
        event: null as any
    },
];

// Apri il popover associato al tasto
const openMenu = (ev: Event, menuId: string): string | null => {
    const menu = menus.find(m => m.id === menuId);
    if (menu) {
        menu.event = ev;
        return menuId;
    }
    return null;
}

// Gestisci click su voce di menu
const onMenuItem = (menuId: string, label: string): null => {
    console.log(`Selezionato ${label} da ${menuId}`);
    return null;
}

export {
    menus,
    openMenu,
    onMenuItem
};
