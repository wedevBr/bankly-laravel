# Changelog

All notable changes to `bankly-laravel` will be documented in this file
## 1.30.0 - 2023-09-28
- Refactoring DocumentAnalysis class to return metadata only if document is SELFIE
- Typed Request object on tests when asserting HTTP facade
## 1.29.1 - 2023-09-12
- Allowing null properties to billets
## 1.29.0 - 2023-09-12
- Added missing arrayable contract in BankAccount class
- Added new feature: Payment Simulation

## 1.28.0 - 2023-09-06
- Added missing Arrayable contract to some classes and interfaces
## 1.27.4 - 2023-08-14
- Modified TOTP endpoints to send body as JSON

## 1.27.3 - 2023-08-11
- Added missing setPassphrase method to BanklyTOTP

## 1.27.2 - 2023-08-11
- Added new argument to registerPixKey method, accepting hash to send when key_type is EMAIL or PHONE

## 1.27.1 - 2023-08-07
- Fixed wrongly types on the following files: `src/Types/Pix/Payer.php`, `src/Types/Pix/PixDynamicQrCode.php`
- Fixed validator with new rules from Bankly
- `src/Validators/Pix/PixDynamicQrCodeValidator.php`

## 1.27.0 - 2023-07-27
- Added missing facades class
- Fixed some properties that can be nullable at Bankly/Auth/Auth class
- Added missing changelog

## 1.26.2 - 2023-07-27
- Fixed minor bugs at scope properties

## 1.26.1 - 2023-07-27
- Fixed scopes properties at Bankly/Auth/Auth and Bankly class
- Fixed minor bugs on type hinted methods

## 1.26.0 - 2023-07-26
- Added support for PHP 8.1 and PHP 8.2 versions
- Added support for Laravel 9/10
- Dropped support for PHP 8.0
- Dropped support for Laravel 8.x
- Added Bankly OTP endpoints
- Tests has been upgraded
- Added some types to properties and methods return

## 1.25.0 - 2023-06-06
- Fixed internal Put Http Method on Bankly class

## 1.24.4 - 2023-05-24
- Fixed method setEncrypt to allow null

## 1.24.3 - 2023-04-04
- Fixed BanklyCard class

## 1.24.2 - 2023-03-24
- Fixed class Bankly to use mtlsSettings from setters

## 1.24.1 - 2023-03-23
- Removed options to set certificates path at login method at Auth class

## 1.24.0 - 2023-03-23
- Added methods for set certificates path for .crt and .pem files

## 1.22.2 - 2022-11-04
 - Added Dynamic QrCode Pix

## 1.22.1 - 2022-08-30
 - Added business customers management

## 1.22.0 - 2022-08-02
- Added webhook messages management
- Added get limits by feature

## 1.21.7 - 2022-07-01
- Add get token method

## 1.21.6 - 2022-06-21
- Fix card address number validation

## 1.21.5 - 2022-06-06
- Added offboarding support

## 1.21.4 - 2022-05-26
- Added billet cancellation

## 1.21.3 - 2022-05-21
- Added account closure
- Refactored billets

## 1.21.2 - 2022-05-13
- Added Pix refound

## 1.21.1 - 2022-05-03
- Fix Pix Qr Code Validation

## 1.21.0 - 2022-04-25
- Added full mTLS support

## 1.20.5 - 2022-04-11
- Improved banks methods

## 1.20.4 - 2022-04-05
- Added physical card tracking method
- Changed card methods

## 1.20.3 - 2022-03-22
- Added multiple token support

## 1.20.2 - 2022-03-22
- Added mTLS PIX support

## 1.20.1 - 2022-02-23
- Added income report
- Changed bank slip printing
- Added PIX key transfer
- Changed PIX static QrCode transfer

## 1.20.0 - 2022-02-11
- Added support for PHP 8.1
- Dropped support for PHP 7.x
- Added support for Laravel 9.x
- Dropped support for Laravel 7.x
- Scope property added in token request

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
