<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];

// Fetch Queue
$stmt = $conn->prepare("SELECT a.*, u.full_name as patient_name, u.username as student_id 
                        FROM appointments a 
                        JOIN users u ON a.patient_id = u.id 
                        WHERE a.doctor_id = ? AND a.status IN ('pending', 'at_lab')
                        ORDER BY a.appointment_date ASC");
$stmt->execute([$doctor_id]);
$appointments = $stmt->fetchAll();

// Fetch Completed Labs
$res_stmt = $conn->prepare("SELECT lr.*, u.full_name as patient_name 
                            FROM lab_requests lr 
                            JOIN users u ON lr.patient_id = u.id 
                            WHERE lr.doctor_id = ? AND lr.status = 'completed' 
                            ORDER BY lr.created_at DESC");
$res_stmt->execute([$doctor_id]);
$completed_labs = $res_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Workspace | Bonga Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --p: #34c759; --bg: #f5f5f7; --accent: #0071e3; --lab: #5856d6; --danger: #ff3b30; --history: #af52de; }
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); display: flex; height: 100vh; color: #1d1d1f; overflow: hidden; }
        .sidebar { width: 260px; background: #fff; padding: 25px; border-right: 1px solid #e1e1e7; display: flex; flex-direction: column; }
        .nav-link { padding: 12px; text-decoration: none; color: #6e6e73; font-weight: 600; border-radius: 10px; margin-bottom: 5px; cursor: pointer; border: none; background: none; text-align: left; width: 100%; }
        .nav-link.active { background: rgba(52, 199, 89, 0.1); color: var(--p); }
        .lab-btn { background: var(--lab) !important; color: white !important; margin-top: 10px; position: relative; }
        .badge-count { position: absolute; right: 10px; background: var(--danger); color: white; padding: 2px 7px; border-radius: 50%; font-size: 0.7rem; }
        .content { flex: 1; padding: 25px; overflow-y: auto; }
        .main-grid { display: grid; grid-template-columns: 300px 1fr 320px; gap: 20px; height: calc(100vh - 120px); }
        .card { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); display: flex; flex-direction: column; border: 1px solid #eee; }
        .scroll-area { flex: 1; overflow-y: auto; margin-top: 10px; padding-right: 5px; }
        .patient-box { padding: 15px; background: #f8f9fa; border-radius: 12px; margin-bottom: 10px; border: 1px solid #eee; cursor: pointer; transition: 0.2s; }
        .patient-box:hover { border-color: var(--p); background: #fff; }
        input, textarea, select { width: 100%; padding: 12px; margin: 8px 0; border-radius: 8px; border: 1px solid #ddd; background: #fcfcfc; font-size: 0.9rem; }
        .btn-submit { background: #1d1d1f; color: white; padding: 15px; border: none; border-radius: 10px; width: 100%; font-weight: 700; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        #labOverlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center; backdrop-filter: blur(4px); }
        .modal { background:white; padding:30px; border-radius:20px; width:650px; max-height:80vh; overflow-y:auto; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2 style="color: var(--p); font-weight: 800; margin-bottom: 25px;"><i class="fa-solid fa-house-medical"></i> Clinic OS</h2>
        <a class="nav-link active"><i class="fa-solid fa-user-doctor"></i> Consultation</a>
        <button onclick="document.getElementById('labOverlay').style.display='flex'" class="nav-link lab-btn">
            <i class="fa-solid fa-flask-vial"></i> Lab Results 
            <?php if(count($completed_labs) > 0): ?> <span class="badge-count"><?= count($completed_labs) ?></span> <?php endif; ?>
        </button>
        <div style="margin-top: auto;">
            <a href="../auth/logout.php" class="nav-link" style="color:var(--danger)"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </div>

    <div class="content">
        <header style="margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-weight: 800;">Clinical Workspace</h1>
                <p style="color: #666; font-weight: 500;"><?= date('l, d M Y') ?> | <span id="clock"></span></p>
            </div>
            <div style="text-align: right;">
                <span style="background: #eef; color: var(--accent); padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">Dr. <?= htmlspecialchars($_SESSION['full_name']) ?></span>
            </div>
        </header>

        <div class="main-grid">
            <div class="card">
                <h3><i class="fa-solid fa-users-rectangle"></i> Waiting Room</h3>
                <div class="scroll-area">
                    <?php foreach($appointments as $apt): ?>
                        <div class="patient-box" onclick="selectPatient(<?= $apt['patient_id'] ?>, <?= $apt['id'] ?>, '<?= addslashes($apt['patient_name']) ?>')">
                            <strong><?= htmlspecialchars($apt['patient_name']) ?></strong><br>
                            <small style="color:var(--accent)">ID: <?= htmlspecialchars($apt['student_id']) ?></small>
                            <?php if($apt['status'] == 'at_lab'): ?> <br><small style="color:var(--lab); font-weight:bold;">[ AT LAB ]</small> <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card">
                <h3><i class="fa-solid fa-notes-medical"></i> Consultation</h3>
                <form id="consultForm">
                    <input type="hidden" name="patient_id" id="current_patient_id">
                    <input type="hidden" name="apt_id" id="current_appointment_id">
                    
                    <input type="text" id="display_p_name" readonly placeholder="Select a patient..." style="background:#f0f7ff; border:none; color:var(--accent); font-weight:700;">

                    <label style="font-weight:700; font-size:0.75rem;">DIAGNOSIS</label>
                    <textarea name="diagnosis" id="diag_field" rows="4" placeholder="Enter findings..." required></textarea>

                    <label style="font-weight:700; font-size:0.75rem;">ACTION</label>
                    <select name="disposition" id="dispositionSelect" onchange="toggleExtraFields()">
                        <option value="discharge">Finalize & Discharge</option>
                        <option value="lab">Send to Laboratory</option>
                        <option value="referral">External Referral</option>
                    </select>

                    <div id="labSection" style="display:none; background:#f4f4f9; padding:10px; border-radius:10px;">
                        <select name="lab_request">
                            <option value="">-- Select Lab Test --</option>
                            <option value="Malaria RDT">Malaria RDT</option>
                            <option value="CBC">Full Blood Count</option>
                            <option value="Urinalysis">Urinalysis</option>
                        </select>
                    </div>

                    <label style="font-weight:700; font-size:0.75rem;">PRESCRIPTION</label>
                    <textarea name="prescription" id="presc_field" rows="3" placeholder="Medications..."></textarea>

                    <button type="submit" class="btn-submit">SAVE & COMPLETE VISIT</button>
                </form>
            </div>

            <div class="card">
                <h3><i class="fa-solid fa-clock-rotate-left"></i> Medical History</h3>
                <div id="historyBox" class="scroll-area">
                    <p style="text-align:center; color:#999; margin-top:50px;">Select patient to view history.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="labOverlay" onclick="if(event.target == this) this.style.display='none'">
        <div class="modal">
            <h2 style="color:var(--lab);"><i class="fa-solid fa-flask"></i> Laboratory Results</h2>
            <hr><br>
            <?php if(empty($completed_labs)): ?> <p>No results ready.</p> <?php else: ?>
                <table style="width:100%; border-collapse:collapse;">
                    <?php foreach($completed_labs as $lab): ?>
                        <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:10px;"><b><?= $lab['patient_name'] ?></b><br><small><?= $lab['test_name'] ?></small></td>
                            <td style="padding:10px; color:var(--danger); font-weight:bold;"><?= $lab['test_results'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script>
        setInterval(() => { document.getElementById('clock').innerText = new Date().toLocaleTimeString(); }, 1000);

        function toggleExtraFields() {
            const val = document.getElementById('dispositionSelect').value;
            document.getElementById('labSection').style.display = (val === 'lab') ? 'block' : 'none';
        }

        async function selectPatient(patientId, appointmentId, patientName) {
            // Setup UI
            document.getElementById('current_patient_id').value = patientId;
            document.getElementById('current_appointment_id').value = appointmentId;
            document.getElementById('display_p_name').value = "Examining: " + patientName;

            const hBox = document.getElementById('historyBox');
            hBox.innerHTML = '<p style="padding:20px; color:#888;">Loading history...</p>';

            try {
                // Fetch Data
                const response = await fetch(`get_history_ajax.php?patient_id=${patientId}`);
                
                if (!response.ok) {
                    throw new Error(`Server returned status ${response.status}`);
                }

                const data = await response.json();
                hBox.innerHTML = ""; 
                
                if (!data || data.length === 0) {
                    hBox.innerHTML = '<p style="padding:20px; color:#888; text-align:center;">No previous records found.</p>';
                } else {
                    data.forEach(item => {
                        hBox.innerHTML += `
                            <div style="padding:15px; border-left:4px solid var(--history); background:#f9f9fb; border-radius:8px; margin-bottom:12px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                    <b style="color:var(--history); font-size:0.8rem;">${item.visit_date}</b>
                                    <span style="background:#eee; padding:2px 6px; border-radius:4px; font-size:0.65rem;">Dr. ${item.doctor_name || 'Staff'}</span>
                                </div>
                                <div style="font-size:0.85rem;"><strong>D:</strong> ${item.diagnosis}</div>
                                <div style="font-size:0.8rem; color:#555; margin-top:5px;"><strong>P:</strong> ${item.prescription || 'N/A'}</div>
                            </div>`;
                    });
                }
            } catch (err) {
                console.error("History Load Error:", err);
                hBox.innerHTML = `<p style="color:red; padding:20px;">Failed to load history. (Error: ${err.message})</p>`;
            }
        }

        document.getElementById('consultForm').onsubmit = async (e) => {
            e.preventDefault();
            if(!document.getElementById('current_patient_id').value) { alert("Please select a patient!"); return; }
            
            const formData = new FormData(e.target);
            try {
                const res = await fetch('save_record.php', { method: 'POST', body: formData });
                const result = await res.json();
                if(result.success) { 
                    alert("Visit Processed Successfully"); 
                    location.reload(); 
                } else { 
                    alert("Error: " + result.message); 
                }
            } catch (err) { 
                alert("Critical Error: Connection failed."); 
            }
        };
    </script>
</body>
</html>