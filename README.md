# CakePHP Application Skeleton

[![Build Status](https://img.shields.io/travis/cakephp/app/master.svg?style=flat-square)](https://travis-ci.org/cakephp/app)
[![Total Downloads](https://img.shields.io/packagist/dt/cakephp/app.svg?style=flat-square)](https://packagist.org/packages/cakephp/app)

A skeleton for creating applications with [CakePHP](https://cakephp.org) 3.x.

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

## Configuration

Read and edit `config/app.php` and setup the `'Datasources'` and any other
configuration relevant for your application.


## Description
The unique hash of the given URL will be computed using MD5 algorithm, which will give us 
128 bit hash value. Then this hash is encoded using base64 encoding which will give us a string.
We choose the first 8 bit of the encoded hash as the key of the shortened URL. 

To overcome the problem of generating same key for the same URL, we append the original url with the increasing sequence number which is nothing but id of the last record.

We have the option of giving the expiration date for each url while hashing the URL. If the URL is accessed after it gets expired, the record will be deleted from the db and "URL not found" will be displayed to the user.
