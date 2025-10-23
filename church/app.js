// Data for branches and cell groups
const branches = [
    { name: "Tarbanacle", cellGroups: ["Juresalem Center"] },
    { name: "Mount Horeb", cellGroups: ["Lubuto"] },
    { name: "Gatseman", cellGroups: ["Light"] },
    { name: "Bestsheba", cellGroups: ["Chipulukusu"] },
    { name: "Mount Sinai", cellGroups: ["Kaloko"] },
    { name: "Bethesda", cellGroups: ["Praise"] },
    { name: "Mount Moria", cellGroups: ["Healing"] },
    { name: "Canan", cellGroups: ["Outreach"] },
    { name: "Judah", cellGroups: ["Chifubu"] },
    { name: "Hebron", cellGroups: ["Kabushi"] }, 
    { name: "Mount Holie", cellGroups: ["Luashya"] },
    { name: "Ebenezar", cellGroups: ["Lufwanyama"] },
    { name: "Mount Zion", cellGroups: ["Chambeshi"] },
    { name: "Ramah", cellGroups: ["Pamodzi"] },
    { name: "Riverside Branch", cellGroups: ["Twapia"] },
    { name: "Hilltop Branch", cellGroups: ["Foundation"] },
    { name: "Hilltop Branch", cellGroups: ["Foundation"] },
];

// Sample data for members (this will be updated dynamically)
let members = [
    { id: "1", name: "John Phiri", maritalStatus: "Married", address: "123 Church St", cellGroup: "Alpha", branch: "Central Branch", attendance: "90%" },
    { id: "2", name: "Jane Smith", maritalStatus: "Single", address: "456 Hope Blvd", cellGroup: "Faith", branch: "East Branch", attendance: "85%" },
    { id: "3", name: "Michael Bwalya", maritalStatus: "Divorced", address: "789 Grace Ave", cellGroup: "Light", branch: "West Branch", attendance: "88%" },
    { id: "4", name: "Emily Davis", maritalStatus: "Widowed", address: "321 Love Rd", cellGroup: "Joy", branch: "South Branch", attendance: "92%" },
    { id: "5", name: "Paul Wilson", maritalStatus: "Single", address: "654 Victory Way", cellGroup: "Vision", branch: "North Branch", attendance: "80%" },
];

/// Render branches and cell groups dynamically
document.addEventListener('DOMContentLoaded', () => {
    const branchList = document.getElementById('branch-list');

    branches.forEach((branch, index) => {
        const branchDiv = document.createElement('div');
        branchDiv.classList.add('branch');

        // Branch name
        const branchName = document.createElement('h3');
        branchName.textContent = branch.name;
        branchDiv.appendChild(branchName);

        // Cell groups list
        const cellGroupList = document.createElement('ul');
        branch.cellGroups.forEach(group => {
            const groupItem = document.createElement('li');
            const link = document.createElement('a');
            link.textContent = group;
            // Link all cell groups to the 'celltables.html' page
            link.href = 'celltable.html'; // Change the link here
            groupItem.appendChild(link);
            cellGroupList.appendChild(groupItem);
        });

        branchDiv.appendChild(cellGroupList);
        branchList.appendChild(branchDiv);
    });
});

// Function to search for a member by ID
function searchMember() {
    const searchInput = document.getElementById('member-search').value;
    const memberDetailsDiv = document.getElementById('member-details');

    // Clear previous results
    memberDetailsDiv.innerHTML = "";

    // Find the member by ID
    const member = members.find(m => m.id === searchInput);

    if (member) {
        // Display member details
        memberDetailsDiv.innerHTML = `
            <div class="member-card">
                <h3>Member Details</h3>
                <p><strong>ID:</strong> ${member.id}</p>
                <p><strong>Name:</strong> ${member.name}</p>
                <p><strong>Marital Status:</strong> ${member.maritalStatus}</p>
                <p><strong>Address:</strong> ${member.address}</p>
                <p><strong>Cell Group:</strong> ${member.cellGroup}</p>
                <p><strong>Branch:</strong> ${member.branch}</p>
                <p><strong>Attendance:</strong> ${member.attendance}</p>
            </div>
        `;
    } else {
        // Display error message if member not found
        memberDetailsDiv.innerHTML = `
            <div class="error-message">
                <p>Member not found. Please check the Member ID and try again.</p>
            </div>
        `;
    }
}

