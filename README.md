php artisan nova:install
php artisan migrate
php artisan nova:user
php artisan make:migration create_posts_table --create="posts"
php artisan migrate
php artisan nova:resource Post
