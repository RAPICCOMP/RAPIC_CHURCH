const months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
let startYear = new Date().getFullYear();
let yearRange = 5;

function generateContributions(yearStart, memberId) {
    const contributionsDiv = document.getElementById('contributions');
    contributionsDiv.innerHTML = '';
    const currentYear = yearStart + yearRange - 1;

    // Fetch existing contributions from the database for the member
    fetch(`fetch_bethel_contributions.php?memberId=${memberId}`)
        .then(response => response.json())
        .then(data => {
            const tickedMonths = data; // Contains the months and years that are already ticked

            for (let year = yearStart; year <= currentYear; year++) {
                const yearDiv = document.createElement('div');
                yearDiv.className = 'year-section';
                yearDiv.innerHTML = `<h5>${year}</h5>`;

                const monthsContainer = document.createElement('div');
                monthsContainer.className = 'months-container';

                months.forEach(month => {
                    const isChecked = tickedMonths.some(item => item.month.toUpperCase() === month && item.year === year);
                    const monthCheckbox = document.createElement('div');
                    monthCheckbox.innerHTML = `
                        <label>
                            <input type="checkbox" data-year="${year}" data-month="${month}" ${isChecked ? 'checked' : ''}/> ${month}
                        </label>
                    `;
                    monthsContainer.appendChild(monthCheckbox);
                });

                yearDiv.appendChild(monthsContainer);
                contributionsDiv.appendChild(yearDiv);
            }
        })
        .catch(error => console.error('Error fetching contributions:', error));
}

// The rest of your code remains the same.


    function populateMemberTable() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_bethel_member.php", true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const members = JSON.parse(xhr.responseText);
                const tableBody = document.getElementById('memberTable').querySelector('tbody');
                tableBody.innerHTML = '';
                members.forEach(member => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><a href="#" class="view-card" data-id="${member.id}">${member.id}</a></td>
                        <td>${member.name}</td>
                        <td>
                            <button class="view-card" data-id="${member.id}">View Card</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
    
                // Add event listeners for the "View Card" buttons
                const viewButtons = document.querySelectorAll('.view-card');
                viewButtons.forEach(button => {
                    button.addEventListener('click', function(event) {
                        const memberId = this.getAttribute('data-id');
                        viewMembershipCard(memberId); // Call the function to show the card
                    });
                });
            } else {
                alert("Error fetching members.");
            }
        };
        xhr.send();
    }
    

    // Call this when viewing the membership card to ensure the correct months are ticked
    function viewMembershipCard(memberId) {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `fetch_bethel_member.php?id=${memberId}`, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const member = JSON.parse(xhr.responseText);
                console.log(member); // Check if member data is being fetched correctly
                document.getElementById('cardNumber').innerText = `# ${member.id}`;
                document.getElementById('memberName').innerText = member.name;
                document.getElementById('memberDOB').innerText = member.dob;
                document.getElementById('memberGender').innerText = member.gender;
                document.getElementById('memberJoining').innerText = member.joining || 'Date not available';
                document.getElementById('memberNationality').innerText = member.nationality;
                document.getElementById('memberTown').innerText = member.town;
                document.getElementById('memberHouse').innerText = member.house || 'House number not available'; 
                document.getElementById('membershipCard').style.display = 'block';
    
                // Generate contributions and load the checked months for this member
                generateContributions(startYear, memberId);
            } else {
                alert("Error fetching member details.");
            }
        };
        xhr.send();
    }
    

    function renewCard() {
        const allChecked = [...document.querySelectorAll('#contributions input[type="checkbox"]')].every(cb => cb.checked);
        if (allChecked) {
            startYear += yearRange;
            generateContributions(startYear);
        } else {
            alert('Please complete all checkboxes to renew the card.');
        }
    }

    function closeCard() {
        document.getElementById('membershipCard').style.display = 'none';
    }

    function addNewMember() {
        const newMember = {
            name: prompt("Enter Member Name:"),
            dob: prompt("Enter Date of Birth (YYYY-MM-DD):"),
            gender: prompt("Enter Gender:"),
            joining: prompt("Enter Year of Joining:"),
            nationality: prompt("Enter Nationality:"),
            town: prompt("Enter Town:"),
            house: prompt("Enter House No.:")
        };

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "add_bethel_member.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert(xhr.responseText);
                populateMemberTable();
            } else {
                alert("Error adding member.");
            }
        };
        xhr.send(
            `name=${newMember.name}&dob=${newMember.dob}&gender=${newMember.gender}&joining=${newMember.joining}&nationality=${newMember.nationality}&town=${newMember.town}&house=${newMember.house}`
        );
    }

    document.getElementById('contributions').addEventListener('change', function(e) {
        if (e.target.type === 'checkbox') {
            const year = e.target.dataset.year;
            const month = e.target.dataset.month;
            const isChecked = e.target.checked;
            const memberId = document.getElementById('cardNumber').innerText.replace('# ', '');
    
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_bethel_contribution.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                } else {
                    alert("Error updating contributions.");
                }
            };
            xhr.send(`memberId=${memberId}&year=${year}&month=${month}&isChecked=${isChecked ? 1 : 0}`);
        }
    });
    

    populateMemberTable();
