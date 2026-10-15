/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 06: JavaScript File Linking, Events & Array Functions
 * ============================================================================
 * 
 * Concepts Demonstrated:
 * - External JS file linking
 * - JavaScript event listeners ('click', 'change', 'input')
 * - Core Functional Array Methods:
 *   1. forEach()  - Iterating and DOM generation
 *   2. filter()   - Filtering datasets by condition
 *   3. map()      - Transforming data structures
 *   4. reduce()   - Aggregating statistical sums
 *   5. find()     - Searching single element by criteria
 *   6. some()     - Boolean test: at least one match
 *   7. every()    - Boolean test: all items match
 */

document.addEventListener('DOMContentLoaded', () => {

  // Master Dataset of Cyber Threats
  const threatsDataset = [
    { id: 1, name: 'Phishing Scam', severity: 'High', score: 8, target: 'User Passwords' },
    { id: 2, name: 'Ransomware Extortion', severity: 'Critical', score: 10, target: 'Hospital & Enterprise Files' },
    { id: 3, name: 'Keylogger Spyware', severity: 'High', score: 7, target: 'Keystrokes & Banking' },
    { id: 4, name: 'Adware Popups', severity: 'Low', score: 3, target: 'Browser Navigation' },
    { id: 5, name: 'DDoS Flooding', severity: 'Medium', score: 6, target: 'Web Server Availability' },
    { id: 6, name: 'SQL Injection', severity: 'Critical', score: 10, target: 'Backend Relational Databases' }
  ];

  const resultsConsole = document.getElementById('resultsConsole');
  const renderedList   = document.getElementById('renderedList');

  // --------------------------------------------------------------------------
  // 1. Array.prototype.forEach() -> Initial Render of All Items
  // --------------------------------------------------------------------------
  function renderThreatCards(threats) {
    if (!renderedList) return;
    renderedList.innerHTML = '';

    threats.forEach((t) => {
      const card = document.createElement('div');
      card.className = 'card';
      card.style.background = 'var(--bg-primary)';
      card.style.marginBottom = '0.8rem';
      card.style.padding = '0.8rem 1rem';

      let badgeClass = 'badge-info';
      if (t.severity === 'Critical') badgeClass = 'badge-danger';
      else if (t.severity === 'High') badgeClass = 'badge-warning';

      card.innerHTML = `
        <div class="flex-between">
          <strong>#${t.id}: ${escapeHTML(t.name)}</strong>
          <span class="badge ${badgeClass}">${t.severity} (Risk: ${t.score}/10)</span>
        </div>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.3rem;">
          Primary Target: <em>${escapeHTML(t.target)}</em>
        </p>
      `;
      renderedList.appendChild(card);
    });
  }

  // Helper log function to display output in interactive console
  function logResult(title, description, rawOutput) {
    if (!resultsConsole) return;
    resultsConsole.innerHTML = `
      <div style="border-left: 4px solid var(--accent-cyan); padding-left: 0.8rem; margin-bottom: 0.8rem;">
        <h4 style="color: var(--accent-cyan); margin: 0;">${escapeHTML(title)}</h4>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0.3rem 0;">${escapeHTML(description)}</p>
      </div>
      <pre style="background: #090d16; padding: 0.8rem; border-radius: 4px; color: #a7f3d0; font-family: var(--font-mono); font-size: 0.85rem; overflow-x: auto;">${escapeHTML(JSON.stringify(rawOutput, null, 2))}</pre>
    `;
  }

  // --------------------------------------------------------------------------
  // 2. Array.prototype.filter() -> Event: 'click' on Filter Button
  // --------------------------------------------------------------------------
  document.getElementById('btnFilter')?.addEventListener('click', () => {
    // Return only threats with Critical severity
    const criticalThreats = threatsDataset.filter(t => t.severity === 'Critical');
    logResult(
      'Array.filter() Result',
      'Filtered threats where item.severity === "Critical":',
      criticalThreats
    );
    renderThreatCards(criticalThreats);
  });

  // --------------------------------------------------------------------------
  // 3. Array.prototype.map() -> Event: 'click' on Map Button
  // --------------------------------------------------------------------------
  document.getElementById('btnMap')?.addEventListener('click', () => {
    // Transform array of objects into array of formatted string labels
    const threatLabels = threatsDataset.map(t => `[${t.severity.toUpperCase()}] ${t.name} -> Targets: ${t.target}`);
    logResult(
      'Array.map() Result',
      'Transformed array into customized string representations:',
      threatLabels
    );
  });

  // --------------------------------------------------------------------------
  // 4. Array.prototype.reduce() -> Event: 'click' on Reduce Button
  // --------------------------------------------------------------------------
  document.getElementById('btnReduce')?.addEventListener('click', () => {
    // Calculate total aggregate risk score and average
    const totalScore = threatsDataset.reduce((accumulator, item) => accumulator + item.score, 0);
    const avgScore = (totalScore / threatsDataset.length).toFixed(2);
    
    logResult(
      'Array.reduce() Result',
      'Aggregated sum of all threat score values into a single summary:',
      {
        totalThreats: threatsDataset.length,
        cumulativeRiskScore: totalScore,
        averageRiskScore: parseFloat(avgScore)
      }
    );
  });

  // --------------------------------------------------------------------------
  // 5. Array.prototype.find() -> Event: 'click' on Find Button
  // --------------------------------------------------------------------------
  document.getElementById('btnFind')?.addEventListener('click', () => {
    // Find first threat targeting database
    const foundItem = threatsDataset.find(t => t.target.includes('Database'));
    logResult(
      'Array.find() Result',
      'Located first element matching target.includes("Database"):',
      foundItem || 'None found'
    );
  });

  // --------------------------------------------------------------------------
  // 6. Array.prototype.some() and every() -> Event: 'click'
  // --------------------------------------------------------------------------
  document.getElementById('btnSomeEvery')?.addEventListener('click', () => {
    const hasCritical = threatsDataset.some(t => t.severity === 'Critical');
    const allHigh = threatsDataset.every(t => t.score >= 5);

    logResult(
      'Array.some() & Array.every() Results',
      'Boolean predicate assertions across the collection:',
      {
        'some(severity === "Critical")': hasCritical,
        'every(score >= 5)': allHigh
      }
    );
  });

  // --------------------------------------------------------------------------
  // 7. Reset / Show All Button
  // --------------------------------------------------------------------------
  document.getElementById('btnReset')?.addEventListener('click', () => {
    logResult('All Threats Loaded', 'Displaying all 6 default array records:', threatsDataset);
    renderThreatCards(threatsDataset);
  });

  // Helper function to prevent XSS
  function escapeHTML(str) {
    return String(str).replace(/[&<>'"]/g, tag => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      "'": '&#39;',
      '"': '&quot;'
    }[tag] || tag));
  }

  // Initial execution on page load
  renderThreatCards(threatsDataset);
  logResult('Practical 06 Initialized', 'Click buttons above to trigger JavaScript events and execute array methods.', threatsDataset);
});
