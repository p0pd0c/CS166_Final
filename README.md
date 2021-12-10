# CS166_Final
[The Matrix HQ](https://jdiscipi.w3.uvm.edu/cs166/live)

Use the aforementioned link to explore the live project (in order to test db functionality and flow).

Author email: jdiscipi@uvm.edu

## Description
This project is a culmination of the information taught in CS166 Cyber Security Principles. The codebase is theoretically secure against XSS and SQL injection attacks/
The site implements authentication using PHP Sessions and authorization through role-based access control.

## Components
The project consists of a MySQL database (hosted on silk) and the backend code is interleaved with the front end pages (using PHP). 
When a request is made to the webserver, the PHP interpreter will fire up and bake values into the page during execution flow and user navigation.

### Sessions
The project makes use of PHP Sessions to keep a user logged in until they decide to log out or the session expires.

### WebAuth
The project also implements WebAuth in order to protect admin actions in the DB. In order to get admin rights an existing admin must first change your role to 'admin' using the admin page.

Using the admin page as such will also place the user's netId into tblAdmin. Only users present in tblAdmin will be able to authenticate with UVM's WebAuth (meaning that the username must also be a valid netId) to access the admin tables.

### Admin tables
The admin tables allow admins to reset the login attempts (to unlock a locked account). It also allows the admin to issue a password reset and role changes (mentioned previously). Note: If a user forgets their password... they cannot reset it (admins may delete accounts however).. password resets are only enabled if the admin has requested it and the user can authenticate with the old password. 


# WARNING
DO NOT USE YOUR REAL UVM NETID AND PASSWORD when REGISTERING your account!
Only use your actual netId if you plan on accessing the admin page.
You should only ever enter your PASSWORD in the WebAuth form (administrators only).

### Note
Currently, only jdiscipi and jreddy1 are authorized to access the admin page via WebAuth.
These users can create other admins using the admin tables from the admin page. 

If you want to have your netId added to the table, please contact Jim or the project author.
