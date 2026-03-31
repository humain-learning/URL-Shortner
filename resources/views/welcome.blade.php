<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ parse_url(config('app.short_url_domain'), PHP_URL_HOST) ?? 'hl.ai' }} - URL Shortener</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@latest/font/lucide.css" />
  <style>
    :root {
      --primary: #6366f1;
      --primary-foreground: #ffffff;
      --destructive: #ef4444;
      --success: #10b981;
      --muted: #6b7280;
      --bg: #f9fafb;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: sans-serif;
      background: var(--bg);
      color: #111827;
    }

    header {
      background: white;
      border-bottom: 1px solid #e5e7eb;
      position: sticky;
      top: 0;
      z-index: 50;
      padding: 1rem 2rem;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .logo-box {
      width: 40px;
      height: 40px;
      background: linear-gradient(to right, var(--primary), #9333ea);
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 0.5rem;
    }

    .logo-text h1 {
      font-size: 1.25rem;
      font-weight: bold;
      background: linear-gradient(to right, var(--primary), #9333ea);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
nav {
  display: flex;
  justify-content: flex-start; /* Align buttons to the left */
  gap: 1rem; /* Adds spacing between the buttons */
}

.nav-button {
  border: none;
  padding: 0.5rem 1rem;
  background: transparent;
  cursor: pointer;
  border-radius: 0.375rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: background 0.3s ease, color 0.3s ease;
}

.nav-button.active {
  background: var(--primary);
  color: var(--primary-foreground);
}

    .main {
      padding: 2rem;
      max-width: 720px;
      margin: auto;
    }

    .card {
      background: white;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
      margin-bottom: 2rem;
    }

    .card h2 {
      font-size: 2rem;
      font-weight: bold;
      margin: 1rem 0;
      background: linear-gradient(to right, var(--primary), #9333ea);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .input-group {
      margin-bottom: 1rem;
    }

    .input-group label {
      font-size: 0.875rem;
      display: block;
      margin-bottom: 0.25rem;
    }

    input, select {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #d1d5db;
      border-radius: 0.5rem;
      font-size: 1rem;
    }

    button.submit {
      background: var(--primary);
      color: white;
      border: none;
      width: 100%;
      padding: 0.75rem;
      font-weight: 600;
      font-size: 1rem;
      border-radius: 0.5rem;
      margin-top: 1rem;
      cursor: pointer;
    }

    .result-card {
      background: white;
      padding: 1rem;
      border-radius: 0.75rem;
      border: 1px solid #e5e7eb;
      margin-bottom: 1rem;
    }

    .result-card.expired {
      opacity: 0.6;
      border-color: var(--destructive);
    }

    .url-display {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .url-actions {
      display: flex;
      gap: 0.5rem;
    }

    .url-actions button {
      background: none;
      border: none;
      cursor: pointer;
    }

    .text-muted {
      color: var(--muted);
      font-size: 0.875rem;
    }

    .success {
      color: var(--success);
    }

    .api-docs {
      margin-top: 2rem;
      background: white;
      padding: 1.5rem;
      border-radius: 1rem;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .api-docs h3 {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 1rem;
    }

    .api-docs p {
      margin-bottom: 1rem;
    }

    .code-block {
      background: #f3f4f6;
      padding: 1rem;
      border-radius: 0.375rem;
      font-family: monospace;
      margin-bottom: 1rem;
    }

    .original-url {
      width:80%; word-wrap: break-word;
      margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
      .original-url{
        width:50%;

      }
    }
  </style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>

  <header>
    <div class="container flex justify-between items-center">
      <div class="logo">
        <div class="logo-box"><i class="fa fa-link"></i></div>
        <div class="logo-text">
          <h1>{{ parse_url(config('app.short_url_domain'), PHP_URL_HOST) ?? 'hl.ai' }}</h1>
          <p class="text-muted">URL Shortener</p>
        </div>
      </div>
      <nav>
        {{-- <button class="nav-button active" onclick="switchTab('shortener')"><i class="fa fa-shortlink"></i> Shortener</button> --}}
        {{-- <button class="nav-button" onclick="switchTab('api')"><i class="fa fa-code"></i> API Docs</button> --}}
      </nav>
    </div>
  </header>

  <main class="main" id="shortener-tab">
    <div class="card">
      <div style="text-align:center;">
        <div class="logo-box" style="margin:auto;"><i class="fa fa-link" style="color:white;"></i></div>
        <h2>URL Shortener</h2>
        <p class="text-muted">Shorten your long URLs with custom expiration times</p>
      </div>

      <div class="input-group">
        <label for="url">Enter your long URL</label>
        <input type="url" id="url" placeholder="https://example.com/very-long-url" />
      </div>

      <div class="input-group">
        <label for="expiry">Expiration Time</label>
        <select id="expiry">
          <option value="1h">1 Hour</option>
          <option value="24h" selected>24 Hours</option>
          <option value="7d">7 Days</option>
          <option value="30d">30 Days</option>
          <option value="never">Never Expires</option>
        </select>
      </div>

      <button class="submit" onclick="shortenUrl()">Shorten URL</button>
    </div>

    <div id="results"></div>
  </main>

  <main class="main" id="api-tab" style="display:none;">
    <div class="card api-docs">
      <h3>API Documentation</h3>
      <p>Use the following API endpoints to interact with the URL shortener.</p>

      <div class="code-block">
        <strong>POST /shorten</strong><br>
        <p>Shortens a long URL and returns the shortened version.</p>
        <pre>
{
  "url": "https://example.com/very-long-url",
  "expiry": "24h"
}
        </pre>
        <p><strong>Response:</strong></p>
        <pre>
{
  "original": "https://example.com/very-long-url",
  "shortened": "{{ rtrim(config('app.short_url_domain'), '/') }}/abc123",
  "expiresAt": "2025-07-12T14:00:00Z"
}
        </pre>
      </div>

      <div class="code-block">
        <strong>GET /{code}</strong><br>
        <p>Redirects to the original URL using the shortened code.</p>
        <pre>GET {{ rtrim(config('app.short_url_domain'), '/') }}/{code}</pre>
      </div>

      <p><strong>Note:</strong> Expiration times can be set as: 1h, 24h, 7d, 30d, or never.</p>
    </div>
  </main>
{{-- <script>
  const results = [];

  function switchTab(tab) {
    document.getElementById("shortener-tab").style.display = tab === "shortener" ? "block" : "none";
    document.getElementById("api-tab").style.display = tab === "api" ? "block" : "none";
    document.querySelectorAll(".nav-button").forEach(btn => btn.classList.remove("active"));
    document.querySelectorAll(".nav-button")[tab === "shortener" ? 0 : 1].classList.add("active");
  }

  function generateShortCode() {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let result = "";
    for (let i = 0; i < 6; i++) {
      result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
  }

  // function calculateExpiry(option) {
  //   const now = new Date();
  //   switch (option) {
  //     case "1h": return new Date(now.getTime() + 60 * 60 * 1000);
  //     case "24h": return new Date(now.getTime() + 24 * 60 * 60 * 1000);
  //     case "7d": return new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
  //     case "30d": return new Date(now.getTime() + 30 * 24 * 60 * 60 * 1000);
  //     case "never": return new Date(now.getTime() + 100 * 365 * 24 * 60 * 60 * 1000);
  //     default: return now;
  //   }
  // }


  function shortenUrl() {
    const url = document.getElementById("url").value;
    const expiry = document.getElementById("expiry").value;

    if (!url || !url.startsWith("http")) {
      alert("Please enter a valid URL including http:// or https://");
      return;
    }

    const code = generateShortCode();
    const expiresAt = (expiry);

    const newEntry = {
      _token : "{{ csrf_token() }}",
      original_url: url,
      shortened_url: `${shortUrlDomain}/${code}`,
      expires_at: expiresAt,
    };

    results.unshift(newEntry);
    document.getElementById("url").value = "";
    renderResults();

    // Ajax request to store data in the database
    $.ajax({
      url: '/shorten-url',
      type: 'POST',
      data: newEntry,
      success: function(response) {
        console.log('Shortened URL saved successfully:', response);
      },
      error: function(error) {
        console.error('Error saving shortened URL:', error);
      }
    });
  }// Load results from localStorage on page load
let results = JSON.parse(localStorage.getItem("shortenedUrls")) || [];

// Function to render results from localStorage
function renderResults() {
  const container = document.getElementById("results");
  container.innerHTML = "<h3>Your Shortened URLs</h3>";
  
  results.forEach((item, index) => {
    const expired = new Date() > new Date(item.expires_at);
    container.innerHTML += `
      <div class="result-card ${expired ? "expired" : ""}">
        <div class="url-display">
          <div>
            <p class="text-muted">Original:</p>
            <p style="width:80%; word-wrap: break-word;">${item.original_url}</p>
            <p class="text-muted">Shortened:</p>
            <p class="success"><a href="${item.shortened_url}" target="_blank" onclick="incrementClick(${index})">${item.shortened_url}</a></p>
          </div>
          <div class="url-actions">
            <button onclick="copyToClipboard('${item.shortened_url}')"><i class="lucide lucide-copy"></i></button>
            <button onclick="window.open('${item.original_url}', '_blank')"><i class="lucide lucide-external-link"></i></button>
          </div>
        </div>
        <div class="text-muted">
          Clicks: ${item.clicks} &nbsp;&nbsp; | &nbsp;&nbsp;
          ${expired ? "<span style='color:var(--destructive)'>Expired</span>" : "Expires: " + new Date(item.expires_at).toLocaleDateString()}
        </div>
      </div>
    `;
  });
}

// Function to handle click increment and update localStorage
function incrementClick(index) {
  // Increment the click count for the clicked shortened URL
  results[index].clicks += 1;

  // Update the results in localStorage
  localStorage.setItem("shortenedUrls", JSON.stringify(results));

  // Re-render the results to reflect the updated click count
  renderResults();
}

// Function to save new shortened URLs to localStorage
function shortenUrl() {
  const url = document.getElementById("url").value;
  const expiry = document.getElementById("expiry").value;

  if (!url || !url.startsWith("http")) {
    alert("Please enter a valid URL including http:// or https://");
    return;
  }

  const code = generateShortCode();
  const expiresAt = calculateExpiry(expiry); // Ensure the expiry date is valid

  const newEntry = {
    original_url: url,
    shortened_url: `${shortUrlDomain}/${code}`,
    expires_at: expiresAt,
    clicks: 0,  // Initial click count is 0
  };

  // Save the new entry to the results
  results.unshift(newEntry);
  
  // Save updated results to localStorage
  localStorage.setItem("shortenedUrls", JSON.stringify(results));

  // Clear input fields and render the updated results
  document.getElementById("url").value = "";
  renderResults();
}

// Function to copy shortened URL to clipboard
function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(() => {
    alert("Copied to clipboard!");
  }, () => {
    alert("Failed to copy!");
  });
}

// Call renderResults to display saved URLs and their click counts on page load
document.addEventListener('DOMContentLoaded', renderResults);

</script> --}}

<script>
  const shortUrlDomain = "{{ rtrim(config('app.short_url_domain'), '/') }}";

  // Load results from localStorage on page load
  let results = JSON.parse(localStorage.getItem("shortenedUrls")) || [];

  // Function to render results from localStorage
  function renderResults() {
    const container = document.getElementById("results");
    container.innerHTML = "<h3>Your Shortened URLs</h3>";
    
    results.forEach((item, index) => {
      const expired = new Date() > new Date(item.expires_at);
      container.innerHTML += `
        <div class="result-card ${expired ? "expired" : ""}">
          <div class="url-display">
            <div>
              <p class="text-muted">Original:</p>
              <p class="original-url">${item.original_url}</p>
              <p class="text-muted">Shortened:</p>
              <p class="success"><a href="${item.shortened_url}" target="_blank" onclick="incrementClick(${index})">${item.shortened_url}</a></p>
            </div>
            <div class="url-actions">
              <button onclick="copyToClipboard('${item.shortened_url}')"><i class="lucide lucide-copy"></i></button>
              <button onclick="window.open('${item.original_url}', '_blank')"><i class="lucide lucide-external-link"></i></button>
            </div>
          </div>
          <div class="text-muted">
            Clicks: ${item.clicks} &nbsp;&nbsp; | &nbsp;&nbsp;
            ${expired ? "<span style='color:var(--destructive)'>Expired</span>" : "Expires: " + new Date(item.expires_at).toLocaleDateString()}
          </div>
        </div>
      `;
    });
  }

  // Function to handle click increment and update localStorage
  function incrementClick(index) {
    // Increment the click count for the clicked shortened URL
    results[index].clicks += 1;

    // Update the results in localStorage
    localStorage.setItem("shortenedUrls", JSON.stringify(results));

    // Re-render the results to reflect the updated click count
    renderResults();
  }

  // Function to generate random short code
  function generateShortCode() {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let result = "";
    for (let i = 0; i < 6; i++) {
      result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
  }

  // Calculate expiry date based on selection
  function calculateExpiry(option) {
    const now = new Date();
    switch (option) {
      case "1h": return new Date(now.getTime() + 60 * 60 * 1000);
      case "24h": return new Date(now.getTime() + 24 * 60 * 60 * 1000);
      case "7d": return new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
      case "30d": return new Date(now.getTime() + 30 * 24 * 60 * 60 * 1000);
      case "never": return new Date(now.getTime() + 100 * 365 * 24 * 60 * 60 * 1000);
      default: return now;
    }
  }

  // Function to shorten the URL
  function shortenUrl() {
    const url = document.getElementById("url").value;
    const expiry = document.getElementById("expiry").value;

    if (!url || !url.startsWith("http")) {
      alert("Please enter a valid URL including http:// or https://");
      return;
    }

    const code = generateShortCode();
    const expiresAt = calculateExpiry(expiry); // Get valid expiry date

    const newEntry = {
      original_url: url,
      shortened_url: `${shortUrlDomain}/${code}`,
      expires_at: expiresAt,
      clicks: 0,  // Initial click count is 0
    };

    // Save the new entry to the results
    results.unshift(newEntry);
    
    // Save updated results to localStorage
    localStorage.setItem("shortenedUrls", JSON.stringify(results));

    // Clear input fields and render the updated results
    document.getElementById("url").value = "";
    renderResults();

    // Ajax request to store data in the database
    $.ajax({
      url: '/shorten-url',
      type: 'POST',
      data: {
        _token: "{{ csrf_token() }}",  // CSRF token for security
        original_url: url,
        shortened_url: `${shortUrlDomain}/${code}`,
        expires_at: expiresAt,
      },
      success: function(response) {
        console.log('Shortened URL saved successfully:', response);
      },
      error: function(error) {
        console.error('Error saving shortened URL:', error);
      }
    });
  }

  // Function to copy shortened URL to clipboard
  function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
      alert("Copied to clipboard!");
    }, () => {
      alert("Failed to copy!");
    });
  }

  // Call renderResults to display saved URLs and their click counts on page load
  document.addEventListener('DOMContentLoaded', renderResults);

</script>



</body>
</html>

