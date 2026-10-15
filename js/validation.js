/* ==========================================================================
   Cybersecurity Awareness and Learning Portal
   Practical 06: Client-Side Form Validation using JavaScript & Regular Expressions
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  const pledgeForm = document.getElementById('pledgeForm');
  const formStatus = document.getElementById('formStatus');

  if (!pledgeForm) return;

  pledgeForm.addEventListener('submit', function (event) {
    // Prevent the default browser form submission
    event.preventDefault();

    // Clear previous error messages
    clearErrors();

    // Fetch form input values
    const fullName = document.getElementById('fullName').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const category = document.getElementById('category').value;
    const agreePledge = document.getElementById('agreePledge').checked;

    let isValid = true;

    // 1. Validate Full Name (Must not be empty, min 3 chars, letters and spaces only)
    const nameRegex = /^[A-Za-z\s]{3,}$/;
    if (!nameRegex.test(fullName)) {
      showError('fullNameError', 'Please enter a valid full name (minimum 3 letters).');
      isValid = false;
    }

    // 2. Validate Email using Standard Regular Expression
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      showError('emailError', 'Please enter a valid email address (e.g., student@college.edu).');
      isValid = false;
    }

    // 3. Validate Phone Number (Exact 10 digits)
    const phoneRegex = /^[0-9]{10}$/;
    if (!phoneRegex.test(phone)) {
      showError('phoneError', 'Please enter a valid 10-digit mobile number.');
      isValid = false;
    }

    // 4. Validate Category selection
    if (!category) {
      showError('categoryError', 'Please select your role / affiliation.');
      isValid = false;
    }

    // 5. Validate Checkbox Agreement
    if (!agreePledge) {
      showError('pledgeError', 'You must agree to uphold cyber hygiene practices.');
      isValid = false;
    }

    // If all validation rules pass
    if (isValid) {
      formStatus.innerHTML = `
        <div class="success-message">
          <strong>✅ Thank you, ${escapeHTML(fullName)}!</strong><br>
          Your Cyber Safety Pledge has been validated successfully on the client side.<br>
          A confirmation token has been generated: <code>CYBER-PLG-${Math.floor(100000 + Math.random() * 900000)}</code>.
        </div>
      `;
      pledgeForm.reset();
    } else {
      formStatus.innerHTML = '';
    }
  });

  // Helper function to display error message under a field
  function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
      errorElement.textContent = message;
    }
  }

  // Helper function to clear all error messages
  function clearErrors() {
    const errorElements = document.querySelectorAll('.error-message');
    errorElements.forEach((el) => {
      el.textContent = '';
    });
  }

  // Helper function to prevent Cross-Site Scripting (XSS) in output
  function escapeHTML(str) {
    return str.replace(/[&<>'"]/g, 
      tag => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#39;',
        '"': '&quot;'
      }[tag] || tag)
    );
  }
});
