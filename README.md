# Control Financiero

> Aplicación web de gestión financiera personal — registra ingresos, egresos, categorías y obligaciones mensuales desde un solo lugar.

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![Livewire](https://img.shields.io/badge/Livewire-3-FB70A9?logo=livewire&logoColor=white)](https://livewire.laravel.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-38BDF8?logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![Pest PHP](https://img.shields.io/badge/Tests-Pest_PHP_3-brightgreen)](https://pestphp.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

---

## Descripcion

**Control Financiero** es una aplicacion web de finanzas personales que permite a cada usuario llevar un registro detallado de sus movimientos financieros. Ofrece una interfaz reactiva sin recargas de pagina, impulsada por Livewire directamente desde el servidor, sin necesidad de una API REST separada ni de un framework SPA.

Pensada para ser rapida, sencilla y privada: cada usuario solo ve sus propios datos.

---

## Caracteristicas

- **Transacciones** — registra ingresos y egresos con fecha, monto, categoria y estado (pagado / pendiente).
- **Pagos pendientes** — lista de cuentas por cobrar y por pagar con resaltado de fecha de vencimiento.
- **Categorias** — crea y administra categorias propias con slug auto-generado; protegidas contra eliminacion si tienen transacciones asociadas.
- **Obligaciones mensuales** — define pagos recurrentes (ej. renta, suscripciones) con dia limite; genera automaticamente transacciones pendientes para los meses restantes del año.
- **Dashboard** — metricas rapidas: balance, total de ingresos y egresos del mes en curso.
- **Filtros y busqueda** — filtra transacciones por rango de fechas (hoy, mes actual, mes anterior, historico) y por categoria.
- **Autenticacion completa** — registro, login, verificacion de correo, restablecimiento de contrasena y eliminacion de cuenta.
- **Configuracion de perfil** — cambia nombre, correo y contrasena desde ajustes.
- **PWA-ready** — soporte basico de Progressive Web App via `silviolleite/laravelpwa`.
- **Tema claro / oscuro** — preferencia de apariencia persistida por usuario.

---

## Stack Tecnico

### Backend

| Tecnologia | Version | Rol |
|---|---|---|
| PHP | >= 8.2 | Lenguaje base |
| Laravel | 12 | Framework MVC |
| Livewire | 3 | Componentes reactivos server-side |
| Livewire Volt | 1.7 | Componentes de archivo unico (single-file) |
| Livewire Flux | 2.1 | Biblioteca de componentes UI pre-construidos |
| Laravel Pail | 1.2 | Log viewer en terminal |
| Laravel Sail | 1.41 | Entorno Docker de desarrollo |

### Frontend

| Tecnologia | Version | Rol |
|---|---|---|
| Tailwind CSS | 4 | Framework de estilos utilitarios |
| Vite | 7 | Bundler de assets |
| Alpine.js | (via Livewire) | Interacciones minimas en cliente |
| flatpickr | 4.6 | Selector de fechas |
| Axios | 1.7 | Cliente HTTP (CSRF, peticiones internas) |

### Base de Datos y Almacenamiento

| Tecnologia | Uso |
|---|---|
| MySQL / PostgreSQL | Base de datos principal |
| SQLite (en memoria) | Tests automatizados |
| Redis | Cache y colas (opcional) |
| Database driver | Sesiones, cache y colas por defecto |

### Calidad y Pruebas

| Herramienta | Rol |
|---|---|
| Pest PHP 3 | Framework de testing |
| Laravel Pint | Formateo de codigo (PSR-12) |
| Mockery | Mocks en tests unitarios |
| GitHub Actions | CI — tests y build en cada push/PR |

---

## Arquitectura

La aplicacion sigue el patron **Laravel + Livewire monolitico**: no existe API REST ni SPA separada. Toda la reactividad se maneja en el servidor a traves de componentes Livewire.

```
app/
├── Livewire/
│   ├── Auth/           # Login, Register, ForgotPassword, ResetPassword, VerifyEmail
│   ├── Transactions/   # Filters (listado), Create, Update, PendingTransactions
│   ├── Category/       # CategoryIndex, CategoryCreate, CategoryEdit
│   ├── Obligations/    # MonthlyBillings
│   ├── Metrics/        # Grid (dashboard)
│   ├── Settings/       # Profile, Password, Appearance, DeleteUserForm
│   ├── Forms/          # TransactionForm, CategoryForm, ObligationForm (objetos de formulario)
│   └── Actions/        # Logout
├── Models/
│   ├── User.php        # hasMany(Transaction, Category)
│   ├── Transaction.php # belongsTo(User, Category) | enum type: income|expense | enum state: paid|pending
│   ├── Category.php    # hasMany(Transaction), belongsTo(User) | slug auto-generado
│   └── Obligation.php  # belongsTo(User) | genera transacciones pendientes
└── Policies/           # Autorizacion por recurso (TransactionPolicy, etc.)

resources/views/
├── layouts/app.blade.php   # Shell principal de la app
├── dashboard.blade.php
└── livewire/               # Vistas de cada componente Livewire
```

### Modelo de Datos

```
User ──< Transaction >── Category
User ──< Category
User ──< Obligation
```

Todos los modelos usan **UUID** como clave primaria y estan **scopeados al usuario autenticado**.

### Diagrama de Estados — Transaction

```
[Creada] ──► state: pending  ──► state: paid
              └ expected_payment_date (fecha de vencimiento)
```

---

## Requisitos Previos

- PHP >= 8.2 con extensiones: `pdo`, `mbstring`, `xml`, `curl`, `zip`
- Composer >= 2
- Node.js >= 20 y npm
- MySQL >= 8 o PostgreSQL >= 15 (o SQLite para desarrollo rapido)

---

## Instalacion

```bash
# 1. Clonar el repositorio
git clone https://github.com/jasanzdev/financely.git
cd financely

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS
npm install

# 4. Configurar entorno
cp .env.example .env
php artisan key:generate

# 5. Configurar la base de datos en .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=financely
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Ejecutar migraciones
php artisan migrate

# (Opcional) Sembrar datos de ejemplo
php artisan db:seed
```

---

## Desarrollo

```bash
# Inicia servidor PHP, queue listener y Vite en paralelo
composer run dev

# Solo build de assets para produccion
npm run build
```

---

## Testing

```bash
# Ejecuta la suite completa (limpia cache + Pest)
composer test

# Ejecutar un test o clase especifica
php artisan test --filter=TransactionTest
```

Los tests usan una base de datos **SQLite en memoria** configurada en `phpunit.xml`, por lo que no requieren una base de datos real.

---

## Formateo de Codigo

```bash
# Aplica Laravel Pint (PSR-12)
./vendor/bin/pint
```

---

## Variables de Entorno Clave

| Variable | Descripcion | Default |
|---|---|---|
| `APP_NAME` | Nombre de la aplicacion | `Control Financiero` |
| `DB_CONNECTION` | Driver de base de datos | `mysql` |
| `QUEUE_CONNECTION` | Driver de colas | `database` |
| `CACHE_STORE` | Driver de cache | `database` |
| `SESSION_DRIVER` | Driver de sesiones | `database` |
| `MAIL_MAILER` | Driver de correo | `log` (desarrollo) |

---

## Integracion Continua

El repositorio incluye workflows de **GitHub Actions**:

| Workflow | Trigger | Descripcion |
|---|---|---|
| `tests.yml` | Push / PR a `main` | Instala dependencias, ejecuta `composer test` |
| `build.yml` | Push / PR a `main` | Instala deps de Composer y Node, ejecuta `npm run build` |

---

## Licencia

Distribuido bajo la licencia **MIT**. Consulta el archivo [LICENSE](LICENSE) para mas detalles.

---

## Autor

**Jose Alejandro** — [@jasanzdev](https://github.com/jasanzdev)