// Function to load cell groups dynamically into the dropdown
function loadCellGroups() {
    const cellGroupDropdown = document.getElementById("cell-group");
    branches.forEach((branch) => {
        branch.cellGroups.forEach(group => {
            const option = document.createElement("option");
            option.value = group;
            option.textContent = group;
            cellGroupDropdown.appendChild(option);
        });
    });
}

// Function to add a new member
function addMember(event) {
    event.preventDefault();

    const name = document.getElementById("name").value.trim();
    const address = document.getElementById("address").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const email = document.getElementById("email").value.trim();
    const dob = document.getElementById("dob").value;
    const maritalStatus = document.getElementById("marital-status").value;

    if (!name || !address || !phone || !maritalStatus) {
        alert("Please fill in all required fields.");
        return;
    }

    // Assign a member ID (the next ID in the array)
    const newMemberId = String(members.length + 1);

    // Automatically assign a cell group based on address
    let assignedCellGroup = "General";
    for (let branch of branches) {
        for (let group of branch.cellGroups) {
            if (address.toLowerCase().includes(group.toLowerCase())) {
                assignedCellGroup = group;
                break;
            }
        }
    }

    // Determine branch based on assigned cell group
    const assignedBranch = branches.find(branch => branch.cellGroups.includes(assignedCellGroup)).name;

    // Add the new member to the members array
    const newMember = {
        id: newMemberId,
        name,
        address,
        phone,
        email,
        dob,
        maritalStatus,
        cellGroup: assignedCellGroup,
        branch: assignedBranch,
        attendance: "0%",
    };

    members.push(newMember);

    // Display success message
    alert(`Member added successfully!\nMember ID: ${newMemberId}\nAssigned Cell Group: ${assignedCellGroup}\nAssigned Branch: ${assignedBranch}`);

    // Reset the form
    document.getElementById("add-member-form").reset();
}

// Event listener for form submission
document.getElementById("add-member-form").addEventListener("submit", addMember);

// Load cell groups on page load
window.onload = loadCellGroups;


// Fetch branches and populate dropdown
fetch("http://localhost:5000/branches")
    .then((response) => response.json())
    .then((data) => {
        const branchList = document.getElementById("branch-list");
        data.forEach((branch) => {
            const branchDiv = document.createElement("div");
            branchDiv.classList.add("branch");
            branchDiv.innerHTML = `<h3>${branch.branch_name}</h3><p>Location: ${branch.location}</p>`;
            branchList.appendChild(branchDiv);
        });
    })
    .catch((err) => console.error("Error loading branches:", err));


    document.getElementById("add-member-form").addEventListener("submit", (e) => {
        e.preventDefault();
    
        const memberData = {
            name: document.getElementById("name").value,
            address: document.getElementById("address").value,
            phone: document.getElementById("phone").value,
            email: document.getElementById("email").value,
            dob: document.getElementById("dob").value,
            marital_status: document.getElementById("marital-status").value,
            cell_group_id: document.getElementById("cell-group").value,
        };
    
        fetch("http://localhost:5000/add-member", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(memberData),
        })
            .then((response) => response.json())
            .then((data) => {
                alert(data.message);
            })
            .catch((err) => console.error("Error adding member:", err));
    });

    



    const express = require('express');
const bodyParser = require('body-parser');
const mongoose = require('mongoose');

const app = express();

// Middleware
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// MongoDB Connection
mongoose.connect('mongodb://localhost:27017/churchdb', {
    useNewUrlParser: true,
    useUnifiedTopology: true,
});

const memberSchema = new mongoose.Schema({
    name: String,
    address: String,
    phone: String,
    email: String,
    dob: Date,
    maritalStatus: String,
    cellGroup: String,
});

const Member = mongoose.model('Member', memberSchema);

// API Endpoint to Add Member
app.post('/add-member', async (req, res) => {
    try {
        const newMember = new Member(req.body);
        await newMember.save();
        res.status(201).json({ message: 'Member added successfully!' });
    } catch (error) {
        res.status(400).json({ error: error.message });
    }
});

// Start Server
const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});




