# 📝 TODO-APP — Laravel Backend + Vue 3 Frontend

Este proyecto es una aplicación completa de tareas (**Fullstack**) construida con:

- **Laravel 10** (Backend API REST)
- **Vue 3 + Pinia + Vue Router + Typescript** (Frontend)
- **Spatie Laravel Permissions** (Roles y permisos)
- **Sanctum** (Autenticación API)

---

## 📦 Estructura del proyecto
```bash
todo-app/
│── backend/ # API en Laravel
│── frontend/ # SPA en Vue 3
└── README.md
```
---

# 🚀 Instalación Rápida

## 1️⃣ Clonar el repositorio

```bash
git clone https://github.com/francovegadev/task-app-laravel-vue.git
cd todo-app
```
---

## 🛠 Instalación Backend (Laravel)

```bash
cd backend
composer install
```
---

```bash
cp .env.example .env
```
---

```bash
php artisan key:generate
```
---

## 💾 Crear base de datos postgresql

- **DB_CONNECTION**=pgsql
- **DB_PORT**=5432
- **DB_DATABASE**=tasks_db
- **DB_USERNAME**=postgres
- **DB_PASSWORD**=admin

```bash
php artisan migrate --seed
```

## ▶ Ejecutar el servidor

```bash
php artisan serve
```

## 💻 Instalación Frontend (Vue 3)

```bash
cd ../frontend
```
---

### Se puede utilizar npm para la instalación de los paquetes
```bash
npm install
npm run dev
```
---
### Se puede utilizar bun para la instalación de los paquetes

* **Mac/linux**
```bash
curl -fsSL https://bun.com/install | bash
```
---

* **Windows**
```bash
powershell -c "irm bun.sh/install.ps1|iex"
```
---

* **Package Managers**
```bash
npm install -g bun # the last `npm` command you'll ever need
```
---
```bash
brew install oven-sh/bun/bun
```
---
```bash
scoop install bun
```
---

## 🔐 Autenticación

El backend usa Laravel Sanctum, por lo que debes asegurarte de que:
- **El frontend corra en http://localhost:5173**
- **El backend corra en http://localhost:8000**
- **SANCTUM_STATEFUL_DOMAINS esté configurado correctamente en .env**

```ini
SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost
```

## 👤 Roles disponibles

* **Admin** 
  - acceso total
  - email: admin@email.com
  - password: admin1234

* **Editor** 
  - puede crear/editar
  - email: editor@email.com
  - password: editor1234

* **Viewer** 
  - solo lectura
  - email: viwer@email.com
  - password: viwer1234
