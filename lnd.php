<?php
// Start session

// Assuming you have a database connection, replace these credentials with your own
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ppa";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Career Development Division Documents</title>
  <style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
    min-height: 100vh;
    color: #333;
    line-height: 1.6;
  }

  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }

  /* Navigation */
  nav {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-bottom: 30px;
    padding: 20px 0;
  }

  .nav-button {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.2);
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .nav-button:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  }

  .nav-button.logout:hover {
    background: linear-gradient(135deg, #1565c0, #e74c3c);
    border-color: #e74c3c;
  }

  /* Header */
  .header {
    text-align: center;
    margin-bottom: 50px;
    position: relative;
  }

  .header::before {
    content: '';
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, #1976d2, #42a5f5);
    border-radius: 2px;
  }

  h1 {
    font-size: clamp(2.5rem, 5vw, 3.5rem);
    font-weight: 700;
    color: #1976d2;
    text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.3);
    margin-bottom: 15px;
    letter-spacing: -1px;
  }

  .subtitle {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 300;
    margin-top: 10px;
  }

  /* Sections Grid */
  .sections-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 40px;
  }

  .section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    padding: 20px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    min-height: 200px;
  }

  .section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #1976d2, #42a5f5);
    transform: scaleX(0);
    transition: transform 0.4s ease;
  }

  .section:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  }

  .section:hover::before {
    transform: scaleX(1);
  }

  .section-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #1976d2, #1565c0);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
  }

  .section h2 {
    font-size: 1.2rem;
    color: #2c3e50;
    margin-bottom: 15px;
    font-weight: 600;
    letter-spacing: -0.5px;
  }

  .section ul {
    list-style: none;
  }

  .section li {
    margin-bottom: 8px;
    position: relative;
  }

  .section a {
    color: #4a5568;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 8px;
    display: block;
    transition: all 0.3s ease;
    position: relative;
    background: rgba(0, 0, 0, 0.02);
    border: 1px solid rgba(0, 0, 0, 0.05);
  }

  .section a::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #1976d2, #1565c0);
    border-radius: 0 3px 3px 0;
    transform: scaleY(0);
    transition: transform 0.3s ease;
  }

  .section a:hover {
    color: #1976d2;
    background: rgba(25, 118, 210, 0.05);
    border-color: rgba(25, 118, 210, 0.2);
    transform: translateX(8px);
  }

  .section a:hover::before {
    transform: scaleY(1);
  }

  /* Document count badge */
  .doc-count {
    position: absolute;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #1976d2, #42a5f5);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
  }

  /* Loading animation */
  .loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 8px;
    height: 20px;
    margin-bottom: 10px;
  }

  @keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .container {
      padding: 15px;
    }

    nav {
      justify-content: center;
      flex-wrap: wrap;
    }

    .sections-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
    }

    .section {
      padding: 25px;
    }

    h1 {
      font-size: 2.5rem;
    }
  }

  /* Accessibility */
  .section a:focus {
    outline: 2px solid #1976d2;
    outline-offset: 2px;
  }

  .nav-button:focus {
    outline: 2px solid white;
    outline-offset: 2px;
  }

  /* Print styles */
  @media print {
    body {
      background: white;
      color: black;
    }
    
    .nav-button, .section::before, .section-icon {
      display: none;
    }
    
    .section {
      background: white;
      box-shadow: none;
      border: 1px solid #ccc;
      break-inside: avoid;
    }
  }
  </style>
</head>
<body>
  <div class="container">
    <nav>
      <button class="nav-button" onclick="window.location.href='https://tinyurl.com/PPAONES'">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
          <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
        </svg>
        Home
      </button>
      <button class="nav-button logout" onclick="window.location.href='logout1.php'">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
        </svg>
        Logout
      </button>
    </nav>

    <div class="header">
      <h1>Career Development Division</h1>
      <p class="subtitle">Document Management System</p>
    </div>

    <div class="sections-grid">
      <div class="section">
        <div class="doc-count">3</div>
        <div class="section-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 24 24">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
          </svg>
        </div>
        <h2>Learning & Development</h2>
        <ul>
          <li><a href="https://tinyurl.com/LearningDevt-Guidelines" target="_blank">Learning Development Guidelines</a></li>
          <li><a href="https://tinyurl.com/LearningDevt-Reports" target="_blank">Learning Development Reports</a></li>
          <li><a href="https://ppati-lms.ppa.com.ph/" target="_blank">PPATI-LMS</a></li>
        </ul>
      </div>

      <div class="section">
        <div class="doc-count">3</div>
        <div class="section-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 24 24">
            <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
          </svg>
        </div>
        <h2>Career Planning and Monitoring</h2>
        <ul>
          <li><a href="https://tinyurl.com/Competency-Monitoring" target="_blank">Competency-Monitoring</a></li>
          <li><a href="https://tinyurl.com/Competency-PMF" target="_blank">Performance Monitoring</a></li>
          <li><a href="https://tinyurl.com/LearningDevt-SystemsReview" target="_blank">Learning Development Systems Review</a></li>
        </ul>
      </div>

      <div class="section">
        <div class="doc-count">5</div>
        <div class="section-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 24 24">
            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
          </svg>
        </div>
        <h2>Training Delivery Section</h2>
        <ul>
          <li><a href="https://tinyurl.com/PPALAPREAP" target="_blank">LAP-Inhouse</a></li>
          <li><a href="https://tinyurl.com/LAP-NonTraining" target="_blank">LAP-Non Training</a></li>
          <li><a href="https://tinyurl.com/TIAMonitoring-Inhouse" target="_blank">TIA Monitoring Inhouse</a></li>
          <li><a href="https://tinyurl.com/LISTOFGRAD2" target="_blank">List of Graduates - TDS</a></li>
          <li><a href="https://tinyurl.com/PPA-Learners-Profile" target="_blank">PPA Learners Profile</a></li>
        </ul>
      </div>

      <div class="section">
        <div class="doc-count">4</div>
        <div class="section-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 24 24">
            <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
          </svg>
        </div>
        <h2>Scholarship Grants Section</h2>
        <ul>
          <li><a href="https://tinyurl.com/lisfofgrad1" target="_blank">List of Graduates of SGS</a></li>
          <li><a href="https://tinyurl.com/Localscho" target="_blank">TIA Monitoring Local & Scholarship</a></li>
          <li><a href="https://tinyurl.com/LIstgrads" target="_blank">List of Providers</a></li>
          <li><a href="https://tinyurl.com/PTR-Monitoring" target="_blank">REAP-Monitoring</a></li>
        </ul>
      </div>
    </div>
  </div>

  <script>
    // Add loading animation for external links
    document.querySelectorAll('a[target="_blank"]').forEach(link => {
      link.addEventListener('click', function(e) {
        const originalText = this.textContent;
        this.innerHTML = '<span class="loading-skeleton" style="width: 80%; height: 16px;"></span>';
        
        setTimeout(() => {
          this.textContent = originalText;
        }, 1000);
      });
    });

    // Add intersection observer for animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);

    // Initially hide sections for animation
    document.querySelectorAll('.section').forEach((section, index) => {
      section.style.opacity = '0';
      section.style.transform = 'translateY(50px)';
      section.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
      observer.observe(section);
    });

    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        document.activeElement.blur();
      }
    });

    // Add performance monitoring
    window.addEventListener('load', function() {
      console.log('Page loaded in', performance.now(), 'ms');
    });
  </script>

  <script src="script.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>