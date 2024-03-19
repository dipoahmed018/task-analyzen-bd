## User CRUD

#### Requirements

-   <a href="https://laravel.com/docs/11.x/deployment#server-requirements">Laravel 11 Requirements</a>
-   PHP >= 8.2
-   MySQL >= 8.0
-   Node >= 20
-   Composer >= 2

#### Installation

Add `user-crud` and `user-crud-test` databases on your mysql. If you want to change the names make sure you reflact those chages on .env database configuration.

Maker Sure you are on project root directory

If you are on linux just running the `setup.sh` file will suffice instead of running these bellow commands.

1. `composer install`
2. `npm inserall`
3. `cp .env.example .env`
4. `cp .env.testing.example .env.testing`
5. `php artisan key:generate`
6. `php artisan migrate --seed`
7. `php artisn storage:link`
8. `npm run build`
9. `php artisn serve`

After running the last command, you will get an address ex. `http://localhost:8000`. Copy that address and paste it in both of yours `.env` and `.env.testing` files `APP_URL` environment variable. and you are good to go. Copy the url and paste it in you web browser to see the website.

#### Creadentials

Email: `test@example.com`
Password: `password`

#### Testing

Run `php artisan test`

#### One concern

-   As you have instructed me to use listener to listen for user update and update the user addressess accordingly I could not do that since Model `updated`, `updating`, `saved`, `saving` listeners are only fired on user update which opens up an edge case of addresses not being updated if there is no changes on the user info but only on user addresses.
