# Forum Website Project

This project is a PHP-based forum website that allows users to register, log in, post questions related to university modules, and respond with answers. The forum supports user management, module management, and interactive functionalities like asking and answering questions. The system includes role-based access where admins have additional control over modules and users.


## Features

- **User Authentication**: Users can sign up, log in, and manage their profiles.
- **Module Management**: Admins can add, edit, and delete university modules.
- **Question and Answer System**: Users can post questions related to modules, and other users can respond with answers.
- **Admin Control**: Admins can delete users, manage modules, and delete posts or answers.
- **Dynamic Forms**: Various forms for submitting questions, answers, and contacting admin.
- **Responsive Design**: The website is styled with CSS and uses external libraries for responsiveness and styling.
- **Session Management**: Sessions are used to store and manage user data securely.

## Technologies

- **Backend**: PHP, MySQL (PDO for database access)
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: Custom CSS and Google Fonts
- **Libraries**: FontAwesome for icons
- **Database**: MySQL

## Setup and Installation

### Prerequisites

- A web server with PHP 7.x or higher (e.g., Apache or Nginx)
- MySQL database
- Composer (optional, for managing dependencies)

### Steps

1. Clone the repository to your local machine:
2. Create a MySQL Database: Set up a new MySQL database and import the provided database schema. You can use the following SQL command to create the database:
CREATE DATABASE your-database-name;
3. Configure Database Connection: Modify the database connection settings in the PHP files (e.g., login.php, signup.php) to match your MySQL database credentials
4. Upload the Project: Upload the project files to your web server or set up a local development environment using software like XAMPP, MAMP, or WAMP.

## Database Structure

The forum website uses a MySQL database to manage users, modules, posts, and answers. Below is a simplified structure of the database:

### Tables

#### Users
- `id` (Primary Key): Unique identifier for each user.
- `name`: The first name of the user.
- `surname`: The last name of the user.
- `email`: User's email address (used for login).
- `password`: Hashed password for secure authentication.

#### Modules
- `id` (Primary Key): Unique identifier for each module.
- `name`: The name of the module (e.g., "Web Development", "Database Systems").

#### Posts (Questions)
- `id` (Primary Key): Unique identifier for each question.
- `Title`: The title of the question.
- `Description`: Detailed description or body of the question.
- `User_ID` (Foreign Key): The user who posted the question.
- `Module_ID` (Foreign Key): The module to which the question belongs.
- `Photo` (optional): A photo or image attachment for the question (if applicable).

#### Answers
- `id` (Primary Key): Unique identifier for each answer.
- `answer`: The text of the answer provided by a user.
- `user_id` (Foreign Key): The user who provided the answer.
- `module_id` (Foreign Key): The module to which the answer relates.
- `post_id` (Foreign Key): The post (question) to which the answer belongs.


## Key Functionalities

1. **User Authentication**:
   - **Sign Up / Log In**: Users can register a new account or log in with an existing one. Passwords are hashed for security.
   - **Profile Management**: Users can manage their account details (e.g., name, email) through an edit profile page.
   - **Session Management**: User sessions are managed using PHP sessions to ensure secure access throughout the platform.

2. **Posting and Answering**:
   - **Ask Questions**: Registered users can post questions, selecting a related module. They can add a title, description, and optionally upload a photo.
   - **Answer Questions**: Users can browse questions and post answers. Each answer is linked to a specific question, and users can view all answers under the respective question.
   - **Edit/Delete Posts**: Users can edit or delete their own questions and answers. Admins have the authority to delete any post or answer.

3. **Admin Panel**:
   - **User Management**: Admins can view, edit, and delete users from the platform.
   - **Module Management**: Admins can create, edit, or delete modules, which users can then associate with their questions.
   - **Post/Answer Management**: Admins can delete any question or answer posted by users. This ensures that inappropriate content can be moderated.

4. **Question and Answer Interaction**:
   - **View Questions**: Users can view all questions related to a specific module, and see details such as the question title, description, and the user who posted it.
   - **View Answers**: Questions display all answers provided by other users, creating a dynamic discussion for each question.

5. **Search Functionality**:
   - **Search by Keyword**: Users can search for questions using a keyword in the title or description. This is done in real-time to provide quick results.
   - **Filter by Module**: Users can filter questions by module, allowing them to focus on specific topics related to their courses.

## Future Enhancements

1. **File Uploads**: Introduce the ability for users to upload files (e.g., PDFs, images) when submitting questions or answers, expanding the range of shared content.
2. **Email Notifications**: Add email notifications for users to receive updates on new answers or administrative announcements.
3. **User Roles and Permissions**: Expand the user roles to include moderators or other role-based access controls, granting different privileges based on the user type.
4. **Improved Security**:
   - Implement **CSRF protection** to secure forms from cross-site request forgery attacks.
   - Add **password recovery** functionality for users who forget their credentials.
5. **Tagging and Categories**: Introduce a tagging system for questions to make it easier to categorize and search for specific types of questions (e.g., "Homework," "Exams").

## Troubleshooting

### 1. Database Connection Issues
   - **Issue**: "Connection failed" or unable to connect to the MySQL database.
   - **Solution**: 
     - Ensure your database credentials are correct in the `PDO` connection strings within the PHP files.
     - Confirm that the MySQL service is running, and the database is accessible.
     - Make sure that your database username has the correct permissions to access the `mdb_rk7545s` database.

### 2. Permission Denied Errors
   - **Issue**: You see "permission denied" errors when trying to upload files or save changes.
   - **Solution**: 
     - Ensure that the web server (e.g., Apache or Nginx) has read/write permissions for the project directories.
     - You can set the correct permissions using the command:
       ```bash
       sudo chmod -R 755 /path/to/project
       sudo chown -R www-data:www-data /path/to/project
       ```

### 3. Session Problems
   - **Issue**: Users are being logged out unexpectedly, or sessions are not being maintained.
   - **Solution**: 
     - Verify that session handling is enabled in your PHP configuration (`php.ini`). Look for `session.save_path` and ensure it is correctly set.
     - Clear existing session data or restart the server to resolve any session-related caching issues.

### 4. Search Not Functioning
   - **Issue**: The search functionality does not return results or behaves unexpectedly.
   - **Solution**: 
     - Ensure that JavaScript is enabled in your browser, as the search feature relies on real-time JavaScript functionality.
     - Debug by inspecting the console log for any JavaScript errors in the browser's developer tools.




   
