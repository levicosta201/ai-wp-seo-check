<?php
namespace AiWpSeoCheck\Infrastructure;

class OpenAIClient {
    private string $api_key;

    public function __construct( string $api_key ) {
        $this->api_key = $api_key;
    }

    public function adjust_content( string $content ): array {
        $prompt = 'You fix only grammar and spelling without changing meaning or structure. '
            . 'Return a JSON object with keys "content" (the corrected text), '
            . '"changes" (array of brief change descriptions), and "explanation" '
            . 'explaining when no changes are needed.';

        $body = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                [ 'role' => 'system', 'content' => $prompt ],
                [ 'role' => 'user', 'content' => $content ],
            ],
            'response_format' => [ 'type' => 'json_object' ],
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
            return [ 'content' => $content, 'changes' => [], 'explanation' => '' ];
        }

        $data = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( empty( $data['choices'][0]['message']['content'] ) ) {
            return [ 'content' => $content, 'changes' => [], 'explanation' => '' ];
        }

        $payload = json_decode( $data['choices'][0]['message']['content'], true );
        if ( ! is_array( $payload ) ) {
            return [ 'content' => $content, 'changes' => [], 'explanation' => '' ];
        }

        $clean_content = sanitize_textarea_field( $payload['content'] ?? $content );
        $changes       = [];
        if ( ! empty( $payload['changes'] ) && is_array( $payload['changes'] ) ) {
            foreach ( $payload['changes'] as $change ) {
                $changes[] = sanitize_text_field( $change );
            }
        }
        $explanation = sanitize_text_field( $payload['explanation'] ?? '' );

        return [
            'content'     => $clean_content,
            'changes'     => $changes,
            'explanation' => $explanation,
        ];
    }
}
