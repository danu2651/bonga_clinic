<?php
session_start();
// Verify session and role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php"); 
    exit;
}

// Database Connection (Ensure this path is correct)
require_once('../includes/db.php');

$student_id = $_SESSION['user_id'];

// 1. Fetch Student Info (The "Reception Card" data)
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

// 2. Fetch Lab Results
$lab_stmt = $conn->prepare("SELECT * FROM lab_requests WHERE patient_id = ? ORDER BY created_at DESC");
$lab_stmt->execute([$student_id]);
$lab_results = $lab_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal | Bonga University</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --p: #0071e3; 
            --bg: #f5f5f7; 
            --t: #1d1d1f;
            --glass: rgba(255, 255, 255, 0.8);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg); 
            color: var(--t); 
            display: flex; 
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar Styling */
        .sidebar { 
            width: 280px; 
            background: #fff; 
            padding: 40px 25px; 
            display: flex; 
            flex-direction: column; 
            border-right: 1px solid rgba(0,0,0,0.05); 
        }
        .logo { font-weight: 800; font-size: 1.4rem; margin-bottom: 50px; text-decoration: none; color: #000; }
        .logo span { color: var(--p); }

        .nav-link { 
            padding: 14px 18px; 
            text-decoration: none; 
            color: #86868b; 
            font-weight: 600; 
            display: flex; 
            align-items: center; 
            gap: 12px;
            border-radius: 14px; 
            margin-bottom: 8px;
            transition: 0.3s;
        }
        .nav-link:hover, .nav-link.active { background: #f5f5f7; color: var(--p); }

        /* Main Content area */
        .main-content { flex: 1; padding: 40px; overflow-y: auto; }

        /* Bento Grid Layout */
        .portal-grid { 
            display: grid; 
            grid-template-columns: 1.5fr 1fr; 
            gap: 30px; 
        }

        /* Digital ID Card (From Reception) */
        .id-card {
            background: linear-gradient(135deg, #1d1d1f, #434343);
            color: white;
            padding: 30px;
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            margin-bottom: 30px;
        }
        .id-card::after {
            content: ""; position: absolute; top: -50px; right: -50px;
            width: 150px; height: 150px; background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .id-card .univ { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.6; }
        .id-card .name { font-size: 1.8rem; font-weight: 800; margin: 15px 0; }
        .id-card .details { display: flex; gap: 30px; font-size: 0.85rem; }
        .id-card .details span { opacity: 0.7; display: block; margin-bottom: 4px; }

        /* General Section Card */
        .section-card {
            background: #fff;
            padding: 30px;
            border-radius: 28px;
            border: 1px solid rgba(0,0,0,0.03);
            margin-bottom: 30px;
        }
        .section-card h3 { margin-bottom: 20px; font-weight: 800; display: flex; align-items: center; gap: 10px; }

        /* Lab Results Table */
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; color: #86868b; font-size: 0.8rem; text-transform: uppercase; border-bottom: 1px solid #f5f5f7; }
        td { padding: 15px; border-bottom: 1px solid #f5f5f7; font-size: 0.95rem; }
        .status-badge { 
            padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;
        }
        .status-pending { background: #fff8e1; color: #f57f17; }
        .status-ready { background: #e8f5e9; color: #2e7d32; }

        @media (max-width: 900px) { .portal-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <aside class="sidebar">
        <a href="#" class="logo">BONGA<span>CLINIC</span></a>
        
        <nav>
            <a href="#" class="nav-link active"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-calendar-alt"></i> Appointments</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-flask"></i> Lab Results</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-user-gear"></i> Profile Settings</a>
        </nav>

        <div style="margin-top: auto;">
            <a href="../auth/logout.php" class="nav-link" style="color: #ff3b30;">
                <i class="fa-solid fa-power-off"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="portal-grid">
            
            <div class="left-col">
                <div style="margin-bottom: 30px;">
                    <h1 style="font-size: 2.2rem; font-weight: 800;">Hello, <?php echo explode(' ', $_SESSION['full_name'])[0]; ?>!</h1>
                    <p style="color: #86868b;">Here is your health overview for today.</p>
                </div>

                <div class="id-card">
                    <p class="univ">Bonga University Student Health Card</p>
                    <h2 class="name"><?php echo $_SESSION['full_name']; ?></h2>
                    <div class="details">
                        <div><span>Student ID</span><strong><?php echo $_SESSION['user_id']; ?></strong></div>
                        <div><span>Status</span><strong>Active Member</strong></div>
                        <div><span>Joined</span><strong>2026</strong></div>
                    </div>
                </div>

                <div class="section-card">
                    <h3><i class="fa-solid fa-microscope" style="color:var(--p)"></i> Recent Lab Tests</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Test Name</th>
                                <th>Date Requested</th>
                                <th>Status</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($lab_results)): ?>
                                <tr><td colspan="4" style="text-align:center; color:#86868b; padding:40px;">No lab records found.</td></tr>
                            <?php else: ?>
                                <?php foreach($lab_results as $res): ?>
                                <tr>
                                    <td><strong><?php echo $res['test_name']; ?></strong></td>
                                    <td><?php echo date('M d, Y', strtotime($res['created_at'])); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo ($res['status'] == 'completed') ? 'status-ready' : 'status-pending'; ?>">
                                            <?php echo ucfirst($res['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $res['result'] ?? '---'; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="right-col">
                <div class="section-card" style="background: var(--p); color: #fff;">
                    <h3 style="color:#fff">Quick Stats</h3>
                    <div style="display:grid; gap:15px; margin-top:10px;">
                        <div style="background:rgba(255,255,255,0.1); padding:15px; border-radius:15px;">
                            <span style="font-size:0.75rem; opacity:0.8;">Blood Group</span>
                            <p style="font-size:1.5rem; font-weight:800;">O+</p>
                        </div>
                        <div style="background:rgba(255,255,255,0.1); padding:15px; border-radius:15px;">
                            <span style="font-size:0.75rem; opacity:0.8;">Last Visit</span>
                            <p style="font-size:1.1rem; font-weight:700;">Never</p>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <h3>Health Tips</h3>
                    <div style="background: #fdf2f2; padding: 20px; border-radius: 20px; border-left: 5px solid #ff3b30;">
                        <p style="font-size: 0.9rem; color: #ff3b30; font-weight: 700;">Alert</p>
                        <p style="font-size: 0.85rem; margin-top: 5px;">Flu season is active on campus. Remember to wash your hands frequently.</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>