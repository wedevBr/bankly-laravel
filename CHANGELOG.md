# Changelog

All notable changes to `bankly-laravel` will be documented in this file

## 1.20.0 - 2022-02-11
- Added support for PHP 8.1
- Dropped support for PHP 7.x
- Added support for Laravel 9.x
- Dropped support for Laravel 7.x

## 1.19.1 - 2021-12-02
- Removed character length validation on program ID
## 1.19.0 - 2021-11-04
- Changed authentication method
- Added getters and setters in auth class
- Added event after authentication


## 1.18.1 - 2021-10-19
- Fixed bankly pix facade class name

## 1.18.0 - 2021-09-29
- Create static QRCodes
- Reading static and dynamic QRCodes

## 1.17.0 - 2021-09-23
- Create customer account

## 1.16.0 - 2021-08-26
- Allows sending PIX to payment type accounts

## 1.15.0 - 2021-06-30
- Card activation for the first time

## 1.14.0 - 2021-06-21
- Card status change
- Card password change
- Contactless configuration
- Card query by document
- Card query by activate code
- Card query by account
- Get the next card statuses
- Token generate for digital wallet
- Corrected documentation block
- Unused Imports Removed

## 1.13.1 - 2021-06-17
- Fixed the body format on request when obtaining the security card data
- Fixed the body format on request on the duplicate card

## 1.13.0 - 2021-06-16
- Get card data by proxy number

## 1.12.0 - 2021-06-16
- Card security data query
- Duplicate of card

## 1.11.0 - 2021-06-09
- Added class for cards management
- Card management class to use with the facade
- Get card transactions

## 1.10.0 - 2021-06-04
- Allows you to enter empty key for EVP type
- Fixed physical card url route
- Adjusted physical card names
- Fix of create customer method

## 1.9.0 - 2021-05-16
- Added obtaining customer data
- Added obtaining customer accounts

## 1.8.0 - 2021-05-14
- Added deposit billet management
- Added PIX cashout Manual
- Added PIX cashout StaticQrCode
- Fix features tests

## 1.7.0 - 2021-04-27
- Refactor Virtual Card
- Feature event dates
- Physical card creation

## 1.6.0 - 2021-04-21
- Added PIX for this package
- Added a method for headers on HTTP calls

## 1.5.0 - 2021-03-27
- Virtual cards creation
- Pay bills

## 1.4.0 - 2021-03-04
- Fixed support for PHP8
- Added Inputs for Customer (Customer, Address, Phone, Analysis)
- Added Inputs for Documents

## 1.3.0 - 2021-01-26
- Added support to Laravel 8.x
- Added support to PHP8

## 1.2.0 - 2020-10-22
- Fixed events params
- Fixed balance request
- Added notes on methods that have different responses from staging and production environment

## 1.1.1 - 2020-10-15
- Fixed /events params

## 1.1.0 - 2020-10-14
- Added new settings API
- Removed /baas from staging

## 1.0.0 - 2020-09-29

- initial release
