# Organization Relationships
> RESTful service called “Organization Relationships” that stores organizations with relations (parent to child relation)
> Live demo [_here_](https://org.agencypin.com).

## Table of Contents
* [General Info](#general-information)
* [Requirements](#requirements)
* [Setup](#setup)
* [Usage](#usage)

## General Information
One organization may have multiple parents and daughters. All relations and organizations are inserted with one request.

API has a feature to retrieve all relations of one organization. This endpoint response includes all parents, daughters and sisters of a given organization.

## Requirements
- PHP >= 7.4
- Apache Web Server
- MySQL >= 5.5

## Setup
- Install MySQL/MariaDB to your server. Follow instructions here: https://www.mariadbtutorial.com/getting-started/install-mariadb/

- Create database (navigate to application/db and find create.sql). Run SQL queries.

```
# clone repository
git clone https://dronyxxx@bitbucket.org/dronyxxx/org.git

# change config file and add your local variables including API endpoints
cd application/config
cp config_example.php config.php

# edit file
vi config.php
```

Change the following properties only

"host"        => "",
"username"    => "",
"password"    => "",
"dbname"      => ""

"save_data_url" => "",
"get_organizations_url" => ""

- NB! Set server DOCUMENT_ROOT to point to /public folder

Run application in your favourite browser

## Usage

- Get JSON example in public/example.json
- Paste json file contents into textarea and hit Submit button. This wil store your JSON into database
- Click "Get siblings by organization name" link to search for organization siblings
- Provide organization name and get json response