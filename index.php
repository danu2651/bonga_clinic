<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bonga University | Digital Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        :root { 
            --p: #0071e3; 
            --pd: #005bb5; 
            --t: #1d1d1f; 
            --ts: #86868b; 
            --bg: #ffffff;
            --glass: rgba(255, 255, 255, 0.7);
            --tr: all 0.5s cubic-bezier(0.16, 1, 0.3, 1); 
        }

        * { margin: 0; padding: 0; box-sizing: border-box; outline: none; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--t); background: var(--bg); overflow-x: hidden; scroll-behavior: smooth; }

        /* Smooth Navbar */
        .nav { position: fixed; top: 0; width: 100%; z-index: 1000; backdrop-filter: blur(25px); background: var(--glass); border-bottom: 1px solid rgba(0,0,0,0.03); padding: 25px 8%; display: flex; justify-content: space-between; align-items: center; transition: var(--tr); }
        .logo { font-weight: 800; font-size: 1.5rem; text-decoration: none; color: var(--t); letter-spacing: -1px; }
        .logo span { color: var(--p); }
        .menu { display: flex; gap: 40px; list-style: none; align-items: center; }
        .menu a { text-decoration: none; color: var(--t); font-weight: 600; font-size: 0.9rem; transition: 0.3s; opacity: 0.7; }
        .menu a:hover { opacity: 1; color: var(--p); }
        .btn-nav { background: var(--t); color: #fff !important; padding: 12px 28px; border-radius: 50px; font-weight: 600; transition: var(--tr); }
        .btn-nav:hover { transform: scale(1.05); background: var(--p); }

        /* Hero Section */
        .hero { height: 100vh; display: flex; align-items: center; position: relative; padding: 0 8%; background: #fafafa; overflow: hidden; }
        .hero-text { width: 55%; z-index: 2; }
        .hero h1 { font-size: 5rem; line-height: 1; font-weight: 800; letter-spacing: -3px; margin-bottom: 25px; }
        .hero h1 span { background: linear-gradient(90deg, #0071e3, #2bd2ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero p { font-size: 1.25rem; color: var(--ts); max-width: 500px; margin-bottom: 40px; }
        .hero-img { position: absolute; right: -5%; width: 50%; height: 80%; border-radius: 40px; background: url('https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=1000') center/cover; box-shadow: 0 30px 60px rgba(0,0,0,0.1); }

        /* Stats Section */
        .stats { display: flex; justify-content: space-around; padding: 80px 8%; background: var(--t); color: #fff; }
        .stat-item h2 { font-size: 3rem; font-weight: 800; color: var(--p); }
        .stat-item p { opacity: 0.6; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; }

        /* Section Styling */
        section { padding: 120px 8%; }
        .title { margin-bottom: 70px; }
        .title h2 { font-size: 3rem; font-weight: 800; letter-spacing: -2px; }
        .line { width: 60px; height: 5px; background: var(--p); margin-top: 15px; border-radius: 10px; }

        /* Bento Grid Services */
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; }
        .grid-services { display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px; }
        .s-card { background: #f5f5f7; padding: 50px; border-radius: 40px; transition: var(--tr); border: 1px solid transparent; }
        .s-card:hover { background: #fff; border-color: #eee; transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.05); }
        .s-card i { font-size: 2.5rem; color: var(--p); margin-bottom: 30px; }

        /* Enhanced Medical Team Cards */
        .doc { 
            position: relative; 
            border-radius: 35px; 
            overflow: hidden; 
            aspect-ratio: 4/5; 
            cursor: pointer; 
            background: #f5f5f7;
        }
        .doc img { 
            width: 100%; height: 100%; object-fit: cover; 
            filter: grayscale(20%); 
            transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1); 
        }
        .overlay { 
            position: absolute; inset: 0; 
            background: linear-gradient(to top, rgba(0, 113, 227, 0.9), rgba(0, 113, 227, 0.2)); 
            display: flex; flex-direction: column; justify-content: flex-end; padding: 30px;
            opacity: 0; transition: all 0.5s ease; color: #fff; 
        }
        .overlay h4 { font-size: 1.4rem; font-weight: 700; margin-bottom: 5px; }
        .overlay p { font-size: 0.9rem; opacity: 0.9; margin-bottom: 15px; }
        .social-icons { display: flex; gap: 12px; }
        .social-icons a { 
            width: 35px; height: 35px; background: rgba(255,255,255,0.2); 
            border-radius: 50%; display: flex; align-items: center; justify-content: center; 
            color: white; text-decoration: none; transition: 0.3s; backdrop-filter: blur(5px);
        }
        .social-icons a:hover { background: #fff; color: var(--p); transform: translateY(-3px); }
        .doc:hover .overlay { opacity: 1; }
        .doc:hover img { transform: scale(1.1); filter: grayscale(0%); }

        /* Health Tips Section */
        .tip-card { border-radius: 30px; overflow: hidden; background: #fff; border: 1px solid #eee; }
        .tip-content { padding: 30px; }
        .tag { background: #eef7ff; color: var(--p); padding: 5px 15px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }

        /* Contact Section */
        .contact-container { display: grid; grid-template-columns: 1fr 1fr; gap: 100px; background: #000; padding: 80px; border-radius: 50px; color: #fff; }
        .contact-form input, .contact-form textarea { width: 100%; background: #1d1d1f; border: 1px solid #333; padding: 20px; border-radius: 15px; color: #fff; margin-bottom: 20px; }
        .btn-send { background: var(--p); color: #fff; border: none; padding: 20px 40px; border-radius: 15px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .btn-send:hover { background: #fff; color: #000; }

        footer { padding: 80px 8%; background: #fafafa; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        
        .flex-btn { display: flex; gap: 20px; }
        .b-fill { background: var(--p); color: white; padding: 18px 40px; border-radius: 50px; text-decoration: none; font-weight: 700; }
        .b-outline { border: 2px solid var(--t); color: var(--t); padding: 18px 40px; border-radius: 50px; text-decoration: none; font-weight: 700; }

        @media (max-width: 1024px) { 
            .hero-text { width: 100%; text-align: center; } 
            .hero-img { display: none; }
            .grid-services, .contact-container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <nav class="nav" id="navbar">
        <a href="#" class="logo">BONGA<span>CLINIC</span></a>
        <ul class="menu">
            <li><a href="#home">Overview</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#doctors">Medical Team</a></li>
            <li><a href="#contact">Support</a></li>
            <li><a href="auth/login.php" class="btn-nav">Sign In</a></li>
        </ul>
    </nav>

    <section id="home" class="hero">
        <div class="hero-text" data-aos="fade-up">
            <h1>Better Care for <span>Better Grades.</span></h1>
            <p>Experience the next generation of student healthcare. Fast, digital, and always professional.</p>
            <div class="flex-btn">
                <a href="auth/login.php" class="b-fill">Book Appointment</a>
                <a href="#contact" class="b-outline">Contact Us</a>
            </div>
        </div>
        <div class="hero-img" data-aos="fade-left"></div>
    </section>

    <div class="stats">
        <div class="stat-item" data-aos="zoom-in"><h2>4k+</h2><p>Students Served</p></div>
        <div class="stat-item" data-aos="zoom-in" data-aos-delay="100"><h2>24/7</h2><p>Emergency Help</p></div>
        <div class="stat-item" data-aos="zoom-in" data-aos-delay="200"><h2>10+</h2><p>Specialists</p></div>
    </div>

    <section id="services">
        <div class="title" data-aos="fade-up">
            <h2>Digital First Services</h2>
            <div class="line"></div>
        </div>
        <div class="grid-services">
            <div class="s-card" data-aos="fade-up">
                <i class="fa-solid fa-bolt"></i>
                <h3>Priority Wellness</h3>
                <p>Your health is our ultimate priority. We've streamlined our care process to ensure you receive the attention you deserve exactly when you need it</p>
            </div>
            <div class="s-card" data-aos="fade-up" data-aos-delay="100">
                <i class="fa-solid fa-microscope"></i>
                <h3>Direct Lab Access</h3>
                <p>View your blood work and test results directly on your smartphone.</p>
            </div>
            <div class="s-card" data-aos="fade-up" data-aos-delay="200">
                <i class="fa-solid fa-shield-halved"></i>
                <h3>Secure Records</h3>
                <p>Your medical history is encrypted and available only to you and your doctor.</p>
            </div>
        </div>
    </section>

    <section id="doctors">
        <div class="title" data-aos="fade-up">
            <h2>Medical Excellence</h2>
            <div class="line"></div>
        </div>
        <div class="grid">
            <div class="doc" data-aos="fade-up">
                <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&q=80&w=600">
                <div class="overlay">
                    <h4>Dr. Elias Samuel</h4>
                    <p>Chief Medical Officer</p>
                    <div class="social-icons">
                        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="#"><i class="fa-solid fa-envelope"></i></a>
                    </div>
                </div>
            </div>
            <div class="doc" data-aos="fade-up" data-aos-delay="100">
                <img src="data:image/jpeg;base64,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">
                <div class="overlay">
                    <h4>Dr. Martha Tesfaye</h4>
                    <p>Senior Physician</p>
                    <div class="social-icons">
                        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="#"><i class="fa-solid fa-envelope"></i></a>
                    </div>
                </div>
            </div>
            <div class="doc" data-aos="fade-up" data-aos-delay="200">
                <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=600">
                <div class="overlay">
                    <h4>Abebe Kebede</h4>
                    <p>Lab Specialist</p>
                    <div class="social-icons">
                        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="#"><i class="fa-solid fa-flask"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="tips" style="background: #fafafa;">
        <div class="title" data-aos="fade-up">
            <h2>Health Insights</h2>
            <div class="line"></div>
        </div>
        <div class="grid-services">
            <div class="tip-card" data-aos="zoom-in">
                <img src="https://images.unsplash.com/photo-1511688826399-13f7295c9a97?auto=format&fit=crop&q=80&w=400" width="100%">
                <div class="tip-content">
                    <span class="tag">Nutrition</span>
                    <h4 style="margin-top:15px;">Brain Food for Exam Season</h4>
                    <p style="color:var(--ts); font-size:0.9rem; margin-top:10px;">Boost your memory with these 5 local superfoods.</p>
                </div>
            </div>
            <div class="tip-card" data-aos="zoom-in" data-aos-delay="100">
                <img src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&q=80&w=400" width="100%">
                <div class="tip-content">
                    <span class="tag">Mental Health</span>
                    <h4 style="margin-top:15px;">Managing Campus Stress</h4>
                    <p style="color:var(--ts); font-size:0.9rem; margin-top:10px;">Simple techniques for pre-exam anxiety.</p>
                </div>
            </div>
            <div class="tip-card" data-aos="zoom-in" data-aos-delay="200">
                <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&q=80&w=400" width="100%">
                <div class="tip-content">
                    <span class="tag">Fitness</span>
                    <h4 style="margin-top:15px;">Staying Active in Dorms</h4>
                    <p style="color:var(--ts); font-size:0.9rem; margin-top:10px;">A 10-minute workout you can do anywhere.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="contact">
        <div class="contact-container" data-aos="fade-up">
            <div>
                <h2 style="font-size: 3rem; margin-bottom: 20px;">Let's talk.</h2>
                <p style="opacity: 0.7; margin-bottom: 40px;">Have questions about our services or need technical help?</p>
                <div style="display:grid; gap:20px;">
                    <p><i class="fa-solid fa-envelope" style="color:var(--p)"></i> support@bonga.edu.et</p>
                    <p><i class="fa-solid fa-phone" style="color:var(--p)"></i> +251 912 345 678</p>
                    <p><i class="fa-solid fa-location-dot" style="color:var(--p)"></i> Bonga University Main Campus</p>
                </div>
            </div>
            <form class="contact-form">
                <input type="text" placeholder="Full Name">
                <input type="email" placeholder="Student Email">
                <textarea rows="5" placeholder="How can we help you?"></textarea>
                <button type="submit" class="btn-send">Send Message</button>
            </form>
        </div>
    </section>

    <footer>
        <p style="font-weight: 800;">BONGA<span>CLINIC</span></p>
        <p style="color:var(--ts); font-size:0.8rem;">&copy; 2026 Bonga University. All Rights Reserved.</p>
        <div class="social-icons" style="display:flex; gap:20px;">
            <i class="fa-brands fa-instagram" style="color:#000"></i>
            <i class="fa-brands fa-twitter" style="color:#000"></i>
            <i class="fa-brands fa-facebook" style="color:#000"></i>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true });

        window.onscroll = () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.style.padding = '15px 8%';
                navbar.style.boxShadow = '0 10px 30px rgba(0,0,0,0.05)';
            } else {
                navbar.style.padding = '25px 8%';
                navbar.style.boxShadow = 'none';
            }
        };
    </script>
</body>
</html>