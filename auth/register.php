<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Bonga Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --p: #0071e3; --bg: #f8fafc; --t: #1d1d1f; --ts: #6e6e73; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(rgba(248,250,252,0.9), rgba(248,250,252,0.9)), 
                        url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&q=80&w=2000');
            background-size: cover; background-attachment: fixed; padding: 40px 20px;
        }
        .reg-container {
            background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); width: 100%;
            max-width: 850px; padding: 50px; border-radius: 40px; box-shadow: 0 40px 100px rgba(0,0,0,0.06);
            border: 1px solid rgba(255,255,255,0.6); text-align: center;
        }
        .logo { font-weight: 800; font-size: 1.4rem; color: var(--t); text-decoration: none; margin-bottom: 20px; display: inline-block; }
        .logo span { color: var(--p); }
        .logo i { color: var(--p); margin-right: 8px; }
        h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: 10px; }
        p.desc { color: var(--ts); margin-bottom: 35px; }

        .role-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px; margin-bottom: 35px; }
        .role-card { background: #fff; border: 2px solid #eef2f6; padding: 20px; border-radius: 20px; cursor: pointer; transition: 0.3s; }
        .role-card i { font-size: 1.8rem; color: var(--ts); margin-bottom: 12px; }
        .role-card h4 { font-weight: 700; color: var(--t); font-size: 0.85rem; }
        .role-card.active { border-color: var(--p); background: rgba(0, 113, 227, 0.04); }
        .role-card.active i { color: var(--p); }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; text-align: left; }
        label { font-size: 0.8rem; font-weight: 700; color: var(--t); display: block; margin-bottom: 8px; }
        input { width: 100%; padding: 14px 18px; border-radius: 14px; border: 1.5px solid #e2e8f0; font-weight: 600; outline: none; transition: 0.3s; }
        input:focus { border-color: var(--p); }
        .btn-reg { grid-column: span 2; background: var(--p); color: #fff; padding: 18px; border: none; border-radius: 16px; font-weight: 800; cursor: pointer; margin-top: 15px; }
        .back-login { margin-top: 25px; display: block; text-decoration: none; color: var(--ts); font-weight: 600; }
        .back-login span { color: var(--p); }
    </style>
</head>
<body>
    <div class="reg-container">
        <a href="../index.php" class="logo"><i class="fa-solid fa-house-medical"></i> BONGA<span>CLINIC</span></a>
        <h2>Join the Portal</h2>
        <p class="desc">Select your professional role to continue</p>

        <div class="role-grid">
            <div class="role-card active" onclick="selectRole('student', this)"><i class="fa-solid fa-user-graduate"></i><h4>Student</h4></div>
            <div class="role-card" onclick="selectRole('doctor', this)"><i class="fa-solid fa-user-doctor"></i><h4>Doctor</h4></div>
            <div class="role-card" onclick="selectRole('receptionist', this)"><i class="fa-solid fa-clipboard-user"></i><h4>Reception</h4></div>
            <div class="role-card" onclick="selectRole('lab', this)"><i class="fa-solid fa-microscope"></i><h4>Lab Tech</h4></div>
        </div>

        <form action="process_registration.php" method="POST" class="form-grid">
            <input type="hidden" id="selectedRole" name="role" value="student">
            <div class="input-group"><label id="id-label">Student ID Number</label><input type="text" name="id_number" placeholder="BGR/1000/18" required></div>
            <div class="input-group"><label>Full Name</label><input type="text" name="fullname" required></div>
            <div class="input-group"><label>Email Address</label><input type="email" name="email" required></div>
            <div class="input-group"><label>Phone Number</label><input type="tel" name="phone" required></div>
            <div class="input-group"><label>Create Password</label><input type="password" name="password" required></div>
            <div class="input-group"><label>Confirm Password</label><input type="password" name="confirm_password" required></div>
            <button type="submit" class="btn-reg">Complete Registration</button>
        </form>
        <a href="login.php" class="back-login">Already have an account? <span>Sign In</span></a>
    </div>

    <script>
        function selectRole(role, element) {
            document.querySelectorAll('.role-card').forEach(card => card.classList.remove('active'));
            element.classList.add('active');
            document.getElementById('selectedRole').value = role;
            document.getElementById('id-label').innerText = (role === 'student') ? "Student ID Number" : "Employee ID Number";
        }
    </script>
</body>
</html>