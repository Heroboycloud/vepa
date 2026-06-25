<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
  <title>✨ Random Profile · cool UI</title>
  <!-- Bootstrap 5 + icons + Google Font (Inter) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: linear-gradient(145deg, #f6f9fc 0%, #eef2f7 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .app-wrapper {
      flex: 1;
      display: flex;
      flex-direction: column;
    }
    /* header / footer modern glass */
    .modern-header {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.5);
      box-shadow: 0 8px 20px -8px rgba(0,20,40,0.08);
      padding: 1rem 0;
    }
    .modern-footer {
      background: rgba(255, 255, 255, 0.6);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border-top: 1px solid rgba(255, 255, 255, 0.6);
      padding: 1.2rem 0;
      margin-top: auto;
      color: #2c3e50;
      font-weight: 450;
      letter-spacing: -0.01em;
    }
    .brand-glow {
      font-weight: 700;
      font-size: 1.6rem;
      background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: -0.02em;
    }
    .brand-glow i {
      -webkit-text-fill-color: #1a2a6c;
      background: none;
      margin-right: 6px;
    }
    /* card profile */
    .profile-card {
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      border: 1px solid rgba(255,255,255,0.5);
      border-radius: 48px 48px 32px 32px;
      box-shadow: 0 25px 45px -18px rgba(0,20,40,0.25), 0 0 0 1px rgba(255,255,255,0.3) inset;
      transition: all 0.2s ease;
      padding: 2rem 1.8rem 2.2rem;
      max-width: 680px;
      margin: 0 auto;
      width: 100%;
    }
    .profile-card:hover {
      box-shadow: 0 35px 60px -20px rgba(0,20,40,0.3);
      background: rgba(255, 255, 255, 0.85);
    }
    .avatar-lg {
      width: 120px;
      height: 120px;
      border-radius: 100px;
      object-fit: cover;
      border: 4px solid white;
      box-shadow: 0 12px 24px -8px rgba(0,0,0,0.15);
      transition: transform 0.25s ease;
      background: #e9edf2;
    }
    .avatar-lg:hover {
      transform: scale(1.02);
    }
    .info-badge {
      background: rgba(230, 240, 255, 0.5);
      backdrop-filter: blur(2px);
      -webkit-backdrop-filter: blur(2px);
      border-radius: 60px;
      padding: 0.35rem 1.2rem;
      font-weight: 500;
      font-size: 0.9rem;
      color: #1f2a44;
      border: 1px solid rgba(255,255,255,0.6);
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    .detail-row {
      background: rgba(255,255,255,0.4);
      backdrop-filter: blur(2px);
      -webkit-backdrop-filter: blur(2px);
      border-radius: 40px;
      padding: 0.6rem 1.2rem;
      border: 1px solid rgba(255,255,255,0.6);
      transition: background 0.15s;
      font-weight: 450;
      color: #1e293b;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 0.5rem 0.2rem;
    }
    .detail-row .label {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      opacity: 0.65;
      font-weight: 600;
    }
    .detail-row .value {
      font-weight: 600;
      word-break: break-word;
      text-align: right;
      flex: 1;
      min-width: 120px;
    }
    .copy-btn {
      background: rgba(255,255,255,0.5);
      backdrop-filter: blur(2px);
      -webkit-backdrop-filter: blur(2px);
      border: 1px solid rgba(255,255,255,0.7);
      border-radius: 30px;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #1f2a44;
      transition: all 0.15s;
      cursor: pointer;
      font-size: 1rem;
      flex-shrink: 0;
    }
    .copy-btn:hover {
      background: #ffffff;
      border-color: #a0b8d9;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      color: #0b1a33;
      transform: scale(1.02);
    }
    .copy-btn.copied {
      background: #2b6f4b;
      border-color: #1f5237;
      color: white;
    }
    .btn-generate {
      background: rgba(255,255,255,0.7);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      border: 1px solid rgba(255,255,255,0.7);
      border-radius: 60px;
      padding: 0.7rem 2.2rem;
      font-weight: 600;
      color: #1f2a44;
      transition: all 0.2s;
      box-shadow: 0 6px 14px rgba(0,20,40,0.04);
      letter-spacing: -0.01em;
    }
    .btn-generate:hover {
      background: white;
      border-color: #b7c9e2;
      box-shadow: 0 12px 28px -10px rgba(0,20,40,0.15);
      transform: translateY(-2px);
    }
    .btn-generate i {
      margin-right: 8px;
    }
    .footer-text {
      opacity: 0.7;
      font-size: 0.9rem;
      font-weight: 450;
    }
    .loader-dots::after {
      content: '';
      animation: dots 1s steps(4, end) infinite;
    }
    @keyframes dots {
      0% { content: ''; }
      25% { content: '.'; }
      50% { content: '..'; }
      75% { content: '...'; }
    }
    @media (max-width: 576px) {
      .profile-card { padding: 1.5rem 1rem; border-radius: 32px; }
      .avatar-lg { width: 96px; height: 96px; }
      .brand-glow { font-size: 1.3rem; }
      .detail-row { padding: 0.5rem 1rem; }
    }
    /* shimmer skeleton (optional) */
    .skeleton-text {
      background: #e2e8f0;
      border-radius: 30px;
      height: 1.2rem;
      width: 80%;
      animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
      0% { opacity: 0.6; }
      50% { opacity: 1; }
      100% { opacity: 0.6; }
    }
  </style>
</head>
<body>

<div class="app-wrapper">

  <!-- HEADER -->
  <header class="modern-header">
    <div class="container d-flex flex-wrap align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <span class="brand-glow"><i class="bi bi-person-bounding-box"></i>rand<span style="-webkit-text-fill-color:#b21f1f;">Profile</span></span>
        <span class="badge bg-light text-dark rounded-pill px-3 py-1 border border-white/50" style="backdrop-filter:blur(4px); font-weight:500;">✨ live</span>
      </div>
      <div class="d-flex gap-3">
        <a href="#" class="text-decoration-none text-dark opacity-75 d-none d-sm-inline" style="font-weight:500;"><i class="bi bi-github"></i></a>
        <a href="#" class="text-decoration-none text-dark opacity-75" style="font-weight:500;"><i class="bi bi-arrow-repeat"></i> random</a>
      </div>
    </div>
  </header>

  <!-- MAIN CONTENT -->
  <main class="container py-4 py-md-5 flex-grow-1 d-flex flex-column align-items-center">
    <div class="w-100" style="max-width: 720px;">

      <!-- generate button -->
      <div class="d-flex justify-content-center mb-4">
        <button id="generateBtn" class="btn-generate btn btn-light border-0 px-4 py-2 shadow-sm">
          <i class="bi bi-dice-5-fill"></i> Generate new profile
        </button>
      </div>

      <!-- PROFILE CARD -->
      <div id="profileCard" class="profile-card">
        <!-- dynamic content will be injected here -->
        <div id="profileContent" class="d-flex flex-column align-items-center text-center">
          <!-- avatar -->
          <div class="mb-3">
            <img id="avatarImg" src="https://randomuser.me/api/portraits/men/32.jpg" alt="avatar" class="avatar-lg" style="background:#d4dce8;">
          </div>
          <!-- name + badge -->
          <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 mb-2">
            <h2 id="fullName" class="h4 fw-bold mb-0" style="color:#0b1a33;">Sven Petersen</h2>
            <span class="info-badge"><i class="bi bi-patch-check-fill" style="color:#2563eb;"></i> verified</span>
          </div>
          <!-- location -->
          <div class="mb-3 d-flex align-items-center gap-1 text-secondary" style="font-size:0.95rem;">
            <i class="bi bi-geo-alt-fill" style="color:#b21f1f;"></i>
            <span id="locationText">Berlin, Germany</span>
          </div>

          <!-- detail rows with copy buttons -->
          <div class="w-100 d-flex flex-column gap-2 mt-1">
            <!-- email -->
            <div class="detail-row">
              <span class="label"><i class="bi bi-envelope-fill me-1"></i>email</span>
              <span id="emailValue" class="value">sven.petersen@example.com</span>
              <button class="copy-btn" data-copy-target="emailValue" title="copy email"><i class="bi bi-copy"></i></button>
            </div>
            <!-- phone -->
            <div class="detail-row">
              <span class="label"><i class="bi bi-telephone-fill me-1"></i>phone</span>
              <span id="phoneValue" class="value">(555) 123-4567</span>
              <button class="copy-btn" data-copy-target="phoneValue" title="copy phone"><i class="bi bi-copy"></i></button>
            </div>
            <!-- cell -->
            <div class="detail-row">
              <span class="label"><i class="bi bi-phone-fill me-1"></i>cell</span>
              <span id="cellValue" class="value">(555) 987-6543</span>
              <button class="copy-btn" data-copy-target="cellValue" title="copy cell"><i class="bi bi-copy"></i></button>
            </div>
            <!-- full address -->
            <div class="detail-row">
              <span class="label"><i class="bi bi-house-door-fill me-1"></i>address</span>
              <span id="addressValue" class="value">123 Main St, 10115</span>
              <button class="copy-btn" data-copy-target="addressValue" title="copy address"><i class="bi bi-copy"></i></button>
            </div>
          </div>

          <div class="mt-3 w-100 d-flex justify-content-between align-items-center">
            <span class="badge bg-white/60 text-dark rounded-pill px-3 py-2 border" style="font-weight:400; backdrop-filter:blur(2px);">
              <i class="bi bi-person-fill me-1"></i> ID: <span id="userId" class="fw-semibold">N0-3821</span>
            </span>
            <span class="badge bg-white/50 text-dark rounded-pill px-3 py-2 border" style="font-weight:400;">
              <i class="bi bi-calendar3 me-1"></i> <span id="userAge">32</span> yrs
            </span>
          </div>
        </div>
      </div>

      <!-- toast (copy feedback) -->
      <div id="copyToast" class="fixed-bottom mb-4 d-flex justify-content-center pointer-events-none" style="z-index:999; bottom:20px; left:0; right:0;">
        <div class="bg-dark text-white px-4 py-2 rounded-5 shadow-lg opacity-0 transition-all" style="transition: opacity 0.25s; backdrop-filter:blur(6px); background: rgba(15,23,42,0.85) !important; font-weight:500; border:1px solid rgba(255,255,255,0.1);" id="toastMsg">
          <i class="bi bi-check-circle-fill me-2" style="color:#4ade80;"></i> copied!
        </div>
      </div>

    </div>
  </main>

  <!-- FOOTER -->
  <footer class="modern-footer">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <span class="footer-text"><i class="bi bi-cpu me-1"></i> randomuser.me · live API</span>
      <span class="footer-text d-flex gap-3">
        <span><i class="bi bi-shield-check"></i> private</span>
        <span>© 2026</span>
      </span>
    </div>
  </footer>
</div>

<!-- Bootstrap JS (optional but good for toggles) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  (function() {
    "use strict";

    // DOM refs
    const avatarImg = document.getElementById('avatarImg');
    const fullName = document.getElementById('fullName');
    const locationText = document.getElementById('locationText');
    const emailValue = document.getElementById('emailValue');
    const phoneValue = document.getElementById('phoneValue');
    const cellValue = document.getElementById('cellValue');
    const addressValue = document.getElementById('addressValue');
    const userId = document.getElementById('userId');
    const userAge = document.getElementById('userAge');
    const generateBtn = document.getElementById('generateBtn');
    const toastMsg = document.getElementById('toastMsg');

    // toast timer
    let toastTimer = null;

    // show toast
    function showToast(text = 'copied!') {
      if (toastTimer) {
        clearTimeout(toastTimer);
        toastTimer = null;
      }
      toastMsg.textContent = text;
      toastMsg.classList.remove('opacity-0');
      toastMsg.classList.add('opacity-100');
      toastTimer = setTimeout(() => {
        toastMsg.classList.remove('opacity-100');
        toastMsg.classList.add('opacity-0');
        toastTimer = null;
      }, 1800);
    }

    // fetch & render profile
    async function fetchRandomProfile() {
      try {
        // show subtle loading state (optional)
        generateBtn.disabled = true;
        generateBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span> loading...`;

        const response = await fetch('https://randomuser.me/api/');
        if (!response.ok) throw new Error('API error');
        const data = await response.json();
        const user = data.results[0];

        // extract
        const name = `${user.name.first} ${user.name.last}`;
        const email = user.email;
        const phone = user.phone;
        const cell = user.cell;
        const location = `${user.location.city}, ${user.location.country}`;
        const street = `${user.location.street.number} ${user.location.street.name}`;
        const postcode = user.location.postcode || '';
        const fullAddress = `${street}, ${postcode}`;
        const id = user.id.value || user.login.uuid.slice(0, 8);
        const age = user.dob.age;
        const avatar = user.picture.large;

        // update DOM
        avatarImg.src = avatar;
        fullName.textContent = name;
        locationText.textContent = location;
        emailValue.textContent = email;
        phoneValue.textContent = phone;
        cellValue.textContent = cell;
        addressValue.textContent = fullAddress;
        userId.textContent = id;
        userAge.textContent = age;

        // update copy buttons (they have data-copy-target, we just re-bind)
        // after DOM update, all copy buttons work with the same handler

      } catch (err) {
        console.warn('fetch error:', err);
        // fallback: keep existing or show error message
        showToast('⚠️ could not load');
      } finally {
        generateBtn.disabled = false;
        generateBtn.innerHTML = `<i class="bi bi-dice-5-fill"></i> Generate new profile`;
      }
    }

    // --- copy handler (delegation) ---
    document.addEventListener('click', function(e) {
      const copyBtn = e.target.closest('.copy-btn');
      if (!copyBtn) return;

      const targetId = copyBtn.dataset.copyTarget;
      if (!targetId) return;

      const targetEl = document.getElementById(targetId);
      if (!targetEl) return;

      // get text to copy
      let textToCopy = targetEl.textContent.trim();
      if (!textToCopy) {
        showToast('nothing to copy');
        return;
      }

      // copy via clipboard API
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(textToCopy).then(() => {
          showToast('✅ copied!');
          // visual feedback on button
          copyBtn.classList.add('copied');
          setTimeout(() => copyBtn.classList.remove('copied'), 800);
        }).catch(() => {
          fallbackCopy(textToCopy, copyBtn);
        });
      } else {
        fallbackCopy(textToCopy, copyBtn);
      }
    });

    // fallback copy using input
    function fallbackCopy(text, btn) {
      const input = document.createElement('input');
      input.value = text;
      document.body.appendChild(input);
      input.select();
      try {
        document.execCommand('copy');
        showToast('✅ copied!');
        btn.classList.add('copied');
        setTimeout(() => btn.classList.remove('copied'), 800);
      } catch (e) {
        showToast('⚠️ copy failed');
      }
      document.body.removeChild(input);
    }

    // generate on button click
    generateBtn.addEventListener('click', fetchRandomProfile);

    // load initial profile on page load
    fetchRandomProfile();

    // extra: click on avatar or name re-generates? (optional)
    // we keep it simple: only button

  })();
</script>

</body>
</html>
