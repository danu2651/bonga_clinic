<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
    header("Location: ../auth/login.php"); exit;
}

// Fetch Students and Doctors
$students = $conn->query("SELECT id, full_name, username, phone, department, blood_group FROM users WHERE role = 'student' ORDER BY created_at DESC")->fetchAll();
$doctors  = $conn->query("SELECT id, full_name FROM users WHERE role = 'doctor' ORDER BY full_name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reception Central | Bonga University Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --p: #af52de; --p-dark: #7d3ca3; --p-light: #f3e8ff; --bg: #f5f5f7; }
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: #1d1d1f; display: flex; height: 100vh; overflow: hidden; }
        .sidebar { width: 280px; background: #fff; padding: 30px; border-right: 1px solid #e1e1e7; display: flex; flex-direction: column; }
        .content { flex: 1; padding: 40px; overflow-y: auto; }
        .nav-btn { padding: 15px; text-decoration: none; color: #6e6e73; font-weight: 600; border-radius: 12px; margin-bottom: 8px; cursor: pointer; transition: 0.3s; display: block; border:none; background:none; width:100%; text-align:left; }
        .nav-btn.active { background: rgba(175, 82, 222, 0.1); color: var(--p); }
        .grid { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 30px; }
        .card { background: #fff; border-radius: 28px; padding: 30px; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        .form-group { margin-bottom: 15px; }
        label { font-size: 0.8rem; font-weight: 700; color: #86868b; margin-left: 5px; }
        input, select, textarea { width: 100%; padding: 14px; margin-top: 5px; border-radius: 14px; border: 1.5px solid #f2f2f7; background: #fbfbfd; outline: none; transition: 0.2s; }
        input:focus { border-color: var(--p); background: #fff; }
        #patientSearchInput { border-radius: 14px 14px 0 0; border-bottom: none; margin-bottom: 0; border-color: var(--p); }
        #patientSelect { border-radius: 0 0 14px 14px; border-top: 1px solid #eee; height: 180px !important; }
        #patientSelect option { padding: 12px; border-bottom: 1px solid #f9f9fb; cursor: pointer; }
        .btn-main { background: var(--p); color: white; border: none; padding: 16px; border-radius: 16px; width: 100%; font-weight: 800; cursor: pointer; transition: 0.3s; }
        .table-container { margin-top: 20px; max-height: 500px; overflow-y: auto; border-radius: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th { position: sticky; top: 0; background: #fff; padding: 15px; text-align: left; font-size: 0.75rem; color: #86868b; text-transform: uppercase; border-bottom: 2px solid #f5f5f7; }
        td { padding: 15px; border-bottom: 1px solid #f5f5f7; font-size: 0.9rem; }
        .badge { padding: 4px 10px; border-radius: 8px; font-weight: 700; font-size: 0.75rem; background: #f2f2f7; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px); }
        .modal-content { background: #fff; margin: 2% auto; padding: 35px; border-radius: 32px; width: 500px; box-shadow: 0 30px 60px rgba(0,0,0,0.15); }
        .medical-card-preview { background: linear-gradient(135deg, #1d1d1f 0%, #434343 100%); color: white; padding: 30px; border-radius: 24px; position: relative; overflow: hidden; margin: 20px 0; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <h2 style="font-weight: 800; margin-bottom: 40px; color:var(--p)">BONGA<span style="color:#1d1d1f">CLINIC</span></h2>
        <button class="nav-btn active"><i class="fa-solid fa-house-chimney-user"></i> Reception Desk</button>
        <button class="nav-btn" onclick="openModal('regModal')"><i class="fa-solid fa-plus-circle"></i> New Registration</button>
        <div style="margin-top: auto;">
            <a href="../auth/logout.php" class="nav-btn" style="color: #ff3b30;"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </aside>

    <main class="content">
        <div class="grid">
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:center">
                    <h3><i class="fa-solid fa-address-book" style="color:var(--p)"></i> Patient Directory</h3>
                    <input type="text" id="dirSearch" placeholder="Search Directory..." onkeyup="filterDirectory()" style="width:250px; margin:0">
                </div>
                <div class="table-container">
                    <table id="dirTable">
                        <thead>
                            <tr><th>Student Name</th><th>Dept</th><th>Blood</th><th>ID</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($students as $s): ?>
                            <tr>
                                <td><strong><?= $s['full_name'] ?></strong></td>
                                <td><span class="badge"><?= $s['department'] ?></span></td>
                                <td style="color:#ff3b30;"><?= $s['blood_group'] ?></td>
                                <td style="font-family:monospace;"><?= $s['username'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h3><i class="fa-solid fa-calendar-check" style="color:var(--p)"></i> Book Visit</h3>
                <form id="bookAptForm">
                    <div class="form-group">
                        <label>Quick Search Patient</label>
                        <input type="text" id="patientSearchInput" placeholder="Filter..." onkeyup="filterPatientSelect()">
                        <select name="patient_id" id="patientSelect" size="5" required>
                            <?php foreach($students as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?> (<?= $s['username'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Assign Doctor</label>
                        <select name="doctor_id" required>
                            <?php foreach($doctors as $d): ?>
                                <option value="<?= $d['id'] ?>">Dr. <?= $d['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Reason for Consultation</label>
                        <textarea name="reason" rows="2" required></textarea>
                    </div>
                    <button type="submit" class="btn-main">Send to Doctor</button>
                </form>
            </div>
        </div>
    </main>

    <div id="regModal" class="modal">
        <div class="modal-content">
            <h2 style="font-weight:800;">New Student Enrollment</h2>
            <form id="fullRegForm">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="text" name="username" placeholder="Student ID (e.g. BGR/100/24)" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <select name="department" required>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Health Science">Health Science</option>
                    <option value="Engineering">Engineering</option>
                </select>
                <select name="blood_group">
                    <option value="A+">A+</option><option value="O+">O+</option><option value="B+">B+</option>
                </select>
                <button type="submit" class="btn-main" style="margin-top:20px;">Register Student</button>
                <button type="button" onclick="closeModal('regModal')" style="width:100%; background:none; border:none; color:#888; margin-top:10px;">Cancel</button>
            </form>
        </div>
    </div>

    <div id="cardModal" class="modal">
        <div class="modal-content" style="text-align: center;">
            <div id="printArea">
                <div class="medical-card-preview">
                    <div style="text-align:left; font-size:0.6rem; opacity:0.7">Bonga University Clinic</div>
                    <h2 id="cardName" style="text-align:left; margin-top:10px;">NAME</h2>
                    <div style="display:flex; justify-content:space-between; margin-top:20px;">
                        <div style="text-align:left"><small>ID NUMBER</small><div id="cardID" style="font-weight:800">000</div></div>
                        <div style="text-align:right"><small>DEPT</small><div id="cardDept" style="font-weight:700">DEPT</div></div>
                    </div>
                </div>
            </div>
            <button class="btn-main" onclick="window.print()"><i class="fa-solid fa-print"></i> Print Card</button>
            <button class="btn-main" onclick="location.reload()" style="margin-top:10px; background:#888">Close</button>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).style.display = 'block'; }
        function closeModal(id) { document.getElementById(id).style.display = 'none'; }

        function filterDirectory() {
            let val = document.getElementById('dirSearch').value.toUpperCase();
            let rows = document.getElementById('dirTable').rows;
            for (let i = 1; i < rows.length; i++) {
                rows[i].style.display = rows[i].innerText.toUpperCase().includes(val) ? "" : "none";
            }
        }

        function filterPatientSelect() {
            let filter = document.getElementById('patientSearchInput').value.toUpperCase();
            let options = document.getElementById('patientSelect').getElementsByTagName('option');
            for (let i = 0; i < options.length; i++) {
                options[i].style.display = options[i].innerText.toUpperCase().includes(filter) ? "" : "none";
            }
        }

        // AJAX for Booking Appointment
        document.getElementById('bookAptForm').onsubmit = async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            try {
                const response = await fetch('book_appointment.php', { method: 'POST', body: formData });
                // We use text() first in case book_appointment.php sends a redirect or raw string
                const result = await response.text(); 
                alert("Patient successfully moved to Doctor's Queue!");
                location.reload();
            } catch (error) {
                alert("Connection Error.");
            }
        };

        // AJAX for Registration
        document.getElementById('fullRegForm').onsubmit = async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const response = await fetch('process_full_reg.php', { method: 'POST', body: formData });
            const result = await response.json();

            if(result.success) {
                document.getElementById('cardName').innerText = formData.get('full_name');
                document.getElementById('cardID').innerText = formData.get('username');
                document.getElementById('cardDept').innerText = formData.get('department');
                closeModal('regModal');
                openModal('cardModal');
            } else {
                alert("Registration Failed: " + result.message);
            }
        };
    </script>
</body>
</html>