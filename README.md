php artisan nova:install
php artisan migrate
php artisan nova:user
php artisan make:migration create_posts_table --create="posts"
php artisan make:model Post
php artisan migrate
php artisan nova:resource Post
php artisan nova:resource Tag
php artisan make:migration create_tags_table --create="tags"
php artisan make:model Tag
php artisan make:migration create_post_tag_table --create="post_tag"
php artisan migrate
php artisan make:policy PostPolicy -m Post
composer require laravel/scout
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
composer require algolia/algoliasearch-client-php
php artisan scout:import "App\Post"
