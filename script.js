// Contact form validation
document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const message = document.getElementById('message').value.trim();

            if (name === '' || email === '' || message === '') {
                alert("All fields are required!");
                e.preventDefault();
            } else if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
                alert("Please enter a valid email.");
                e.preventDefault();
            }
        });
    }

    // Quiz scoring
    const quizForm = document.getElementById('quizForm');
    if (quizForm) {
        quizForm.addEventListener('submit', function (e) {
            e.preventDefault();
            let score = 0;

            const answers = {
                q1: 'b',
                q2: 'c',
                q3: 'c',
                q4: 'c',
                q5: 'a'
            };

            Object.keys(answers).forEach((q, index) => {
                const selected = document.querySelector(`input[name="${q}"]:checked`);
                if (selected && selected.value === answers[q]) {
                    score++;
                }
            });

            const resultDiv = document.getElementById('quizResult');
            resultDiv.innerHTML = `<h3>Your Score: ${score} / ${Object.keys(answers).length}</h3>`;
        });
    }
});
