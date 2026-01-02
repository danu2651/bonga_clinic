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
                <img src="https://images.unsplash.com/photo-1559839734-2b71f1536780?auto=format&fit=crop&q=80&w=600">
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