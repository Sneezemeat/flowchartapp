A Symfony and Vue.js project that implements a flowchart editor. It was created especially for beginners in computer science, 
who do not have much experience in programming. This application helps
to understand basic concepts of programming by using a tutorial. After this,
the application can be used as a simple editor for flowcharts.

This application requires npm and composer. 
If you have npm and composer installed, run:

`> npm install`

and

`> composer install`

After this, specify a database URL in the .env file and run 

`> bin/console doctrine:database:create`

and

`> bin/console doctrine:migrations:migrate`