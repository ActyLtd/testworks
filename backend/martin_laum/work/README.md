# test-work (100% PHP based)
create a RESTful service that stores organisations with relations (parent to child relation). Organization name is unique. One organisation may have multiple parents and daughters. All relations and organisations are inserted with one request (endpoint 1). API has a feature to retrieve all relations of one organization (endpoint 2). This endpoint response includes all parents, daughters and sisters of a given organization.
# What I used
- XAMPP
- VS Code
- Postman
# Installation
1. Download the repo files and place db.php, api.php files and .htaccess into your preferred server(In my case They are located in the XAMPP htdocs folder) and Import the api_db.sql file into your MySQL database client (in my case PHPMyAdmin for example) to create the necessary tables with sample data that was provided in the test work file.
2. Make any neccessary changes in db.php
3. Use Postman or any other similar program to insert your data.
4. Included api_db.sql has example data to test out endpoint 2. (exactly the same data as it is in the test work task.)
# File explanation
- api.php   - Has the necessary code to run both endpoints.
- db.php    - Has the necessary database connection settings.
- .htaccess - Clean up URLs to provide prettier endpoints without the file extensions in the URL.
# Example endpoint URLs (assuming it's running on localhost)
http://localhost/api/Black%20banana <br />
http://localhost/api/Paradise%20Island/1 <br />
http://localhost/api/Black%20banana/2 (should return error: no records found!)
