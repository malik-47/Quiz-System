<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - Quiz Pro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="quiz-container container">
        <h2 class="question" id="question"></h2>
        <div class="progress-bar">
            <div class="progress-fill" id="progress" style="width: 0%"></div>
        </div>
        <div class="options" id="options"></div>
        <button class="next-btn" id="nextBtn" onclick="nextQuestion()" style="display: none;">Next Question</button>
        <div id="scoreModal" style="display: none; margin-top: 20px; padding: 20px; background: rgba(72, 187, 120, 0.2); border-radius: 10px;">
            <h3>Your Score: <span id="finalScore"></span>/20</h3>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>

    <script>
        let questions = [], currentIndex = 0, answers = {}, selectedOption = null;
        
        // Load questions
        fetch('../api/get_questions.php')
            .then(r => r.json())
            .then(data => {
                questions = data;
                updateProgress();
                showQuestion();
            });

        function showQuestion() {
            const q = questions[currentIndex];
            document.getElementById('question').textContent = q.question;
            
            let html = '';
            for (let j = 1; j <= 4; j++) {
                html += `<button class="option-btn" onclick="selectOption(${j})">${q['option' + j]}</button>`;
            }
            document.getElementById('options').innerHTML = html;
            
            document.getElementById('nextBtn').style.display = 'none';
            selectedOption = null;
        }

        function selectOption(optionNum) {
            // Remove previous selection
            document.querySelectorAll('.option-btn').forEach(btn => {
                btn.classList.remove('selected');
            });
            // Select current
            event.target.classList.add('selected');
            answers[questions[currentIndex].id] = optionNum;
            selectedOption = optionNum;
            document.getElementById('nextBtn').style.display = 'block';
        }

        function nextQuestion() {
            if (!selectedOption) return; // Must select option
            
            currentIndex++;
            if (currentIndex < questions.length) {
                showQuestion();
            } else {
                submitQuiz();
            }
            updateProgress();
        }

        function updateProgress() {
            const progress = ((currentIndex / questions.length) * 100);
            document.getElementById('progress').style.width = progress + '%';
        }

        function submitQuiz() {
            fetch('../api/submit.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(answers)
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('finalScore').textContent = data.score;
                document.getElementById('scoreModal').style.display = 'block';
                document.querySelector('.quiz-container').scrollIntoView({ behavior: 'smooth' });
            });
        }
    </script>
</body>
</html>
