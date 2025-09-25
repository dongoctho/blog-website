<?php
use GuzzleHttp\Client;
use Fuel\Core\Input;
use Fuel\Core\Log;
use Fuel\Core\Config;
use Fuel\Core\Response;
use Fuel\Core\Controller_Template;

/**
 * Controller xử lý chatbot với tích hợp ChatGPT API
 */
class Controller_Chatbot extends Controller_Template
{
    /**
     * Gửi tin nhắn đến ChatGPT API và trả về phản hồi
     */
    public function action_send_message()
    {
        // Chỉ cho phép POST request
        if (Input::method() !== 'POST') {
            return Response::forge(json_encode(['error' => 'Method not allowed'], JSON_UNESCAPED_UNICODE), 405, array('Content-Type' => 'application/json'));
        }

        // Lấy tin nhắn từ request
        $message = Input::post('message');
        
        // Kiểm tra tin nhắn có rỗng không
        if (empty($message)) {
            return Response::forge(json_encode(['error' => 'Message is required'], JSON_UNESCAPED_UNICODE), 400, array('Content-Type' => 'application/json'));
        }

        try {
            // Gọi Gemini API
            $response = $this->callChatGPT($message);

            return Response::forge(json_encode([
                'success' => true,
                'message' => $response
            ], JSON_UNESCAPED_UNICODE), 200, array('Content-Type' => 'application/json'));
            
        } catch (Exception $e) {
            // Log lỗi
            Log::error('Gemini API Error: ' . $e->getMessage());
            
            // Fallback response khi API không hoạt động
            $fallbackResponses = [
                "Xin lỗi, tôi đang gặp sự cố kỹ thuật. Vui lòng thử lại sau.",
                "Hiện tại tôi không thể kết nối đến server. Bạn có thể liên hệ admin để được hỗ trợ.",
                "Tôi đang trong quá trình bảo trì. Vui lòng thử lại sau ít phút.",
                "Có lỗi xảy ra với hệ thống AI. Bạn có thể gửi email cho chúng tôi để được hỗ trợ."
            ];
            
            $fallbackMessage = $fallbackResponses[array_rand($fallbackResponses)];
            
            return Response::forge(json_encode([
                'success' => true,
                'message' => $fallbackMessage,
                'debug' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE), 200, array('Content-Type' => 'application/json'));
        }
    }

    /**
     * Gọi API Google Gemini để lấy phản hồi
     */
    private function callChatGPT($message)
    {
        // Load config Gemini
        $config = Config::load('gemini', true);
        $apiKey = $config['api_key'];
        
        $client = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/v1beta/',
            'timeout' => 10,
        ]);

        try {
            $response = $client->post('models/gemini-2.0-flash:generateContent?key=' . $apiKey , [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [['text' => $message]],
                        ],
                    ],
                ],
            ]);

            $body = $response->getBody();
            $data = json_decode($body, true);
     
            // Kiểm tra xem có dữ liệu phản hồi và có text được tạo hay không
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            } else {
                return null; // Hoặc xử lý lỗi khác tùy theo nhu cầu
            }
     
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            // Xử lý lỗi khi gọi API
            error_log("Lỗi khi gọi API Gemini: " . $e->getMessage());
            return null;
        }
    }
}
