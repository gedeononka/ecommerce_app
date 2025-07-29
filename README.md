<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
<h2>ecommerce_app</h2> 
Project Description

This project is a complete e-commerce web application designed for online product sales.

Key Features:
Customer Side:

Browse product catalog with category filtering

Add products to a shopping cart

Place orders with flexible payment options:

Online payment before delivery

Cash on delivery (payment upon receipt)

Admin Dashboard (Back Office):

Manage products, categories, orders, and user accounts

Monitor and update order and payment statuses

Access a secure interface for streamlined operations

Additional Functionalities:

Automated email notifications for order updates and status changes

Generation and download of invoices in PDF format
Here's a more professional and polished version of your instructions:

---

### 🚀 How to Launch the Application

Follow the steps below to set up and run the application locally:

1. Create a database named **`ecommerce`**
2. Run the database migrations:

  
   php artisan migrate  

3. Seed the roles table:

 
   php artisan db:seed --class=RoleSeeder  

4. Seed the remaining database data:

  
   php artisan db:seed  
 
5. Start the development server:

  
   php artisan serve  











