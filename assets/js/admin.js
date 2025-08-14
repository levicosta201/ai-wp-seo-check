(function($){
    $(function(){
        var button = $('#ai-wp-seo-check-adjust');
        if (!button.length) {
            return;
        }
        var feedback = $('.ai-wp-seo-check-feedback');
        var progress = $('.ai-wp-seo-check-progress');
        button.on('click', function(){
            if (AiWpSeoCheck.missingKey) {
                feedback.text('Missing API key.');
                return;
            }
            var editorSelect = window.wp && wp.data && wp.data.select ? wp.data.select('core/editor') : null;
            var content = editorSelect && editorSelect.getEditedPostContent ? editorSelect.getEditedPostContent() : $('#content').val();
            feedback.text('Processing...');
            progress.show();
            $.post(AiWpSeoCheck.ajaxUrl, {
                action: 'ai_wp_seo_check_adjust',
                nonce: AiWpSeoCheck.nonce,
                content: content
            }).done(function(resp){
                if (resp.success) {
                    if (resp.data.changed) {
                        var editorDispatch = window.wp && wp.data && wp.data.dispatch ? wp.data.dispatch('core/editor') : null;
                        if (editorDispatch && editorDispatch.editPost) {
                            editorDispatch.editPost({content: resp.data.content});
                        } else {
                            $('#content').val(resp.data.content);
                        }
                        if (resp.data.changes && resp.data.changes.length) {
                            var list = '<ul>';
                            resp.data.changes.forEach(function(change){
                                list += '<li>' + change + '</li>';
                            });
                            list += '</ul>';
                            feedback.html('Changes made:' + list);
                        } else {
                            feedback.text('Content updated.');
                        }
                    } else {
                        feedback.text(resp.data.message || 'No changes needed.');
                    }
                } else {
                    feedback.text(resp.data || 'Error');
                }
            }).fail(function(){
                feedback.text('Request failed.');
            }).always(function(){
                progress.hide();
            });
        });
    });
})(jQuery);
