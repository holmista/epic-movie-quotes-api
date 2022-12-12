Epic Movie Quotes is an API which enables users to create, update and view quotes from different movies. After you sign in with your email and password or with a google account you get redirected to a paginated news feed where all quotes from different users are displayed. You can also visit you profile page and update your avatar or username. You can also create a movie which will be added to your list of movies. If someone comments on your quote or likes it you will get a live notification of it.
<br>Here is the [front-end repository](https://github.com/RedberryInternship/tornike-buchukuri-epic-movie-quotes)

#

### Table of Contents

-   [Prerequisites](#prerequisites)
-   [Tech Stack](#tech-stack)
-   [Getting Started](#getting-started)
-   [Migrations](#migration)
-   [Development](#development)
-   [Project Structure](#project-structure)
-   [Create Database](#create-database)

#

### Prerequisites

-   *PHP@8.1 and up*
-   _MYSQL@8 and up_
-   *npm@8.15 and up*
-   *composer@2.4 and up*

#

### Tech Stack

-   [Laravel@9.x](https://laravel.com/docs/6.x) - back-end framework
-   [Vite](https://vitejs.dev/) - is a bundler which makes an ease for a developer to start working on JS files and compile them with such simplicity...
-   [swagger-ui](https://swagger.io/tools/swagger-ui/) - library for api endpoints documentation

#

### Getting Started

1\. First of all you need to clone epic-movie-quotes-api repository from github:

```sh
git clone https://github.com/RedberryInternship/tornike-buchukuri-epic-movie-quotes-api.git
```

2\. Go to the root of the folder:

```sh
cd tornike-buchukuri-epic-movie-quotes-api
```

3\. Next step requires you to run _composer install_ in order to install all the dependencies.

```sh
composer install
```

4\. after you have installed all the PHP dependencies, it's time to install all the JS dependencies:

```sh
npm install
```

5\. link storage to public directory:

```sh
php artisan storage:link
```

6\. Now we need to set our env file:

```sh
cp .env.example .env
```

And now you should provide **.env** file all the necessary environment variables(View Section [Create Database](#create-database) and [Setup Pusher](#setup-pusher)):

Now execute in the root of you project following:

```sh
php artisan key:generate
```

Which generates auth key.

after setting up **.env** file, execute:

```sh
php artisan config:cache
```

in order to cache environment variables.

#

### Migration

if you've completed getting started section, then migrating database if fairly simple process, just execute:

```sh
php artisan migrate
```

fill the categories table with the seeder

```sh
php artisan db:seed --class=CategorySeeder
```

#

### Development

You can run Laravel's built-in development server by executing:

```sh
php artisan serve
```

when working on JS you may run:

```sh
npm run dev
```

[Database Design Diagram](https://drawsql.app/teams/oit/diagrams/epic-movie-quotes)

### Create Database

1\. Firstly get into mysql prompt and provide password if neccessary:

```sh
sudo mysql
```

2\. After that create a new user, replace your_username and your_password with some other values:

```sh
CREATE USER 'your_username'@'localhost' IDENTIFIED BY 'your_password';
```

3\. Grant permissions to newly created user:

```sh
GRANT ALL PRIVILEGES ON *.* TO 'your_username'@'localhost' WITH GRANT OPTION;
```

4\. Create a database:

```sh
CREATE DATABASE epicMovieQuotes;
```

### Setup Pusher

setting up pusher is fairly simple, you should just visit their [website](https://pusher.com/) and create an account, after that you should create a channel and copy server credentials to .env file
