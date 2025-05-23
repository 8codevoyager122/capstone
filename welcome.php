<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-language Code Editor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<nav class="border-gray-200 bg-blue-600 dark:bg-blue-600 dark:border-gray-700 mb-8">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
            <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">CodingMania</span>
        </a>
        <button data-collapse-toggle="navbar-solid-bg" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-solid-bg" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>
    </div>
</nav>

<div class="w-full max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">Multi-language Code Editor</h1>

    <!-- Language Selector -->
    <div class="flex justify-between items-center mb-6">
        <label for="language-select" class="text-lg font-medium text-gray-800">Select Language:</label>
        <select id="language-select" class="p-2 border border-gray-300 rounded-md">
            <option value="javascript">JavaScript</option>
            <option value="python">Python</option>
            <option value="php">PHP</option>
            <option value="cpp">C++</option>
        </select>
    </div>

    <!-- Question Section -->
    <div class="bg-blue-100 p-4 rounded-md mb-6">
        <h2 class="text-lg font-semibold text-blue-700">Question:</h2>
       
        <?php
        include 'question.php';
        ?>
    </div>

    <!-- Code Input Section -->
    <div class="flex flex-col space-y-4">
        <label for="code-input" class="text-gray-800 font-medium">Write your code:</label>
        <textarea id="code-input" rows="10"
            class="w-full p-4 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
            placeholder="Write your code here..."></textarea>
    </div>

    <!-- Buttons and Chatbot Section -->
    <div class="flex items-center justify-between mt-4 space-x-4">

        <div class="flex-grow flex justify-center">
            <button id="chatbot"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">
                Chat with us 
            </button>
        </div>

        <!-- Run Code Button -->
        <div class="flex-grow flex justify-center">
            <button id="run-code"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">
                Run Code
            </button>
        </div>

        <!-- Show Answer Button -->
        <button id="show-answer"
            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none">
            Show Answer
        </button>

         <!-- Next Question Button -->
        <button id="next-question"
        class="px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none">
        Next Question
    </button>

    



    </div>

    <!-- Output Section -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-800">Output:</h3>
        <pre id="code-output" class="mt-2 bg-gray-900 text-white p-4 rounded-md overflow-auto"></pre>
    </div>

    <!-- Chatbox Section -->
    <div id="chatbox" class="fixed top-0 left-0 h-full w-1/5 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 p-6 z-50">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Chat with Us</h2>
            <button id="close-chatbox" class="text-gray-600 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <div class="flex flex-col space-y-4 h-full">
            <div class="flex-grow overflow-y-auto bg-gray-100 p-4 rounded-md" id="chat-content">
                <!-- Chat messages will appear here -->
            </div>
            <input type="text" id="chat-input" class="border border-gray-300 p-2 rounded-md" placeholder="Type your message...">
            <button id="send-message" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mt-2 mb-8">Send</button>
        </div>
    </div>

</div>

<script>
    // Toggle chatbox visibility
    const chatbox = document.getElementById('chatbox');
    document.getElementById('chatbot').addEventListener('click', () => {
        chatbox.classList.toggle('-translate-x-full');
    });

    // Close chatbox when close button is clicked
    document.getElementById('close-chatbox').addEventListener('click', () => {
        chatbox.classList.add('-translate-x-full');
    });

    // Function to send the message

    // Function to send the message along with the code and language context
    function sendMessage() {
    const chatInput = document.getElementById('chat-input');
    const chatContent = document.getElementById('chat-content');
    if (chatInput.value.trim()) {
        // Display the user's message in the chatbox
        const message = document.createElement('div');
        message.className = 'bg-blue-500 text-white p-2 rounded-lg mb-2 self-end';
        message.textContent = chatInput.value;
        chatContent.appendChild(message);
        // Send the message to PHP to get the ChatGPT response
        fetch('get_response.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ prompt: chatInput.value })
        })
        .then(response => response.json())
        .then(data => {
            const botMessage = document.createElement('div');
            botMessage.className = 'bg-gray-300 text-black p-2 rounded-lg mb-2 self-start';
            botMessage.textContent = data.response;
            chatContent.appendChild(botMessage);
        })
        .catch(error => {
            console.error('Error:', error);
        });
        // Clear the input
        chatInput.value = '';
    }
}
    // Send message when clicking the "Send" button
    document.getElementById('send-message').addEventListener('click', sendMessage);
    // Send message when pressing Enter in the chat input
    document.getElementById('chat-input').addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent newline in the input field
            sendMessage();
        }
    });

    
