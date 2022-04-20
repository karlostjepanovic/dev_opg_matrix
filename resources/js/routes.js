export const routes = [
    {
        name: 'index',
        path: '/',
        redirect: { name: 'setFamilyFarm'},
    },
    {
        name: 'setFamilyFarm',
        path: '/set-family-farm',
        component: require('./views/SetFamilyFarm').default
    },
    {
        name: 'profile',
        path: '/profile',
        component: require('./views/settings/Profile').default,
    },
    {
        name: 'changePassword',
        path: '/change-password',
        component: require('./views/settings/ChangePassword').default,
    },
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
            /* KULTURE */
            {
                name: 'appCultures',
                path: 'cultures',
                component: require('./views/admin/cultures/Index').default,
            },
            /* SREDSTVA */
            {
                name: 'appSupplies',
                path: 'supplies',
                component: require('./views/admin/supplies/Index').default,
            },
        ],
    },

    /* OPG */
    /* ADMINISTRACIJA SUSTAVA */
    {
        path: '/family-farm/',
        component: require('./views/familyFarm/Template').default,
        children: [
            /* MATRICE */
            {
                name: 'showMatrices',
                path: 'matrices',
                component: require('./views/familyFarm/matrices/Index').default,
            },
            {
                path: 'matrix',
                component: require('./views/familyFarm/matrices/Template').default,
                children: [
                    {
                        name: 'operations',
                        path: 'operations',
                        component: require('./views/familyFarm/matrices/operations/Index').default,
                    },
                    {
                        name: 'notes',
                        path: 'notes',
                        component: require('./views/familyFarm/matrices/notes/Index').default,
                    },
                ]
            },
            /* DJELATNICI */
            {
                name: 'showEmployees',
                path: 'employees',
                component: require('./views/familyFarm/employees/Index').default,
            },
            /* KATASTARSKE ČESTICE */
            {
                name: 'showCadastralParcels',
                path: 'cadastral-parcels',
                component: require('./views/familyFarm/cadastralParcels/Index').default,
            },
            /* KULTURE */
            {
                name: 'showCultures',
                path: 'cultures',
                component: require('./views/familyFarm/cultures/Index').default,
            },
        ],
    },
    {
        name: '404',
        path: '*',
        component: require('./components/404').default
    }
];
