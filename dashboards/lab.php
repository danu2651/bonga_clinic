<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'lab') {
    header("Location: ../auth/login.php"); 
    exit;
}

try {
    $stmt = $conn->query("SELECT lr.*, u.full_name as patient_name, u.username as student_id, d.full_name as doctor_name 
                          FROM lab_requests lr 
                          JOIN users u ON lr.patient_id = u.id 
                          JOIN users d ON lr.doctor_id = d.id 
                          WHERE lr.status = 'pending' 
                          ORDER BY lr.created_at DESC");
    $requests = $stmt->fetchAll();
} catch (PDOException $e) {
    $requests = []; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Terminal | Bonga University</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --p: #0071e3; 
            --s: #5856d6; 
            --bg: #f5f5f7; 
            --t: #1d1d1f;
            --ts: #86868b;
            --glass: rgba(255, 255, 255, 0.8);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--t); display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar - Modern Glass Effect */
        .sidebar { 
            width: 280px; 
            background: #fff; 
            padding: 40px 25px; 
            border-right: 1px solid rgba(0,0,0,0.05); 
            display: flex; 
            flex-direction: column; 
        }
        .logo { font-weight: 800; font-size: 1.4rem; color: var(--t); margin-bottom: 50px; text-decoration: none; }
        .logo span { color: var(--s); }

        .user-pill { 
            background: #fafafa; 
            padding: 15px; 
            border-radius: 20px; 
            margin-bottom: 30px; 
            border: 1px solid rgba(0,0,0,0.03);
        }

        .nav-link { 
            padding: 14px 18px; 
            text-decoration: none; 
            color: var(--ts); 
            font-weight: 600; 
            display: flex; 
            align-items: center; 
            gap: 12px;
            border-radius: 14px; 
            transition: 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .nav-link:hover, .nav-link.active { background: #f5f5f7; color: var(--s); }

        /* Content Area */
        .content { flex: 1; padding: 50px; overflow-y: auto; }
        .header { margin-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end; }

        /* Bento Grid */
        .grid { display: grid; grid-template-columns: 1fr 400px; gap: 30px; }

        .card { 
            background: #fff; 
            border-radius: 30px; 
            padding: 35px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.02); 
            border: 1px solid rgba(0,0,0,0.03);
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 0.75rem; color: var(--ts); text-transform: uppercase; letter-spacing: 1px; padding-bottom: 20px; }
        td { padding: 20px 0; border-top: 1px solid #f5f5f7; }

        .patient-info strong { display: block; font-size: 1rem; color: var(--t); }
        .patient-info small { color: var(--ts); }

        .test-tag { 
            background: #f0f0ff; 
            color: var(--s); 
            padding: 6px 14px; 
            border-radius: 10px; 
            font-size: 0.8rem; 
            font-weight: 700; 
        }

        .btn-action { 
            background: var(--t); 
            color: #fff; 
            border: none; 
            padding: 12px 24px; 
            border-radius: 12px; 
            font-weight: 700; 
            cursor: pointer; 
            transition: 0.3s;
        }
        .btn-action:hover { background: var(--s); transform: scale(1.02); }

        /* Processing Form */
        textarea { 
            width: 100%; background: #fafafa; border: 2px solid #f0f0f5; 
            border-radius: 20px; padding: 20px; font-size: 1rem; 
            margin: 20px 0; outline: none; transition: 0.3s;
        }
        textarea:focus { border-color: var(--s); background: #fff; }

        .alert-toast { 
            background: #1d1d1f; color: #fff; padding: 16px 24px; 
            border-radius: 20px; display: flex; align-items: center; gap: 12px;
            position: fixed; top: 30px; right: 30px; z-index: 100;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <a href="#" class="logo">BONGA<span>LAB</span></a>
        
        <div class="user-pill">
            <small style="color: var(--ts); font-weight: 700; text-transform: uppercase; font-size: 0.65rem;">Technician</small>
            <p style="font-weight: 700; margin-top: 4px;"><?= htmlspecialchars($_SESSION['full_name']) ?></p>
        </div>

        <nav style="flex: 1;">
            <a href="#" class="nav-link active"><i class="fa-solid fa-layer-group"></i> Active Queue</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-clock-rotate-left"></i> History</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-flask-vial"></i> Inventory</a>
        </nav>

        <a href="../auth/logout.php" class="nav-link" style="color: #ff3b30;">
            <i class="fa-solid fa-power-off"></i> Logout
        </a>
    </aside>

    <main class="content">
        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert-toast">
                <i class="fa-solid fa-circle-check" style="color: #34c759;"></i>
                <span>Results dispatched successfully.</span>
            </div>
        <?php endif; ?>

        <div class="header">
            <div>
                <h1 style="font-size: 2.2rem; font-weight: 800; letter-spacing: -1px;">Lab Worklist</h1>
                <p style="color: var(--ts); margin-top: 5px;">You have <strong><?= count($requests) ?></strong> tests awaiting processing.</p>
            </div>
            <div style="background: #fff; padding: 10px 20px; border-radius: 15px; border: 1px solid rgba(0,0,0,0.05);">
                <span id="clock" style="font-weight: 700; font-variant-numeric: tabular-nums;"></span>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <h3><i class="fa-solid fa-vials" style="color: var(--s); margin-right: 10px;"></i> Pending Samples</h3>
                
                <?php if(empty($requests)): ?>
                    <div style="text-align: center; padding: 60px 0;">
                        <img src="https://cdn-icons-png.flaticon.com/512/5058/5058432.png" style="width: 80px; opacity: 0.1; margin-bottom: 20px;">
                        <p style="color: var(--ts);">Clear worklist. No pending samples.</p>
                    </div>
                <?php else: ?>
                <table style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th>Patient Detail</th>
                            <th>Test Type</th>
                            <th>Requested By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($requests as $r): ?>
                        <tr>
                            <td class="patient-info">
                                <strong><?= htmlspecialchars($r['patient_name']) ?></strong>
                                <small><?= htmlspecialchars($r['student_id']) ?></small>
                            </td>
                            <td><span class="test-tag"><?= htmlspecialchars($r['test_name']) ?></span></td>
                            <td><span style="font-size: 0.9rem; font-weight: 600;">Dr. <?= htmlspecialchars($r['doctor_name']) ?></span></td>
                            <td>
                                <button class="btn-action" onclick="openLabForm(<?= $r['id'] ?>, '<?= addslashes($r['patient_name']) ?>', '<?= addslashes($r['test_name']) ?>')">
                                    Input Results
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>

            <div id="resultCard" style="display:none;">
                <div class="card" style="border: 2px solid var(--s); position: sticky; top: 0;">
                    <h3 style="margin-bottom: 5px;">Update Finding</h3>
                    <p style="color: var(--ts); font-size: 0.85rem; margin-bottom: 20px;">Finalize clinical observation for:</p>
                    
                    <div style="padding: 20px; background: #f5f5f7; border-radius: 20px; margin-bottom: 20px;">
                        <p id="target_patient" style="font-weight: 800; font-size: 1.1rem; color: var(--s);"></p>
                    </div>

                    <form action="submit_lab_results.php" method="POST">
                        <input type="hidden" name="request_id" id="request_id">
                        <label style="font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">Clinical Remarks</label>
                        <textarea name="test_results" rows="10" placeholder="Type detailed findings..." required></textarea>
                        
                        <button type="submit" class="btn-action" style="width: 100%; background: var(--s); padding: 18px;">
                            <i class="fa-solid fa-paper-plane" style="margin-right: 8px;"></i> Dispatch Results
                        </button>
                        
                        <button type="button" onclick="closeForm()" style="width: 100%; background: none; border: none; margin-top: 15px; color: var(--ts); font-weight: 600; cursor: pointer;">
                            Dismiss
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        function openLabForm(id, name, test) {
            document.getElementById('resultCard').style.display = 'block';
            document.getElementById('request_id').value = id;
            document.getElementById('target_patient').innerText = name + " [" + test + "]";
            document.querySelector('textarea').focus();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function closeForm() {
            document.getElementById('resultCard').style.display = 'none';
        }

        // Live Clock
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'});
        }, 1000);

        // Auto-hide toast
        setTimeout(() => {
            const toast = document.querySelector('.alert-toast');
            if(toast) toast.style.display = 'none';
        }, 4000);
    </script>
</body>
</html>