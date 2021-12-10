# CS166_Final
[The Matrix HQ](https://jdiscipi.w3.uvm.edu/cs166/live)

Use the aforementioned link to explore the live project (in order to test db functionality and flow).

Author email: jdiscipi@uvm.edu

## Description
This project is a culmination of the information taught in CS166 Cyber Security Principles. The codebase is theoretically secure against XSS and SQL injection attacks/
The site implements authentication using PHP Sessions and authorization through role-based access control.

## Components
The project consists of a MySQL database (hosted on silk) and the backend code is interleaved with the front end pages (using PHP, and jquery). 
When a request is made to the webserver, the PHP interpreter will fire up and bake values into the page during execution flow and user navigation.

### Sessions
The project makes use of PHP Sessions to keep a user logged in until they decide to log out or the session expires.

### WebAuth
The project also implements WebAuth in order to protect admin actions in the DB. In order to get admin rights an existing admin must first change your role to 'admin' using the admin page.

Using the admin page as such will also place the user's netId into tblAdmin. Only users present in tblAdmin will be able to authenticate with UVM's WebAuth (meaning that the username must also be a valid netId) to access the admin tables.

### Admin tables
The admin tables allow admins to reset the login attempts (to unlock a locked account). It also allows the admin to issue a password reset and role changes (mentioned previously). Note: If a user forgets their password... they cannot reset it (admins may delete accounts however).. password resets are only enabled if the admin has requested it and the user can authenticate with the old password.

### Strong password generator
When a user is on the register page or viewing the password reset form (resulting from admin request), a button is displayed that generates a random, secure password that follows my password guidelines. 

Clicking the button will generate a new password on the client side (prints it to the screen) and the user can choose to copy this password and paste it into the password field or make their own.

### Account locking
If a user fails to login to their account 3 times... they will be prevented from logging in until an admin uses the admin tables to reset the login attempts by clicking the number.

# WARNING
DO NOT USE YOUR REAL UVM NETID AND PASSWORD when REGISTERING your account!
Only use your actual netId if you plan on accessing the admin page.
You should only ever enter your PASSWORD in the WebAuth form (administrators only).

### Note
Currently, only jdiscipi and jreddy1 are authorized to access the admin page via WebAuth.
These users can create other admins using the admin tables from the admin page. 

If you want to have your netId added to the table, please contact Jim or the project author.

## Credentials
Roles are granted only by admins who are tied to a uvm netId.

The credentials for the database are to be stored in /lib/pass.php (not included here for security reasons). This file is used by Database.php to separate reading from writing (part of the principle of least privilege). 

| username | password | access level (role) |
|----------|----------|---------------------|
| accountant | bHgUqGmMaYcJm!hWtMiJwEt2f | accountant |
| engineer | uFeVjD3KaRgGjNmI#MsNsQcQc | engineer |
| guest | hOqQ2PhPaRfYlRlUqWa-xEaEs | guest |
| manager | tGx4pRoTnDxVf$fCkKdLdGkBh | manager |
| admin | sMiDiNnAlYmBaAb3lTg*lMvRe | admin |

### SETUP Instructions
Due to my reliance on the MySQL setup provided by silk, it is not possible to spin this up yourself and you must use my live edition.

That being said, I can provide the table structures... but ultimately you will need to use webdb to create the database, set up your reader and writer accounts, and phpmyadmin to create the tables.

## tblAdmin

Database: `JDISCIPI_cs166_final`

Table structure for table `tblAdmin`

CREATE TABLE `tblAdmin` (
  `netId` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

Dumping data for table `tblAdmin`

INSERT INTO `tblAdmin` (`netId`) VALUES
('admin'),
('jdiscipi'),
('jreddy1');

Indexes for dumped tables

Indexes for table `tblAdmin`

ALTER TABLE `tblAdmin`
  ADD PRIMARY KEY (`netId`);
COMMIT;

## tblUser

Table structure for table `tblUser`

CREATE TABLE `tblUser` (
  `fldUsername` varchar(12) NOT NULL,
  `fldPassword` varchar(144) NOT NULL,
  `fldLevel` varchar(15) NOT NULL,
  `fldLoginAttempts` int(11) NOT NULL,
  `fldResetPassword` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

Dumping data for table `tblUser`

INSERT INTO `tblUser` (`fldUsername`, `fldPassword`, `fldLevel`, `fldLoginAttempts`, `fldResetPassword`) VALUES
('accountant', '299469801b0777c64a27c1e06011ec90244ff46c2d5c8a410de1a4742856bce5191ef8be0e206a470b0b1ecf61205b83f7094831be77fa80da5d7195f4de9c565c50c1f375d170fe', 'accountant', 0, 0),
('admin', '93c6bbefbf82415106ba696b84411727a28aedafe6c2f3739b5c7fe81fcd209246cb0d772789f6059dc7a60e9fd1e3311cdbd2c5dbbbe667a71fbdf034badcea0d1f212a5adae28a', 'admin', 0, 0),
('dbrown20', '6463ceea3dc2d9e7538d7f2da7477825b0491930635a29046a0e84d4168c6d648c5a44e7a6f33959bb3e77635470ffa7f1dfafdde003d7a6739ce64f69cec78f7dae517d7c7784d1', 'guest', 0, 0),
('engineer', 'c8aa2c7b77ec90caa62eab474278d4b2a5f2826b2356a2b6e4148990351075908333b7bb2f6af77ef4007003dc6d707b5d35f0ec7358b8dca52a4b1eb5d4b7e411fa2da28095d785', 'engineer', 0, 0),
('guest', '8f0607a682e535cf846964789b67f36d187fecdc072adbb7871f3084443d584bd0d8c8618bf28c7d5ab25668e081b988cdaa65adfd123b06301cf8726a2e79881d7378b6f44c03aa', 'guest', 0, 0),
('jdiscipi', '23cffbc921d7903ece2e7e123a82eb4252120fe766110e80d838018ca19d93f6740a7e8a113d1237acff035928009b793b462efa91a4542d7758bd9b61200424bcb60e536c090b90', 'admin', 0, 0),
('manager', '55fc1031bbca74d6bee1b21c959026bc59f5c2145679b3af91d66e5da90bd079ca303296b8ddd139c16f4a0f8dc7d2d806f6ac218403820415cdde7aea8ca834e8149901ead70691', 'manager', 0, 0);

Indexes for dumped tables
Indexes for table `tblUser`

ALTER TABLE `tblUser`
  ADD PRIMARY KEY (`fldUsername`);
COMMIT;