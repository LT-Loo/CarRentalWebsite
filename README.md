## Intro
A car rental website built using the Laravel framework, integrating AJAX techniques to enable dynamic data access without disrupting user experience. This website provides a streamlined online car renting experience for users. The website is implemented with a smart search feature which provides real-time suggestions, enhancing user convenience and reducing search time.

## Special Features
### Smart Search Feature
When the search box is focused, it displays a list of **recently searched keywords**. These keywords are stored in and retrieved from the **session storage**.

As the user types, the list dynamically updates to provide **real-time query suggestions**. The user input is compared against data from a JSON file containing car informations, such as car names, models and brands. Any entries that partially or fully match the input are displayed as suggestions.

This feature leverages AJAX to enable seamless, asynchronous updates to the suggestion list without interrupting user navigation or requiring a full page reload.

### Input Validation with Live Feedback
This feature is integrated into the reservation form, where form inputs are validated using the **jQuery Validation plugin**. Custom validation rules and error messages are defined to ensure data accuracy. If an input fails to meet the specified rule, a corresponding error message is displayed in real time.

## Tech Stack
- Language: PHP, JavaScript
- Framework: Laravel, JQuery, Bootstrap
- Database: MySQL

## Installation
1. Run `npm install` to install npm packages and dependencies.
2. Run `npm artisan migrate` to create tables in database.
3. Run `php artisan serve` to start the local development server and launch the website in your browser.

## Developer
Loo<br>
loo.workspace@gmail.com
