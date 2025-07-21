document.addEventListener('DOMContentLoaded', function() {
    // Form validation for login, register, create, and edit post forms
    const forms = document.querySelectorAll('form:not(.comment-form)');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const username = form.querySelector('input[name="username"]');
            const password = form.querySelector('input[name="password"]');
            const title = form.querySelector('input[name="title"]');
            const content = form.querySelector('textarea[name="content"]');
            
            let errors = [];
            
            if (username && username.value.trim().length < 3) {
                errors.push('Username must be at least 3 characters long');
            }
            
            if (password && password.value.length < 6) {
                errors.push('Password must be at least 6 characters long');
            }
            
            if (title && title.value.trim().length < 5) {
                errors.push('Title must be at least 5 characters long');
            }
            
            if (content && content.value.trim().length < 10) {
                errors.push('Content must be at least 10 characters long');
            }
            
            if (errors.length > 0) {
                event.preventDefault();
                alert(errors.join('\n'));
            } else {
                // Add loading state to button
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Submitting...';
                }
            }
        });
    });
    
    // Logout confirmation
    const logoutLinks = document.querySelectorAll('a[href="logout.php"]');
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to logout?')) {
                event.preventDefault();
            }
        });
    });
    
    // Delete post confirmation with AJAX
    const deleteLinks = document.querySelectorAll('.delete-post');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
                return;
            }
            
            const postId = new URLSearchParams(link.search).get('id');
            link.textContent = 'Deleting...';
            link.style.pointerEvents = 'none';
            
            fetch('delete_post.php?id=' + postId, {
                method: 'GET'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to delete post');
                }
                return response.text();
            })
            .then(data => {
                // Remove post from DOM
                const article = link.closest('article');
                if (article) {
                    article.style.opacity = '0';
                    setTimeout(() => article.remove(), 300);
                } else {
                    // If on post.php, redirect to index
                    window.location.href = 'index.php';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete post');
                link.textContent = 'Delete';
                link.style.pointerEvents = 'auto';
            });
        });
    });
    
    // AJAX comment submission
    const commentForm = document.querySelector('.comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const content = commentForm.querySelector('textarea[name="comment"]').value;
            const postId = new URLSearchParams(window.location.search).get('id');
            
            if (content.trim().length < 5) {
                alert('Comment must be at least 5 characters long');
                return;
            }
            
            const submitButton = commentForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Posting...';
            
            fetch('post.php?id=' + postId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'comment=' + encodeURIComponent(content)
            })
            .then(response => response.text())
            .then(data => {
                // Create new comment element
                const commentSection = document.querySelector('section');
                const newComment = document.createElement('div');
                newComment.className = 'comment';
                newComment.style.opacity = '0';
                newComment.innerHTML = `
                    <p>${content.replace(/\n/g, '<br>')}</p>
                    <p>By ${document.querySelector('nav span')?.textContent?.replace('Welcome, ', '') || 'You'} on ${new Date().toLocaleString()}</p>
                `;
                commentSection.insertBefore(newComment, commentForm);
                commentForm.querySelector('textarea').value = '';
                submitButton.disabled = false;
                submitButton.textContent = 'Post Comment';
                // Fade in new comment
                setTimeout(() => {
                    newComment.style.transition = 'opacity 0.3s ease';
                    newComment.style.opacity = '1';
                }, 10);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to post comment');
                submitButton.disabled = false;
                submitButton.textContent = 'Post Comment';
            });
        });
    }
});