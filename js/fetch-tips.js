/* ==========================================================================
   Cybersecurity Awareness and Learning Portal
   Practical 08: Asynchronous JavaScript (Fetch API & Promises)
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  const tipsContainer = document.getElementById('tipsContainer');
  const randomTipBox = document.getElementById('randomTipBox');
  const randomTipBtn = document.getElementById('randomTipBtn');

  let loadedTips = [];

  // Function to asynchronously fetch cybersecurity tips from local JSON
  async function loadCyberTips() {
    try {
      if (tipsContainer) {
        tipsContainer.innerHTML = '<p style="color: var(--text-muted);">Fetching tips asynchronously via Fetch API...</p>';
      }

      // Modern Fetch API with async/await
      const response = await fetch('data/tips.json');

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      // Parsing the JSON body
      loadedTips = await response.json();

      // Render the full list of tips
      renderTips(loadedTips);

      // Display initial random tip
      displayRandomTip();

    } catch (error) {
      console.error('Error fetching cyber tips:', error);
      if (tipsContainer) {
        tipsContainer.innerHTML = `
          <div class="card" style="border-color: var(--accent-red);">
            <p style="color: var(--accent-red);">⚠️ Failed to load tips via Fetch API.</p>
            <p style="font-size: 0.85rem; color: var(--text-muted);">
              Note: Modern browsers block local <code>file://</code> fetch requests due to CORS security policies. 
              Run this page via a local web server (e.g. Node server or XAMPP) to see Fetch API in action.
            </p>
          </div>
        `;
      }
    }
  }

  // Render all tips into the container
  function renderTips(tips) {
    if (!tipsContainer) return;
    tipsContainer.innerHTML = '';

    tips.forEach((tip) => {
      const tipCard = document.createElement('div');
      tipCard.className = 'card';
      tipCard.style.marginBottom = '1rem';

      tipCard.innerHTML = `
        <div class="flex-between">
          <h4 style="color: var(--accent-cyan);">${escapeHTML(tip.title)}</h4>
          <span class="badge badge-info">${escapeHTML(tip.category)}</span>
        </div>
        <p style="margin-top: 0.5rem; color: var(--text-muted); font-size: 0.95rem;">
          ${escapeHTML(tip.description)}
        </p>
      `;

      tipsContainer.appendChild(tipCard);
    });
  }

  // Pick and display a random tip
  function displayRandomTip() {
    if (!randomTipBox || loadedTips.length === 0) return;

    const randomIndex = Math.floor(Math.random() * loadedTips.length);
    const tip = loadedTips[randomIndex];

    randomTipBox.innerHTML = `
      <div style="background-color: var(--bg-primary); padding: 1rem; border-radius: 6px; border-left: 4px solid var(--accent-green);">
        <strong style="color: var(--accent-green); font-size: 1.05rem;">💡 ${escapeHTML(tip.title)}</strong>
        <p style="margin-top: 0.4rem; color: var(--text-main); font-size: 0.95rem;">${escapeHTML(tip.description)}</p>
        <small style="color: var(--accent-cyan); display: inline-block; margin-top: 0.4rem;">Category: ${escapeHTML(tip.category)}</small>
      </div>
    `;
  }

  // Button event to refresh random tip
  if (randomTipBtn) {
    randomTipBtn.addEventListener('click', displayRandomTip);
  }

  // Simple string sanitization to prevent XSS
  function escapeHTML(str) {
    return String(str).replace(/[&<>'"]/g, 
      tag => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#39;',
        '"': '&quot;'
      }[tag] || tag)
    );
  }

  // Trigger initial fetch
  loadCyberTips();
});
