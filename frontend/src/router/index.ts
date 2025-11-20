import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: () => import('@/views/auth/loginView.vue'),
      meta: { requiresGuest: true }
    },
    {
      path: '/login',
      name: 'loginView',
      component: () => import('@/views/auth/loginView.vue'),
      meta: { requiresGuest: true }
    },
    {
      path: '/register',
      name: 'registerView',
      component: () => import('@/views/auth/registerView.vue'),
      meta: { requiresGuest: true }
    },
    {
      path: '/profile',
      name: 'profileView',
      component: () => import('@/views/auth/ProfileView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard',
      name: 'dashboardView',
      component: () => import('@/views/auth/dashboardView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/tasks',
      name: 'tasksView',
      component: () => import('@/views/tasks/tasksView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/task/:id/edit',
      name: 'tasksUpdate',
      component: () => import('@/views/tasks/tasksUpdate.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/task/:id',
      name: 'tasksShow',
      component: () => import('@/views/tasks/tasksShow.vue'),
      meta: { requiresAuth: true }
    }
  ],
})

// check user auth
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('token')
  
  if (to.meta.requiresAuth && !isAuthenticated) {
    return next({ name: 'loginView' })
  } else if (to.meta.requiresGuest && isAuthenticated) {
    return next({ name: 'tasksView' })
  } else {
    return next()
  }
})

export default router
