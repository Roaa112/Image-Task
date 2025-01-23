## Contributing  
Thank you for considering contributing to this project! If you would like to contribute, please follow the installation steps below.

## Installation Steps  
To set up the project locally, follow these steps:

```sh
git clone https://github.com/Roaa112/Image-Task.git
cd Image-Task

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate --seed

npm install
npm run build 
