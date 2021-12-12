# Motorcycle Market 

This task aims to build an API that get data from Motorcycle owners and show it to buyers.

## Project Setup 

### To run the project run this command ( docker should be installed)
```
    sudo docker-compose up --d --build
    composer install
    php artisan migrate
    php artisan passport:install
    php artisan serve
```

## API routes :

### Register
```
    Route : /api/register/
    Method : POST
    Body : {name, email, phone, password}
    Response :
        200 success : {token}
        422 Fail : {message : The given data was invalid., errors:[]}
```

### Login
```
    Route : /api/login/
    Method : POST
    Body : {email, password}
    Response :
        200 success : {token}
        422 Fail : {message : The given data was invalid., errors:[]}
        401 Fail : UnAuthorised Access
```

### Get all motorcycles
```
    Route : /api/products/
    Method : GET
    Response :
        200 success : [products]
```

### Post a product 
```
    Route : /api/products/
    Headers : {Authorization}
    Method : POST
    Body : {model, make, year, description}
    Response :
        201 success : {motorcycle}
        422 Fail : {message : The given data was invalid., errors:[]}
        401 Fail : UnAuthorised Access
```

### Add images to product
```
    Route : /api/products/{id}/image
    Headers : {Authorization}
    Method : POST
    Body : {images[]}
    Response :
        200 success : {'Images Uploaded Successfully'}
        422 Fail : {message : The given data was invalid., errors:[]}
        401 Fail : UnAuthorised Access
        404 Fail : resource not found
```

### Mark product as sold
```
    Route : /api/products/{id}
    Headers : {Authorization}
    Method : DELETE
    Response :
        200 success : {'Motocycle Updated Successfully'}
        401 Fail : UnAuthorised Access
        404 Fail : resource not found
```