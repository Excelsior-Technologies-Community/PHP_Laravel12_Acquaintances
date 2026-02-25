# PHP_Laravel12_Acquaintances


## Project Description

PHP_Laravel12_Acquaintances is a Laravel 12 based application that demonstrates how to implement social networking features such as friend requests, follow/unfollow, likes, and ratings using the Laravel Acquaintances package.

This project integrates the multicaret/laravel-acquaintances package to provide ready-made relationship management functionality between users. It allows users to interact with each other similar to social media platforms like Facebook or Instagram.

The system stores all relationship data in the database and provides simple methods to manage friendships and interactions efficiently.


## Core Functionalities

• Send Friend Request between users
• Accept Friend Request
• Follow other users
• Like other users
• Check friendship status
• Store relationship data in database
• Manage user-to-user social interactions


## Traits Used in User Model

• Friendable – Handles friendship functionality
• CanFollow – Allows user to follow others
• CanBeFollowed – Allows user to be followed
• CanLike – Allows user to like others
• CanBeLiked – Allows user to receive likes
• CanRate – Allows user to rate others
• CanBeRated – Allows user to receive ratings



## Requirements

The following software versions are required to run this project:

• PHP >= 8.2
• Laravel 12
• Composer
• MySQL
• XAMPP / Laragon / WAMP

---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Acquaintances "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Acquaintances

```

#### Explanation:

This command installs a fresh Laravel 12 application and creates a new project folder. 

The cd command navigates into the project directory so we can run Laravel commands.





## STEP 2: Database Setup 

### Open .env and set:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_acquaintances
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_acquaintances

```

#### Explanation:

This step connects Laravel with the MySQL database. 

Laravel will use this database to store users, friendships, follows, likes, and other social interaction data.





## STEP 3: Install Laravel Acquaintances Package

### Run:

```
composer require multicaret/laravel-acquaintances

```

#### Explanation:

This command installs the Laravel Acquaintances package, which provides ready-made functions for friend requests, follow system, likes, and social interactions.





## STEP 4: Publish Package Files

### Run: 

```
php artisan vendor:publish --provider="Multicaret\Acquaintances\AcquaintancesServiceProvider"

```

### Run Migration

```
php artisan migrate

```

#### Explanation:

This command publishes the package configuration and migration files into the Laravel project so they can be customized if needed.

This command creates required database tables such as users, friendships, follows, likes, and ratings in the database.




## STEP 5: Update User Model

### Open: app/Models/User.php

#### Replace code:

```
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Multicaret\Acquaintances\Traits\Friendable;
use Multicaret\Acquaintances\Traits\CanFollow;
use Multicaret\Acquaintances\Traits\CanBeFollowed;
use Multicaret\Acquaintances\Traits\CanLike;
use Multicaret\Acquaintances\Traits\CanBeLiked;
use Multicaret\Acquaintances\Traits\CanRate;
use Multicaret\Acquaintances\Traits\CanBeRated;

class User extends Authenticatable
{
    use Friendable;
    use CanFollow, CanBeFollowed;
    use CanLike, CanBeLiked;
    use CanRate, CanBeRated;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}

```

#### Explanation:

These traits add social relationship functionality to the User model, such as sending friend requests, following users, liking users, and rating users.





## STEP 6: Create Users Seeder

### Run:

```
php artisan make:seeder UserSeeder

```

### Open: database/seeders/UserSeeder.php

```
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'User One',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('123456')
        ]);

        User::create([
            'name' => 'User Two',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('123456')
        ]);
    }
}

```

#### Explanation:

This command creates a seeder file used to insert sample users into the database for testing.

This code creates two test users in the database so we can test friend requests, follow, and like features.




## STEP 7: Register Seeder

### Open: database/seeders/DatabaseSeeder.php

#### Add:

```
public function run(): void
{
    $this->call(UserSeeder::class);
}

```

### Run:

