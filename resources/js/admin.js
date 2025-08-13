(function($){
    $(function(){
        var button = $('#ai-wp-seo-check-adjust');
        if (!button.length) {
            return;
        }
        var feedback = $('.ai-wp-seo-check-feedback');
        button.on('click', function(){
            if (AiWpSeoCheck.missingKey) {
                feedback.text('Missing API key.');
                return;
            }
            var content = wp.data.select('core/editor').getEditedPostContent();
            feedback.text('Processing...');
            $.post(AiWpSeoCheck.ajaxUrl, {
                action: 'ai_wp_seo_check_adjust',
                nonce: AiWpSeoCheck.nonce,
                content: content
            }).done(function(resp){
                if (resp.success) {
                    wp.data.dispatch('core/editor').editPost({content: resp.data.content});
                    feedback.text('Content updated. Please review changes.');
                } else {
                    feedback.text(resp.data || 'Error');
                }
            }).fail(function(){
                feedback.text('Request failed.');
            });
        });
    });
})(jQuery);
