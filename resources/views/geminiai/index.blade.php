@extends('layouts.frontapp')


<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

body {
    font-family: 'Inter', sans-serif;
}

.chat-container {
    height: calc(100vh - 80px);
}

.message-bubble-user {
    border-radius: 18px 18px 4px 18px;
}

.message-bubble-bot {
    border-radius: 18px 18px 18px 4px;
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
</style>

<style>
.chatbot-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

.chatbot-window {
    width: 380px;
    height: 600px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    overflow: hidden;
}

.chatbot-window.active {
    display: flex;
}

.chatbot-header {
    background: linear-gradient(to right, #3b82f6, #8b5cf6);
    color: white;
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.chatbot-messages {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
    background-color: #f9fafb;
}

.chatbot-input-area {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    background: white;
}

.message-bubble-user {
    border-radius: 18px 18px 4px 18px;
    background-color: #3b82f6;
    color: white;
    padding: 12px 16px;
    margin-bottom: 12px;
    max-width: 80%;
    align-self: flex-end;
}

.message-bubble-bot {
    border-radius: 18px 18px 18px 4px;
    background-color: white;
    color: #1f2937;
    padding: 12px 16px;
    margin-bottom: 12px;
    max-width: 80%;
    align-self: flex-start;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
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

.chatbot-toggle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(to right, #3b82f6, #8b5cf6);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.5);
    cursor: pointer;
    transition: transform 0.3s ease;
}

.chatbot-toggle:hover {
    transform: scale(1.05);
}

.validation-message {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 4px;
    margin-left: 16px;
    display: none;
}

.validation-message.show {
    display: block;
}

.input-error {
    border-color: #ef4444 !important;
    background-color: #fef2f2 !important;
}
</style>



@section('content')

<div class="flex flex-col h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm py-4 px-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div
                class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                <i class="fas fa-robot text-white"></i>
            </div>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Gemini AI</h1>
                <p class="text-xs text-green-500 flex items-center">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                    Online
                </p>
            </div>
        </div>
        <div class="flex space-x-4">
            <button class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
    </header>

    <!-- Input Area -->
    <div class="bg-white border-t border-gray-200 p-4">
        <div class="max-w-3xl mx-auto">
            <form action="{{ route('generateAnawers')}}" method="post">
                @csrf
                <div class="flex space-x-4">
                    <div class="flex-1 relative">
                        <input type="text" placeholder="Message Gemini..."
                            class="w-full py-3 px-4 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                            name="type" id="type">
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex space-x-2">
                            <button class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <button class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-microphone"></i>
                            </button>
                        </div>
                    </div>
                    <button
                        class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center hover:bg-blue-700 transition-colors">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                @error('type')
                <span class="text-red-500 ml-4">{{ $message }}</span>
                @enderror
            </form>
        </div>

    </div>
    <div class="chat-container overflow-y-auto p-4 bg-gradient-to-b from-gray-50 to-gray-100">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Welcome Message -->
            @isset($responseresult)
            <!-- User Message -->
            <div class="flex justify-end">
                <div class="flex space-x-3 max-w-[80%]">
                    <div class="bg-blue-600 message-bubble-user p-4 shadow-sm">
                        <p class="text-white whitespace-pre-wrap- w-full">
                            {{ $type }}
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex-shrink-0 flex items-center justify-center">
                        <i class="fas fa-user text-gray-600 text-sm"></i>
                    </div>
                </div>
            </div>
            <div class="flex justify-start">
                <div class="flex space-x-3 max-w-[80%]">
                    <!-- Avatar -->
                    <div
                        class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex-shrink-0 flex items-center justify-center">
                        <i class="fas fa-robot text-white text-sm"></i>
                    </div>

                    <!-- Message bubble -->
                    <div class="bg-white message-bubble-bot p-4 shadow-sm rounded-md">
                        <p class="text-gray-800 whitespace-pre-wrap w-full">
                            {{ $responseresult }}
                        </p>
                    </div>
                </div>
            </div>

            @endisset
        </div>
    </div>
</div>

<div class="chatbot-container">
    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center">
                    <i class="fas fa-robot text-purple-600"></i>
                </div>
                <div>
                    <h2 class="font-semibold">Gemini AI Assistant</h2>
                    <p class="text-xs opacity-80">Online • Always here to help</p>
                </div>
            </div>
            <button id="closeChatbot" class="text-white opacity-80 hover:opacity-100">
                <i class="fas fa-times text-red-500"></i>
            </button>
        </div>

        <div class="chatbot-messages flex flex-col whitespace-pre-wrap" id="chatbotMessages">
            <!-- Welcome message -->
            <div class="message-bubble-bot">
                <p>Hello! I'm Gemini AI, your helpful assistant. How can I help you today?</p>
            </div>
        </div>

        <div class="chatbot-input-area">
            <form id="chatbotForm" method="POST">
                @csrf
                <div class="flex space-x-2">
                    <div class="flex-1 relative">
                        <input type="text" placeholder="Type your message..."
                            class="w-full py-3 px-4 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                            name="type" id="chatInput" autocomplete="off">
                    </div>
                    <button type="submit"
                        class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center hover:bg-blue-700 transition-colors"
                        id="sendButton">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="validation-message" id="validationMessage">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <span id="validationText">Message cannot be empty</span>
                </div>
            </form>
        </div>
    </div>

    <div class="chatbot-toggle" id="chatbotToggle">
        <i class="fas fa-comments text-xl"></i>
    </div>
</div>
@endsection



@push('js')

<script>
$(document).ready(function() {
    // Toggle chatbot window
    $('#chatbotToggle').click(function() {
        $('#chatbotWindow').addClass('active');
        $('#chatInput').focus();
    });

    // Close chatbot window
    $('#closeChatbot').click(function() {
        $('#chatbotWindow').removeClass('active');
    });

    // Handle form submission
    $('#chatbotForm').on('submit', function(e) {
        e.preventDefault();

        const message = $('#chatInput').val().trim();

        // Validate message
        if (!message) {
            showValidation('Message cannot be empty');
            return;
        }

        if (message.length > 500) {
            showValidation('Message is too long (max 500 characters)');
            return;
        }

        // Clear validation and input
        hideValidation();
        $('#chatInput').val('');

        // Add user message to chat
        addMessage(message, 'user');

        // Show typing indicator
        showTypingIndicator();

        // Send message to server via AJAX
        $.ajax({
            url: "{{ route('ajaxresponseanswers') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                type: message
            },
            success: function(response) {
                // Remove typing indicator
                removeTypingIndicator();

                // Use the JSON response
                const botResponse = response.response ||
                    'Sorry, I could not process your request.';

                // Add bot response to chat
                addMessage(botResponse, 'bot');
            },
            error: function(xhr, status, error) {
                // Remove typing indicator
                removeTypingIndicator();

                // Show error message
                addMessage(
                    'Sorry, there was an error processing your request. Please try again.',
                    'bot');
                console.error('AJAX Error:', error);
            }
        });
    });

    // Add message to chat
    function addMessage(text, sender) {
        const messageClass = sender === 'user' ? 'message-bubble-user' : 'message-bubble-bot';
        const messageHtml = `<div class="${messageClass}">${text}</div>`;

        $('#chatbotMessages').append(messageHtml);

        // Scroll to bottom
        scrollToBottom();
    }

    // Show typing indicator
    function showTypingIndicator() {
        const typingHtml = `
                    <div class="message-bubble-bot" id="typingIndicator">
                        <div class="flex space-x-1">
                            <div class="typing-indicator"></div>
                            <div class="typing-indicator"></div>
                            <div class="typing-indicator"></div>
                        </div>
                    </div>
                `;

        $('#chatbotMessages').append(typingHtml);
        scrollToBottom();
    }

    // Remove typing indicator
    function removeTypingIndicator() {
        $('#typingIndicator').remove();
    }

    // Scroll to bottom of chat
    function scrollToBottom() {
        const messagesContainer = $('#chatbotMessages');
        messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
    }

    // Show validation message
    function showValidation(message) {
        $('#validationText').text(message);
        $('#validationMessage').addClass('show');
        $('#chatInput').addClass('input-error');
    }

    // Hide validation message
    function hideValidation() {
        $('#validationMessage').removeClass('show');
        $('#chatInput').removeClass('input-error');
    }

    // Allow sending with Enter key
    $('#chatInput').keypress(function(e) {
        if (e.which === 13) {
            $('#chatbotForm').submit();
            return false;
        }
    });

    // Clear validation when user starts typing
    $('#chatInput').on('input', function() {
        hideValidation();
    });
});
</script>
@endpush