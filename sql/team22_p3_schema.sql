-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';
DROP DATABASE IF EXISTS `cs6400_sm19_team22`;
SET SQL_MODE='ALLOW_INVALID_DATES';
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_sm19_team22
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_sm19_team22;

-- DROP TABLE IF EXISTS Users, Manager, InventoryClerk, Salesperson, Customer, Person, Business, VehicleType, Manufacturer, Vehicle, VehicleColor, Recall, Vendor, Repair, Buy, Sell;

-- tables for users
CREATE TABLE Users (
  username varchar(50) NOT NULL,
  password varchar(50) NOT NULL,
  login_first_name varchar(50) NOT NULL,
  login_last_name varchar(50) NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE Manager (
  username varchar(50) NOT NULL,
  manager_permission varchar(50) NOT NULL,
  PRIMARY KEY (username),
  UNIQUE (manager_permission),
  FOREIGN KEY (username)
    REFERENCES Users (username)
);

CREATE TABLE InventoryClerk (
  username varchar(50) NOT NULL,
  inventory_clerk_permission varchar(50) NOT NULL,
  PRIMARY KEY (username),
  UNIQUE (inventory_clerk_permission),
  FOREIGN KEY (username)
    REFERENCES Users (username)
);

CREATE TABLE Salesperson (
  username varchar(50) NOT NULL,
  salesperson_permission varchar(50) NOT NULL,
  PRIMARY KEY (username),
  UNIQUE (salesperson_permission),
  FOREIGN KEY (username)
    REFERENCES Users (username)
);

-- tables for customer

CREATE TABLE Customer (
  customer_id varchar(50) NOT NULL,
  phone_number varchar(50) NOT NULL,
  email varchar(50) NULL,
  customer_street varchar(50) NOT NULL,
  customer_city varchar(50) NOT NULL,
  customer_state varchar(50) NOT NULL,
  customer_zip int NOT NULL,
  PRIMARY KEY (customer_id)
);

CREATE TABLE Person (
  customer_id varchar(50) NOT NULL,
  driver_license_number varchar(50) NOT NULL,
  customer_first_name varchar(50) NOT NULL,
  customer_last_name varchar(50) NOT NULL,
  PRIMARY KEY (driver_license_number),
  FOREIGN KEY (customer_id)
    REFERENCES Customer (customer_id)
);

CREATE TABLE Business (
  customer_id varchar(50) NOT NULL,
  tax_identification_number varchar(50) NOT NULL,
  business_name varchar(50) NOT NULL,
  primary_contact_name varchar(50) NOT NULL,
  primary_contact_title varchar(50) NOT NULL,
  PRIMARY KEY (tax_identification_number),
  FOREIGN KEY (customer_id)
    REFERENCES Customer (customer_id)
);

-- vehicle and repair table
CREATE TABLE VehicleType (
  type_name varchar(50) NOT NULL,
  PRIMARY KEY (type_name)
);

CREATE TABLE Manufacturer (
  manufacturer_name varchar(50) NOT NULL,
  PRIMARY KEY (manufacturer_name)
);

CREATE TABLE Vehicle (
  vin varchar(50) NOT NULL,
  vehicle_mileage int NOT NULL,
  vehicle_description varchar(250) NULL,
  model_name varchar(50) NOT NULL,
  model_year int NOT NULL,
  type_name varchar(50) NOT NULL,
  manufacturer_name varchar(50) NOT NULL,
  sale_price decimal NOT NULL,
  PRIMARY KEY (vin),
  FOREIGN KEY (type_name)
    REFERENCES VehicleType (type_name),
  FOREIGN KEY (manufacturer_name)
    REFERENCES Manufacturer (manufacturer_name)
);

CREATE TABLE VehicleColor (
  vin varchar(50) NOT NULL,
  vehicle_color varchar(50) NOT NULL,
  PRIMARY KEY (vin, vehicle_color),
  FOREIGN KEY (vin)
    REFERENCES Vehicle (vin)
);

CREATE TABLE Recall (
  recall_manufacturer varchar(50) NOT NULL,
  recall_description varchar(250) NULL,
  NHTSA_recall_compaign_number varchar(50) NOT NULL,
  PRIMARY KEY (nhtsa_recall_compaign_number),
  FOREIGN KEY (recall_manufacturer)
    REFERENCES Manufacturer (manufacturer_name)
);

CREATE TABLE Vendor (
  vendor_name varchar(50) NOT NULL,
  vendor_phone_number varchar(50) NOT NULL,
  vendor_street varchar(50) NOT NULL,
  vendor_city varchar(50) NOT NULL,
  vendor_state varchar(50) NOT NULL,
  vendor_zip int NOT NULL,
  PRIMARY KEY (vendor_name)
);

CREATE TABLE Repair (
  vin varchar(50) NOT NULL,
  start_date timestamp NOT NULL,
  end_date timestamp NOT NULL,
  repair_status varchar(50) NOT NULL,
  repair_description varchar(250) NULL,
  vendor_name varchar(50) NOT NULL,
  repair_cost decimal NOT NULL,
  nhtsa_recall_compaign_number varchar(50) NULL,
  inventory_clerk_permission varchar(50) NOT NULL,
  UNIQUE (vin, start_date),
  FOREIGN KEY (vin)
    REFERENCES Vehicle (vin),
  FOREIGN KEY (vendor_name)
    REFERENCES Vendor (vendor_name),
  FOREIGN KEY (nhtsa_recall_compaign_number)
    REFERENCES Recall (nhtsa_recall_compaign_number)
);

-- buy and sell transactions
CREATE TABLE Buy (
  vin varchar(50) NOT NULL,
  customer_id varchar(50) NOT NULL,
  inventory_clerk_permission varchar(50) NOT NULL,
  purchase_date timestamp NOT NULL,
  purchase_price decimal NOT NULL,
  purchase_condition varchar(50) NOT NULL,
  KBB_value decimal NOT NULL,
  UNIQUE (vin, inventory_clerk_permission, customer_id),
  PRIMARY KEY (vin),
  FOREIGN KEY (vin)
    REFERENCES Vehicle (vin),
  FOREIGN KEY (inventory_clerk_permission)
    REFERENCES InventoryClerk (inventory_clerk_permission),
  FOREIGN KEY (customer_id)
    REFERENCES Customer (customer_id)
);

CREATE TABLE Sell (
  vin varchar(50) NOT NULL,
  customer_id varchar(50) NOT NULL,
  salesperson_permission varchar(50) NOT NULL,
  sale_date timestamp NOT NULL,
  UNIQUE (vin, salesperson_permission, customer_id),
  PRIMARY KEY (vin),
  FOREIGN KEY (vin)
    REFERENCES Vehicle (vin),
  FOREIGN KEY (salesperson_permission)
    REFERENCES Salesperson (salesperson_permission),
  FOREIGN KEY (customer_id)
    REFERENCES Customer (customer_id)
);