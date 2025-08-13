<?php
namespace AiWpSeoCheck\Infrastructure;

class OpenAIClient {
    private string $api_key;

    public function __construct( string $api_key ) {
        $this->api_key = $api_key;
    }

    public function adjust_content( string $content ): string {
        $prompt = 'Fix grammar and spelling without changing meaning or structure:';
        $body   = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [ 'role' => 'system', 'content' => $prompt ],
                [ 'role' => 'user', 'content' => $content ],
            ],
        ];

        $response = wp_remote_post(
            'https://api.openai.com/v1/chat/completions',
            [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $this->api_key,
                ],
                'body'    => wp_json_encode( $body ),
                'timeout' => 20,
            ]
        );

        if ( is_wp_error( $response ) ) {
            return $content;
        }

        $data = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( empty( $data['choices'][0]['message']['content'] ) ) {
            return $content;
        }

        return sanitize_textarea_field( $data['choices'][0]['message']['content'] );
    }
}
