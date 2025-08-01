# PetLink
PetLink is a web application designed to connect pet owners with freelancers offering various pet-related services. There is NO real payment processing involved, only a simulated checkout process.

## Implemented Features

**User:**
- [x] Register a new account.
- [X] Log in and out.
- [x] Edit their profile, including their name, username, password, and email.

**Freelancers:**
- [X] List new services, providing details such as category, pricing, delivery time, and service description, along with images or videos.
- [X] Track and manage their offered services.
- [X] Respond to inquiries from clients regarding their services and provide custom offers if needed.
- [X] Mark services as completed once delivered.

**Clients:**
- [X] Browse services using filters like category, price, and rating.
- [X] Engage with freelancers to ask questions or request custom orders.
- [X] Hire freelancers and proceed to checkout (simulate payment process).
- [X] Leave ratings and reviews for completed services.

**Admins:**
- [X] Elevate a user to admin status.
- [X] Introduce new service categories and other pertinent entities.
- [X] Oversee and ensure the smooth operation of the entire system.

**Extra:**
- [X] Users can personalize their experience by adding their own pets to their profile.

## How to run
```
git clone git@github.com:casemiromjm/petlink.git
cd petlink
sqlite3 database/database.db < database/database.sql
php -S localhost:9000
```

## Credentials for getting started

- test_admin/1234
- test_user/1234
