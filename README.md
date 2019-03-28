# ToDoList

This project has been realized as part of project 8 **Openclassroom** training.

### Server Requirements of the Web App
- Application Server PHP 5.5.9 or higher
- Database MySQL >= 5.7.11

Installation
-----------------
- Clone the master branch

       git clone git@github.com:gdpweb/todolist.git
        
- Install dependencies with:
    
       composer install
- Create database: 

       bin/console doctrine:database:create
              
- Update database:

       bin/console doctrine:schema:update --force
        
- Load data fixtures:

       bin/console doctrine:fixtures:load
        
- Run PHP's built-in Web Server: 

       bin/console server:run
        
- Navigate to: localhost:8000      
        
Test TodoList with PHPUnit
-----------------   

- Create and update database test:

       bin/console doctrine:database:create --env=test
       bin/console doctrine:schema:create --env=test

- Start the test and get the coverage

       vendor/bin/simple-phpunit --coverage-html web/tests

- See code coverage

       http://127.0.0.1:8000/tests/index.html
        
- **Documentation PHPUnit** : [phpunit.de]( https://phpunit.de/)


Licence
--------
[![Open Source Love](https://badges.frapsoft.com/os/v2/open-source.png?v=103)](https://github.com/ellerbrock/open-source-badges/)

#### Maintainability
[![Maintainability](https://api.codeclimate.com/v1/badges/367bdbd4f6566ce810be/maintainability)](https://codeclimate.com/github/gdpweb/todolist/maintainability)