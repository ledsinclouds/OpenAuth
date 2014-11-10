OpenAuth Module
===============

Introduction
------------
Social Connect Module

Installation
------------

Using Git 
---------
You can install using native git submodules:

    git clone git@github.com:ledsinclouds/OpenAuth.git
    
Using Composer 
--------------

Add to composer And Install:

    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/ledsinclouds/OpenAuth.git"
        }
    ]

	"ledsinclouds/open-auth": "dev-master"  
	composer update
   
Requires
--------

    "opauth/opauth": "0.4.4",
    "opauth/google": "dev-master",
    "opauth/twitter": "dev-master",
    "opauth/facebook": "dev-master",
    "opauth/github": "dev-master"
		
