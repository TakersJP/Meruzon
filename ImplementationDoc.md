## **Implementation Document: Meruzon**

**Team Members:** Takumi Choi(37325289), Teppei Toyoda(77115434), Seiya Iwama(91173278)

### **1\. Introduction**

This document provides a technical overview of the Meruzon project, an E-Commerce Store for Handmade Goods. The application allows users to **browse products**, **register accounts**, **manage profiles**, **add items to a cart**, **checkout**, **leave reviews**, and **view order history**. Administrators can **manage users, products, and reviews**.

The application is built using the LAMP stack (Linux, Apache, MySQL, PHP) deployed on the `cosc360.ok.ubc.ca` server. The front-end utilizes HTML5, CSS, and JavaScript for user interaction and validation. Key technologies and concepts employed include session management, prepared statements for database security, password hashing, AJAX for asynchronous updates, and version control using Git hosted on GitHub.

### **2\. Implemented Features**

* **User Authentication:**  
  * User registration with personal details, username, password, and profile image upload. (`register.php`, `register_validation.js`)  
  * Secure user login using username and password. (`login.php`, `login_validation.js`)  
  * Password hashing (PASSWORD\_DEFAULT) and verification. (`register.php`, `login.php`)  
  * Session-based state management to maintain login status across pages. (PHP `session_start()` used extensively)  
  * User logout functionality. (`logout.php`)  
* **User Types:**  
  * **Unregistered Users:** Can browse and search products. Cannot purchase or review.	  
  * **Registered Users:** Can perform all unregistered user actions plus: manage profile, add items to cart, checkout, view order history, post reviews.  
  * **Admin Users:** Can perform all registered user actions plus: access admin dashboard, manage users (search, delete), manage products (search, rename, delete), manage reviews (search, delete). (`admin.php`, `manage_*.php`)  
* **Product Catalog:**  
  * Display products with images, names, prices, and categories. (`listprod.php`, `shop.php`)  
  * Product detail view. (`detailprod.php`)  
  * Product search by name. (`listprod.php`, `shop.php`)  
  * Product filtering by category. (`listprod.php`, `shop.php`)  
  * Product data retrieved dynamically from MySQL database. (`get_products.php`)  
* **Shopping Cart:**  
  * Add products to the cart. (`add_cart.php` via `detailprod.php` / `listprod.php`)  
  * View cart contents with subtotals and total. (`showcart.php`)  
  * Update item quantities in the cart via AJAX (no page reload required). (`update_cart_ajax.php`, `showcart.php`)  
  * Remove items from the cart. (`remove_from_cart.php`)  
* **Checkout Process:**  
  * Collect payment information (type, number, expiry date). (`checkout.php`)  
  * Client-side validation for payment details. (`checkout.php` inline script)  
  * Server-side validation for payment details. (`process_payment.php`)  
  * Process order: Create order record, create order item records, clear cart. (`process_payment.php`)  
  * Display order confirmation page with details and tracking number. (`order-confirmation.php`)  
* **Order History:**  
  * Registered users can view their past orders. (`listorder.php`)  
* **Product Reviews:**  
  * Registered users can submit reviews (rating and comment) on product detail pages. (`add_review.php`, `detailprod.php`)  
  * Reviews are displayed on the product detail page. (`get_reviews.php`, `detailprod.php`)  
  * (Implied Asynchronous) Review submission likely updates the review list without full page reload, managed via JavaScript in `detailprod.php`.  
* **Profile Management:**  
  * Registered users can view their profile information. (`customer.php`)  
  * Users can upload/update their profile image. (`update_profile_image.php`, `customer.php`)  
  * Profile image is displayed in the header when logged in. (`header.php`)  
* **Admin Panel:**  
  * Dedicated dashboard for administrators. (`admin.php`)  
  * User management: Search by name/email, view list, delete users. (`manage_users.php`, `delete_user.php`)  
  * Product management: Search by name, view list, rename products, delete products. (`manage_products.php`, `rename_product.php`, `delete_product.php`)  
  * Review management: Search by user ID/username/product name, view list, delete reviews. (`manage_reviews.php`, `delete_review.php`)  
* **Security:**  
  * **Server-Side:** Prepared statements (mysqli) used for all database interactions involving user input to prevent SQL injection. Password hashing employed. Session management implemented. Input validation performed on server (e.g., `process_payment.php`). Admin access checks on relevant pages.  
  * **Client-Side:** Form validation using JavaScript for registration, login, and checkout. Password input fields use `type="password"`.  
* **Database:** MySQL database used to store user information (`users`), products (`items`), shopping cart contents (`cart`), orders (`orders`, `order_items`), and reviews (`reviews`).  
* **Deployment & Version Control:** Application deployed on `cosc360.ok.ubc.ca`. Code managed using Git and hosted on GitHub.

### **3\. File Descriptions**

