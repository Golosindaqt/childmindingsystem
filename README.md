## **Child Minding Management System (CMMS)**

### **Project Overview**
The **Child Minding Management System (CMMS)** is a web-based system designed to manage child enrollment, attendance, reports, and administrative tasks efficiently. The system is built using **Node.js, Express.js, MySQL, and Bootstrap**.

---

## **Installation Guide**
### **1. Prerequisites**
Ensure you have the following software installed:
- **Node.js** (v16 or later) - [Download here](https://nodejs.org/)
- **MySQL** (v8 or later) - [Download here](https://www.mysql.com/)
- **Git** (latest version) - [Download here](https://git-scm.com/)
- **Postman** (for API testing) - [Download here](https://www.postman.com/)

### **2. Clone the Repository**
```sh
git clone https://github.com/yourusername/cmms.git
cd cmms
```

### **3. Install Dependencies**
```sh
npm install
```

### **4. Configure Database**
1. Start your **MySQL Server**.
2. Create a new database:
```sql
CREATE DATABASE cmms_db;
```
3. Import the database schema:
```sh
mysql -u root -p cmms_db < database/schema.sql
```

### **5. Configure Environment Variables**
Create a `.env` file in the root directory and add the following:
```env
PORT=3000
DB_HOST=localhost
DB_USER=root
DB_PASS=yourpassword
DB_NAME=cmms_db
SESSION_SECRET=your_secret_key
JWT_SECRET=your_jwt_secret
```

### **6. Run the Server**
```sh
npm start
```
The server should now be running at `http://localhost:3000`.

---

## **Credentials Format**
### **Default Admin Account**
| Role  | Username | Password |
|-------|----------------|--------------|
| Admin | teacheradmin   | teacheradmin |

### **User Credentials**
| Field      | Description        |
|------------|--------------------|
| `username` | Unique user handle |
| `email`    | User's email       |
| `password` | Encrypted password |

---

## **API Endpoints**
### **Authentication**
| Method | Endpoint       | Description        |
|--------|--------------|--------------------|
| POST   | `/register`  | Register a user   |
| POST   | `/login`     | Login a user      |

### **Admin Functions**
| Method | Endpoint        | Description          |
|--------|---------------|----------------------|
| GET    | `/admin/users` | Fetch all users     |
| DELETE | `/admin/user/:id` | Delete a user |

---

## **Troubleshooting**
If you encounter issues:
1. Check if **MySQL** is running.
2. Ensure **Node.js** dependencies are installed (`npm install`).
3. Verify `.env` configuration.
4. Review logs: 
   ```sh
   npm run dev
   ```
