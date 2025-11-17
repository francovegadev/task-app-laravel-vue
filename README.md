# 📝 TODO-APP — Laravel Backend + Vue 3 Frontend

Este proyecto es una aplicación completa de tareas (**Fullstack**) construida con:

- **Laravel 10** (Backend API REST)
- **Vue 3 + Pinia + Vue Router + Typescript** (Frontend)
- **Spatie Laravel Permissions** (Roles y permisos)
- **Sanctum** (Autenticación API)

---

## 📦 Estructura del proyecto
todo-app/
│── backend/ # API en Laravel
│── frontend/ # SPA en Vue 3
└── README.md


---

# 🚀 Instalación Rápida

## 1️⃣ Clonar el repositorio

```bash
git clone https://github.com/francovegadev/task-app-laravel-vue.git
cd todo-app

---

## 🛠 Instalación Backend (Laravel)

cd backend

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate --seed

▶ Ejecutar el servidor
php artisan serve

# 💻 Instalación Frontend (Vue 3)
```bash
cd ../frontend

npm install

npm run dev

# 🔐 Autenticación

- Este proyecto usa:
- **Laravel Sanctum**
- **Roles & Permisos (Spatie)**

👤 Roles disponibles

- **Admin** — acceso total
- **Editor** — puede crear/editar
- **Viewer** — solo lectura