document.getElementById('add-member-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const memberData = {
        name: document.getElementById('name').value,
        address: document.getElementById('address').value,
        phone: document.getElementById('phone').value,
        email: document.getElementById('email').value,
        dob: document.getElementById('dob').value,
        maritalStatus: document.getElementById('marital-status').value,
        cellGroup: document.getElementById('cell-group').value,
    };

    try {
        const response = await fetch('http://localhost:3000/add-member', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(memberData),
        });

        if (response.ok) {
            alert('Member added successfully!');
            e.target.reset(); // Clear form
        } else {
            const errorData = await response.json();
            alert(`Error: ${errorData.error}`);
        }
    } catch (error) {
        console.error('Error adding member:', error);
        alert('Error adding member. Please try again.');
    }
});

const cellGroupSelect = document.getElementById('cell-group');

// Populate cell groups dynamically
branches.forEach(branch => {
    branch.cellGroups.forEach(group => {
        const option = document.createElement('option');
        option.value = group;
        option.textContent = `${branch.name} - ${group}`;
        cellGroupSelect.appendChild(option);
    });
});



document.getElementById('add-member-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    // Get form data
    const name = document.getElementById('name').value;
    const address = document.getElementById('address').value;
    const phone = document.getElementById('phone').value;
    const email = document.getElementById('email').value;
    const dob = document.getElementById('dob').value;
    const maritalStatus = document.getElementById('marital-status').value;
    const cellGroup = document.getElementById('cell-group').value;

    // Validate if required fields are filled
    if (!name || !address || !phone || !maritalStatus) {
        alert('Please fill in all required fields.');
        return;
    }

    // Here, you would usually send the data to a server (using Fetch API, AJAX, etc.)
    console.log({
        name, address, phone, email, dob, maritalStatus, cellGroup
    });

    // Simulate a successful member addition
    alert('Member added successfully!');
});



document.getElementById('add-member-form').addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Form submitted!');
});



document.getElementById('add-member-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    // Get form data
    const name = document.getElementById('name').value;
    const address = document.getElementById('address').value;
    const phone = document.getElementById('phone').value;
    const email = document.getElementById('email').value;
    const dob = document.getElementById('dob').value;
    const maritalStatus = document.getElementById('marital-status').value;
    const cellGroup = document.getElementById('cell-group').value;

    // Validate required fields
    if (!name || !address || !phone || !maritalStatus) {
        alert('Please fill in all required fields.');
        return;
    }

    // Prepare data to send to server
    const formData = new FormData();
    formData.append('name', name);
    formData.append('address', address);
    formData.append('phone', phone);
    formData.append('email', email);
    formData.append('dob', dob);
    formData.append('marital_status', maritalStatus);
    formData.append('cell_group', cellGroup);

    // Send data to the backend (PHP script)
    fetch('add_member.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Member added successfully!');
        } else {
            alert('Error adding member: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error adding the member.');
    });
});

function showMemberForm(memberId) {
    const member = members.find(m => m.id === memberId);
    if (member) {
        // Create a new member details card
        const memberCard = document.createElement('div');
        memberCard.style.backgroundColor = "#fff";
        memberCard.style.padding = "20px";
        memberCard.style.margin = "20px";
        memberCard.style.boxShadow = "0 0 10px rgba(0, 0, 0, 0.2)";
        memberCard.style.borderRadius = "8px";
        memberCard.innerHTML = `
            <h2>Member Details</h2>
            <p><strong>ID:</strong> ${member.id}</p>
            <p><strong>Name:</strong> ${member.name}</p>
            <p><strong>Marital Status:</strong> ${member.maritalStatus}</p>
            <p><strong>Address:</strong> ${member.address}</p>
            <p><strong>Contact:</strong> ${member.contact}</p>
            <p><strong>Cell Group:</strong> ${member.cellGroup}</p>
            <p><strong>Contributions:</strong> ${member.contributions}</p>
            <button onclick="closeMemberForm()">Close</button>
        `;
        
        // Append the card to the body
        document.body.appendChild(memberCard);
    } else {
        alert("Member not found!");
    }
}

// Function to close the member form
function closeMemberForm() {
    const memberCard = document.querySelector('div');
    if (memberCard) {
        memberCard.remove();
    }
}














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