* **`/php/` Directory:** Contains server-side PHP scripts.  
  * `config.php`: Establishes MySQL database connection. Included in most other PHP files.  
  * `register.php`/`login.php`/`logout.php`: Handle user authentication lifecycle.  
  * `shop.php`: Main landing page after login / homepage displaying categories and products.  
  * `listprod.php`: Product listing page with filtering/search.  
  * `detailprod.php`: Displays details for a single product, including reviews.  
  * `get_products.php`: Fetches product data (used by `listprod.php`/`shop.php`).  
  * `customer.php`: Displays user profile information.  
  * `update_profile_image.php`: Handles profile image uploads.  
  * `add_cart.php`/`showcart.php`/`update_cart_ajax.php`/`remove_from_cart.php`: Manage shopping cart actions.  
  * `checkout.php`/`process_payment.php`/`order-confirmation.php`: Handle the checkout process.  
  * `listorder.php`: Displays the user's order history.  
  * `add_review.php`/`get_reviews.php`: Handle review submission and retrieval.  
  * `admin.php`: Admin dashboard entry point.  
  * `manage_users.php`/`delete_user.php`: Admin user management functions.  
  * `manage_products.php`/`rename_product.php`/`delete_product.php`: Admin product management functions.  
  * `manage_reviews.php`/`delete_review.php`: Admin review management functions.  
  * `header.php`: Common header included on most pages, manages display of login status and profile picture.  
* **`/script/` Directory:** Contains client-side JavaScript files.  
  * `register_validation.js`: Provides client-side validation for the registration form.  
  * `login_validation.js`: Provides client-side validation for the login form.  
  * `login.js`, `logout.js`: (Note: `login.js` appears to contain Node.js code, which is not used in this LAMP stack project and might be leftover. `logout.js` seems incomplete/potentially unused as logout is handled by `logout.php`). Core client-side logic is often embedded within the PHP files (e.g., `detailprod.php`, `listprod.php`, `showcart.php`, `checkout.php`).  
* **`/css/` Directory:** Contains stylesheets.  
  * `style.css`: Custom styles for the Meruzon application.  
  * `bootstrap.min.css`: Bootstrap framework (although project description discouraged layout frameworks, it might be used for specific components or styling utilities).  
* **Root Directory Files:**  
  * HTML files (`login.html`, `register.html`, etc.): These are remnants from early development; the primary interface is now delivered through PHP files. `header.html` is fetched by some HTML files but `header.php` is used by PHP pages.

### **4\. High-Level Workflow**

1. **User Access:** An unregistered user visits the site (`shop.php` or direct link) and can browse/search products (`listprod.php`, `detailprod.php`).  
2. **Registration/Login:** The user registers (`register.php`) or logs in (`login.php`). User data is validated and stored/checked against the `users` table in the database. A PHP session is started to maintain login state.  
3. **Shopping:** The logged-in user browses products, views details, and adds items to their cart. Cart data is stored in the `cart` table, linked to the user's session ID. The user can view/update/remove items (`showcart.php`). Quantity updates use AJAX (`update_cart_ajax.php`) to interact with the server without page reload.  
4. **Checkout:** The user proceeds to checkout (`checkout.php`), enters payment details (validated client/server-side), and submits. The server processes the payment (`process_payment.php`), creates records in `orders` and `order_items` tables, clears the `cart` table for that user, and redirects to a confirmation page (`order-confirmation.php`).  
5. **Post-Order:** The user can view their profile (`customer.php`), update their image (`update_profile_image.php`), view past orders (`listorder.php`), and add reviews to products (`add_review.php`).  
6. **Admin Access:** An admin user logs in. Their session indicates admin privileges (`is_admin` flag). They can access the admin dashboard (`admin.php`) and perform management tasks on users, products, and reviews via dedicated pages (`manage_*.php`). All admin actions interact securely with the database using prepared statements.

### **5\. Known Limitations**

* **Payment Processing:** Checkout collects payment information but does not integrate with a real payment gateway. Payment details are validated but not actually processed.  
* **Search Functionality:** Product search is basic and likely only matches against product names. User/review search in admin panel might also be basic.  
* **Admin Reporting:** No complex visual reports (charts, graphs) for site usage or sales analytics were implemented, only basic list views with search/delete capabilities.  
* **Error Handling:** While basic error handling exists (e.g., form validation messages), comprehensive error handling for edge cases or database failures might be limited.  
* **Asynchronous Updates:** AJAX is implemented for cart quantity updates. Review submission/display might also be asynchronous, but broader real-time updates (e.g., stock level changes) are not implemented.  
* **Security Hardening:** While core security measures (prepared statements, hashing, sessions) are in place, further hardening (e.g., rate limiting, more extensive input sanitization across all inputs, CSRF protection) could be added.  
* **Scalability:** The current structure is suitable for the project scope but may require optimization for very high traffic loads.  
* **UI/UX:** The user interface is functional but could be enhanced for better user experience and visual appeal. Styling seems basic.

