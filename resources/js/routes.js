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
            {
                name: 'createSupply',
                path: 'create-supply',
                component: require('./views/admin/supplies/Create').default,
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
                        name: 'createProcess',
                        path: 'operation/:id/create-process',
                        component: require('./views/familyFarm/matrices/operations/processes/Create').default,
                    },
                    {
                        name: 'notes',
                        path: 'notes',
                        component: require('./views/familyFarm/matrices/notes/Index').default,
                    },
                    {
                        name: 'reports',
                        path: 'reports',
                        component: require('./views/familyFarm/matrices/reports/Index').default,
                    },
                    {
                        name: 'settings',
                        path: 'settings',
                        component: require('./views/familyFarm/matrices/settings/Index').default,
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
            /* SREDSTVA I ZALIHE */
            {
                name: 'showSupplies',
                path: 'supplies',
                component: require('./views/familyFarm/supplies/Index').default,
            },
            {
                name: 'showSupplyAmounts',
                path: 'supply/amounts/:id',
                component: require('./views/familyFarm/supplies/amounts/Index').default,
            },
            /* IZVJEŠTAJI */
            {
                name: 'showReports',
                path: 'reports',
                component: require('./views/familyFarm/reports/Index').default,
            },
        ],
    },
    {
        name: '404',
        path: '*',
        component: require('./components/404').default
    }
];
