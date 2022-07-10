### Introduction 👋

I have created a Magento module for the specific task given. This module is created and works on the latest Magento version 2.4.4.
A module contains two  RESTful services POST and GET. POST that store organizations with relations (parent to child relation), from the given JSON example and GET that list stored organization and organization relations. 

## Usage

To test this module, please add Test_Acty module in app/code directory

<b> Setup the module </b>

Run the following magento command: 

```bash
bin/magento setup:upgrade 
```
# API REQUESTS

I have used Postman to test my APIS.  

## Insert API 

Api url for insert statement

```bash
/rest/V1/acty/organizaton/insert 
```

Use given JSON to make insert

```json
{ 

    "org_name": "Paradise Island", 

    "daughters": [ 

        { 

            "org_name": "Banana tree", 

            "daughters": [ 

                { 

                    "org_name": "Yellow Banana", 

                    "daughters": [ 

                        { 

                            "org_name": "Pink Banana" 

                        } 

                    ] 

                }, 

                { 

                    "org_name": "Brown Banana" 

                }, 

                { 

                    "org_name": "Black Banana" 

                } 

            ] 

        }, 

        { 

            "org_name": "Big banana tree", 

            "daughters": [ 

                { 

                    "org_name": "Black Banana", 

                    "daughters": [ 

                        { 

                            "org_name": "Phoneutria Spider" 

                        } 

                    ] 

                }, 

                { 

                    "org_name": "Yellow Banana" 

                }, 

                { 

                    "org_name": "Brown Banana" 

                }, 

                { 

                    "org_name": "Green Banana" 

                } 

            ] 

        } 

    ] 

} 
```
Expected Result 200 response code. 

###ERRORS 

Error 1

If you try to add deughter organization to be parent, sql error will be returned. 

Example to get error 1
```json
{
    "org_name": "Paradise Island",
    "daughters": [
        {
            "org_name": "Banana tree",
            "daughters": [
                {
                    "org_name": "Yellow Banana",
                    "daughters": [
                        {
                            "org_name": "Yellow Banana"
                        }
                    ]
                },
                {
                    "org_name": "Brown Banana"
                },
                {
                    "org_name": "Black Banana"
                }
            ]
        }
    ]
}
```


```bash
SQLSTATE[42000]: WITH CHECK OPTION violation: 1644 Check constraint failed, same parent and child id
```

Error 2

Also if you try to add child to be parent of his parent 

Example to get error 2
```json
{
    "org_name": "Paradise Island",
    "daughters": [
        {
            "org_name": "Purple Banana",
            "daughters": [
                {
                    "org_name": "Yellow Banana",
                    "daughters": [
                        {
                            "org_name": "Purple Banana"
                        }
                    ]
                },
                {
                    "org_name": "Brown Banana"
                },
                {
                    "org_name": "Black Banana"
                }
            ]
        },
        {
            "org_name": "Big banana tree",
            "daughters": [
                {
                    "org_name": "Black Banana",
                    "daughters": [
                        {
                            "org_name": "Phoneutria Spider"
                        }
                    ]
                },
                {
                    "org_name": "Yellow Banana"
                },
                {
                    "org_name": "Brown Banana"
                },
                {
                    "org_name": "Green Banana"
                }
            ]
        }
    ]
}
```
Error 2
```bash
SQLSTATE[42000]: Syntax error or access violation: 1172 Result consisted of more than one row
```
## Get List API 

Api url for get statement


```bash
/rest/V1/acty/organizaton/list/$organization/$page
```

Run following APi in Postman 

Example:

```bash
/rest/V1/acty/organizaton/list/Banana%20tree/page/1
```
Expected result:
 
Will get 200 response with json result.
This api will return json result with pagination  (max 100 rows per page).

###ERRORS 

If you run api with  organization name that not exist will get error 

```bash
"There is no results with $oganization organization name"
```

