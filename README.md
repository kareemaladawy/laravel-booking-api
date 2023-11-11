
# Laravel-based Booking API Clone

This Laravel-based booking API provides a comprehensive solution for managing properties, apartments, and bookings, similar to the Booking.com API. It allows property owners to effortlessly manage their listings, while users can seamlessly search and book accommodations.

[![MIT License](https://img.shields.io/badge/License-MIT-blue.svg)](https://choosealicense.com/licenses/mit/)

## Key Features

- Search capabilities: Enabling users to search for properties and apartments based on various criteria, including location, price, amenities, and availability.
- Property Management: Create, update, and manage property details, including photos, descriptions, and amenities.
- Apartment Management: Add, edit, and manage individual apartments within each property, including rooms, rates, and availability.
- Booking Management: Facilitate user searches for available accommodations, and process bookings.
- Authentication and Authorization: Implement robust authentication and authorization mechanisms to ensure secure access to property management features.




## Requirements
- Laravel framework
- PHP >= 8
- Database (MySQL, PostgreSQL, etc.)
## Installation
    
- Clone the repository
- Install dependencies: ``` composer install ```
- Configure database connection in ``` .env ```
- Migrate and seed database tables: ``` php artisan migrate --seed```
## API Reference

#### Make sure to specify API version before every request

``
 /api/v1
``
#### Authenticate
| Endpoint  | Method   | Parameters                |
| :-------- | :------- | :------------------------- |
| `/register`  | `POST` | name, email, password, role_id (Owner/User) |
| `/login`  | `POST` | email, password | 

### Registered User Specific Endpoints
#### Manage bookings
| Endpoint  | Method   | Description                |
| :-------- | :------- | :------------------------- |
| `/user/bookings`  | `POST` | Create booking | 
| `/user/bookings`  | `GET` | View bookings | 
| `/user/bookings/{booking_id}`  | `GET` | View booking | 
| `/user/bookings/{booking_id}`  | `PUT` | Update booking | 
| `/user/bookings/{booking_id}/cancel`  | `PUT` | Cancel booking | 

### Registered Owner Specific Endpoints
#### Manage properties
| Endpoint  | Method   | Description                |
| :-------- | :------- | :------------------------- |
| `/owner/properties`  | `POST` | Create property | 
| `/owner/properties`  | `GET` | View properties | 
| `/owner/properties/{property_id}`  | `GET` | View property | 
| `/owner/properties/{property_id}`  | `PUT` | Update property | 
| `/owner/properties/{property_id}/deactivate`  | `PUT` | Deactivate property | 
| `/owner/properties/{property_id}/activate`  | `PUT` | Activate property | 
| `/owner/properties/{property_id}/photos`  | `POST` | Store property photos | 
| `/owner/properties/{property_id}/photos/{photo_id}/reorder`  | `PUT` | Update photo's order | 


#### Manage apartments
| Endpoint  | Method   | Description                |
| :-------- | :------- | :------------------------- |
| `/owner/properties/{property_id}/apartments`  | `GET` | View property's apartments | 
| `/owner/properties/{property_id}/apartments`  | `POST` | Create apartment | 
| `/owner/properties/{property_id}/apartments/{apartment_id}`  | `GET` | View apartment | 
| `/owner/properties/{property_id}/apartments/{apartment_id}/bookings`  | `GET` | View apartment bookings | 
| `/owner/properties/{property_id}/apartments/{apartment_id}`  | `PUT` | Update apartment | 
| `/owner/properties/{property_id}/apartments/{apartment_id}/deactivate`  | `PUT` | Diactivate apartment | 
| `/owner/properties/{property_id}/apartments/{apartment_id}/activate`  | `PUT` | Activate apartment | 

#### Manage apartment availability
| Endpoint  | Method   | Description                |
| :-------- | :------- | :------------------------- |
| `/owner/properties/{property_id}/apartments/{apartment_id}/prices`  | `GET` | View apartment availability prices
| `/owner/properties/{property_id}/apartments/{apartment_id}/prices`  | `POST` | Create apartment price
| `/owner/properties/{property_id}/apartments/{apartment_id}/prices/{price_id}`  | `GET` | View apartment price
| `/owner/properties/{property_id}/apartments/{apartment_id}/prices/{price_id}`  | `PUT` | Update apartment price
| `/owner/properties/{property_id}/apartments/{apartment_id}/prices/{price_id}`  | `DELETE` | Delete apartment price

### Public endpoints
| Endpoint  | Method   | Description | Optional Parameters |
| :-------- | :------- | :---------- | :---------- |
| `/search`  | `GET` | Search properties and apartments | `city_id, country_id, geoobject_id, adult_capacity, children_capacity, price_from, price_to, facilities`
| `/apartments/view/{apartment_id}`  | `GET` | View apartment |  |
| `/properties/view/{property_id}`  | `GET` | View property |  |





## Running Tests

To run tests, run the following command

```bash
  php artisan test
```


## Deployment

Deploy to any web server that supports PHP



## License

[MIT](https://choosealicense.com/licenses/mit/)


## Authors

- [@kareemalaadwy](https://www.github.com/kareemalaadwy)

  
## Contributing
Contributing is always welcomed