</script>

<script>
    // Mapping frontend language selector values to RapidAPI's expected values
    const languageMap = {
        javascript: 'nodejs',
        python: 'python3',
        php: 'php',
        cpp: 'cpp'
    };

    // Placeholder answers for different languages
    const answers = {
        javascript: 'console.log("Hello, World!");',
        python: 'print("Hello, World!")',
        php: '<?php echo "Hello, World!"; ?>',
        cpp: '#include <iostream>\nusing namespace std;\nint main() {\n    cout << "Hello, World!";\n    return 0;\n}'
    };

    // Run Code Button Event
    document.getElementById('run-code').addEventListener('click', () => {
        const language = document.getElementById('language-select').value; // Get selected language
    const question = document.querySelector(".bg-blue-100 p").textContent.trim(); // Get the question text
    const codeOutput = document.getElementById('code-output'); // Output element

    // Show loading message
    codeOutput.textContent = "Processing your question...";

    // Send the question to RapidAPI
    const data = JSON.stringify({
        language: languageMap[language], // Map frontend language selection to RapidAPI's language identifier
        version: 'latest', // Use the latest version
        code: /* Question: ${question} */, // Attach the question as a comment in the code
        input: null
    });

    const xhr = new XMLHttpRequest();
    xhr.withCredentials = false;

    xhr.addEventListener('readystatechange', function () {
        if (this.readyState === this.DONE) {
            try {
                const response = JSON.parse(this.responseText);
                codeOutput.textContent = response.output || 'No output or execution error.';
            } catch (error) {
                codeOutput.textContent = 'Error parsing response.';
            }
        }
    });


    xhr.open('POST', 'https://online-code-compiler.p.rapidapi.com/v1/');
xhr.setRequestHeader('x-rapidapi-key', 'd3dfff1ac0msh311292519da91c4p17dab9jsna2ce47125585');
xhr.setRequestHeader('x-rapidapi-host', 'online-code-compiler.p.rapidapi.com');
xhr.setRequestHeader('Content-Type', 'application/json');

xhr.send(data);
    });

    // Show Answer Button Event
    


</script>

<script>
    let currentId = 1; // Start with question ID 1

// Event Listener for the Next Question Button
document.getElementById('next-question').addEventListener('click', () => {
    currentId++; // Increment the question ID

    // Fetch the next question from the server
    fetch(`question.php?id=${currentId}`) // ✅ Corrected string interpolation
        .then(response => response.text()) // Expect text response
        .then(data => {
            // ✅ Ensure there's an element with class 'bg-blue-100'
            const questionContainer = document.querySelector('.bg-blue-100');
            if (questionContainer) {
                questionContainer.innerHTML = `<h2 class="text-lg font-semibold text-blue-700">Question:</h2><p>${data}</p>`;
            } else {
                console.error("Error: Question container not found.");
            }
        })
        .catch(error => {
            console.error('Error fetching the next question:', error);
        });
});

// Show Answer Button Event
document.getElementById('show-answer').addEventListener('click', () => {
        const language = document.getElementById('language-select').value;
        const codeOutput = document.getElementById('code-output');
        codeOutput.textContent = `Correct Answer:\n${answers[language]}`;
    });


</script>



</body>

</html>
