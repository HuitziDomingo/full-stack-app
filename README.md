# Arquimides - Full Stack Application

Aplicación full stack completa con backend en Laravel, frontend en Angular, aplicación móvil en React Native y microservicio de reportes en NestJS.

## 🏗️ Arquitectura del Proyecto

```
Arquimides/
├── backend/          # API REST con Laravel
├── frontend/         # Aplicación Web con Angular
├── mobile/           # Aplicación Móvil con React Native
└── reports-service/  # Microservicio de Reportes con NestJS
```

## 🚀 Inicio Rápido

### Prerrequisitos

- Docker y Docker Compose
- Node.js 18+ (para desarrollo local)
- PHP 8.2+ (para desarrollo local del backend)
- Composer (para desarrollo local del backend)
- Bun (para Angular y React Native)
- pnpm (para NestJS Reports Service)

### Levantar todos los servicios con Docker

```bash
# Levantar todos los servicios (Laravel, NestJS, MongoDB, PostgreSQL)
docker-compose up -d

# Ver logs de todos los servicios
docker-compose logs -f

# Detener todos los servicios
docker-compose down
```

### Desarrollo Local

#### Backend (Laravel)
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

#### Frontend (Angular)
```bash
cd frontend
bun install
bun run ng serve
# O alternativamente:
ng serve
```

#### Mobile (React Native)
```bash
cd mobile
bun install
# iOS
bun run react-native run-ios
# Android
bun run react-native run-android
```

#### Reports Service (NestJS)
```bash
cd reports-service
pnpm install
pnpm run start:dev
```

## 📦 Servicios

### Backend API (Laravel)
- **Puerto**: 8000
- **Base de datos**: PostgreSQL
- **Documentación**: Ver [backend/README.md](./backend/README.md)

### Frontend Web (Angular)
- **Puerto**: 4200
- **Documentación**: Ver [frontend/README.md](./frontend/README.md)

### Mobile App (React Native)
- **Documentación**: Ver [mobile/README.md](./mobile/README.md)

### Reports Service (NestJS)
- **Puerto**: 3001
- **Base de datos**: MongoDB
- **Documentación**: Ver [reports-service/README.md](./reports-service/README.md)

## 🗄️ Bases de Datos

- **PostgreSQL**: Puerto 5432 (para Laravel)
- **MongoDB**: Puerto 27017 (para NestJS Reports Service)

## 🛠️ Tecnologías

- **Backend**: Laravel 11
- **Frontend**: Angular 21
- **Mobile**: React Native
- **Microservicio**: NestJS
- **Bases de Datos**: PostgreSQL, MongoDB
- **Contenedores**: Docker & Docker Compose
- **Gestores de Paquetes**: Bun (Angular/React Native), pnpm (NestJS), Composer (Laravel)

## 📝 Notas de Desarrollo

- Cada servicio tiene su propio README con instrucciones específicas
- Los archivos `.env` no deben subirse a Git (ver `.gitignore`)
- Usa `docker-compose.yml` en la raíz para orquestar todos los servicios
- Cada servicio puede ejecutarse independientemente para desarrollo
- **Gestores de paquetes**: 
  - Angular y React Native usan **Bun**
  - NestJS Reports Service usa **pnpm**
  - Laravel usa **Composer**

## 🤝 Contribución

1. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
2. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
3. Push a la rama (`git push origin feature/AmazingFeature`)
4. Abre un Pull Request

## 📄 Licencia

Este proyecto es privado y confidencial.
