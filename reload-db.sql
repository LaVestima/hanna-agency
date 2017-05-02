-- CREATE DATABASE IF NOT EXISTS lave_bachelor;

DROP TABLE IF EXISTS Orders_Products;
DROP TABLE IF EXISTS Orders_Statuses;
DROP TABLE IF EXISTS Invoices_Products;
DROP TABLE IF EXISTS Images;
DROP TABLE IF EXISTS Products_Sizes;
DROP TABLE IF EXISTS Products;
DROP TABLE IF EXISTS Invoices;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS Producers;
DROP TABLE IF EXISTS Customers;
DROP TABLE IF EXISTS Tokens;
DROP TABLE IF EXISTS Login_Attempts;
DROP TABLE IF EXISTS Configurations;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Roles;
DROP TABLE IF EXISTS Sizes;
DROP TABLE IF EXISTS Categories;
DROP TABLE IF EXISTS Currencies_Rates;
DROP TABLE IF EXISTS Currencies;
DROP TABLE IF EXISTS Addresses;
DROP TABLE IF EXISTS Cities;
DROP TABLE IF EXISTS Countries;
-- 22

CREATE TABLE IF NOT EXISTS Countries (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	Name VARCHAR(100) NOT NULL,
	Symbol VARCHAR(10) NOT NULL, 
	Note VARCHAR(200),
	CONSTRAINT Countries_PK PRIMARY KEY (ID),
	CONSTRAINT Countries_Name_U UNIQUE (Name)
);

