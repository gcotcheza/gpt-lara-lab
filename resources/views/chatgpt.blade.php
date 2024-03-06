<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with GPT</title>
    <!-- Optionally include a CSS framework like Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add your custom styles here */
        .container {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Chat with me</h1>
        <form id="chatForm">
            <div class="mb-3">
                <label for="prompt" class="form-label">Enter your prompt:</label>
                <textarea class="form-control" id="prompt" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
        <div id="response" class="mt-4">
            <!-- The response will appear here -->
        </div>
    </div>

    <script>
        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var prompt = document.getElementById('prompt').value;
            fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token for Laravel
                    },
                    body: JSON.stringify({
                        prompt: prompt
                    })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('response').innerText = data.response;
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>
