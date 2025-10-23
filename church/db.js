const mongoose = require('mongoose');

const branchSchema = new mongoose.Schema({
    name: String,
    location: String,
    cellGroups: [{ type: mongoose.Schema.Types.ObjectId, ref: 'CellGroup' }]
});

const Branch = mongoose.model('Branch', branchSchema);
module.exports = Branch;





const mongoose = require('mongoose');

const cellGroupSchema = new mongoose.Schema({
    name: String,
    branch: { type: mongoose.Schema.Types.ObjectId, ref: 'Branch' }
});

const CellGroup = mongoose.model('CellGroup', cellGroupSchema);
module.exports = CellGroup;







const mongoose = require('mongoose');

const memberSchema = new mongoose.Schema({
    name: String,
    address: String,
    phone: String,
    email: String,
    dob: Date,
    maritalStatus: String,
    cellGroup: { type: mongoose.Schema.Types.ObjectId, ref: 'CellGroup' },
    branch: { type: mongoose.Schema.Types.ObjectId, ref: 'Branch' },
    attendance: String
});

const Member = mongoose.model('Member', memberSchema);
module.exports = Member;







app.post('/add-branch', async (req, res) => {
    try {
        const { name, location, cellGroups } = req.body;

        // Create the branch and its cell groups
        const branch = new Branch({ name, location });
        await branch.save();

        // Create cell groups for the branch
        for (const groupName of cellGroups) {
            const cellGroup = new CellGroup({ name: groupName, branch: branch._id });
            await cellGroup.save();
            branch.cellGroups.push(cellGroup);
        }

        // Save the branch with the associated cell groups
        await branch.save();
        res.status(201).json({ message: 'Branch added successfully!' });
    } catch (error) {
        res.status(400).json({ error: error.message });
    }
});








app.post('/add-member', async (req, res) => {
    try {
        const { name, address, phone, email, dob, maritalStatus, cellGroup, branch } = req.body;

        // Find the cell group and branch by ID
        const foundCellGroup = await CellGroup.findById(cellGroup);
        const foundBranch = await Branch.findById(branch);

        // Create the new member
        const member = new Member({
            name,
            address,
            phone,
            email,
            dob,
            maritalStatus,
            cellGroup: foundCellGroup._id,
            branch: foundBranch._id,
            attendance: "0%"
        });

        await member.save();
        res.status(201).json({ message: 'Member added successfully!' });
    } catch (error) {
        res.status(400).json({ error: error.message });
    }
});






app.get('/branches', async (req, res) => {
    try {
        const branches = await Branch.find().populate('cellGroups');
        res.json(branches);
    } catch (error) {
        res.status(400).json({ error: error.message });
    }
});






app.get('/members/:id', async (req, res) => {
    try {
        const member = await Member.findById(req.params.id)
            .populate('cellGroup')
            .populate('branch');
        res.json(member);
    } catch (error) {
        res.status(400).json({ error: 'Member not found' });
    }
});







const express = require('express');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.json());

// Connect to MongoDB
mongoose.connect('mongodb://localhost:27017/churchdb', {
    useNewUrlParser: true,
    useUnifiedTopology: true
});

mongoose.connection.on('connected', () => {
    console.log('Connected to MongoDB');
});

const Branch = require('./models/Branch');
const CellGroup = require('./models/CellGroup');
const Member = require('./models/Member');

// Your routes and server code here

const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});





// When adding a new member
const memberData = {
    name: "John Doe",
    address: "123 Church St",
    phone: "123-456-7890",
    email: "johndoe@example.com",
    dob: "1990-01-01",
    maritalStatus: "Single",
    cellGroup: "ID_of_cell_group",  // CellGroup ID
    branch: "ID_of_branch"         // Branch ID
};

fetch('http://localhost:3000/add-member', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(memberData)
})
.then(response => response.json())
.then(data => alert(data.message))
.catch(error => console.error('Error:', error));












const mysql = require('mysql');

// Create a connection to the database
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',  // default XAMPP MySQL username
    password: '',  // default XAMPP MySQL password (empty)
    database: 'churchdb'
});

// Connect to the database
connection.connect((err) => {
    if (err) {
        console.error('Error connecting to the database:', err);
        return;
    }
    console.log('Connected to the database!');
});

// Example query to fetch all branches
connection.query('SELECT * FROM branches', (err, results) => {
    if (err) {
        console.error('Error fetching branches:', err);
        return;
    }
    console.log('Branches:', results);
});

// Don't forget to close the connection after queries are done
connection.end();
