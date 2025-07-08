# Dolphin

## Overview
Dolphin is a modular, scalable web application built with Vue.js. It features a dynamic sidebar menu that adapts to user roles, providing a tailored experience for Super Admins, Dolphin Admins, Organization Admins, Sales Persons, and Users.

## Features
- Role-based sidebar menu and navigation
- Dashboard, Organizations, Notifications, Leads, Assessments, Training & Resources, My Organization
- Responsive layout with sidebar and navbar
- Modular Vue components for easy maintenance
- Date filtering and sorting for organization data

## Sidebar Menu by Role
| Role               | Sidebar Menu Items                                      |
|--------------------|--------------------------------------------------------|
| Super Admin        | Dashboard, Organizations, Notification, Leads           |
| Dolphin Admin      | Dashboard, Leads                                        |
| User               | Dashboard, Assessments                                  |
| Sales Person       | Dashboard, Assessments                                  |
| Organization Admin | Dashboard, Assessments, Training & Resources, My Organization |

## Project Structure
```
Dolphin
├── src
│   ├── assets/                # Static assets (images, styles)
│   ├── components/            # Reusable Vue components
│   │   ├── auth/              # Auth-related components
│   │   ├── Common/            # Common/shared components
│   │   ├── layout/            # Layout components (Navbar, Sidebar, etc.)
│   ├── middleware/            # Middleware (e.g., auth)
│   ├── router/                # Vue Router setup
│   ├── App.vue                # Root component
│   ├── main.js                # Entry point
├── public/
│   └── index.html             # Main HTML file
├── package.json               # npm configuration
├── README.md                  # Project documentation
```

## Installation
1. Clone the repository:
   ```
   git clone https://github.com/Mitraj294/Dolphin.git
   cd Dolphin
   ```
2. Install dependencies:
   ```
   npm install
   ```

## Usage
To run the application in development mode:
```
npm run serve
```
Visit `http://localhost:8080` in your browser.

## Contributing
Contributions are welcome! Please open an issue or submit a pull request for suggestions or improvements.

## License
This project is licensed under the MIT License.