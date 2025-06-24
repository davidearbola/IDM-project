import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import useAuth from '../composables/useAuth.js';  

const { user } = useAuth();

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/DashboardView.vue'),
      meta: { requiresAuth: true }  
    },
    {
      path: '/forgot-password',
      name: 'forgot-password',
      component: () => import('../views/ForgotPasswordView.vue')
    },
    {
        path: '/reset-password',
        name: 'reset-password',
        component: () => import('../views/ResetPasswordView.vue')
    },
    {
      path: '/verify-email',
      name: 'verify-email',
      component: () => import('../views/VerifyEmailView.vue')
    },
    {
      path: '/resend-verification',
      name: 'resend-verification',
      component: () => import('../views/ResendVerificationView.vue'),
    },
  ],
})

router.beforeEach(async (to, from, next) => {
  const { user, isAuthCheckCompleted, getUser } = useAuth();

  if (!isAuthCheckCompleted.value) {
    await getUser();
  }

  if (to.meta.requiresAuth && !user.value) {
    next({ name: 'login' });
  } else {
    next();
  }
})

export default router
