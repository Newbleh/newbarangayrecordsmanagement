# Barangay Records Management System

A comprehensive web-based system for managing barangay records, built with PHP, MySQL, HTML, CSS, JavaScript, and Bootstrap.

## Features

- **User Registration and Authentication**: Secure login system with role-based access (admin/staff)
- **Resident Profiling**: Complete resident information management
- **Document Tracking**: Track issued documents with expiry dates and status
- **Blotter Records**: Manage incident reports and resolutions
- **Real-time Reporting**: Dashboard with live statistics and reports
- **Cloud Backend Integration**: Firebase Firestore connectivity for cloud announcements and real-time updates

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx) or PHP built-in server
- Modern web browser
- Firebase account with Firestore enabled for cloud announcements

## Installation

1. **Clone or download the project files**

2. **Set up the database**:
   - Create a MySQL database named `barangay_records`
   - Import the schema from `db/database.sql`
   - Update database credentials in `includes/config.php` if necessary

3. **Configure the web server**:
   - Point your web server document root to the project directory
   - Or use PHP built-in server: `php -S localhost:8000`

4. **Configure Firebase**:
   - Create a Firebase project at https://console.firebase.google.com/
   - Enable Firestore in test or production mode
   - Copy the Firebase config values into `assets/js/firebase-config.js`

5. **Access the application**:
   - Open your browser and go to `http://localhost` (or your server URL)
   - Register a new user or use default admin account

## Default Login

- Username: admin
- Password: admin123 (after running the SQL script with hashed password)

## Project Structure

```
/
├── index.php              # Main entry point
├── includes/              # PHP includes
│   ├── config.php         # Database configuration
│   └── functions.php      # Common functions
├── assets/                # Static assets
│   ├── css/
│   └── js/
│       ├── dashboard.js
│       ├── firebase-config.js
│       └── firebase-announcements.js
├── pages/                 # Application pages
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│   ├── residents.php
│   ├── add_resident.php
│   ├── documents.php
│   ├── add_document.php
│   ├── blotter.php
│   ├── add_blotter.php
│   ├── reports.php
│   └── logout.php
├── db/                    # Database files
│   └── database.sql       # Database schema
└── .github/               # GitHub configuration
    └── copilot-instructions.md
```

## Usage

1. **Login**: Use your credentials to access the system
2. **Dashboard**: View system statistics and navigate to different sections
3. **Residents**: Add, view, edit, and manage resident profiles
4. **Documents**: Track documents issued to residents
5. **Blotter**: Record and manage incident reports
6. **Reports**: Generate and view system reports

## Vercel Deployment

This project can be deployed to Vercel using the included `vercel.json` configuration. Because this app requires MySQL, you must configure a remote database and set the following environment variables in your Vercel project settings:

- `DB_HOST`
- `DB_USER`
- `DB_PASS`
- `DB_NAME`

After setting the environment variables, deploy with:

```bash
npx vercel --prod
```

If you need MySQL hosting, use a cloud MySQL provider and update `includes/config.php` accordingly.

## Security Notes

- Change default admin password after first login
- Use HTTPS in production
- Regularly backup the database
- Validate all user inputs (already implemented)
- Do not commit real Firebase credentials from `assets/js/firebase-config.js` to public repositories

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open source. Feel free to use and modify as needed.