CREATE TABLE IF NOT EXISTS Cities (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	Name VARCHAR(100) NOT NULL,
	ID_COUNTRIES INTEGER NOT NULL,
	Note VARCHAR(200),
	CONSTRAINT Cities_PK PRIMARY KEY (ID),
	CONSTRAINT Cities_ID_COUNTRIES_FK FOREIGN KEY (ID_COUNTRIES) REFERENCES Countries(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Cities_Name_Countries_U UNIQUE(Name, ID_COUNTRIES)
);

CREATE TABLE IF NOT EXISTS Addresses (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	ID_COUNTRIES INTEGER NOT NULL,
	ID_CITIES INTEGER NOT NULL,
	Postal_Code VARCHAR(20) NOT NULL,
	Street VARCHAR(200) NOT NULL,
	CONSTRAINT Addresses_PK PRIMARY KEY (ID),
	CONSTRAINT Addresses_ID_COUNTRIES_FK FOREIGN KEY (ID_COUNTRIES) REFERENCES Countries(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Addresses_ID_CITIES_FK FOREIGN KEY (ID_CITIES) REFERENCES Cities(ID)
	ON UPDATE CASCADE ON DELETE CASCADE
);

/*
CREATE VIEW IF NOT EXISTS V_Cities_Countries AS
SELECT ci.name AS City, co.name AS Country, co.symbol
FROM Cities ci
INNER JOIN Countries co ON ci.id_countries=co.id;
*/

CREATE TABLE IF NOT EXISTS Currencies (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	Name VARCHAR(50) NOT NULL,
	Symbol VARCHAR(5) NOT NULL,
	CONSTRAINT Currencies_PK PRIMARY KEY (ID),
	CONSTRAINT Currencies_Name_U UNIQUE(Name),
	CONSTRAINT Currencies_Symbol_U UNIQUE(Symbol)
);


CREATE TABLE IF NOT EXISTS Currencies_Rates (
	ID INTEGER NOT NULL AUTO_INCREMENT,	
	Conversion_Rate DECIMAL(10, 6) NOT NULL,
	Date_Rate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	ID_CURRENCIES INTEGER NOT NULL,
	CONSTRAINT Currencies_H_PK PRIMARY KEY (ID),
	CONSTRAINT Currencies_H_ID_Currencies_FK FOREIGN KEY (ID_CURRENCIES) REFERENCES Currencies(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Currencies_H_Date_Currency_U UNIQUE(Date_Rate, ID_CURRENCIES)
);

/*
CREATE VIEW IF NOT EXISTS V_Currencies_History AS
SELECT c.name, c.symbol, cr.conversion_rate, cr.date_rate
FROM Currencies c
INNER JOIN Currencies_Rates cr ON cr.id_currencies=c.id;
*/

CREATE TABLE IF NOT EXISTS Categories (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	Name VARCHAR(50) NOT NULL,
	Note VARCHAR(200),
	CONSTRAINT Categories_PK PRIMARY KEY (ID),
	CONSTRAINT Categories_Name_U UNIQUE(Name)
);

CREATE TABLE IF NOT EXISTS Sizes (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	Name VARCHAR(50) NOT NULL,
	Note VARCHAR(200),
	CONSTRAINT Sizes_PK PRIMARY KEY (ID),
	CONSTRAINT Sizes_Name_U UNIQUE(Name)
);

CREATE TABLE IF NOT EXISTS Roles (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	Name VARCHAR(50) NOT NULL,
	Code VARCHAR(50) NOT NULL,
	Is_Admin TINYINT(1) NOT NULL DEFAULT 0,
	CONSTRAINT Roles_PK PRIMARY KEY (ID),
	CONSTRAINT Roles_Name_U UNIQUE(Name),
	CONSTRAINT Roles_Code_U UNIQUE(Code)
);

CREATE TABLE IF NOT EXISTS Users (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	Date_Created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	Date_Deleted TIMESTAMP NULL,
	Login VARCHAR(50) NOT NULL,
	Email VARCHAR(100) NOT NULL,
	Password_Hash VARCHAR(200) NOT NULL,
	ID_ROLES INTEGER NOT NULL,
	Path_Slug VARCHAR(50) NOT NULL DEFAULT '',
	CONSTRAINT Users_PK PRIMARY KEY (ID),
	CONSTRAINT Users_Login_U UNIQUE(Login),
	CONSTRAINT Users_Email_U UNIQUE(Email),
	CONSTRAINT Users_Password_Hash_U UNIQUE(Password_Hash),
	CONSTRAINT Users_Path_Slug_U UNIQUE(Path_Slug),
	CONSTRAINT Users_ID_ROLES_FK FOREIGN KEY (ID_ROLES) REFERENCES Roles(ID)
	ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Configurations (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	ID_USERS INTEGER NOT NULL,
	Locale VARCHAR(5) NOT NULL,
	Dark_Mode TINYINT(1) DEFAULT 0,
	CONSTRAINT Configurations_PK PRIMARY KEY (ID),
	CONSTRAINT Configurations_ID_USERS_U UNIQUE(ID_USERS),
	CONSTRAINT Configurations_ID_USERS_FK FOREIGN KEY (ID_USERS) REFERENCES Users(ID)
	ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Login_Attempts (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	ID_USERS INTEGER NOT NULL,
	Time_Logged TIMESTAMP NOT NULL,
	Ip_Adddress VARCHAR(50) NOT NULL,
	Is_Failed TINYINT(1) NOT NULL,
	CONSTRAINT Login_Attempts_PK PRIMARY KEY (ID),
	CONSTRAINT Login_Attempts_ID_USERS_FK FOREIGN KEY (ID_USERS) REFERENCES Users(ID)
	ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Tokens (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	ID_USERS INTEGER NOT NULL,
	Date_Created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	Date_Expired TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	Token VARCHAR(100) NOT NULL,
	CONSTRAINT Tokens_PK PRIMARY KEY (ID),
	CONSTRAINT Tokens_Token_U UNIQUE(Token),
	CONSTRAINT Tokens_ID_USERS_FK FOREIGN KEY (ID_USERS) REFERENCES Users(ID)
	ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Customers (
	ID INTEGER  NOT NULL AUTO_INCREMENT,
	Date_Created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	Date_Deleted TIMESTAMP NULL,
	User_Created INTEGER NOT NULL DEFAULT 0,
	User_Deleted INTEGER,
	Identification_Number VARCHAR(10) NOT NULL,
	First_Name VARCHAR(50) NOT NULL,
	Last_Name VARCHAR(50) NOT NULL,
	Gender VARCHAR(1) NOT NULL,
	Company_Name VARCHAR(200),
	VAT VARCHAR(50),
	ID_ADDRESSES INTEGER NOT NULL,
	Email VARCHAR(200) NOT NULL,
	Newsletter TINYINT(1) NOT NULL DEFAULT 1,
	Phone VARCHAR(50) NOT NULL,
	ID_CURRENCIES INTEGER NOT NULL,
	Default_Discount INTEGER DEFAULT 0,
	ID_USERS INTEGER,
	Path_Slug VARCHAR(50) NOT NULL DEFAULT '',
	CONSTRAINT Customers_PK PRIMARY KEY (ID),
	CONSTRAINT Customers_Identification_Number_U UNIQUE(Identification_Number),
	CONSTRAINT Customers_Gender_CH CHECK (Gender='M' OR Gender='F' OR Gender='O'),
	CONSTRAINT Customers_Path_Slug_U UNIQUE(Path_Slug),
	CONSTRAINT Customers_ID_ADDRESSES_FK FOREIGN KEY (ID_ADDRESSES) REFERENCES Addresses(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Customers_ID_CURRENCIES_FK FOREIGN KEY (ID_CURRENCIES) REFERENCES Currencies(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Customers_ID_USERS_FK FOREIGN KEY (ID_USERS) REFERENCES Users(ID)
	ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Producers (
	ID INTEGER  NOT NULL AUTO_INCREMENT,
	Date_Created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	Date_Deleted TIMESTAMP NULL,
	User_Created INTEGER NOT NULL DEFAULT 0,
	User_Deleted INTEGER,
	Short_Name VARCHAR(50) NOT NULL,
	Full_Name VARCHAR(200) NOT NULL,
	First_Name VARCHAR(50),
	Last_Name VARCHAR(50),
	VAT VARCHAR(50),
	ID_ADDRESSES INTEGER NOT NULL,
	Email VARCHAR(200) NOT NULL,
	Phone VARCHAR(50) NOT NULL,
	ID_USERS INTEGER,
	Path_Slug VARCHAR(50) NOT NULL DEFAULT '',
	CONSTRAINT Producers_PK PRIMARY KEY (ID),
	CONSTRAINT Producers_Path_Slug_U UNIQUE(Path_Slug),
	CONSTRAINT Producers_ID_ADDRESSES_FK FOREIGN KEY (ID_ADDRESSES) REFERENCES Addresses(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Producers_ID_USERS_FK FOREIGN KEY (ID_USERS) REFERENCES Users(ID)
	ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Invoices (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	Date_Created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  Date_Deleted TIMESTAMP NULL,
	User_Created INTEGER NOT NULL DEFAULT 0,
  User_Deleted INTEGER,
	Name VARCHAR(50) NOT NULL,
	ID_CUSTOMERS INTEGER NOT NULL,
	Date_Issued TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	Path_Slug VARCHAR(50) NOT NULL DEFAULT '',
	CONSTRAINT Invoices_PK PRIMARY KEY (ID),
  CONSTRAINT Invoices_User_Created_FK FOREIGN KEY (User_Created) REFERENCES Users(ID)
  ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT Invoices_User_Deleted_FK FOREIGN KEY (User_Deleted) REFERENCES Users(ID)
  ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Invoices_Name_U UNIQUE(Name),
	CONSTRAINT Invoices_Path_Slug_U UNIQUE(Path_Slug),
	CONSTRAINT Invoices_ID_CUSTOMERS_FK FOREIGN KEY (ID_CUSTOMERS) REFERENCES Customers(ID)
	ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Orders (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	Date_Created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	Date_Deleted TIMESTAMP NULL,
	User_Created INTEGER NOT NULL DEFAULT 0,
	User_Deleted INTEGER,
	ID_CUSTOMERS INTEGER NOT NULL,
	Date_Placed TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	Path_Slug VARCHAR(50) NOT NULL DEFAULT '',
	CONSTRAINT Orders_PK PRIMARY KEY (ID),
  CONSTRAINT Orders_User_Created_FK FOREIGN KEY (User_Created) REFERENCES Users(ID)
  ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT Orders_User_Deleted_FK FOREIGN KEY (User_Deleted) REFERENCES Users(ID)
  ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Orders_Path_Slug_U UNIQUE(Path_Slug),
	CONSTRAINT Orders_ID_CUSTOMERS_FK FOREIGN KEY (ID_CUSTOMERS) REFERENCES Customers(ID)
	ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Products (
	ID INTEGER  NOT NULL AUTO_INCREMENT,
	Date_Created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	Date_Deleted TIMESTAMP NULL,
	User_Created INTEGER NOT NULL DEFAULT 0,
	User_Deleted INTEGER,
	Name VARCHAR(50) NOT NULL,
	Price_Producer DECIMAL(10, 2) NOT NULL,
	Price_Customer DECIMAL(10, 2) NOT NULL,
	QR_Code_Path VARCHAR(50) NOT NULL,
	ID_CATEGORIES INTEGER NOT NULL,
	ID_PRODUCERS INTEGER NOT NULL,
	ID_SIZES INTEGER NOT NULL,
	Path_Slug VARCHAR(50) NOT NULL DEFAULT '',
	CONSTRAINT Items_PK PRIMARY KEY (ID),
	CONSTRAINT Products_Name_U UNIQUE(Name),
	CONSTRAINT Products_Path_Slug_U UNIQUE(Path_Slug),
	CONSTRAINT Products_QR_Code_Path_U UNIQUE(QR_Code_Path),
	CONSTRAINT Items_ID_CATEGORIES_FK FOREIGN KEY (ID_CATEGORIES) REFERENCES Categories(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Items_ID_PRODUCERS_FK FOREIGN KEY (ID_PRODUCERS) REFERENCES Producers(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Items_ID_SIZES_FK FOREIGN KEY (ID_SIZES) REFERENCES Sizes(ID)
	ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Products_Sizes (
	ID INTEGER NOT NULL AUTO_INCREMENT,
	ID_PRODUCTS INTEGER NOT NULL,
	ID_SIZES INTEGER NOT NULL,
	Availability INTEGER NOT NULL,
	CONSTRAINT Products_Sizes_PK PRIMARY KEY (ID),
	CONSTRAINT Products_Sizes_ID_PRODUCTS_FK FOREIGN KEY (ID_PRODUCTS) REFERENCES Products(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Products_Sizes_ID_SIZES_FK FOREIGN KEY (ID_SIZES) REFERENCES Sizes(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Products_Sizes_Availability_CH CHECK (Availability>=0)
);

CREATE TABLE IF NOT EXISTS Images (
	ID INTEGER  NOT NULL AUTO_INCREMENT,
	File_Path VARCHAR(50) NOT NULL,
	ID_PRODUCTS INTEGER NOT NULL,
	Sequence_Position INTEGER NOT NULL,
	CONSTRAINT Images_PK PRIMARY KEY (ID),
	CONSTRAINT Images_File_Path_U UNIQUE(File_Path),
	CONSTRAINT Images_Products_Position_U UNIQUE(ID_PRODUCTS, Sequence_Position),
	CONSTRAINT Images_ID_PRODUCTS_FK FOREIGN KEY (ID_PRODUCTS) REFERENCES Products(ID)
	ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Invoices_Products (
	ID INTEGER NOT NULL,
	ID_INVOICES INTEGER NOT NULL,
	ID_PRODUCTS INTEGER NOT NULL,
	Quantity INTEGER NOT NULL,
	Discount INTEGER NOT NULL,
	Price_Final DECIMAL(10, 2) NOT NULL,
	Note VARCHAR(200),
	CONSTRAINT Invoices_Products_PK PRIMARY KEY (ID),
	CONSTRAINT Invoices_Products_ID_INVOICES_FK FOREIGN KEY (ID_INVOICES) REFERENCES Invoices(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Invoices_Products_ID_PRODUCTS_FK FOREIGN KEY (ID_PRODUCTS) REFERENCES Products(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Invoices_Products_Discount_CH CHECK (Discount>=0 AND Discount<=100)
);

CREATE TABLE IF NOT EXISTS Orders_Statuses (
	ID INTEGER  NOT NULL AUTO_INCREMENT,
	Name VARCHAR(50) NOT NULL,
	CONSTRAINT Orders_Statuses_PK PRIMARY KEY (ID),
	CONSTRAINT Orders_Statuses_Name_U UNIQUE(Name)
);

CREATE TABLE IF NOT EXISTS Orders_Products (
	ID INTEGER  NOT NULL AUTO_INCREMENT,
	ID_ORDERS INTEGER NOT NULL,
	ID_PRODUCTS INTEGER NOT NULL,
	ID_STATUSES INTEGER NOT NULL DEFAULT 1,
	Quantity INTEGER NOT NULL,
	Discount INTEGER NOT NULL DEFAULT 0,
	Note VARCHAR(200),
	CONSTRAINT Orders_Products_PK PRIMARY KEY (ID),
	CONSTRAINT Orders_Products_ID_ORDERS_FK FOREIGN KEY (ID_ORDERS) REFERENCES Orders(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Orders_Products_ID_PRODUCTS_FK FOREIGN KEY (ID_PRODUCTS) REFERENCES Products(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Orders_Products_ID_STATUSES_FK FOREIGN KEY (ID_STATUSES) REFERENCES Orders_Statuses(ID)
	ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT Orders_Products_Discount_CH CHECK (Discount>=0 AND Discount<=100)
);

#####################################################################################################################################################################################

INSERT INTO Countries (ID, Name, Symbol) VALUES (1, 'Poland', 'PL');
INSERT INTO Countries (ID, Name, Symbol) VALUES (2, 'Germany', 'DE');
INSERT INTO Countries (ID, Name, Symbol) VALUES (3, 'France', 'FR');
INSERT INTO Countries (ID, Name, Symbol) VALUES (4, 'Canada', 'CA');
INSERT INTO Countries (ID, Name, Symbol) VALUES (5, 'United States of America', 'US');
INSERT INTO Countries (ID, Name, Symbol) VALUES (6, 'Netherlands', 'NL');
INSERT INTO Countries (ID, Name, Symbol) VALUES (7, 'Belgium', 'BE');
INSERT INTO Countries (ID, Name, Symbol) VALUES (8, 'Sri Lanka', 'LK');
INSERT INTO Countries (ID, Name, Symbol) VALUES (9, 'United Kingdom', 'GB');
INSERT INTO Countries (ID, Name, Symbol) VALUES (10, 'Japan', 'JP');

INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (1, 'Warsaw', 1);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (2, 'Lodz', 1);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (3, 'Berlin', 2);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (4, 'New York', 5);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (5, 'Ottawa', 4);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (6, 'Springdale', 5);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (7, 'Burgoberbach', 2);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (8, 'Sri Jayawardenepura Kotte', 8);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (9, 'Llanfair­pwllgwyngyll­gogery­chwyrn­drobwll­llan­tysilio­gogo­goch', 9);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (10, 'Essendon', 9);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (11, 'Almelo', 6);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (12, 'San Diego', 5);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (13, 'Garvan', 9);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (14, 'Annemasse', 3);
INSERT INTO Cities (ID, Name, ID_COUNTRIES) VALUES (15, 'Toronto', 4);

INSERT INTO Addresses (ID, ID_COUNTRIES, ID_CITIES, Postal_Code, Street) VALUES (1, 5, 6, 'AR 72764', '3240 Green Hill Road');
INSERT INTO Addresses (ID, ID_COUNTRIES, ID_CITIES, Postal_Code, Street) VALUES (2, 2, 7, '91595', 'Heiligengeistbrücke 65');
INSERT INTO Addresses (ID, ID_COUNTRIES, ID_CITIES, Postal_Code, Street) VALUES (3, 9, 10, 'AL9 0LY', '66 Red Lane');
INSERT INTO Addresses (ID, ID_COUNTRIES, ID_CITIES, Postal_Code, Street) VALUES (4, 6, 11, '7605 XX', 'Spreeuwenstraat 73');
INSERT INTO Addresses (ID, ID_COUNTRIES, ID_CITIES, Postal_Code, Street) VALUES (5, 5, 12, 'CA 92121', '331 Hamill Avenue');
INSERT INTO Addresses (ID, ID_COUNTRIES, ID_CITIES, Postal_Code, Street) VALUES (6, 9, 13, 'PH33 3AF', '50 Old Chapel Road');
INSERT INTO Addresses (ID, ID_COUNTRIES, ID_CITIES, Postal_Code, Street) VALUES (7, 3, 14, '74100', '65, Avenue De Marlioz');
INSERT INTO Addresses (ID, ID_COUNTRIES, ID_CITIES, Postal_Code, Street) VALUES (8, 4, 15, 'ON M5H 1P6', '4370 Adelaide St');

INSERT INTO Currencies (ID, Name, Symbol) VALUES (1, 'Euro', 'EUR');
INSERT INTO Currencies (ID, Name, Symbol) VALUES (2, 'US Dollar', 'USD');
INSERT INTO Currencies (ID, Name, Symbol) VALUES (3, 'British Pound', 'GBP');
INSERT INTO Currencies (ID, Name, Symbol) VALUES (4, 'Canadian Dollar', 'CAD');
INSERT INTO Currencies (ID, Name, Symbol) VALUES (5, 'Swiss Franc', 'CHF');
INSERT INTO Currencies (ID, Name, Symbol) VALUES (6, 'Polish Zloty', 'PLN');
INSERT INTO Currencies (ID, Name, Symbol) VALUES (7, 'Japanese Yen', 'JPY');
INSERT INTO Currencies (ID, Name, Symbol) VALUES (8, 'Chinese Yuan Renminbi', 'CNY');

INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (1, 1.05531, '2016-11-24', 2);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (2, 0.847750, '2016-11-24', 3);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (3, 1.42434, '2016-11-24', 4);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (4, 1.07346, '2016-11-24', 5);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (5, 4.42238, '2016-11-24', 6);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (6, 1.05897, '2016-11-25', 2);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (7, 0.849787, '2016-11-25', 3);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (8, 1.42962, '2016-11-25', 4);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (9, 1.07335, '2016-11-25', 5);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (10, 4.41699, '2016-11-25', 6);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (11, 1.03738, '2016-12-28', 2);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (12, 0.849468, '2016-12-28', 3);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (13, 1.40844, '2016-12-28', 4);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (14, 1.07041, '2016-12-28', 5);
INSERT INTO Currencies_Rates (ID, Conversion_Rate, Date_Rate, ID_CURRENCIES) VALUES (15, 4.40688, '2016-12-28', 6);

INSERT INTO Categories (ID, Name) VALUES (1, 'Shirts');
INSERT INTO Categories (ID, Name) VALUES (2, 'Trousers');
INSERT INTO Categories (ID, Name) VALUES (3, 'Shoes');
INSERT INTO Categories (ID, Name) VALUES (4, 'Hats');
INSERT INTO Categories (ID, Name) VALUES (5, 'Socks');

INSERT INTO Sizes (ID, Name) VALUES (1, 'S');
INSERT INTO Sizes (ID, Name) VALUES (2, 'M');
INSERT INTO Sizes (ID, Name) VALUES (3, 'L');
INSERT INTO Sizes (ID, Name) VALUES (4, 'XL');
INSERT INTO Sizes (ID, Name) VALUES (5, '39');
INSERT INTO Sizes (ID, Name) VALUES (6, '41');
INSERT INTO Sizes (ID, Name) VALUES (7, '44');

INSERT INTO Roles (ID, Name, Code) VALUES (1, 'Super Administrator', 'ROLE_SUPER_ADMIN');
INSERT INTO Roles (ID, Name, Code) VALUES (2, 'Administrator', 'ROLE_ADMIN');
INSERT INTO Roles (ID, Name, Code) VALUES (3, 'User', 'ROLE_USER');
INSERT INTO Roles (ID, Name, Code) VALUES (4, 'Customer', 'ROLE_CUSTOMER');
INSERT INTO Roles (ID, Name, Code) VALUES (5, 'Producer', 'ROLE_PRODUCER');
INSERT INTO Roles (ID, Name, Code) VALUES (6, 'Guest', 'ROLE_GUEST');

INSERT INTO Users (ID, Login, Email, Password_Hash, ID_ROLES, Path_Slug) VALUES (1, 'guest', 'guest@guest.guest', '$2y$13$gd0WItzUO2MGRzz0posdVeZz.K.518ecBdWwg5US24GvITcAz6Xm6', 6, 'ewWSIGrRBcRtkddBn2FlDzonjEPKbVRyN8qJ69vcaM5xJCuRPc');
INSERT INTO Users (ID, Date_Created, Login, Email, Password_Hash, ID_ROLES, Path_Slug) VALUES (2, '2016-12-02', 'admin123', 'admin@ad.min', '$2y$13$q43l5g4fan65xCr0dkTxpe71Z7PQqqYatz8zYGWPbRGOCiyh2mQIC', 2, 'GgUSZbnDcBu6wosgoz8jaaHVpA1euAI1jQa8mEMYAt9LQVPVuw');
INSERT INTO Users (ID, Date_Created, Login, Email, Password_Hash, ID_ROLES, Path_Slug) VALUES (3, '2016-07-07', 'user', 'user@used.user', '$2y$13$LK082JKNh7ME7o6Vn/bjkeDEJfajPXCiegqlDaDESnrGjflvYrNAG', 3, 'HKAZmjMlvAielHIFOwdZkjoA8Us1M6S5jdoXziiJuTU0negPQl');
INSERT INTO Users (ID, Date_Created, Login, Email, Password_Hash, ID_ROLES, Path_Slug) VALUES (4, '2015-01-01', 'admin', 'admin@admin.admin', '$2y$13$J5XJ.bvLTJ0M0kmun722nOiG3C2HCQi2v7NtNiOjWEJXZD2SXwnVi', 1, 'JWcX5wnVXm6cHmRprqymsFRNlaXZIsr8aa1oNNSDT4239wePs6');
INSERT INTO Users (ID, Login, Email, Password_Hash, ID_ROLES, Path_Slug) VALUES (5, 'customer', 'customer@customer.customer', '$2y$13$t5mD8ZZYbb0Zje9DgyKtV.vthmphXjMw5N//1IT/lfuzzB69ifFBK', 4, 'zNZyD0Fmumh3XQImmrPBDa7Fiv6wL2zfiHoswBayMxcuqXT1k6');

-- INSERT INTO Configurations () VALUES ();

-- INSERT INTO Login_Attempts () VALUES ();

-- INSERT INTO Tokens () VALUES ();

INSERT INTO Customers (ID, Identification_Number, First_Name, Last_Name, Gender, ID_ADDRESSES, Email, Phone, ID_CURRENCIES, ID_USERS, Path_Slug) VALUES (1, '9276156937', 'Richard', 'McFarlin', 'M', 1, 'RichardRMcFarlin@teleworm.us', '479-316-2396', 2, 5, 'tt53zAw6IewAKQqhLT3tK3Bm4jxYRAOJ48aCif4p6T7BkCQW8v');
INSERT INTO Customers (ID, Identification_Number, First_Name, Last_Name, Gender, ID_ADDRESSES, Email, Phone, ID_CURRENCIES, Path_Slug) VALUES (2, '1132466159', 'Lisa', 'Sanger', 'F', 2, 'LisaSanger@teleworm.us', '09805 81 50 95', 1, 'WNyPJ0er9sbE2V5vA2x0cTNPDovu35xdlJEt38BTDCGDLiiVY6');
INSERT INTO Customers (ID, Identification_Number, First_Name, Last_Name, Gender, ID_ADDRESSES, Email, Phone, ID_CURRENCIES, ID_USERS, Path_Slug) VALUES (3, '5438663546', 'Isobel', 'Briggs', 'F', 3, 'MeganHicks@teleworm.us', '077 3928 7528', 3, 3, 'F2zMfO7T0P0ilZDedkCuhPlq646b8XcfBHIfhzSIH42fsU4cxk');
INSERT INTO Customers (ID, Identification_Number, First_Name, Last_Name, Gender, ID_ADDRESSES, Email, Phone, ID_CURRENCIES, ID_USERS, Path_Slug) VALUES (4, '2211239911', 'Puck', 'van Uitert', 'O', 4, 'PuckvanUitert@rhyta.com', '06-15368249', 1, 4, 'PN2dJhsPz0h9tonhT3H5dWtkuOWBhZXpHr1h4y6QT4KotU7q9G');

INSERT INTO Producers (ID, Short_Name, Full_Name, VAT, ID_ADDRESSES, Email, Phone, Path_Slug) VALUES (1, 'Opticomp', 'Opticomp Clothing', 'US773365342', 5, 'LarrySLowell@armyspy.com', '858-401-5106', 'ogZ3y7fN2bRYa8nNpBHnB6nNgAKveqxUQTIeZ8fE4NARoGCNic');
INSERT INTO Producers (ID, Short_Name, Full_Name, First_Name, Last_Name, VAT, ID_ADDRESSES, Email, Phone, Path_Slug) VALUES (2, 'Omni', 'Omni Architectural Designs', 'Christopher', 'Harris', 'GB028338520', 6, 'RileyCraig@teleworm.us', '078 2966 5839', 'tRtLZ93FMfzrS4UpUegAQRkCTKgk3NYBiISNwluyleFJx7lV0u');
INSERT INTO Producers (ID, Short_Name, Full_Name, VAT, ID_ADDRESSES, Email, Phone, ID_USERS, Path_Slug) VALUES (3, 'Asiatic', 'Asiatic Solutions', 'FR69210542996', 7, 'BrigittePatry@teleworm.us', '04.29.54.23.82', 4, 'jOtz5btgwsFE29KeXWMan1M9jRHt3THBxFGdvoO5Lijt6xlufd');
INSERT INTO Producers (ID, Short_Name, Full_Name, First_Name, Last_Name, VAT, ID_ADDRESSES, Email, Phone, ID_USERS, Path_Slug) VALUES (4, 'H & H', 'Hughes & Hatcher', 'Katherine J.', 'Hepp', '6wSyi4BQ10vkRqm', 8, 'KatherineJHepp@rhyta.com', '416-981-2808', 3, 'KXFH0ur7He79vxdoqIqANtDEgCWqAXaJIVXyhyD7v8vIUfXFZs');

INSERT INTO Orders (ID, ID_CUSTOMERS, User_Created, Date_Placed, Path_Slug) VALUES (1, 2, 2, '2017-02-09', 'Umgewrmyefi6thiDJZMmz4LHuKrJDjaVbPZzfCgwLS6Fr5FKhs');
INSERT INTO Orders (ID, ID_CUSTOMERS, User_Created, Date_Placed, Path_Slug) VALUES (2, 3, 3, '2016-08-13', 'g11SxwcyZw9ILjAiCi0eVjF6BT8cy5BixERUR0Lm79cGQFTGwJ');
INSERT INTO Orders (ID, ID_CUSTOMERS, User_Created, Date_Placed, Path_Slug) VALUES (3, 1, 5, '2015-12-01', 'ZZgtZjORKCCKKTflMgLr7UpKpC2ErHGE2LqW6tMASMylmPKlBP');
INSERT INTO Orders (ID, ID_CUSTOMERS, User_Created, Date_Placed, Path_Slug) VALUES (4, 4, 4, '2016-11-17', 'DgUqtlNvIMx2uzQz5iqU5perhrHME8tUkVR9aGT095W0rWCk7U');

INSERT INTO Invoices (ID, Name, ID_CUSTOMERS, User_Created, Date_Issued, Path_Slug) VALUES (1, '1/2016', 2, 1, '2016-02-26', 'fQ5oxD0fkgVdl9NZp6QbO9aDhF4wqbY9FqKfpVckiCOsdk6vpG');
INSERT INTO Invoices (ID, Name, ID_CUSTOMERS, User_Created, Date_Issued, Path_Slug) VALUES (2, '4/2017', 4, 2, '2017-03-01', 'iqHtsskeLDHD5LtJBfFBzPNB5AWGSrr16IGhCrZA2AtcOH1tFT');
INSERT INTO Invoices (ID, Name, ID_CUSTOMERS, User_Created, Date_Issued, Path_Slug) VALUES (3, '14/2015', 3, 4, '2015-12-18', 'OsZStBE2IzTOAJtpIgdt9UaAUXb8E38w1rctIaYPLyBA3GfNVb');
INSERT INTO Invoices (ID, Name, ID_CUSTOMERS, User_Created, Date_Issued, Path_Slug) VALUES (4, '16/2015', 1, 2, '2015-10-15', 'rsooOH9b8LZW8A4c6kb0wDjhpqonhCNWcXo9AgZuHI8HrEV4q0');
INSERT INTO Invoices (ID, Name, ID_CUSTOMERS, User_Created, Date_Issued, Path_Slug) VALUES (5, '10/2014', 2, 2, '2014-11-01', 'sNQT0j1GFoMrQ8UieAPb7UKAVLg9ID02z07rC13AOdustnEgBe');

INSERT INTO Products (ID, Name, Path_Slug, Price_Producer, Price_Customer, ID_CATEGORIES, ID_PRODUCERS, ID_SIZES, QR_Code_Path) VALUES (1, 'Cool Shoe', 'Cool-Shoe-39', 50, 79.99, 3, 2, 5, 'qr/shoes/cool_qr.jpg');
INSERT INTO Products (ID, Name, Path_Slug, Price_Producer, Price_Customer, ID_CATEGORIES, ID_PRODUCERS, ID_SIZES, QR_Code_Path) VALUES (2, 'TF Hat No. 7', 'TF-Hat-No-7-M', 7, 12.5, 4, 3, 2, 'qr/hats/tf7_qr.jpg');
INSERT INTO Products (ID, Name, Path_Slug, Price_Producer, Price_Customer, ID_CATEGORIES, ID_PRODUCERS, ID_SIZES, QR_Code_Path) VALUES (3, 'Simple Top Hat', 'Simple-Top-Hat-XL', 55, 65.99, 4, 2, 4, 'qr/hats/simple_qr.jpg');

INSERT INTO Products_Sizes (ID, ID_PRODUCTS, ID_SIZES, Availability) VALUES (1, 1, 5, 12);
INSERT INTO Products_Sizes (ID, ID_PRODUCTS, ID_SIZES, Availability) VALUES (2, 1, 6, 4);
INSERT INTO Products_Sizes (ID, ID_PRODUCTS, ID_SIZES, Availability) VALUES (3, 1, 7, 26);
INSERT INTO Products_Sizes (ID, ID_PRODUCTS, ID_SIZES, Availability) VALUES (4, 2, 2, 55);
INSERT INTO Products_Sizes (ID, ID_PRODUCTS, ID_SIZES, Availability) VALUES (5, 2, 4, 8);

INSERT INTO Images (ID, File_Path, ID_PRODUCTS, Sequence_Position) VALUES (1, 'shoes/cool-1.jpg', 1, 1);
INSERT INTO Images (ID, File_Path, ID_PRODUCTS, Sequence_Position) VALUES (2, 'shoes/cool-2.jpg', 1, 2);
INSERT INTO Images (ID, File_Path, ID_PRODUCTS, Sequence_Position) VALUES (3, 'hats/tf7.png', 2, 1);

INSERT INTO Invoices_Products (ID, ID_INVOICES, ID_PRODUCTS, Quantity, Discount, Price_Final) VALUES (1, 2, 1, 4, 15, 68.99);
INSERT INTO Invoices_Products (ID, ID_INVOICES, ID_PRODUCTS, Quantity, Discount, Price_Final) VALUES (2, 2, 2, 6, 5, 11.99);
INSERT INTO Invoices_Products (ID, ID_INVOICES, ID_PRODUCTS, Quantity, Discount, Price_Final) VALUES (3, 4, 3, 2, 10, 59.99);
INSERT INTO Invoices_Products (ID, ID_INVOICES, ID_PRODUCTS, Quantity, Discount, Price_Final) VALUES (4, 3, 1, 17, 0, 79.99);
INSERT INTO Invoices_Products (ID, ID_INVOICES, ID_PRODUCTS, Quantity, Discount, Price_Final) VALUES (5, 3, 3, 22, 0, 65.99);

INSERT INTO Orders_Statuses (ID, Name) VALUES (1, 'Queued');
INSERT INTO Orders_Statuses (ID, Name) VALUES (2, 'In Progress');
INSERT INTO Orders_Statuses (ID, Name) VALUES (3, 'Completed');
INSERT INTO Orders_Statuses (ID, Name) VALUES (4, 'Rejected');

-- INSERT INTO Orders_Products () VALUES ();
INSERT INTO Orders_Products (ID, ID_ORDERS, ID_PRODUCTS, ID_STATUSES, Quantity, Discount) VALUES (1, 1, 1, 1, 5, 10);
INSERT INTO Orders_Products (ID, ID_ORDERS, ID_PRODUCTS, ID_STATUSES, Quantity, Discount) VALUES (2, 1, 2, 1, 12, 0);
