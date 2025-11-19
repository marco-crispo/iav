# 🗺️ I Am Available (IAV)

**I Am Available (IAV)** is a real-time web and mobile application that allows users to share their live location on a common map, display their availability status, and interact with others through private messages.  
Its goal is to make it easier for people to meet and connect in real life — from casual encounters to meaningful relationships.

---

## 🚀 Main Features

- **Authentication (Laravel Sanctum)**
  - User registration with email verification  
  - Login / logout  
  - Automatic token refresh

- **User Profile Management**
  - Avatar and cover image upload  
  - Bio, personal info, languages, interests  
  - Relationship and availability status  
  - Real-time updates to all connected clients

- **Real-Time Map (Leaflet + Ably)**
  - View nearby users on a live-updating map  
  - Interactive markers with profile previews  
  - Real-time updates using Ably channels  
  - Automatic position updates every few seconds

- **Presence System**
  - Online/offline tracking using Ably Presence  
  - Hybrid sync with `online_users` table in Redis/MySQL  
  - Automatic cleanup of disconnected users

- **Private Messaging**
  - Dedicated channels for each user (e.g., `messages.sent.{userId}`)  
  - Instant message notifications via toast popups  
  - Persistent chat history

- **Notifications**
  - Real-time push notifications for new messages and profile updates  
  - Synced across all active user sessions

- **Admin Dashboard**
  - Built with [Backpack for Laravel](https://backpackforlaravel.com/)  
  - Active theme: **Tabler**  
  - Manage users, messages, system settings

---

## 🧱 Tech Stack

### **Backend**
- [Laravel 12](https://laravel.com/)
- [Sanctum](https://laravel.com/docs/sanctum) — API authentication  
- [Redis](https://redis.io/) — cache, queues, and presence tracking  
- [Ably Realtime](https://ably.com/) — real-time broadcasting  
- [MySQL 8+](https://www.mysql.com/) — main relational database  
- [Laravel Cloud](https://cloud.laravel.com/) — deployment and orchestration  
- [Backpack Tabler Theme](https://github.com/Laravel-Backpack/theme-tabler) — admin interface

### **Frontend**
- [Vue.js 3](https://vuejs.org/)
- [Ionic Vue](https://ionicframework.com/docs/vue) — responsive mobile-first UI  
- [TypeScript](https://www.typescriptlang.org/)
- [Leaflet](https://leafletjs.com/) — interactive map  
- [Pinia](https://pinia.vuejs.org/) — state management  
- [Axios](https://axios-http.com/) — API requests  
- [Font Awesome](https://fontawesome.com/) — icons  
- [Ably JS SDK](https://ably.com/docs/getting-started/javascript)

---

## ⚙️ Installation & Setup

### 1 Clone the repository
git clone https://github.com/marco-crispo/iav.git
cd iav

### 2 Install Laravel dependencies
composer install
create a .env file
php artisan key:generate

### 3 Edit the .env file:
APP_NAME=IAV
APP_ENV=local
APP_KEY=your-app-key
APP_DEBUG=true
APP_URL=http://localhost:8000        # dominio dell'API che genera il link firmato
FRONTEND_URL=http://localhost:8000/app   # dominio della tua app Vue/Ionic

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db-name
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_DRIVER=ably
ABLY_KEY=your-ably-key
BROADCAST_CONNECTION=ably
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=your-email-username
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email-username
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"


### 4 Install Vue/Ionic dependencies
cd frontend
npm install

### 5 Edit the frontend .env file
VITE_STORAGE_URL=http://localhost:8000/storage
VITE_API_URL=http://localhost:8000/api
VITE_WS_URL=ws://localhost:3001
VITE_APP_BASE_ROUTE=/
VITE_AGE_LOWER_VAL=18
VITE_AGE_UPPER_VAL=130
VITE_DEFAULT_RADIUS_KM=500

### 6 Run migrations and seed initial data
php artisan migrate --seed


### 7 Start local development servers in two separate terminal or put them together
#### 1 Start Laravel Vite
(cd ./iav && composer run dev)

#### 2 Start Laravel queue worker
(cd ./iav && php artisan queue:work)


#### 3 App available at
http://localhost:8000


## 🔒 **Security**

- All API endpoints protected via Laravel Sanctum
- Ably channels secured through token-based authentication using /ably-token
- User files (avatars, covers) served via Laravel’s storage system
- Custom email verification with branding and redirect support

## 👥 **Real-Time Events Overview**
Laravel Event	    Ably Channel	                Description	Frontend                Handler
UserLocationUpdated	public:users.location	        Broadcast updated user coordinates	Map (Leaflet)
UserStatusUpdated	public:users.status	            Broadcast availability change	    Profile page
UserProfileUpdated	public:users.profile	        Profile info update	                Profile view
NewMessageSent	    public:messages.sent.{userId}	New private message	                Toast popup
presence.enter/leave	public:presence:user-location	User enters or leaves presence	Pinia store presence.ts

## 🧠 **Developer Notes**

 - Online users are tracked hybridly:
     - via Ably Presence for immediate state
     - via the online_users table for database queries
 - Frontend presence and map updates are managed by Pinia
 - All real-time logic is centralized in ablyClient.ts
 - Debug and monitor presence directly in your Ably Dashboard

👨‍💻 Author
Marco Crispo
Full-stack Developer
Laravel • Vue • Ionic • Realtime Apps

📜 License
This project is distributed under the MIT License.
