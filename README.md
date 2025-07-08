# Dolphin Project

## Overview
Dolphin is a web application built with Vue.js that provides a user-friendly interface for users. The application is designed to be modular and scalable, allowing for easy maintenance and updates.

## Project Structure
The project is organized into the following directories and files:

```
Dolphin
├── src
│   ├── assets          # Static assets such as images, fonts, and stylesheets
│   ├── components      # Reusable Vue components
│   │   └── HelloWorld.vue
│   ├── views           # Vue components for different views
│   │   └── Home.vue
│   ├── App.vue         # Root component of the application
│   ├── main.js         # Entry point of the Vue application
│   └── router
│       └── index.js    # Vue Router setup
├── public
│   └── index.html      # Main HTML file for the application
├── package.json        # npm configuration file
└── README.md           # Project documentation
```

## Installation
To get started with the Dolphin project, follow these steps:

1. Clone the repository:
   ```
   git clone <repository-url>
   ```

2. Navigate to the project directory:
   ```
   cd Dolphin
   ```

3. Install the dependencies:
   ```
   npm install
   ```

## Usage
To run the application in development mode, use the following command:
```
npm run serve
```
This will start a local development server, and you can view the application in your browser at `http://localhost:8080`.

# In your project directory, run:
npm install
npm run serve

## Contributing
Contributions are welcome! Please feel free to submit a pull request or open an issue for any suggestions or improvements.

## License
This project is licensed under the MIT License. See the LICENSE file for more details.


////look we have total this menu in sidebar
Dashboard
Organizations
Notification
Leads
assesments
Training &Resources
My Organization

this all and only this needs to be in ide bar meny and for filter

for super admin this only
Dashboard
Organizations
Notification
Leads


for Dolphin Admin
Dashboard and leads

for users
Dashboard and assesments

for sales pers 
Dashboard and assesments

for organization admin
dashboard
assesments
Training &Resources
My Organization


//
1929px x 1525px with sidebar and navbar 
d
orgTab
date filter

 else if (this.sortKey === 'contractStart') {
        orgs = orgs.slice().sort((a, b) => {
          const dateA = new Date(a.contractStart);
          const dateB = new Date(b.contractStart);
          return this.sortAsc ? dateA - dateB : dateB - dateA;
        });
      } else if (this.sortKey === 'contractEnd') {
        orgs = orgs.slice().sort((a, b) => {
          const dateA = new Date(a.contractEnd);
          const dateB = new Date(b.contractEnd);
          return this.sortAsc ? dateA - dateB : dateB - dateA;
        });
      }