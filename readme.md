## Web Service Symfony Back-end API

# How To Run

```bash
docker-compose up
```

You can try http://127.0.0.1/api/v1/product

With below commands you will migrate the database and create dummy datas.

```bash
sudo docker-compose exec appOrder php ./bin/console --env=test make:migration
sudo docker-compose exec appOrder php ./bin/console --env=test doctrine:migrations:migrate
sudo docker-compose exec appOrder php ./bin/console --env=test doctrine:fixtures:load
```

These commadns are for the unit test database.

```bash
sudo docker-compose exec appOrder php ./bin/console --env=test doctrine:migrations:migrate
sudo docker-compose exec appOrder php ./bin/console --env=test doctrine:fixtures:load
```




### Genereal Service Resposne

Service will return this kind of response.

```JSON
{
    "status": "success",
    "data": {
        "id": 10,
        "orderDetails": [
            {
                "id": 28,
                "product": {
                    "id": 1,
                    "title": "UpdatedTitle1",
                    "description": "UpdatedDescription1",
                    "status": true
                },
                "price": "100.00"
            },
            {
                "id": 5,
                "product": {
                    "id": 2,
                    "title": "UpdatedTitle2",
                    "description": "UpdatedDescription2",
                    "status": true
                },
                "price": "100.00"
            },
        ],
        "totalPrice": "200.00",
        "createdAt": 1632936183,
        "updatedAt": 1632936183
    }
}
```

```
{
"status": "failure",
"data": "Not a valid Token"
}
```

We are providing Docker environment for local development. You can install [docker](https://docs.docker.com/install/)
& [docker-compose on](https://docs.docker.com/compose/install/) your machine and run `docker-compose up` on project's
root directory.

# Logic

Post and Put endpoints involves authentication. You must sign your payload to send your request. There is a written example in php below.

User and Currency are created automatically with fixtures.


# Api Endpoints

## PRODUCT

`GET` [/api/v1/product](#post-1billingstart-trialjson) <br/>

You don't need to enter any parameters for getting product list

`POST` [/api/v1/product/](#post-1billingstart-trialjson) <br/>
`PUT` [/api/v1/product/{$id}](#post-1billingstart-trialjson) <br/>

**Parameters**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `currency` | required | string  | Currency of the product.                                                                     |
|     `status` | required | boolean  | Status of the product. `true` or `false`
|     `title` | required | string  | Title of the product
|     `description` | optional | string  | Description of the product.
|     `price` | required | decimal  | Price of the product.

## CURRENCY

`GET` [/api/v1/currency](#post-1billingstart-trialjson) <br/>

Ypu will get currency types.

## ORDER

`GET` [/api/v1/order](#post-1billingstart-trialjson) <br/>

Ypu will get orders. You can enter 2 parameteres to filter the datas.

**Parameters**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `startedAt` | optional | string  | Minimum date for the orders to retrieve.                                                                     |
|     `endedAt` | optional | string  | Minimum date for the orders to retrieve.

`POST` [/api/v1/order](#post-1billingstart-trialjson) <br/>

You can insert some orders to database. In this endpoint there is a custom authentication. Here is the formula. 

```php
$payload = [
    'products' => [ 1, 2, 3 ];
]
$payloadString = http_build_query($payload)  
$signature = hash_hmac('sha256',$payloadString,  $secretKey);

```
Only products with the status true must be inserted otherwise api will return error.


**Parameters**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `products` | required | array  | Product id array.                                                                     |
|     `signature` | required | string  | Sha1 calculated crypted data.


`GET` [/api/v1/order/{id}](#link) <br/>

You can get specific order from this endpoint.

