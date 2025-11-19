import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw, RouteLocationNormalized, NavigationGuardNext } from 'vue-router';
import RegisterPage from '@/views/registerPage.vue';
import loginPage from '@/views/loginPage.vue';
import homePage from '@/views/homePage.vue';
import FilterPage from '@/views/filterPage.vue';
import profilePage from '@/views/profilePage.vue';
import notificationsPage from '@/views/notificationsPage.vue';
import verifyemailPage from '@/views/verifyemailPage.vue';
import logoutPage from '@/views/logoutPage.vue';
import { isEmailVerified } from '@/composables/authCheck';
import NotFoundPage from '@/views/notFoundPage.vue';

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        redirect: () => {
            const token = localStorage.getItem('token');
            return token ? '/home' : '/login';
        }
    },

    // autenticazione
    {
        path: '/register',
        component: RegisterPage,
        meta: { requiresAuth: false, requiresEmailVerified: false }
    },
    {
        path: '/login',
        component: loginPage,
        meta: { requiresAuth: false, requiresEmailVerified: false }
    },
    {
        path: '/verifyemail',
        component: verifyemailPage,
        meta: { requiresAuth: false, requiresEmailVerified: false }
    },

    // protected pages
    {
        path: '/home',
        component: homePage,
        meta: { requiresAuth: true, requiresEmailVerified: true }
    },
    {
        path: '/filter',
        component: FilterPage,
        meta: { requiresAuth: true, requiresEmailVerified: true }
    },
    {
        path: '/profile',
        component: profilePage,
        meta: { requiresAuth: true, requiresEmailVerified: true }
    },
    {
        path: '/notifications',
        component: notificationsPage,
        meta: { requiresAuth: true, requiresEmailVerified: true }
    },
    {
        path: '/logout',
        component: logoutPage,
        meta: { requiresAuth: true, requiresEmailVerified: true }
    },

    // 404 fallback
    {
        path: '/:catchAll(.*)',
        component: NotFoundPage,
        meta: { requiresAuth: true, requiresEmailVerified: false }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})


router.beforeEach(async (to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => {
    const token = localStorage.getItem('token');

    if (to.meta.requiresAuth && !token) {
        return next('/login');
    }

    if (to.meta.requiresEmailVerified && token) {
        const isVerified = await isEmailVerified(token);
        if (!isVerified) {
            return next('/verifyemail');
        }
    }

    next();
});

export default router;
