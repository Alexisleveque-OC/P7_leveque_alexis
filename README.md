# P7_leveque_alexis

This is README.md file of my repository fot Project 7 of the application developer PHP/Symfony.

## Story of Project
- Create all UML diagrams.
- Create all issues.
- Development of applications.
- Write README.
- Presentation of project to a mentor.

## Context
See the project context just here >>> https://openclassrooms.com/fr/paths/59/projects/43/assignment

## How to install ?

### Step 1 (optional if you have composer) :
- You need to install composer in your workplace. For this, let's go here https://getcomposer.org/download/. 
- Download and install it on your computer.

### Step 2 :
- Create a directory in your localserver (Exemple for Wamp : C:/wamp64/www). And clone project with this link https://github.com/Alexisleveque-OC/P7_leveque_alexis.git .

### Step 3 : 
- Copy file ".env" to ".env.local" (in directory App) whit your information.

### Step 4:
- The security is Based on LexikJWT so you will have to install and configure that bundle. You can find documentation here : https://github.com/lexik/LexikJWTAuthenticationBundle

### Step 4 :
- For database and Fixtures, in your terminal enter this command "composer prepare".
That's all, your Database is create and fixtures are load ! ;) 

### Step 5 :
Run App in your server if you have it or enter this command "symfony server:start"

#### optional
Create a virtualhost, for this :
- Go to your localhost
- In tool tabs, click on "Create a Virtual Host"
- Set fields with your information (ex : "BileMo.local" and "absolute path"/your_directory(create in the beginning)/P7_leveque_alexis/public )

#### Note 
- If some extension doesn't work correctly you can do this :
In your terminal, go to your directory of project and submit : "composer install"

- If you want to do some tests,copy ".env.local" and paste it with the name ".env.local.test". Enter in console "composer make-test", the app will be test and fixtures back in original condition.
