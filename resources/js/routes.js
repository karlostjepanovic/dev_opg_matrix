export const routes = [
    {
        name: 'index',
        path: '',
        redirect: { name: 'setFamilyFarm'},
    },
    /*{
        path: '/settings/',
        component: require('./views/settings/Template').default,
        children: [
            {
                name: 'profile',
                path: 'profile',
                component: require('./views/settings/Profile').default,
            },
            {
                name: 'changePassword',
                path: 'change-password',
                component: require('./views/settings/ChangePassword').default,
            },
        ],
    },*/
    /* ADMINISTRACIJA SUSTAVA */
    {
        path: '/admin/',
        component: require('./views/admin/Template').default,
        children: [
            /* OPG-ovi */
            {
                name: 'appFamilyFarms',
                path: 'family-farms',
                component: require('./views/admin/familyFarms/Index').default,
            },
            /* KORISNICI */
            {
                name: 'appUsers',
                path: 'users',
                component: require('./views/admin/users/Index').default,
            },
        ],
    },

    {
        name: 'setFamilyFarm',
        path: '/set-family-farm',
        component: require('./views/SetFamilyFarm').default
    },
    {
        name: '404',
        path: '*',
        component: require('./components/404').default
    }
];
