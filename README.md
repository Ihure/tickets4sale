# Tickets4sale

This is a demo APP for a movie store inventory. The backend is built using [Slim PHP](http://www.slimframework.com/).
The frontend is built in [Angular x8](https://angular.io/)

## setup

* clone the app

# Backend

This are instructions and considerations taken when building the backend

* to install dependencies run the instructions in backend
```bash
 cd backend
 composer install
```

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writable.

The app has been developed with a mysql database. the starter db can be found in
the root folder of backend directory. Kindly import this before proceeding.

Before running the app ensure:
* import starter.sql
* change db settings in app/config/settings to your local db settings

To run the application , you can run these commands 

```bash
cd backend
composer start
```

To Run this command in the application directory to run the test suite

```bash
composer test
```

The test scenarios are included in the starter db therefore they will test against
scenario 1 and scenario 2.

### Rest API

This is a REST API. You can test this via [Postman](https://www.getpostman.com/).
I created a POST request that is in the formart [your_backend_url]/inventory/inventory/checkInventory
i.e
```bash
http://localhost:8080/inventory/checkInventory
```
This url accepts three arguments:
* csv - this will be csv file containing the shows inventory
* show_date - the show date in YYYY-MM-DD format
* query_date - Query date in YYYY-MM-DD format

The result will be a json such us
```json
{"inventory":[{"genre":"musical","shows":[{"title":"Cats","genre":"musical","tickets_left":50,"tickets_available":5,"status":"open for sale"}]},{"genre":"comedy","shows":[{"title":"Comedy of Errors","genre":"comedy","tickets_left":100,"tickets_available":10,"status":"open for sale"}]},{"genre":"drama","shows":[{"title":"Everyman","genre":"drama","tickets_left":100,"tickets_available":10,"status":"open for sale"}]}]}
```

The same url is used by the web solution but with one additional field
* csv - becomes optional
* web - set to 1

### Business logic

The first step is after uploading we process the csv and get:
* the last day of the show
* when sales start for the show
* the last day of sales for the show

This is then stored in the database. the second step is getting the tickets available, sold, status of tickets
and tickets available based on the query date and show date. After this we then format the data into Preferred 
JSON format. for backend or frontend  


That's it!.

# Frontend

These are the instructions for the frontend

* to install dependencies run the instructions in frontend dir
```bash
 cd frontend
 yarn install
```

To run the application , you can run these commands 

```bash
cd frontend
ng serve
```

The app will be available at `http://localhost:4200/` browse to this page

You will have the option to enter the date submit and you'll get results
such as those below

[[https://github.com/Ihure/tickets4sale/blob/master/frontend/sc2.PNG|alt=octocat]]

the query date will be day of querying