```
php artisan db:seed

```


#### Explanation:

This step registers the UserSeeder so Laravel knows which seeder to run.

This command inserts the test users into the database.




## STEP 8: Create Controller

### Run: 

```
php artisan make:controller AcquaintanceController

```

### Open: app/Http/Controllers/AcquaintanceController.php

```
<?php

namespace App\Http\Controllers;

use App\Models\User;

class AcquaintanceController extends Controller
{

    // Send Friend Request
    public function sendRequest()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->befriend($user2);

        return "Friend Request Sent";
    }

    // Accept Friend Request
    public function acceptRequest()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user2->acceptFriendRequest($user1);

        return "Friend Request Accepted";
    }

    // Follow User
    public function followUser()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->follow($user2);

        return "User Followed";
    }

    // Like User
    public function likeUser()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->like($user2);

        return "User Liked";
    }

    // Check Friend Status
    public function checkFriend()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        if ($user1->isFriendWith($user2)) {
            return "They are Friends";
        }

        return "Not Friends";
    }

}

```

#### Explanation:

This controller contains functions for sending friend requests, accepting requests, following users, liking users, and checking friendship status.





## STEP 9: Create Routes

### Open: routes/web.php

#### Add:

```
use App\Http\Controllers\AcquaintanceController;

Route::get('/send-request', [AcquaintanceController::class, 'sendRequest']);

Route::get('/accept-request', [AcquaintanceController::class, 'acceptRequest']);

Route::get('/follow-user', [AcquaintanceController::class, 'followUser']);

Route::get('/like-user', [AcquaintanceController::class, 'likeUser']);

Route::get('/check-friend', [AcquaintanceController::class, 'checkFriend']);

```

#### Explanation:

Routes connect URLs with controller functions so users can perform social actions through the browser.





## STEP 10: Test in Browser

### Run: 

```
php artisan serve

```

### Open:

```
http://127.0.0.1:8000

```





## STEP 11: Test Output

#### Open browser:

### Send Request

```
http://127.0.0.1:8000/send-request

```

#### Output:

```
Friend Request Sent

```


<img width="1919" height="870" alt="Screenshot 2026-02-25 120604" src="https://github.com/user-attachments/assets/d8e8b029-0d06-4729-9b5f-cc404ad86d1d" />


### Accept Request

```
http://127.0.0.1:8000/accept-request

```

#### Output:

```
Friend Request Accepted

```


<img width="1919" height="871" alt="Screenshot 2026-02-25 120830" src="https://github.com/user-attachments/assets/d93a41b3-d003-46c0-898e-dc87831bdbb4" />


### Follow User

```
http://127.0.0.1:8000/follow-user

```

#### Output:

```
User Followed

```


<img width="1919" height="922" alt="Screenshot 2026-02-25 120905" src="https://github.com/user-attachments/assets/82899624-e8d6-4944-a38b-1f418475073b" />


### Like User

```
http://127.0.0.1:8000/like-user

```

#### Output:

```
User Liked

```


<img width="1919" height="842" alt="Screenshot 2026-02-25 120957" src="https://github.com/user-attachments/assets/829d934e-0108-499c-bb64-0b02c6c7718b" />


### Check Friend

```
http://127.0.0.1:8000/check-friend

```

#### Output:

```
They are Friends

```


<img width="1919" height="871" alt="Screenshot 2026-02-25 121039" src="https://github.com/user-attachments/assets/acac1ffd-b455-4e74-9e30-a66fdfd2c601" />


---

# Project Folder Structure:

```
PHP_Laravel12_Acquaintances
│
├── app
│   ├── Models
│   │   └── User.php
│   │
│   └── Http
│       └── Controllers
│           └── AcquaintanceController.php
│
├── database
│   ├── migrations
│   └── seeders
│       ├── DatabaseSeeder.php
│       └── UserSeeder.php
│
├── routes
│   └── web.php
│
├── .env

```

