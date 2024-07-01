# REST API - VUE Project

## Usage

* SQL File is located at /garage-management-backend/sql/garage-mngt-db.sql
* Installation for each project is explained in their respective README.md files

## Accounts:
- Email: guest@example.com
- Password: 123456

- Email: admin@example.com
- Password: 123456

## Functions
* Create: Register, Add Vehicle, Add Parking Spot
* Read: Show parking spots, vehicles, bills
* Update: Check-in / Check-out vehicle
* Delete: Delete parking spot

## App Flow
1. Guest registers their vehicle with their account
2. Employee checks-in/checks-out the vehicle
3. Depending on the time spent, 
a bill is generated for the guest. (The price is 30 cents per second)
4. Guest pays the bill