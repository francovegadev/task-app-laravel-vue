import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
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
    }
  ],
})

export default router
