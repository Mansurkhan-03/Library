import {createRouter, createWebHistory} from 'vue-router'
import {defineAsyncComponent} from "vue";

const ifAdmin = (to, from, next) => {
    const isAdmin = JSON.parse(atob(localStorage.getItem('token').split('.')[1])).roles.includes('ROLE_ADMIN');
    if (isAdmin) {
        next();
    } else {
        next({name: 'home'});
    }
}

const ifAuthorized = (to, from, next) => {
    if (localStorage.getItem('token')) {
        next();
    } else {
        next({name: 'login'});
    }
}

const ifNotAuthorized = (to, from, next) => {
    if (!localStorage.getItem('token')) {
        next();
    } else {
        next({name: 'home'});
    }
}

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: ()=> import('../views/HomePage.vue'),
            meta: {
                layout: defineAsyncComponent(() => import('@/layouts/DefaultLayout.vue'))
            },
            beforeEnter: ifAuthorized,
        },
        {
            path: '/book-info/:bookId',
            name: 'book-info',
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: () => import('../views/BookInfoPage.vue'),
            meta: {
                layout: defineAsyncComponent(() => import('@/layouts/DefaultLayout.vue'))
            },
            beforeEnter: ifAuthorized,
        },
        {
            path: '/login',
            name: 'login',
            component: ()=> import('../views/LoginPage.vue'),
            meta: {
                layout: defineAsyncComponent(() => import('@/layouts/BlankLayouts.vue'))
            },
            beforeEnter: ifNotAuthorized,
        },
        {
            path: '/signup',
            name: 'signup',
            component: ()=> import('../views/SignUpPage.vue'),
            meta: {
                layout: defineAsyncComponent(() => import('@/layouts/BlankLayouts.vue'))
            },
            beforeEnter: ifNotAuthorized,
        },
        {
            path: '/forgot-password',
            name: 'forgot-password',
            component: ()=> import('../views/ForgotPasswordPage.vue'),
            meta: {
                layout: defineAsyncComponent(() => import('@/layouts/BlankLayouts.vue'))
            },
            beforeEnter: ifNotAuthorized,
        },
        {
            path: '/categories',
            name: 'categories',
            component: ()=> import('../views/CategoriesListPage.vue'),
            meta: {
                layout: defineAsyncComponent(() => import('@/layouts/DefaultLayout.vue'))
            },
            beforeEnter: ifAuthorized,
        },
        {
            path: '/add-book',
            name: 'add-book',
            component: ()=> import('../views/CreateBookPage.vue'),
            meta: {
                layout: defineAsyncComponent(() => import('@/layouts/DefaultLayout.vue'))
            },
            beforeEnter: ifAuthorized,
        },
        {
            path: '/by-category/:id',
            name: 'by-category',
            component: ()=> import('../views/HomePage.vue'),
            meta: {
                layout: defineAsyncComponent(() => import('@/layouts/DefaultLayout.vue'))
            },
            beforeEnter: ifAuthorized,
        },
        {
            path: '/by-like/:id',
            name: 'by-like',
            component: ()=> import('../views/HomePage.vue'),
            meta: {
                layout: defineAsyncComponent(() => import('@/layouts/DefaultLayout.vue'))
            },
            beforeEnter: ifAuthorized,
        },
        {
            path: '/statistics',
            name: 'statistics',
            component: ()=> import('../views/AdminPage.vue'),
            meta: {
                layout: defineAsyncComponent(() => import('@/layouts/MainBlankLayout.vue'))
            },
            beforeEnter: [ifAuthorized, ifAdmin]
        },
    ],
})

export default router
