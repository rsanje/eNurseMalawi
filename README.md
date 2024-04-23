# eNurseMalawi

The poject is Developed as part of Final Computer Science based on real requirements


## Table of Contents

- [eNurseMalawi](#enursemalawi)
  - [Table of Contents](#table-of-contents)
  - [Introduction](#introduction)
  - [Features](#features)
  - [Getting Started](#getting-started)
    - [Prerequisites](#prerequisites)
    - [Installation](#installation)

## Introduction
The Project focuses on developing a Nurse registration and licensing system based on the setup of the Nursing and Midwives council of Malawi. A literature review was one to study similar systems available in other countries. The requirements of the project have been gathered based, analysed and then prioritised using MoSCoW.

## Features

List of key features or functionalities of the project.

- Registration
- Licensing
- Search for Nurses (To verify their registration status)
- Managing Nurse details, qualification information, documents and employment history
- Manage staff roles in the system

## Getting Started

Instructions on how to get the project up and running on a local machine.

### Prerequisites

List of software or tools needed to run the project.

- Lamp for linux user / Wamp for Windows Users and Mamp for Mac Users
- Code editor (e.g Visual Studio Code[vscode])
- Internet Browser
- Git bash

### Installation

Step-by-step guide on how to install and configure the project.

```bash
$ cd <wamp www directoy in your local machine>
$ git clone git@github.com:rsanje/eNurseMalawi.git
$ cd project_directory
$ Start wamp and make sure all servers are running
$ On the internt browser go to localhost/eNurseMalawi/setup.php 
```
- The setup will create the database `nmcm` and all tables. 
- if you get an error creating connecting to the database, find `db.php` and change the following:

```bash
$host = 'localhost';
$username = 'root'; 
$password = ''; 
$database = 'nmcm';
```