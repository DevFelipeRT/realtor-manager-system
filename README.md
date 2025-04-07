# Real Estate Agent Management System

This is a web system developed to assist real estate agents in managing their activities and properties.

## Technologies Used

- **PHP**: Backend programming language.
- **MySQL**: Relational database for data storage.
- **HTML/CSS/JavaScript**: Frontend technologies.

## Prerequisites

- **PHP 7.4** or higher
- **MySQL 5.7** or higher
- **Web server** (Apache/Nginx)

## Installation

1. **Clone the repository**:
   ```bash
   git clone [REPOSITORY_URL]
   ```

2. **Configure the `.env` file** with your database credentials:
   ```
   DB_HOST=localhost
   DB_NAME=your_database_name
   DB_USER=your_username
   DB_PASS=your_password
   ```

3. **Import the database**:
   ```bash
   mysql -u your_username -p your_database_name < database/your_file.sql
   ```

4. **Set permissions for the storage directory**:
   ```bash
   chmod -R 775 storage/
   ```

5. **Configure autoload**:
   - The `autoload.php` file is set to automatically load classes from the `app/` directory.

## Project Structure

- `app/` - Contains the main application logic
- `config/` - Configuration files
- `database/` - Database scripts and migrations
- `public/` - Public files and application entry point
- `storage/` - File storage and logs

## Usage

- **System Access**: After installation, access the system through the browser pointing to the configured server.
- **Features**:
  - **Agent Registration**: Allows the registration of real estate agents with detailed information, including full name, CPF (Cadastro de Pessoa Física), and CRECI credentials (number, state, and category).
  - **Agent Editing**: Provides the ability to edit and update existing agent records, ensuring information is always up-to-date.
  - **Agent Deletion**: Enables the deletion of agent records with prior confirmation, ensuring intentional removal.
  - **Agent Viewing**: Displays all registered agents in an organized table, facilitating information consultation and management.
  - **Data Validation**: Implements server-side validation for all form data using PHP to ensure data integrity and security.
  - **Responsive Interface**: Features a responsive interface with a basic layout structure, providing a consistent user experience across different devices.
  - **Project Organization**: Maintains a clean project organization, ideal for learning purposes and portfolio inclusion.

## License

This project is licensed under the MIT License.

## References

- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/) 