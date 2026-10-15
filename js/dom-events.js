/* ==========================================================================
   Cybersecurity Awareness and Learning Portal
   Practical 05: JavaScript DOM Manipulation & Event Handling
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {

  /* ------------------------------------------------------------------------
     1. Interactive Password Strength Checker (Event: 'input')
     ------------------------------------------------------------------------ */
  const passwordInput = document.getElementById('passwordInput');
  const strengthMeter = document.getElementById('strengthMeter');
  const strengthFeedback = document.getElementById('strengthFeedback');

  if (passwordInput && strengthMeter && strengthFeedback) {
    passwordInput.addEventListener('input', function () {
      const password = this.value;
      const result = evaluatePasswordStrength(password);

      // Update meter width and color
      strengthMeter.style.width = result.percentage + '%';
      strengthMeter.style.backgroundColor = result.color;

      // Update descriptive text
      strengthFeedback.textContent = result.label;
      strengthFeedback.style.color = result.color;
    });
  }

  // Pure function to calculate score based on password entropy rules
  function evaluatePasswordStrength(password) {
    if (!password || password.length === 0) {
      return { percentage: 0, label: 'Enter a password to check', color: '#94a3b8' };
    }

    let score = 0;

    // Rule 1: Length check
    if (password.length >= 8) score += 25;
    if (password.length >= 12) score += 15;

    // Rule 2: Contains lowercase and uppercase letters
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score += 20;

    // Rule 3: Contains numerical digits
    if (/[0-9]/.test(password)) score += 20;

    // Rule 4: Contains special symbols (!@#$%^&* etc.)
    if (/[^A-Za-z0-9]/.test(password)) score += 20;

    if (score < 40) {
      return { percentage: score, label: 'Weak (Vulnerable to brute-force)', color: '#ef4444' };
    } else if (score < 75) {
      return { percentage: score, label: 'Moderate (Decent, but can be improved)', color: '#f59e0b' };
    } else {
      return { percentage: Math.min(score, 100), label: 'Strong (High cryptographic entropy)', color: '#10b981' };
    }
  }


  /* ------------------------------------------------------------------------
     2. Interactive Accordion (Event: 'click')
     ------------------------------------------------------------------------ */
  const accordionButtons = document.querySelectorAll('.accordion-btn');

  accordionButtons.forEach((btn) => {
    btn.addEventListener('click', function () {
      // Toggle the 'active' class on the clicked button
      this.classList.toggle('active');

      // The content container immediately following the button
      const content = this.nextElementSibling;
      if (content) {
        if (content.style.maxHeight) {
          content.style.maxHeight = null;
          content.style.padding = '0 1rem';
        } else {
          content.style.maxHeight = content.scrollHeight + 'px';
          content.style.padding = '0.8rem 1rem';
        }
      }
    });
  });

});
