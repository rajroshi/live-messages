jQuery(document).ready(function($) {
    // Previous message cache for comparison
    let previousMessages = '';

    // Icons for different message types
    const typeIcons = {
        info: '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>',
        success: '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>',
        warning: '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>',
        important: '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>'
    };

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function shareMessage(platform, title, content) {
        const url = window.location.href;
        const text = `${title} - ${content}`;
        
        let shareUrl = '';
        switch(platform) {
            case 'twitter':
                shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
                break;
            case 'facebook':
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}&quote=${encodeURIComponent(text)}`;
                break;
            case 'whatsapp':
                shareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(text + ' ' + url)}`;
                break;
            case 'telegram':
                shareUrl = `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
                break;
            case 'email':
                shareUrl = `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(text + '\n\n' + url)}`;
                break;
        }
        
        if (platform === 'email') {
            window.location.href = shareUrl;
        } else {
            window.open(shareUrl, 'share-dialog', 'width=600,height=400');
        }
    }

    // Make shareMessage available globally
    window.shareMessage = shareMessage;

    function renderMessage(message) {
        return `
            <div class="message-item message-type-${message.type}" data-id="${message.id}">
                <div class="message-type-indicator">
                    ${typeIcons[message.type]}
                </div>
                <div class="message-content-wrapper">
                    <h3 class="message-title type-${message.type}">${message.title}</h3>
                    <div class="message-content">${message.content}</div>
                    <div class="message-meta">
                        <div class="meta-info">
                            Posted by ${message.author_name} • ${message.formatted_date}
                        </div>
                        <div class="share-buttons">
                            <button onclick="shareMessage('twitter', '${message.title.replace(/'/g, "\\'")}', '${message.content.replace(/'/g, "\\'")}')" class="share-btn twitter" title="Share on Twitter">
                                <svg viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M22.46,6C21.69,6.35 20.86,6.58 20,6.69C20.88,6.16 21.56,5.32 21.88,4.31C21.05,4.81 20.13,5.16 19.16,5.36C18.37,4.5 17.26,4 16,4C13.65,4 11.73,5.92 11.73,8.29C11.73,8.63 11.77,8.96 11.84,9.27C8.28,9.09 5.11,7.38 3,4.79C2.63,5.42 2.42,6.16 2.42,6.94C2.42,8.43 3.17,9.75 4.33,10.5C3.62,10.5 2.96,10.3 2.38,10C2.38,10 2.38,10 2.38,10.03C2.38,12.11 3.86,13.85 5.82,14.24C5.46,14.34 5.08,14.39 4.69,14.39C4.42,14.39 4.15,14.36 3.89,14.31C4.43,16 6,17.26 7.89,17.29C6.43,18.45 4.58,19.13 2.56,19.13C2.22,19.13 1.88,19.11 1.54,19.07C3.44,20.29 5.7,21 8.12,21C16,21 20.33,14.46 20.33,8.79C20.33,8.6 20.33,8.42 20.32,8.23C21.16,7.63 21.88,6.87 22.46,6Z"/></svg>
                            </button>
                            <button onclick="shareMessage('facebook', '${message.title.replace(/'/g, "\\'")}', '${message.content.replace(/'/g, "\\'")}')" class="share-btn facebook" title="Share on Facebook">
                                <svg viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z"/></svg>
                            </button>
                            <button onclick="shareMessage('whatsapp', '${message.title.replace(/'/g, "\\'")}', '${message.content.replace(/'/g, "\\'")}')" class="share-btn whatsapp" title="Share on WhatsApp">
                                <svg viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.04 14.69 2 12.04 2Z"/></svg>
                            </button>
                            <button onclick="shareMessage('telegram', '${message.title.replace(/'/g, "\\'")}', '${message.content.replace(/'/g, "\\'")}')" class="share-btn telegram" title="Share on Telegram">
                                <svg viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M9.78,18.65L10.06,14.42L17.74,7.5C18.08,7.19 17.67,7.04 17.22,7.31L7.74,13.3L3.64,12C2.76,11.75 2.75,11.14 3.84,10.7L19.81,4.54C20.54,4.21 21.24,4.72 20.96,5.84L18.24,18.65C18.05,19.56 17.5,19.78 16.74,19.36L12.6,16.3L10.61,18.23C10.38,18.46 10.19,18.65 9.78,18.65Z"/></svg>
                            </button>
                            <button onclick="shareMessage('email', '${message.title.replace(/'/g, "\\'")}', '${message.content.replace(/'/g, "\\'")}')" class="share-btn email" title="Share via Email">
                                <svg viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    let currentPage = 1;
    const messagesPerPage = 10;

    function loadMessages(page = 1) {
        $.ajax({
            url: liveMessages.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_messages',
                nonce: liveMessages.nonce,
                page: page,
                per_page: messagesPerPage
            },
            success: function(response) {
                if (response.success) {
                    let html = '';
                    response.data.messages.forEach(function(message) {
                        html += renderMessage(message);
                    });
                    
                    // Add pagination if there are more pages
                    if (response.data.total_pages > 1) {
                        html += '<div class="messages-pagination">';
                        if (page > 1) {
                            html += `<button class="pagination-btn prev" data-page="${page - 1}">← Previous</button>`;
                        }
                        html += `<span class="page-info">Page ${page} of ${response.data.total_pages}</span>`;
                        if (page < response.data.total_pages) {
                            html += `<button class="pagination-btn next" data-page="${page + 1}">Next →</button>`;
                        }
                        html += '</div>';
                    }
                    
                    $('#messages-list').html(html || '<div class="no-messages">No messages yet.</div>');
                    
                    // Update current page
                    currentPage = page;
                }
            }
        });
    }

    // Handle form submission
    $('#submit-message').on('click', function(e) {
        e.preventDefault();
        
        const title = $('#message-title').val();
        const message = $('#new-message').val();
        const type = $('#message-type').val();
        
        if (!title) {
            alert('Please enter a title');
            return;
        }
        
        if (!message) {
            alert('Please enter a message');
            return;
        }
        
        console.log('Submitting message:', { title, message, type });
        
        $.ajax({
            url: liveMessages.ajaxurl,
            type: 'POST',
            data: {
                action: 'submit_message',
                nonce: liveMessages.nonce,
                title: title,
                message: message,
                type: type
            },
            success: function(response) {
                console.log('Submit response:', response);
                if (response.success) {
                    $('#message-title').val('');
                    $('#new-message').val('');
                    loadMessages();
                } else {
                    alert('Error: ' + (response.data || 'Failed to save message'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Submit error:', error);
                alert('Error submitting message: ' + error);
            }
        });
    });

    // Handle pagination clicks
    $(document).on('click', '.pagination-btn', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadMessages(page);
        // Scroll to top of messages
        $('html, body').animate({
            scrollTop: $('#live-messages-container').offset().top - 50
        }, 500);
    });

    // Initial load
    loadMessages(1);
    
    // Auto refresh every 20 seconds, but only if on first page
    setInterval(function() {
        if (currentPage === 1) {
            loadMessages(1);
        }
    }, 20000);
}); 