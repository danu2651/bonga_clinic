<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Bonga University Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --p: #0071e3; 
            --pd: #005bb5; 
            --t: #1d1d1f; 
            --ts: #6e6e73; 
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body {
            height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(rgba(255,255,255,0.8), rgba(255,255,255,0.8)), 
                        url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&q=80&w=2000');
            background-size: cover; background-position: center; overflow: hidden;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.4); border-radius: 35px; padding: 50px 45px;
            width: 100%; max-width: 440px; text-align: center; box-shadow: 0 40px 100px rgba(0,0,0,0.1);
            animation: fadeIn 0.8s ease-out;
        }

        .logo { font-weight: 800; font-size: 1.5rem; color: var(--t); text-decoration: none; display: block; margin-bottom: 30px; }
        .logo span { color: var(--p); }
        .logo i { color: var(--p); margin-right: 8px; }

        h2 { font-size: 2rem; font-weight: 800; color: var(--t); margin-bottom: 10px; letter-spacing: -1px; }
        p.sub { color: var(--ts); margin-bottom: 35px; font-size: 0.95rem; line-height: 1.4; }

        .form-group { text-align: left; margin-bottom: 22px; }
        label { display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 8px; color: var(--t); opacity: 0.9; }
        
        .input-box {
            background: #fff; border: 1.5px solid #e1e1e7; border-radius: 15px;
            transition: 0.3s; display: flex; align-items: center;
        }
        .input-box i { padding: 0 15px; color: var(--p); font-size: 1rem; }
        .input-box input { width: 100%; padding: 15px 15px 15px 0; border: none; background: transparent; outline: none; font-size: 1rem; font-weight: 600; color: var(--t); }
        .input-box:focus-within { border-color: var(--p); box-shadow: 0 0 0 4px rgba(0,113,227,0.1); }

        .btn-login {
            width: 100%; padding: 18px; background: var(--p); color: #fff; border: none; border-radius: 15px;
            font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 15px; transition: 0.3s;
            box-shadow: 0 10px 20px rgba(0,113,227,0.15);
        }
        .btn-login:hover { background: var(--pd); transform: translateY(-2px); }

        .footer-links { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-top: 25px; 
            font-size: 0.85rem; 
        }
        .footer-links a.forgot { color: var(--ts); text-decoration: none; font-weight: 600; }
        
        .btn-create {
            color: var(--p); 
            text-decoration: none; 
            font-weight: 800; 
            border: 1.5px solid var(--p); 
            padding: 8px 18px; 
            border-radius: 12px; 
            transition: 0.3s;
        }
        .btn-create:hover {
            background: var(--p);
            color: #fff;
            box-shadow: 0 5px 15px rgba(0, 113, 227, 0.2);
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <div class="glass-card">
        <a href="../index.php" class="logo"><i class="fa-solid fa-house-medical"></i> BONGA<span>CLINIC</span></a>
        <h2>Welcome Back</h2>
        <p class="sub">Access the official Bonga University Student Digital Health Portal.</p>

        <form id="loginForm">
            <div class="form-group">
                <label>User ID</label>
                <div class="input-box">
                    <i class="fa-solid fa-id-card"></i>
                    <input type="text" id="uid" placeholder="BGR/1000/18" required>
                </div>
            </div>
            <div class="form-group">
                <label>Portal Password</label>
                <div class="input-box">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="pass" placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="btn-login">Verify & Sign In</button>
        </form>

        <div class="footer-links">
            <a href="#" class="forgot">Forgot Password?</a>
            <a href="register.php" class="btn-create">Create Account</a>
        </div>
    </div>

  <script>
    document.getElementById('loginForm').onsubmit = async (e) => {
        e.preventDefault();
        console.log("Login attempt started..."); // Debug: Check if button click works

        const uid = document.getElementById('uid').value;
        const pass = document.getElementById('pass').value;

        const formData = new FormData();
        formData.append('username', uid);
        formData.append('password', pass);

        try {
            const response = await fetch('login_logic.php', {
                method: 'POST',
                body: formData
            });

            // If the server crashes, response.ok will be false
            if (!response.ok) {
                const text = await response.text();
                console.error("Server Error:", text);
                alert("Server error. Check the console (F12) for details.");
                return;
            }

            const result = await response.json();
            console.log("Server Response:", result); // Debug: See what PHP says

            if (result.success) {
                // REDIRECTION LOGIC
                let target = "";
                if (result.role === 'admin') {
                    target = "../admin/dashboard/admin.php";
                } else if (result.role === 'student') {
                    target = "../dashboards/student.php";
                } else {
                    target = "../dashboards/" + result.role + ".php";
                }
                
                console.log("Redirecting to:", target);
                window.location.assign(target); // Force redirection
                
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error("Fetch Error:", error);
            alert("Connection error: Make sure login_logic.php exists in the same folder.");
        }
    }
</script>
</body>
</html>