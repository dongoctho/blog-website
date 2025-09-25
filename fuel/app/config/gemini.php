<?php

/**
 * Cấu hình Google Gemini API
 */
return array(
    // API Key của Google Gemini
    // Lấy API key từ: https://aistudio.google.com/app/apikey
    'api_key' => 'AIzaSyD281nigAE2OP9d6cCkNU6VvQ3phgWBesg',
    
    // Model sử dụng (gemini-2.0-flash, gemini-1.5-pro, gemini-1.5-flash)
    'model' => 'gemini-2.0-flash',
    
    // Số token tối đa trong phản hồi
    'max_tokens' => 500,
    
    // Độ sáng tạo (0.0 - 1.0)
    'temperature' => 0.7,
    
    // Timeout cho API call (giây)
    'timeout' => 30,
    
    // System prompt cho chatbot
    'system_prompt' => 'Bạn là một chatbot hỗ trợ cho Blog CMS. Hãy trả lời bằng tiếng Việt một cách thân thiện và hữu ích. Nếu không biết câu trả lời, hãy nói rằng bạn đang học hỏi thêm.',
    
    // Base URL của Gemini API
    'base_url' => 'https://generativelanguage.googleapis.com/v1beta/',
    
    // Endpoint cho text generation
    'endpoint' => 'models/gemini-2.0-flash:generateContent'
);
