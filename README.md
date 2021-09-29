# SnowTricks
[![Maintainability](https://api.codeclimate.com/v1/badges/c89161fd80993fbda54a/maintainability)](https://codeclimate.com/github/Flo654/P6_snowtricks/maintainability)

The project has been done with versions of :
*   MYSQL 8.0.23
*   PHP 7.4.9
*   APACHE 2.4.46

you need to have composer installed (if not: https://getcomposer.org/download/)

## :gear: PROJECT INIT

### Step one: installing the repository

*   Clone this repo on your:computer:

### Step two: configuration

#### configuring .env file

from the root :file_folder: `snowtricks` , modify `.env.test` <br/>


#### configuring mailer

for the project I Used MailHog.
MailHog is an email testing tool that makes it very easy to install and configure a local email server. MailHog sets up a fake SMTP server. You can configure your favorite web applications to use MailHog's SMTP server to send and receive emails

How to use MailHog to test emails locally:
https://github.com/mailhog/MailHog

###> symfony/mailer ###
`MAILER_DSN=smtp://localhost:1025`
###< symfony/mailer ###




#### configuring database

###> doctrine/doctrine-bundle ###
`DATABASE_URL="mysql://user:password@127.0.0.1:3306/database?serverVersion=5.7"`
###< doctrine/doctrine-bundle ###

Replace `user`, `password`, and `database` by your credentials.

:heavy_exclamation_mark: :heavy_exclamation_mark:  After configuration don't forget to remove .test extention from the file `.env.test`
 At the end, the name of the file have to be `.env`.

 ## :gear: PROJECT LAUNCH
 
*   Step1: from the root :file_folder: `snowtricks`. use  `composer install` , to load all dependancies.
*   Step2: To run the projet, from the root :file_folder: `snowtricks` , open a terminal and enter this command : `composer start`.
*   Follow the link and ....enjoy !! 
