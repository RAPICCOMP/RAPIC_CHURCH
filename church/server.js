const express = require("express");
const mysql = require("mysql2");
const bodyParser = require("body-parser");

const app = express();
app.use(bodyParser.json());

// Database Connection
const db = mysql.createConnection({
    host: "localhost",
    user: "root", // Your MySQL username
    password: "", // Your MySQL password
    database: "ChurchDB"
});

db.connect((err) => {
    if (err) {
        console.error("Database connection failed: " + err.message);
        return;
    }
    console.log("Connected to the database.");
});

// API Endpoints
// Fetch branches
app.get("/branches", (req, res) => {
    db.query("SELECT * FROM Branches", (err, results) => {
        if (err) throw err;
        res.json(results);
    });
});

// Add member
app.post("/add-member", (req, res) => {
    const { name, address, phone, email, dob, marital_status, cell_group_id } = req.body;
    db.query(
        "INSERT INTO Members (name, address, phone, email, dob, marital_status, cell_group_id) VALUES (?, ?, ?, ?, ?, ?, ?)",
        [name, address, phone, email, dob, marital_status, cell_group_id],
        (err, results) => {
            if (err) throw err;
            res.json({ message: "Member added successfully!", id: results.insertId });
        }
    );
});

const PORT = 5000;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));
