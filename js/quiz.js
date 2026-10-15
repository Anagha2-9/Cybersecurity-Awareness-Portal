/* ==========================================================================
   Cybersecurity Awareness and Learning Portal
   Practical 07: Dynamic Cybersecurity Quiz using JavaScript
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  const quizForm = document.getElementById('quizForm');
  const questionsContainer = document.getElementById('questionsContainer');
  const resultCard = document.getElementById('resultCard');
  const scoreText = document.getElementById('scoreText');
  const scoreBadge = document.getElementById('scoreBadge');
  const retakeBtn = document.getElementById('retakeBtn');

  // Array of Quiz Question Objects
  const quizQuestions = [
    {
      id: 1,
      question: "Which of the following is the STRONGEST password practice?",
      options: [
        "Using your birth year with an exclamation mark (e.g. Rahul!2004)",
        "A long passphrase of 4+ random words with numbers/symbols (e.g. blue$Turtle#Jump88)",
        "Reusing a 16-character password across all social media and college portals",
        "Writing your password on a sticky note attached to your monitor"
      ],
      correctIndex: 1,
      explanation: "Long passphrases have higher cryptographic entropy, making them exponentially harder to crack using dictionary and brute-force attacks."
    },
    {
      id: 2,
      question: "You receive an urgent email from your college dean asking you to click a link to avoid account suspension. What should you do?",
      options: [
        "Click the link immediately to prevent losing your college access",
        "Reply with your student ID, password, and contact phone number",
        "Inspect the sender's actual email address, don't click, and verify with college IT",
        "Forward the email to all your classmates to warn them immediately"
      ],
      correctIndex: 2,
      explanation: "Urgency and fear are classic social engineering tactics in phishing. Always independently verify through official college channels."
    },
    {
      id: 3,
      question: "What does the 'S' in 'HTTPS' stand for?",
      options: [
        "System",
        "Secure (encrypted with SSL/TLS)",
        "Speed",
        "Standard"
      ],
      correctIndex: 1,
      explanation: "HTTPS encrypts the traffic between your web browser and the server using SSL/TLS, preventing eavesdropping and Man-in-the-Middle attacks."
    },
    {
      id: 4,
      question: "Why is public Wi-Fi at airports or coffee shops risky without a VPN?",
      options: [
        "It uses too much laptop battery",
        "Public Wi-Fi traffic can be intercepted by anyone on the same network",
        "It disables your computer's firewall permanently",
        "It automatically installs malware on your hard drive"
      ],
      correctIndex: 1,
      explanation: "Unencrypted or shared public Wi-Fi networks allow attackers on the same network to sniff packets or run spoofing attacks."
    },
    {
      id: 5,
      question: "Which cybersecurity pillar ensures that data cannot be tampered with or modified unauthorizedly?",
      options: [
        "Confidentiality",
        "Integrity",
        "Availability",
        "Authentication"
      ],
      correctIndex: 1,
      explanation: "Integrity (from the CIA triad) ensures that data remains accurate and unaltered during storage and transmission."
    }
  ];

  // Function to render questions dynamically into the DOM
  function renderQuiz() {
    if (!questionsContainer) return;
    questionsContainer.innerHTML = '';

    quizQuestions.forEach((q, qIndex) => {
      const questionCard = document.createElement('div');
      questionCard.className = 'card';
      questionCard.style.marginBottom = '1.2rem';
      questionCard.id = `qCard_${qIndex}`;

      const optionsHTML = q.options.map((opt, optIndex) => `
        <label style="display: flex; align-items: flex-start; gap: 0.6rem; margin: 0.6rem 0; cursor: pointer; padding: 0.4rem; border-radius: 4px; background-color: var(--bg-primary);">
          <input type="radio" name="question_${qIndex}" value="${optIndex}" style="margin-top: 0.2rem;" required>
          <span>${escapeHTML(opt)}</span>
        </label>
      `).join('');

      questionCard.innerHTML = `
        <h4 style="color: var(--accent-cyan); margin-bottom: 0.6rem;">
          Question ${qIndex + 1}: ${escapeHTML(q.question)}
        </h4>
        <div class="options-group">
          ${optionsHTML}
        </div>
        <div id="explanation_${qIndex}" style="display: none; margin-top: 0.8rem; font-size: 0.9rem; padding: 0.6rem; border-radius: 4px;"></div>
      `;

      questionsContainer.appendChild(questionCard);
    });
  }

  // Handle Quiz Submission & Score Calculation
  if (quizForm) {
    quizForm.addEventListener('submit', function (event) {
      event.preventDefault();

      let score = 0;
      const total = quizQuestions.length;

      quizQuestions.forEach((q, qIndex) => {
        const selectedRadio = document.querySelector(`input[name="question_${qIndex}"]:checked`);
        const explanationBox = document.getElementById(`explanation_${qIndex}`);

        if (selectedRadio) {
          const selectedAnswer = parseInt(selectedRadio.value, 10);
          
          if (selectedAnswer === q.correctIndex) {
            score++;
            explanationBox.style.display = 'block';
            explanationBox.style.backgroundColor = 'rgba(16, 185, 129, 0.15)';
            explanationBox.style.border = '1px solid var(--accent-green)';
            explanationBox.style.color = 'var(--accent-green)';
            explanationBox.innerHTML = `✅ <strong>Correct!</strong> ${escapeHTML(q.explanation)}`;
          } else {
            explanationBox.style.display = 'block';
            explanationBox.style.backgroundColor = 'rgba(239, 68, 68, 0.15)';
            explanationBox.style.border = '1px solid var(--accent-red)';
            explanationBox.style.color = 'var(--accent-red)';
            explanationBox.innerHTML = `❌ <strong>Incorrect.</strong> Correct answer: <em>${escapeHTML(q.options[q.correctIndex])}</em>.<br>${escapeHTML(q.explanation)}`;
          }
        }
      });

      // Display Final Results Card
      if (resultCard && scoreText && scoreBadge) {
        resultCard.style.display = 'block';
        scoreText.textContent = `You scored ${score} out of ${total} points (${Math.round((score / total) * 100)}%)!`;

        if (score === total) {
          scoreBadge.className = 'badge badge-success';
          scoreBadge.textContent = 'Cyber Master 🏆';
        } else if (score >= 3) {
          scoreBadge.className = 'badge badge-warning';
          scoreBadge.textContent = 'Cyber Aware 🛡️';
        } else {
          scoreBadge.className = 'badge badge-danger';
          scoreBadge.textContent = 'Needs Review ⚠️';
        }

        // Smoothly scroll to the result
        resultCard.scrollIntoView({ behavior: 'smooth' });
      }
    });
  }

  // Handle Retake Button
  if (retakeBtn) {
    retakeBtn.addEventListener('click', () => {
      quizForm.reset();
      resultCard.style.display = 'none';

      // Hide all explanations
      quizQuestions.forEach((_, qIndex) => {
        const explanationBox = document.getElementById(`explanation_${qIndex}`);
        if (explanationBox) explanationBox.style.display = 'none';
      });

      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

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

  // Initial render
  renderQuiz();
});
