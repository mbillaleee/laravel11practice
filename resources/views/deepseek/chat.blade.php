@extends('layouts.frontapp')

@section('title')
DeepSeek AI Chat
@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.chat-container {
    height: calc(100vh - 450px);
}

.message-user {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 18px 18px 4px 18px;
}

.message-bot {
    background-color: #f7f7f8;
    color: #333;
    border-radius: 18px 18px 18px 4px;
    border: 1px solid #e5e5e5;
}

.typing-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #8e8ea0;
    margin: 0 2px;
}

.typing-indicator:nth-child(1) {
    animation: bounce 1.3s infinite ease-in-out;
}

.typing-indicator:nth-child(2) {
    animation: bounce 1.3s infinite ease-in-out 0.15s;
}

.typing-indicator:nth-child(3) {
    animation: bounce 1.3s infinite ease-in-out 0.3s;
}

@keyframes bounce {

    0%,
    60%,
    100% {
        transform: translateY(0);
    }

    30% {
        transform: translateY(-10px);
    }
}

.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

@section('content')
<div class="flex flex-col h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-robot text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">DeepSeek AI Assistant</h1>
                        <div class="flex items-center space-x-4 text-sm">
                            <span id="apiStatus"
                                class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                <i class="fas fa-circle mr-1 text-gray-400"></i>Checking...
                            </span>
                            <span id="tokenCount" class="text-gray-500">Tokens: 0</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="clearChat"
                        class="flex items-center space-x-2 px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:border-gray-400 transition-colors">
                        <i class="fas fa-trash-alt"></i>
                        <span>Clear Chat</span>
                    </button>
                    <button id="testConnection"
                        class="flex items-center space-x-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-bolt"></i>
                        <span>Test API</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex-1 container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Chat Container -->
            <div class="chat-container overflow-y-auto p-6 bg-gray-50" id="chatContainer">
                <div class="max-w-4xl mx-auto space-y-6" id="messagesContainer">
                    <!-- Welcome Message -->
                    <div class="flex justify-start fade-in">
                        <div class="flex space-x-4 max-w-[85%]">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex-shrink-0 flex items-center justify-center">
                                <i class="fas fa-robot text-white"></i>
                            </div>
                            <div class="message-bot p-4 flex-1">
                                <p class="text-gray-800">Hello! I'm DeepSeek AI, your intelligent assistant. I can
                                    help you with various tasks including coding, writing, analysis, and more. How
                                    can I assist you today?</p>
                                <div class="mt-2 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Model: <span id="currentModel">deepseek-chat</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="border-t border-gray-200 bg-white p-6">
                <form id="chatForm" class="space-y-4">
                    @csrf
                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <input type="text" id="messageInput"
                                placeholder="Type your message here... (Press Enter to send)"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                required autocomplete="off">
                            <div id="validationMessage" class="text-red-500 text-sm mt-2 hidden"></div>
                        </div>
                        <button type="submit" id="sendButton"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Send</span>
                        </button>
                    </div>

                    <!-- Settings -->
                    <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600 bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <label class="font-medium">Model:</label>
                            <select id="modelSelect" class="border rounded px-3 py-1 bg-white">
                                <option value="deepseek-chat">DeepSeek Chat</option>
                                <option value="deepseek-coder">DeepSeek Coder</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="font-medium">Temperature:</label>
                            <input type="range" id="temperature" min="0" max="1" step="0.1" value="0.7" class="w-24">
                            <span id="tempValue" class="w-8 text-center">0.7</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="font-medium">Max Tokens:</label>
                            <input type="number" id="maxTokens" min="100" max="8000" value="2000"
                                class="border rounded px-3 py-1 w-20 bg-white">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- API Info Panel -->
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4 flex items-center space-x-2">
                <i class="fas fa-info-circle text-blue-500"></i>
                <span>API Information</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div class="flex items-center space-x-2">
                    <span class="font-medium">Status:</span>
                    <span id="apiStatusText"
                        class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Checking...</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="font-medium">Current Model:</span>
                    <span id="currentModelText" class="text-blue-600">deepseek-chat</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="font-medium">Total Tokens:</span>
                    <span id="totalTokens" class="text-green-600">0</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="font-medium">Messages:</span>
                    <span id="messageCount" class="text-purple-600">1</span>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const messagesContainer = document.getElementById('messagesContainer');
    const chatContainer = document.getElementById('chatContainer');
    const validationMessage = document.getElementById('validationMessage');
    const apiStatus = document.getElementById('apiStatus');
    const apiStatusText = document.getElementById('apiStatusText');
    const temperature = document.getElementById('temperature');
    const tempValue = document.getElementById('tempValue');
    const modelSelect = document.getElementById('modelSelect');
    const maxTokens = document.getElementById('maxTokens');
    const sendButton = document.getElementById('sendButton');
    const clearChat = document.getElementById('clearChat');
    const testConnection = document.getElementById('testConnection');
    const tokenCount = document.getElementById('tokenCount');
    const totalTokens = document.getElementById('totalTokens');
    const messageCount = document.getElementById('messageCount');
    const currentModelText = document.getElementById('currentModelText');
    const currentModel = document.getElementById('currentModel');

    // State
    let totalTokensUsed = 0;
    let messageCounter = 1;

    // Initialize
    checkApiStatus();
    updateModelDisplay();

    // Event Listeners
    temperature.addEventListener('input', function() {
        tempValue.textContent = this.value;
    });

    modelSelect.addEventListener('change', updateModelDisplay);

    chatForm.addEventListener('submit', handleFormSubmit);

    clearChat.addEventListener('click', clearChatHistory);

    testConnection.addEventListener('click', testApiConnection);

    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Functions
    function updateModelDisplay() {
        const model = modelSelect.value;
        currentModel.textContent = model;
        currentModelText.textContent = model;
    }

    async function handleFormSubmit(e) {
        e.preventDefault();

        const message = messageInput.value.trim();
        if (!message) {
            showValidation('Please enter a message');
            return;
        }

        // Clear input and validation
        messageInput.value = '';
        hideValidation();

        // Add user message
        addMessage(message, 'user');

        // Disable send button
        sendButton.disabled = true;
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Sending...</span>';

        // Show typing indicator
        showTypingIndicator();

        try {
            const response = await fetch('{{ route("deepseek.send.message") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: message,
                    model: modelSelect.value,
                    temperature: parseFloat(temperature.value),
                    max_tokens: parseInt(maxTokens.value)
                })
            });

            const data = await response.json();

            removeTypingIndicator();

            if (data.success) {
                addMessage(data.content, 'assistant');

                // Update token count
                if (data.usage) {
                    totalTokensUsed += data.usage.total_tokens;
                    tokenCount.textContent = `Tokens: ${totalTokensUsed}`;
                    totalTokens.textContent = totalTokensUsed;
                }
            } else {
                addMessage(`Error: ${data.error}`, 'assistant');
            }
        } catch (error) {
            removeTypingIndicator();
            addMessage('Error: Network error - please check your connection', 'assistant');
        } finally {
            // Re-enable send button
            sendButton.disabled = false;
            sendButton.innerHTML = '<i class="fas fa-paper-plane"></i><span>Send</span>';
        }
    }

    function addMessage(content, role) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'} fade-in`;

        const messageHTML = `
                        <div class="flex ${role === 'user' ? 'flex-row-reverse' : 'flex-row'} space-x-4 max-w-[85%]">
                            <div class="w-10 h-10 rounded-full ${role === 'user' ? 'bg-gray-400' : 'bg-gradient-to-r from-blue-500 to-purple-600'} flex-shrink-0 flex items-center justify-center">
                                <i class="${role === 'user' ? 'fas fa-user' : 'fas fa-robot'} text-white"></i>
                            </div>
                            <div class="${role === 'user' ? 'message-user' : 'message-bot'} p-4 flex-1">
                                <div class="whitespace-pre-wrap">${escapeHtml(content)}</div>
                                <div class="mt-2 text-xs ${role === 'user' ? 'text-blue-100' : 'text-gray-500'}">
                                    <i class="fas fa-clock mr-1"></i>
                                    ${new Date().toLocaleTimeString()}
                                </div>
                            </div>
                        </div>
                    `;

        messageDiv.innerHTML = messageHTML;
        messagesContainer.appendChild(messageDiv);
        scrollToBottom();

        // Update message count
        messageCounter++;
        messageCount.textContent = messageCounter;
    }

    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'flex justify-start fade-in';
        typingDiv.id = 'typingIndicator';

        const typingHTML = `
                        <div class="flex space-x-4 max-w-[85%]">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex-shrink-0 flex items-center justify-center">
                                <i class="fas fa-robot text-white"></i>
                            </div>
                            <div class="message-bot p-4">
                                <div class="flex space-x-1">
                                    <div class="typing-indicator"></div>
                                    <div class="typing-indicator"></div>
                                    <div class="typing-indicator"></div>
                                </div>
                            </div>
                        </div>
                    `;

        typingDiv.innerHTML = typingHTML;
        messagesContainer.appendChild(typingDiv);
        scrollToBottom();
    }

    function removeTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    function clearChatHistory() {
        messagesContainer.innerHTML = `
                        <div class="flex justify-start fade-in">
                            <div class="flex space-x-4 max-w-[85%]">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex-shrink-0 flex items-center justify-center">
                                    <i class="fas fa-robot text-white"></i>
                                </div>
                                <div class="message-bot p-4 flex-1">
                                    <p class="text-gray-800">Hello! I'm DeepSeek AI, your intelligent assistant. How can I help you today?</p>
                                </div>
                            </div>
                        </div>
                    `;

        totalTokensUsed = 0;
        messageCounter = 1;
        tokenCount.textContent = 'Tokens: 0';
        totalTokens.textContent = '0';
        messageCount.textContent = '1';

        scrollToBottom();
    }

    async function testApiConnection() {
        testConnection.disabled = true;
        testConnection.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Testing...</span>';

        try {
            const response = await fetch('{{ route("deepseek.test.connection") }}');
            const data = await response.json();

            if (data.success) {
                showNotification(`API Test Passed: ${data.response}`, 'success');
            } else {
                showNotification(`API Test Failed: ${data.error}`, 'error');
            }
        } catch (error) {
            showNotification('Network error during API test', 'error');
        } finally {
            testConnection.disabled = false;
            testConnection.innerHTML = '<i class="fas fa-bolt"></i><span>Test API</span>';
        }
    }

    async function checkApiStatus() {
        try {
            const response = await fetch('{{ route("deepseek.check.status") }}');
            const data = await response.json();

            if (data.valid) {
                apiStatus.innerHTML = '<i class="fas fa-circle mr-1 text-green-400"></i>Connected';
                apiStatus.className =
                    'px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                apiStatusText.textContent = 'Connected ✓';
            } else {
                apiStatus.innerHTML = '<i class="fas fa-circle mr-1 text-red-400"></i>Disconnected';
                apiStatus.className =
                    'px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800';
                apiStatusText.textContent = 'Disconnected ✗';
            }
        } catch (error) {
            apiStatus.innerHTML = '<i class="fas fa-circle mr-1 text-red-400"></i>Error';
            apiStatus.className = 'px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800';
            apiStatusText.textContent = 'Connection Failed';
        }
    }

    function showValidation(message) {
        validationMessage.textContent = message;
        validationMessage.classList.remove('hidden');
    }

    function hideValidation() {
        validationMessage.classList.add('hidden');
    }

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${
                        type === 'success' ? 'bg-green-500' : 'bg-red-500'
                    } fade-in`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
});
</script>
@endpush